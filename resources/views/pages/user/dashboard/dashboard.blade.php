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
    
    .table > :not(caption) > * > * {
        padding: 0.5rem 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .badge.bg-info.text-dark {
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>
@endsection

<div>
    <!-- ============================================ -->
    <!-- بخش فیلترها -->
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
                <div class="col-md-3">
                    <label class="form-label">اکانت SFTP</label>
                    <select class="form-select" wire:model.live="sftp_account_id">
                        <option value="">همه اکانت‌ها</option>
                        @foreach($sftpAccounts as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->unit->name ?? 'بدون واحد' }} 
                                ({{ $account->system->name_fa ?? 'بدون سیستم' }})
                            </option>
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
                    <input type="text" class="form-control" wire:model.live="date_from" placeholder="1403/08/12">
                </div>

                <div class="col-md-2">
                    <label class="form-label">تا تاریخ</label>
                    <input type="text" class="form-control" wire:model.live="date_to" placeholder="1405/12/29">
                </div>

                <div class="col-md-3">
                    <label class="form-label">جستجو</label>
                    <div class="input-group">
                        <input type="text" class="form-control" wire:model.live="search" placeholder="واحد، نرم‌افزار، فایل...">
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
                            <th>اکانت SFTP</th>
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
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $backup->sftpAccount?->username ?? '---' }}
                                    </span>
                                </td>
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

@section('script')@endsection