<?php

use App\Models\BackupLog;
use App\Models\SftpAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
     use WithPagination;

    // فیلترها
    public $sftp_account_id = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $search = '';

    // آمارها
    public $totalBackups = 0;
    public $successCount = 0;
    public $failedCount = 0;
    public $successRate = 0;

    protected $queryString = [
        'sftp_account_id' => ['except' => ''],
        'status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    // ============================================
    // ⭐ متدهای به‌روزرسانی فیلترها
    // ============================================
    public function updatedSftpAccountId()
    {
        $this->filter();
    }

    public function updatedStatus()
    {
        $this->filter();
    }

    public function updatedDateFrom()
    {
        $this->filter();
    }

    public function updatedDateTo()
    {
        $this->filter();
    }

    public function updatedSearch()
    {
        $this->filter();
    }

    // ============================================

    public function mount()
    {
        $this->loadStatistics();
    }

    /**
     * دریافت کوئری پایه با اعمال فیلترها (مخصوص کاربر جاری)
     */
    private function getBaseQuery()
    {
        // دریافت ID کاربر جاری
        $userId = Auth::id();
        
        $query = BackupLog::query()
            ->whereHas('sftpAccount', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        // فیلتر بر اساس SFTP Account
        if ($this->sftp_account_id) {
            $query->where('sftp_account_id', $this->sftp_account_id);
        }

        // فیلتر بر اساس وضعیت
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // فیلتر بر اساس تاریخ شروع
        if ($this->date_from) {
            try {
                $dateFrom = Jalalian::fromFormat('Y/m/d', $this->date_from)->toCarbon()->startOfDay();
                $query->where('uploaded_at', '>=', $dateFrom);
            } catch (\Exception $e) {}
        }

        // فیلتر بر اساس تاریخ پایان
        if ($this->date_to) {
            try {
                $dateTo = Jalalian::fromFormat('Y/m/d', $this->date_to)->toCarbon()->endOfDay();
                $query->where('uploaded_at', '<=', $dateTo);
            } catch (\Exception $e) {}
        }

        // فیلتر جستجو
        if ($this->search) {
            $query->where(function($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sftpAccount', function($q2) {
                      $q2->where('username', 'like', '%' . $this->search . '%')
                         ->orWhereHas('unit', function($q3) {
                             $q3->where('name', 'like', '%' . $this->search . '%');
                         })
                         ->orWhereHas('system', function($q4) {
                             $q4->where('name_fa', 'like', '%' . $this->search . '%')
                                ->orWhere('name_en', 'like', '%' . $this->search . '%');
                         });
                  });
            });
        }

        return $query;
    }

    /**
     * بارگذاری آمارها با اعمال فیلترها
     */
    public function loadStatistics()
    {
        $query = $this->getBaseQuery();

        // آمار کلی
        $this->totalBackups = $query->count();
        $this->successCount = (clone $query)->where('status', 'success')->count();
        $this->failedCount = (clone $query)->where('status', 'failed')->count();
        $this->successRate = $this->totalBackups > 0 
            ? round(($this->successCount / $this->totalBackups) * 100, 2) 
            : 0;
    }

    /**
     * اعمال فیلترها
     */
    public function filter()
    {
        $this->resetPage();
        $this->loadStatistics(); // ← این خط برای به‌روزرسانی کارت‌هاست
    }

    /**
     * ریست فیلترها
     */
    public function resetFilters()
    {
        $this->reset(['sftp_account_id', 'status', 'date_from', 'date_to', 'search']);
        $this->filter();
    }

    /**
     * دریافت لیست بکاپ‌ها با فیلترها
     */
    public function getBackups()
    {
        $query = $this->getBaseQuery();

        return $query->with(['sftpAccount.unit', 'sftpAccount.system'])
            ->orderBy('uploaded_at', 'desc')
            ->paginate(15);
    }

    /**
     * دریافت لیست SFTP Account‌های کاربر
     */
    public function getSftpAccounts()
    {
        return SftpAccount::where('user_id', Auth::id())
            ->with(['unit', 'system'])
            ->orderBy('id')
            ->get();
    }

    public function render()
    {
         $sftpAccounts = $this->getSftpAccounts();
        $backups = $this->getBackups();

        return view('pages.user.dashboard.dashboard', [
            'sftpAccounts' => $sftpAccounts,
            'backups' => $backups,
        ]);
    }
};