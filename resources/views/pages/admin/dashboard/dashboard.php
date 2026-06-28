<?php

use App\Models\User;
use App\Models\SftpAccount;
use App\Models\System;
use App\Models\Unit;
use App\Models\BackupLog;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

new class extends Component
{
    use WithPagination;

    // فیلترها
    public $search = '';
    public $role_filter = '';
    public $status_filter = '';
    public $active_tab = 'users'; // users | accounts | systems

    // آمارها
    public $totalUsers = 0;
    public $totalAdmins = 0;
    public $totalManagers = 0;
    public $totalUsersRole = 0;
    public $totalSftpAccounts = 0;
    public $activeSftpAccounts = 0;
    public $inactiveSftpAccounts = 0;
    public $totalSystems = 0;
    public $totalBackupsToday = 0;
    public $successBackupsToday = 0;
    public $failedBackupsToday = 0;
    public $overallSuccessRate = 0;

    // آمار برای نمودارها
    public $weeklyStats = [];
    public $userRoleStats = [];
    public $systemUsageStats = [];

    // ===== متدهای مدیریت نرم‌افزارها =====
    public $editingSystem = null;
    public $editSystemNameFa = '';
    public $editSystemNameEn = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'role_filter' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'active_tab' => ['except' => 'users'],
    ];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // ===== آمار کاربران =====
        $this->totalUsers = User::count();
        $this->totalAdmins = User::where('user_type', 'admin')->count();
        $this->totalManagers = User::where('user_type', 'manager')->count();
        $this->totalUsersRole = User::where('user_type', 'user')->count();

        // ===== آمار اکانت‌ها =====
        $this->totalSftpAccounts = SftpAccount::count();
        $this->activeSftpAccounts = SftpAccount::where('is_active', true)->count();
        $this->inactiveSftpAccounts = SftpAccount::where('is_active', false)->count();

        // ===== آمار سیستم‌ها =====
        $this->totalSystems = System::count();

        // ===== آمار بکاپ‌های امروز =====
        $today = Carbon::now()->toDateString();
        $this->totalBackupsToday = BackupLog::whereDate('uploaded_at', $today)->count();
        $this->successBackupsToday = BackupLog::whereDate('uploaded_at', $today)
            ->where('status', 'success')
            ->count();
        $this->failedBackupsToday = BackupLog::whereDate('uploaded_at', $today)
            ->where('status', 'failed')
            ->count();

        // ===== درصد موفقیت کلی =====
        $totalAll = BackupLog::count();
        $successAll = BackupLog::where('status', 'success')->count();
        $this->overallSuccessRate = $totalAll > 0 ? round(($successAll / $totalAll) * 100, 2) : 0;

        // ===== آمار هفتگی بکاپ‌ها =====
        $this->weeklyStats = $this->getWeeklyStats();

        // ===== آمار توزیع نقش‌های کاربران =====
        $this->userRoleStats = [
            ['label' => 'مدیران', 'value' => $this->totalAdmins, 'color' => '#dc3545'],
            ['label' => 'مدیران سیستم', 'value' => $this->totalManagers, 'color' => '#ffc107'],
            ['label' => 'کاربران عادی', 'value' => $this->totalUsersRole, 'color' => '#0d6efd'],
        ];

        // ===== آمار پرکاربردترین سیستم‌ها =====
        $this->systemUsageStats = $this->getSystemUsageStats();
    }

    public function getWeeklyStats()
    {
        $stats = [];
        $now = Carbon::now();

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $jalaliDate = Jalalian::fromCarbon($date)->format('Y/m/d');

            $total = BackupLog::whereDate('uploaded_at', $date->toDateString())->count();
            $success = BackupLog::whereDate('uploaded_at', $date->toDateString())
                ->where('status', 'success')
                ->count();
            $failed = BackupLog::whereDate('uploaded_at', $date->toDateString())
                ->where('status', 'failed')
                ->count();

            $stats[] = [
                'date' => $jalaliDate,
                'total' => $total,
                'success' => $success,
                'failed' => $failed,
            ];
        }

        return $stats;
    }

    public function getSystemUsageStats()
    {
        return System::withCount(['sftpAccounts as accounts_count'])
            ->withCount(['sftpAccounts as backups_count' => function ($query) {
                $query->join('backup_logs', 'backup_logs.sftp_account_id', '=', 'sftp_accounts.id');
            }])
            ->orderBy('backups_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($system) {
                // دریافت تعداد واقعی بکاپ‌ها
                $backupsCount = BackupLog::whereHas('sftpAccount', function ($q) use ($system) {
                    $q->where('system_id', $system->id);
                })->count();

                return [
                    'name' => $system->name_fa,
                    'name_en' => $system->name_en,
                    'accounts' => $system->sftpAccounts()->count(),
                    'backups' => $backupsCount,
                ];
            })
            ->sortByDesc('backups')
            ->values();
    }

    // ===== متدهای فیلتر و جستجو =====
    public function filter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'role_filter', 'status_filter']);
        $this->filter();
    }

    public function setTab($tab)
    {
        $this->active_tab = $tab;
        $this->filter();
    }

    // ===== دریافت داده‌های جدول‌ها =====
    public function getUsers()
    {
        $query = User::query()
            ->withCount('sftpAccounts');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('family', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->role_filter) {
            $query->where('user_type', $this->role_filter);
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function getSftpAccounts()
    {
        $query = SftpAccount::query()
            ->with(['user', 'unit', 'system']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('username', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('family', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('unit', function ($q3) {
                        $q3->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('system', function ($q4) {
                        $q4->where('name_fa', 'like', '%' . $this->search . '%')
                            ->orWhere('name_en', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->status_filter) {
            $query->where('is_active', $this->status_filter === 'active' ? 1 : 0);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function getSystems()
    {
        $query = System::query()
            ->withCount('sftpAccounts');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name_fa', 'like', '%' . $this->search . '%')
                    ->orWhere('name_en', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('name_fa')->paginate(10);
    }

    // ===== اکشن‌های مدیریتی =====
    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = $user->status === 'active' ? 'deactive' : 'active';
            $user->save();
            $this->loadStatistics();
            session()->flash('message', 'وضعیت کاربر با موفقیت تغییر کرد');
        }
    }

    public function toggleAccountStatus($accountId)
    {
        $account = SftpAccount::find($accountId);
        if ($account) {
            $account->is_active = !$account->is_active;
            $account->save();
            $this->loadStatistics();
            session()->flash('message', 'وضعیت اکانت با موفقیت تغییر کرد');
        }
    }

    public function editSystem($systemId)
    {
        $system = System::find($systemId);
        if ($system) {
            $this->editingSystem = $systemId;
            $this->editSystemNameFa = $system->name_fa;
            $this->editSystemNameEn = $system->name_en ?? '';
            $this->dispatch('show-edit-modal');
        }
    }

    public function updateSystem()
    {
        $this->validate([
            'editSystemNameFa' => 'required|min:2|max:255',
            'editSystemNameEn' => 'nullable|max:100',
        ]);

        $system = System::find($this->editingSystem);
        if ($system) {
            $system->name_fa = $this->editSystemNameFa;
            $system->name_en = $this->editSystemNameEn;
            $system->save();

            $this->reset(['editingSystem', 'editSystemNameFa', 'editSystemNameEn']);
            $this->loadStatistics();
            $this->dispatch('hide-edit-modal');
            session()->flash('message', 'نرم‌افزار با موفقیت ویرایش شد');
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editingSystem', 'editSystemNameFa', 'editSystemNameEn']);
        $this->dispatch('hide-edit-modal');
    }

    public function render()
    {
        return view('pages.admin.dashboard.dashboard', [
            'users' => $this->getUsers(),
            'sftpAccounts' => $this->getSftpAccounts(),
            'systems' => $this->getSystems(),
        ]);
    }
};