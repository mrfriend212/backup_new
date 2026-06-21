<?php

use App\Models\BackupLog;
use App\Models\Unit;
use App\Models\System;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Log;

new class extends Component
{
    use WithPagination;

    // فیلترها
    public $unit_id = '';
    public $system_id = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $search = '';

    // آمارها
    public $totalBackups = 0;
    public $successCount = 0;
    public $failedCount = 0;
    public $successRate = 0;
    public $weeklyStats = [];
    public $unitStats = [];
    public $systemStats = [];

    protected $queryString = [
        'unit_id' => ['except' => ''],
        'system_id' => ['except' => ''],
        'status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->weeklyStats = $this->getWeeklyStats();
        $this->loadStatistics();
    }

    /**
     * دریافت آمار هفتگی با اعمال فیلترها
     */
    public function getWeeklyStats()
    {
        $stats = [];
        $now = Carbon::now();
        
        // دریافت کوئری پایه با فیلترها (بدون محدودیت تاریخ)
        $baseQuery = $this->getBaseQuery();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $jalaliDate = Jalalian::fromCarbon($date)->format('Y/m/d');
            
            // شبیه‌سازی کوئری فیلتر شده برای هر روز
            $dayQuery = clone $baseQuery;
            $dayQuery->whereDate('uploaded_at', $date->toDateString());
            
            $total = $dayQuery->count();
            $success = (clone $dayQuery)->where('status', 'success')->count();
            $failed = (clone $dayQuery)->where('status', 'failed')->count();
            
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
     * دریافت کوئری پایه با اعمال فیلترها
     */
    private function getBaseQuery()
    {
        $query = BackupLog::query();
        
        // فیلتر بر اساس واحد
        if ($this->unit_id) {
            $query->whereHas('sftpAccount', function($q) {
                $q->where('unit_id', $this->unit_id);
            });
        }
        
        // فیلتر بر اساس سیستم (نرم‌افزار)
        if ($this->system_id) {
            $query->whereHas('sftpAccount', function($q) {
                $q->where('system_id', $this->system_id);
            });
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
                  ->orWhereHas('sftpAccount.unit', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('sftpAccount.system', function($q3) {
                      $q3->where('name_fa', 'like', '%' . $this->search . '%')
                         ->orWhere('name_en', 'like', '%' . $this->search . '%');
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

        // ⭐ آمار هفتگی با فیلترها
        $this->weeklyStats = $this->getWeeklyStats();

        // آمار بر اساس واحد
        $this->unitStats = $this->getUnitStats();
    }

    /**
    * دریافت آمار بر اساس واحد
    */
    public function getUnitStats()
    {
        $query = $this->getBaseQuery();
        
        return $query->with(['sftpAccount.unit', 'sftpAccount.system'])
            ->selectRaw('sftp_account_id, COUNT(*) as total, 
                        SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as success_count,
                        SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_count')
            ->groupBy('sftp_account_id')
            ->get()
            ->map(function($item) {
                $unitName = $item->sftpAccount->unit->name ?? 'نامشخص';
                $systemName = $item->sftpAccount->system->name_fa ?? 'نامشخص';
                $systemNameEn = $item->sftpAccount->system->name_en ?? '';
                $total = $item->total;
                $success = $item->success_count;
                $failed = $item->failed_count;
                $rate = $total > 0 ? round(($success / $total) * 100, 2) : 0;
                
                $color = $rate >= 90 ? 'success' : ($rate >= 70 ? 'warning' : 'danger');
                
                return [
                    'unit_name' => $unitName,
                    'system_name' => $systemName,
                    'system_name_en' => $systemNameEn,
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

    /**
     * دریافت آمار بر اساس سیستم (نرم‌افزار)
     */
    public function getSystemStats()
    {
        $query = $this->getBaseQuery();
        
        return $query->with('sftpAccount.system')
            ->selectRaw('sftp_account_id, COUNT(*) as total, 
                         SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as success_count,
                         SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_count')
            ->groupBy('sftp_account_id')
            ->get()
            ->map(function($item) {
                $systemName = $item->sftpAccount->system->name_fa ?? 'نامشخص';
                $systemNameEn = $item->sftpAccount->system->name_en ?? '';
                $total = $item->total;
                $success = $item->success_count;
                $failed = $item->failed_count;
                $rate = $total > 0 ? round(($success / $total) * 100, 2) : 0;
                
                $color = $rate >= 90 ? 'success' : ($rate >= 70 ? 'warning' : 'danger');
                
                return [
                    'system_name' => $systemName,
                    'system_name_en' => $systemNameEn,
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

    /**
     * اعمال فیلترها
     */
    public function filter()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    /**
     * ریست فیلترها
     */
    public function resetFilters()
    {
        $this->reset(['unit_id', 'system_id', 'status', 'date_from', 'date_to', 'search']);
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

    public function updatedUnitId()
    {
        $this->filter();
    }

    public function updatedSystemId()
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


    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $systems = System::orderBy('name_fa')->get();
        $backups = $this->getBackups();

        return view('pages.manager.dashboard.dashboard', [
            'units' => $units,
            'systems' => $systems,
            'backups' => $backups,
        ]);
    }
};