@section('css')
<style>
    .card-stats .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .card-stats .icon-circle i {
        font-size: 1.5rem;
    }
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .nav-tabs .nav-link {
        font-weight: 500;
    }
    .nav-tabs .nav-link .badge {
        font-size: 0.7rem;
        margin-right: 4px;
    }
    /* استایل مودال */
    .modal-header .btn-close-white {
        filter: brightness(0) invert(1);
    }
</style>
@endsection

<div>
    <!-- ============================================ -->
    <!-- کارت‌های آماری -->
    <!-- ============================================ -->
    <div class="row g-3 mb-4">
        <!-- کاربران -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-primary text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-white-50">👥 کاربران</h6>
                            <h2 class="mb-0">{{ number_format($totalUsers) }}</h2>
                            <small class="text-white-50">
                                مدیر: {{ $totalAdmins }} | مدیر سیستم: {{ $totalManagers }} | کاربر: {{ $totalUsersRole }}
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-primary">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- اکانت‌ها -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-success text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-white-50">🔐 اکانت‌های SFTP</h6>
                            <h2 class="mb-0">{{ number_format($totalSftpAccounts) }}</h2>
                            <small class="text-white-50">
                                فعال: {{ $activeSftpAccounts }} | غیرفعال: {{ $inactiveSftpAccounts }}
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-success">
                                <i class="bi bi-server fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- سیستم‌ها -->
        <div class="col-xl-2 col-md-6">
            <div class="card card-stats bg-info text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-white-50">🖥️ نرم‌افزارها</h6>
                            <h2 class="mb-0">{{ number_format($totalSystems) }}</h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-info">
                                <i class="bi bi-code-square fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بکاپ‌های امروز -->
        <div class="col-xl-2 col-md-6">
            <div class="card card-stats bg-warning text-dark shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-dark-50">📦 بکاپ‌های امروز</h6>
                            <h2 class="mb-0">{{ number_format($totalBackupsToday) }}</h2>
                            <small>
                                موفق: {{ $successBackupsToday }} | ناموفق: {{ $failedBackupsToday }}
                            </small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-warning">
                                <i class="bi bi-archive fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- درصد موفقیت -->
        <div class="col-xl-2 col-md-6">
            <div class="card card-stats {{ $overallSuccessRate >= 90 ? 'bg-success' : ($overallSuccessRate >= 70 ? 'bg-warning text-dark' : 'bg-danger') }} text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="card-title text-white-50">📊 درصد موفقیت کلی</h6>
                            <h2 class="mb-0">{{ $overallSuccessRate }}%</h2>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-white text-success">
                                <i class="bi bi-graph-up-arrow fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- بخش نمودارها -->
    <!-- ============================================ -->
    <div class="row g-3 mb-4">
        <!-- چارت هفتگی -->
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart"></i>
                        آمار هفتگی بکاپ‌ها
                    </h5>
                </div>
                <div class="card-body" wire:ignore>
                    <div id="weeklyChartContainer" style="height: 250px;">
                        <div id="weeklyChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- نمودار توزیع کاربران و سیستم‌های پرکاربرد -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart"></i>
                        توزیع نقش کاربران
                    </h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" wire:ignore>
                    <div id="userRoleChart" style="height: 200px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- پرکاربردترین سیستم‌ها -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy"></i>
                        پرکاربردترین سیستم‌ها
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>سیستم</th>
                                    <th class="text-center">اکانت</th>
                                    <th class="text-center">بکاپ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($systemUsageStats as $stat)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $stat['name_en'] ?? $stat['name'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $stat['accounts'] }}</td>
                                        <td class="text-center">{{ $stat['backups'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">داده‌ای موجود نیست</td>
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
    <!-- تاپ‌های مدیریت -->
    <!-- ============================================ -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $active_tab == 'users' ? 'active' : '' }}" 
                    wire:click="setTab('users')" type="button">
                <i class="bi bi-people"></i> کاربران
                <span class="badge bg-primary">{{ $totalUsers }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $active_tab == 'accounts' ? 'active' : '' }}" 
                    wire:click="setTab('accounts')" type="button">
                <i class="bi bi-server"></i> اکانت‌های SFTP
                <span class="badge bg-success">{{ $totalSftpAccounts }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $active_tab == 'systems' ? 'active' : '' }}" 
                    wire:click="setTab('systems')" type="button">
                <i class="bi bi-code-square"></i> نرم‌افزارها
                <span class="badge bg-info">{{ $totalSystems }}</span>
            </button>
        </li>
    </ul>

    <!-- ============================================ -->
    <!-- فیلترها -->
    <!-- ============================================ -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">جستجو</label>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="جستجو...">
                </div>

                @if($active_tab == 'users')
                    <div class="col-md-3">
                        <label class="form-label">نقش</label>
                        <select class="form-select" wire:model.live="role_filter">
                            <option value="">همه نقش‌ها</option>
                            <option value="admin">مدیر</option>
                            <option value="manager">مدیر سیستم</option>
                            <option value="user">کاربر عادی</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select" wire:model.live="status_filter">
                            <option value="">همه</option>
                            <option value="active">فعال</option>
                            <option value="deactive">غیرفعال</option>
                        </select>
                    </div>
                @endif

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                        <i class="bi bi-x-circle"></i> پاک کردن فیلترها
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- لیست‌ها بر اساس تب انتخاب شده -->
    <!-- ============================================ -->
    <div class="card shadow">
        <div class="card-body">
            @if($active_tab == 'users')
                <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>نام و نام‌خانوادگی</th>
                    <th>نام کاربری</th>
                    <th>نقش</th>
                    <th>تعداد اکانت</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->name }} {{ $user->family }}</td>
                        <td><code>{{ $user->username }}</code></td>
                        <td>
                            @php
                                $roleLabels = ['admin' => 'مدیر', 'manager' => 'مدیر سیستم', 'user' => 'کاربر عادی'];
                                $roleColors = ['admin' => 'danger', 'manager' => 'warning', 'user' => 'info'];
                            @endphp
                            <span class="badge bg-{{ $roleColors[$user->user_type] ?? 'secondary' }}">
                                {{ $roleLabels[$user->user_type] ?? $user->user_type }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $user->sftp_accounts_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                {{ $user->status == 'active' ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-{{ $user->status == 'active' ? 'danger' : 'success' }}"
                                    wire:click="toggleUserStatus({{ $user->id }})">
                                <i class="bi bi-{{ $user->status == 'active' ? 'slash-circle' : 'check-circle' }}"></i>
                                {{ $user->status == 'active' ? 'غیرفعال' : 'فعال' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">هیچ کاربری یافت نشد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">نمایش {{ $users->firstItem() ?? 0 }} تا {{ $users->lastItem() ?? 0 }} از {{ $users->total() }}</small>
        {{ $users->links() }}
    </div>
            @elseif($active_tab == 'accounts')
                <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>کاربر</th>
                    <th>واحد</th>
                    <th>سیستم</th>
                    <th>نام کاربری</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sftpAccounts as $account)
                    <tr>
                        <td>{{ $sftpAccounts->firstItem() + $loop->index }}</td>
                        <td>{{ $account->user?->name ?? 'نامشخص' }} {{ $account->user?->family ?? '' }}</td>
                        <td>{{ $account->unit?->name ?? 'نامشخص' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $account->system?->name_en ?? '---' }}
                            </span>
                        </td>
                        <td><code>{{ $account->username }}</code></td>
                        <td>
                            <span class="badge bg-{{ $account->is_active ? 'success' : 'danger' }}">
                                {{ $account->is_active ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-{{ $account->is_active ? 'danger' : 'success' }}"
                                    wire:click="toggleAccountStatus({{ $account->id }})">
                                <i class="bi bi-{{ $account->is_active ? 'slash-circle' : 'check-circle' }}"></i>
                                {{ $account->is_active ? 'غیرفعال' : 'فعال' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">هیچ اکانتی یافت نشد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">نمایش {{ $sftpAccounts->firstItem() ?? 0 }} تا {{ $sftpAccounts->lastItem() ?? 0 }} از {{ $sftpAccounts->total() }}</small>
        {{ $sftpAccounts->links() }}
    </div>
            @elseif($active_tab == 'systems')
                <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>نام فارسی</th>
                    <th>نام انگلیسی</th>
                    <th class="text-center">تعداد اکانت</th>
                    <th class="text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($systems as $system)
                    <tr>
                        <td>{{ $systems->firstItem() + $loop->index }}</td>
                        <td>{{ $system->name_fa }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $system->name_en ?? '---' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $system->sftp_accounts_count }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" 
                                    wire:click="editSystem({{ $system->id }})"
                                    title="ویرایش نرم‌افزار">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">هیچ نرم‌افزاری یافت نشد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">نمایش {{ $systems->firstItem() ?? 0 }} تا {{ $systems->lastItem() ?? 0 }} از {{ $systems->total() }}</small>
        {{ $systems->links() }}
    </div>
            @endif
        </div>
    </div>

    <!-- ============================================ -->
    <!-- مودال ویرایش نرم‌افزار -->
    <!-- ============================================ -->
    <div class="modal fade" id="editSystemModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i>
                        ویرایش نرم‌افزار
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="updateSystem">
                        <div class="mb-3">
                            <label class="form-label">نام فارسی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('editSystemNameFa') is-invalid @enderror" 
                                wire:model="editSystemNameFa" placeholder="مثلاً: سیستم مدیریت بیمارستان">
                            @error('editSystemNameFa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">نام انگلیسی</label>
                            <input type="text" class="form-control @error('editSystemNameEn') is-invalid @enderror" 
                                wire:model="editSystemNameEn" placeholder="مثلاً: HIS">
                            @error('editSystemNameEn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="cancelEdit">
                                <i class="bi bi-x-circle"></i> انصراف
                            </button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-check-circle"></i> ذخیره تغییرات
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    در حال ذخیره...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="{{ asset('assets/lib/chart/apexcharts.js') }}"></script>
<script>
    let weeklyChart = null;
    let userRoleChart = null;

    function renderWeeklyChart() {
        const container = document.getElementById('weeklyChart');
        if (!container) return;

        const stats = @json($weeklyStats);
        if (!stats || stats.length === 0) return;

        if (weeklyChart) {
            weeklyChart.destroy();
            weeklyChart = null;
        }

        weeklyChart = new ApexCharts(container, {
            series: [
                { name: 'موفق', data: stats.map(i => i.success), color: '#198754' },
                { name: 'ناموفق', data: stats.map(i => i.failed), color: '#dc3545' }
            ],
            chart: { type: 'bar', height: 200, toolbar: { show: false } },
            plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
            dataLabels: { enabled: true, offsetY: -10, style: { fontSize: '10px' } },
            xaxis: { categories: stats.map(i => i.date), labels: { style: { fontSize: '10px' } } },
            yaxis: { min: 0, tickAmount: 4 },
            legend: { position: 'top', fontFamily: 'IRANSans, Tahoma, sans-serif' },
        });
        weeklyChart.render();
    }

    function renderUserRoleChart() {
        const container = document.getElementById('userRoleChart');
        if (!container) return;

        const stats = @json($userRoleStats);
        if (!stats || stats.length === 0) return;

        if (userRoleChart) {
            userRoleChart.destroy();
            userRoleChart = null;
        }

        userRoleChart = new ApexCharts(container, {
            series: stats.map(i => i.value),
            labels: stats.map(i => i.label),
            colors: stats.map(i => i.color),
            chart: { type: 'donut', height: 200 },
            legend: { position: 'bottom', fontFamily: 'IRANSans, Tahoma, sans-serif' },
            dataLabels: { enabled: true, style: { fontSize: '11px', fontFamily: 'IRANSans' } },
            plotOptions: { pie: { expandOnClick: true } }
        });
        userRoleChart.render();
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(renderWeeklyChart, 300);
        setTimeout(renderUserRoleChart, 300);
    });

    document.addEventListener('livewire:update', function() {
        setTimeout(renderWeeklyChart, 300);
        setTimeout(renderUserRoleChart, 300);
    });

    // ===== مدیریت مودال =====
    window.addEventListener('show-edit-modal', event => {
        const modal = new bootstrap.Modal(document.getElementById('editSystemModal'));
        modal.show();
    });

    window.addEventListener('hide-edit-modal', event => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editSystemModal'));
        if (modal) {
            modal.hide();
        }
    });

    // وقتی مودال بسته میشه، فرم رو ریست کن
    document.addEventListener('hidden.bs.modal', function (event) {
        if (event.target.id === 'editSystemModal') {
            @this.cancelEdit();
        }
    });
</script>
@endsection