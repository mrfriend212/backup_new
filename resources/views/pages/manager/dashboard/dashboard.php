<?php

use App\Models\BackupLog;
use App\Models\Unit;
use App\Models\SftpAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

new class extends Component
{
    use WithPagination;

    // فیلترها
    public $unit_id = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $search = '';

    // آمارها
    public $totalBackups = 0;
    public $successCount = 0;
    public $failedCount = 0;
    public $successRate = 0;
    public $unitStats = [];

    // ⭐ مهمترین بخش: آمار هفتگی ثابت
    public $weeklyStats = [];

    protected $queryString = [
        'unit_id' => ['except' => ''],
        'status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        // ⭐ اینجا فقط یکبار آمار هفتگی رو محاسبه و ذخیره میکنیم
        $this->weeklyStats = $this->getWeeklyStats();
        
        // بارگذاری بقیه آمار
        $this->loadStatistics();
    }

    /**
     * محاسبه آمار هفتگی (بدون هیچ فیلتری)
     * این متد فقط یکبار در mount اجرا میشه
     */
    public function getWeeklyStats()
    {
        $stats = [];
        $now = Carbon::now();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $jalaliDate = Jalalian::fromCarbon($date)->format('Y/m/d');
            
            // کوئری کاملا مستقل از فیلترها
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

    /**
     * بارگذاری آمارهای متغیر (تحت تاثیر فیلترها)
     */
    public function loadStatistics()
    {
        $query = BackupLog::query();
        
        // اعمال فیلتر واحد
        if ($this->unit_id) {
            $query->whereHas('sftpAccount', function($q) {
                $q->where('unit_id', $this->unit_id);
            });
        }
        
        // اعمال فیلتر وضعیت
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        // اعمال فیلتر تاریخ
        if ($this->date_from) {
            try {
                $dateFrom = Jalalian::fromFormat('Y/m/d', $this->date_from)->toCarbon()->startOfDay();
                $query->where('uploaded_at', '>=', $dateFrom);
            } catch (\Exception $e) {
                // تاریخ نامعتبر
            }
        }
        
        if ($this->date_to) {
            try {
                $dateTo = Jalalian::fromFormat('Y/m/d', $this->date_to)->toCarbon()->endOfDay();
                $query->where('uploaded_at', '<=', $dateTo);
            } catch (\Exception $e) {
                // تاریخ نامعتبر
            }
        }
        
        // اعمال فیلتر جستجو
        if ($this->search) {
            $query->where(function($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sftpAccount.unit', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        // آمار کلی (با فیلترها)
        $this->totalBackups = $query->count();
        $this->successCount = (clone $query)->where('status', 'success')->count();
        $this->failedCount = (clone $query)->where('status', 'failed')->count();
        $this->successRate = $this->totalBackups > 0 
            ? round(($this->successCount / $this->totalBackups) * 100, 2) 
            : 0;

        // آمار بر اساس واحد (با فیلترها)
        $this->unitStats = $this->getUnitStats();
    }

    public function getUnitStats()
    {
        $query = BackupLog::query();
        
        // اعمال فیلترها
        if ($this->unit_id) {
            $query->whereHas('sftpAccount', function($q) {
                $q->where('unit_id', $this->unit_id);
            });
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        if ($this->date_from) {
            try {
                $dateFrom = Jalalian::fromFormat('Y/m/d', $this->date_from)->toCarbon()->startOfDay();
                $query->where('uploaded_at', '>=', $dateFrom);
            } catch (\Exception $e) {}
        }
        
        if ($this->date_to) {
            try {
                $dateTo = Jalalian::fromFormat('Y/m/d', $this->date_to)->toCarbon()->endOfDay();
                $query->where('uploaded_at', '<=', $dateTo);
            } catch (\Exception $e) {}
        }
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sftpAccount.unit', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        return $query->with('sftpAccount.unit')
            ->selectRaw('sftp_account_id, COUNT(*) as total, 
                         SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as success_count,
                         SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_count')
            ->groupBy('sftp_account_id')
            ->get()
            ->map(function($item) {
                $unitName = $item->sftpAccount->unit->name ?? 'نامشخص';
                $total = $item->total;
                $success = $item->success_count;
                $failed = $item->failed_count;
                $rate = $total > 0 ? round(($success / $total) * 100, 2) : 0;
                
                $color = $rate >= 90 ? 'success' : ($rate >= 70 ? 'warning' : 'danger');
                
                return [
                    'unit_name' => $unitName,
                    'total' => $total,
                    'success' => $success,
                    'failed' => $failed,
                    'success_rate' => $rate,
                    'color' => $color
                ];
            })
            ->sortByDesc('success_rate')
            ->values();
    }

    public function filter()
    {
        $this->resetPage();
        $this->loadStatistics(); // فقط آمارهای متغیر رو آپدیت میکنیم
        // ⭐ توجه: weeklyStats رو آپدیت نمیکنیم
    }

    public function resetFilters()
    {
        $this->reset(['unit_id', 'status', 'date_from', 'date_to', 'search']);
        $this->filter();
    }

    public function getBackups()
    {
        $query = BackupLog::with(['sftpAccount.unit', 'sftpAccount.system'])
            ->orderBy('uploaded_at', 'desc');

        if ($this->unit_id) {
            $query->whereHas('sftpAccount', function($q) {
                $q->where('unit_id', $this->unit_id);
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->date_from) {
            try {
                $dateFrom = Jalalian::fromFormat('Y/m/d', $this->date_from)->toCarbon()->startOfDay();
                $query->where('uploaded_at', '>=', $dateFrom);
            } catch (\Exception $e) {}
        }

        if ($this->date_to) {
            try {
                $dateTo = Jalalian::fromFormat('Y/m/d', $this->date_to)->toCarbon()->endOfDay();
                $query->where('uploaded_at', '<=', $dateTo);
            } catch (\Exception $e) {}
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sftpAccount.unit', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->paginate(15);
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $backups = $this->getBackups();

        return view('pages.manager.dashboard.dashboard', [
            'units' => $units,
            'backups' => $backups,
        ]);
    }
};