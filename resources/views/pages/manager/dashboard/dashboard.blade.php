@section('css')
<style>
    .card-stats .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .card-stats .icon-circle i {
        font-size: 2rem;
    }
    
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.5rem 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* ===== استایل صفحه‌بندی ===== */
    .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
        direction: ltr; /* برای درست نمایش داده شدن اعداد */
    }
    
    .pagination .page-item {
        display: inline-block;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #0d6efd;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }
    
    .pagination .page-link:hover {
        background: #e9ecef;
        border-color: #0d6efd;
        color: #0a58ca;
    }
    
    .pagination .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
    
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
        background: #f8f9fa;
        color: #6c757d;
    }
    
    .pagination .page-link i {
        font-size: 14px;
    }
    
    /* ===== استایل برای نمایش اعداد فارسی ===== */
    .pagination .page-link {
        font-family: 'IRANSans', 'Tahoma', 'Arial', sans-serif;
    }
    
    /* ===== ریسپانسیو برای موبایل ===== */
    @media (max-width: 576px) {
        .pagination .page-link {
            min-width: 30px;
            height: 30px;
            padding: 0 6px;
            font-size: 12px;
        }
        
        .pagination {
            gap: 2px;
        }
    }
    
    /* ===== اصلاح راست‌چینی ===== */
    .pagination .page-item:first-child .page-link {
        border-radius: 6px;
    }
    
    .pagination .page-item:last-child .page-link {
        border-radius: 6px;
    }
    
    /* برای Bootstrap 5 RTL */
    .pagination {
        --bs-pagination-border-radius: 6px;
        --bs-pagination-active-bg: #0d6efd;
        --bs-pagination-active-border-color: #0d6efd;
        --bs-pagination-hover-border-color: #0d6efd;
    }
</style>
@endsection

<div>
    <!-- عنوان صفحه -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-speedometer2"></i>
            داشبورد
        </h1>
    </div>

    <!-- کارت‌های آماری -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-primary text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-white-50">تعداد کل بکاپ‌ها</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($totalBackups) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-primary">
                                <i class="bi bi-database fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-success text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-white-50">بکاپ‌های موفق</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($successCount) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-success">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-danger text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-white-50">بکاپ‌های ناموفق</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($failedCount) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-danger">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stats {{ $successRate >= 90 ? 'bg-info' : ($successRate >= 70 ? 'bg-warning' : 'bg-danger') }} text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-white-50">درصد موفقیت</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $successRate }}%</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-info">
                                <i class="bi bi-graph-up-arrow fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- گراف هفتگی و آمار واحدها -->
    <div class="row g-3 mb-4">
        <div class="col-xl-7">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart"></i>
                        آمار هفتگی بکاپ‌ها
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building"></i>
                        آمار بر اساس واحد
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>واحد</th>
                                    <th class="text-center">موفق</th>
                                    <th class="text-center">ناموفق</th>
                                    <th class="text-center">درصد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitStats as $stat)
                                    <tr>
                                        <td>{{ $stat['unit_name'] }}</td>
                                        <td class="text-center text-success">{{ $stat['success'] }}</td>
                                        <td class="text-center text-danger">{{ $stat['failed'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $stat['color'] }}">
                                                {{ $stat['success_rate'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">داده‌ای موجود نیست</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فیلترها و جدول -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-list-ul"></i>
                لیست بکاپ‌ها
            </h5>
        </div>
        <div class="card-body">
            <!-- فیلترها -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">واحد</label>
                    <select class="form-select" wire:model.live="unit_id">
                        <option value="">همه واحدها</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">وضعیت</label>
                    <select class="form-select" wire:model.live="status">
                        <option value="">همه</option>
                        <option value="success">موفق</option>
                        <option value="failed">ناموفق</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">از تاریخ</label>
                    <input type="text" class="form-control" wire:model.live="date_from" placeholder="1404/01/01">
                </div>

                <div class="col-md-2">
                    <label class="form-label">تا تاریخ</label>
                    <input type="text" class="form-control" wire:model.live="date_to" placeholder="1404/12/29">
                </div>

                <div class="col-md-3">
                    <label class="form-label">جستجو</label>
                    <div class="input-group">
                        <input type="text" class="form-control" wire:model.live="search" placeholder="نام واحد یا فایل...">
                        <button class="btn btn-outline-secondary" type="button" wire:click="resetFilters">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- جدول -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>واحد</th>
                            <th>سیستم</th>
                            <th>تاریخ</th>
                            <th>وضعیت</th>
                            <th>نام فایل</th>
                            <th>حجم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $backup)
                            <tr>
                                <td>{{ $backups->firstItem() + $loop->index }}</td>
                                <td>{{ $backup->sftpAccount?->unit?->name ?? 'نامشخص' }}</td>
                                <td>{{ $backup->sftpAccount?->system?->name_fa ?? 'نامشخص' }}</td>
                                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($backup->uploaded_at)->format('Y/m/d H:i') }}</td>
                                <td>
                                    @if($backup->status == 'success')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                            موفق
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i>
                                            ناموفق
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $backup->file_name ?? '---' }}</td>
                                <td>{{ $backup->file_size ? number_format($backup->file_size / 1024, 2) . ' KB' : '---' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-2 d-block"></i>
                                    <span class="text-muted">هیچ رکوردی یافت نشد</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                <div class="text-muted small">
                    <i class="bi bi-info-circle"></i>
                    نمایش {{ $backups->firstItem() ?? 0 }} تا {{ $backups->lastItem() ?? 0 }} از {{ $backups->total() }} رکورد
                </div>
                <div class="d-flex justify-content-center">
                    {{ $backups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="{{ asset('assets/lib/chart/chart.js') }}"></script>
<script>
    let chartInstance = null;

    function renderChart() {
        const ctx = document.getElementById('weeklyChart');
        if (!ctx) {
            console.log('❌ عنصر canvas پیدا نشد');
            return;
        }
        
        // ⭐ داده‌ها رو مستقیم از پراپرتی کامپوننت میگیریم
        const weeklyStats = @this.weeklyStats;
        
        console.log('📊 داده‌های آماری:', weeklyStats);
        
        if (!weeklyStats || weeklyStats.length === 0) {
            console.log('⚠️ داده‌ای برای نمایش وجود ندارد');
            return;
        }
        
        if (chartInstance) {
            chartInstance.destroy();
            chartInstance = null;
        }
        
        const labels = weeklyStats.map(item => item.date);
        const successData = weeklyStats.map(item => item.success);
        const failedData = weeklyStats.map(item => item.failed);
        
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'موفق',
                        data: successData,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgb(40, 167, 69)',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'ناموفق',
                        data: failedData,
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: 'rgb(220, 53, 69)',
                        borderWidth: 2,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'IRANSans, Tahoma, sans-serif' },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
        
        console.log('✅ چارت با موفقیت رندر شد');
    }

    // اجرا در زمان‌های مختلف
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(renderChart, 500);
    });

    document.addEventListener('livewire:init', function() {
        setTimeout(renderChart, 500);
    });

    document.addEventListener('livewire:update', function() {
        // ⭐ بعد از هر آپدیت، دوباره چارت رو رندر نکن!
        // فقط اگر داده‌ها خالی شدن، دوباره رندر کن
        const ctx = document.getElementById('weeklyChart');
        if (ctx && !chartInstance) {
            setTimeout(renderChart, 300);
        }
    });

    window.addEventListener('load', function() {
        setTimeout(renderChart, 300);
    });
</script>
@endsection