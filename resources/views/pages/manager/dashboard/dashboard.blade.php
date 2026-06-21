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
    
    /* استایل برای بج نرم‌افزار */
    .badge.bg-info.text-dark {
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>
@endsection

<div>
    <!-- ============================================ -->
    <!-- بخش فیلترها (انتقال به بالای کارت‌ها) -->
    <!-- ============================================ -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-funnel"></i>
                فیلترها
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">واحد</label>
                    <select class="form-select" wire:model.live="unit_id">
                        <option value="">همه واحدها</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">نرم‌افزار</label>
                    <select class="form-select" wire:model.live="system_id">
                        <option value="">همه نرم‌افزارها</option>
                        @foreach($systems as $system)
                            <option value="{{ $system->id }}">{{ $system->name_fa }} ({{ $system->name_en }})</option>
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

                <div class="col-md-2">
                    <label class="form-label">جستجو</label>
                    <div class="input-group">
                        <input type="text" class="form-control" wire:model.live="search" placeholder="نام واحد یا نرم‌افزار...">
                        <button class="btn btn-outline-secondary" type="button" wire:click="resetFilters">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- کارت‌های آماری -->
    <!-- ============================================ -->
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

    <!-- ============================================ -->
    <!-- آمار هفتگی و آمار واحدها/سیستم‌ها -->
    <!-- ============================================ -->
    <div class="row g-3 mb-4">
        <div class="col-xl-7">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart"></i>
                        آمار هفتگی بکاپ‌ها
                    </h5>
                    <span class="badge bg-primary" id="chart-update-time">
                        <i class="bi bi-clock"></i>
                        بروزرسانی: {{ now()->format('H:i') }}
                    </span>
                </div>
                <div class="card-body" wire:ignore>
                    <div id="weeklyChartContainer" style="height: 280px;">
                        <div id="weeklyChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card shadow h-100">
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
                                    <th>نرم‌افزار</th>
                                    <th class="text-center">موفق</th>
                                    <th class="text-center">ناموفق</th>
                                    <th class="text-center">درصد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitStats as $stat)
                                    <tr>
                                        <td>{{ $stat['unit_name'] }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $stat['system_name_en'] ?: $stat['system_name'] }}
                                            </span>
                                        </td>
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
                                        <td colspan="5" class="text-center">داده‌ای موجود نیست</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- جدول لیست بکاپ‌ها -->
    <!-- ============================================ -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-list-ul"></i>
                لیست بکاپ‌ها
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>واحد</th>
                            <th>نرم‌افزار</th>
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
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $backup->sftpAccount?->system?->name_en ?? '---' }}
                                    </span>
                                </td>
                                <td>{{ $backup->sftpAccount?->system?->name_fa ?? 'نامشخص' }}</td>
                                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($backup->uploaded_at)->format('Y/m/d') }}</td>
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
                                <td colspan="8" class="text-center py-4">
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
                <div>
                    {{ $backups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="{{ asset('assets/lib/chart/apexcharts.js') }}"></script>
<script>
    let chartInstance = null;

    function renderChart() {
        const weeklyStats = @json($weeklyStats);
        
        console.log('📊 داده‌های آماری:', weeklyStats);
        
        if (!weeklyStats || weeklyStats.length === 0) {
            console.log('⚠️ داده‌ای برای نمایش وجود ندارد');
            return;
        }

        const labels = weeklyStats.map(item => item.date);
        const successData = weeklyStats.map(item => item.success);
        const failedData = weeklyStats.map(item => item.failed);
        
        console.log('📌 برچسب‌ها:', labels);
        console.log('🟢 موفق:', successData);
        console.log('🔴 ناموفق:', failedData);

        const options = {
            series: [
                {
                    name: 'موفق',
                    data: successData,
                    color: '#198754'
                },
                {
                    name: 'ناموفق',
                    data: failedData,
                    color: '#dc3545'
                }
            ],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 500
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -15,
                style: {
                    fontSize: '11px',
                    fontFamily: 'IRANSans, Tahoma, sans-serif',
                    fontWeight: 600,
                    colors: ['#333']
                },
                formatter: function(val) {
                    return val > 0 ? val : '';
                }
            },
            xaxis: {
                categories: labels,
                labels: {
                    style: {
                        fontFamily: 'IRANSans, Tahoma, sans-serif',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'تعداد بکاپ',
                    style: {
                        fontFamily: 'IRANSans, Tahoma, sans-serif',
                        fontSize: '12px'
                    }
                },
                min: 0,
                tickAmount: 5,
                labels: {
                    style: {
                        fontFamily: 'IRANSans, Tahoma, sans-serif',
                        fontSize: '11px'
                    },
                    formatter: function(val) {
                        return Math.round(val);
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                fontFamily: 'IRANSans, Tahoma, sans-serif',
                fontSize: '13px',
                markers: {
                    radius: 8,
                    width: 16,
                    height: 16
                },
                itemMargin: {
                    horizontal: 15,
                    vertical: 5
                }
            },
            grid: {
                borderColor: '#e9ecef',
                strokeDashArray: 4,
                position: 'back'
            },
            tooltip: {
                theme: 'light',
                style: {
                    fontFamily: 'IRANSans, Tahoma, sans-serif'
                },
                y: {
                    formatter: function(val) {
                        return val + ' عدد';
                    }
                }
            }
        };

        const container = document.getElementById('weeklyChart');
        
        if (chartInstance) {
            // بهروزرسانی چارت موجود با داده‌های جدید
            chartInstance.updateOptions({
                series: options.series,
                xaxis: {
                    categories: labels
                }
            });
            console.log('✅ چارت به‌روزرسانی شد');
        } else {
            // ایجاد چارت جدید
            chartInstance = new ApexCharts(container, options);
            chartInstance.render();
            console.log('✅ چارت جدید ساخته شد');
        }
    }

    // تابع برای رندر با تلاش مجدد
    function renderChartWithRetry(attempts = 0) {
        const maxAttempts = 10;
        if (attempts > maxAttempts) {
            console.log('❌ حداکثر تلاش برای رندر چارت رد شد');
            return;
        }
        
        const container = document.getElementById('weeklyChart');
        if (container) {
            console.log(`✅ عنصر چارت پیدا شد (تلاش ${attempts + 1})`);
            renderChart();
        } else {
            console.log(`⏳ انتظار برای بارگذاری عنصر... (تلاش ${attempts + 1})`);
            setTimeout(() => {
                renderChartWithRetry(attempts + 1);
            }, 300);
        }
    }

    // اجرا در زمان‌های مختلف
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔄 DOMContentLoaded');
        setTimeout(renderChartWithRetry, 500);
    });

    document.addEventListener('livewire:init', function() {
        console.log('🔄 livewire:init');
        setTimeout(renderChartWithRetry, 500);
    });

    document.addEventListener('livewire:update', function() {
        console.log('🔄 livewire:update - به‌روزرسانی چارت');
        setTimeout(renderChartWithRetry, 300);
    });

    window.addEventListener('load', function() {
        console.log('🔄 load');
        setTimeout(renderChartWithRetry, 300);
    });
</script>
@endsection