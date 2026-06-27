@section('css')
<style>
    .info-item {
        padding: 6px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-item label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .password-mask {
        font-family: monospace;
        letter-spacing: 2px;
    }
    .toggle-password {
        cursor: pointer;
        padding: 2px 8px;
        font-size: 0.8rem;
    }
    .toggle-password:hover {
        background-color: #e9ecef;
    }
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .border-success {
        border-top-color: #198754 !important;
    }
    .border-danger {
        border-top-color: #dc3545 !important;
    }
    code {
        background-color: #f8f9fa;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #d63384;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endsection

<div>
    <!-- آمار اکانت‌ها -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-white-50">تعداد کل اکانت‌ها</h6>
                    <h2 class="mb-0">{{ $accounts->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-white-50">اکانت‌های فعال</h6>
                    <h2 class="mb-0">{{ $accounts->where('is_active', true)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-white-50">اکانت‌های غیرفعال</h6>
                    <h2 class="mb-0">{{ $accounts->where('is_active', false)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h6 class="card-title text-white-50">سیستم‌های متصل</h6>
                    <h2 class="mb-0">{{ $accounts->pluck('system_id')->unique()->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- لیست اکانت‌ها -->
    @if($accounts->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                <h5 class="text-muted">هیچ اکانت SFTP برای شما ثبت نشده است</h5>
                <p class="text-muted small">برای دریافت اکانت با مدیر سیستم تماس بگیرید</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($accounts as $account)
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="card shadow h-100 border-{{ $account->is_active ? 'success' : 'danger' }} border-top border-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-hdd-stack text-primary"></i>
                                {{ $account->unit->name ?? 'واحد نامشخص' }}
                            </h5>
                            <div>
                                {!! $this->getStatusBadge($account->is_active) !!}
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- ستون اول -->
                                <div class="col-md-6">
                                    <!-- واحد -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">واحد</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-building text-primary"></i>
                                            {{ $account->unit->name ?? 'نامشخص' }}
                                        </div>
                                    </div>

                                    <!-- سیستم -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">سیستم / نرم‌افزار</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-code-square text-info"></i>
                                            {{ $account->system->name_fa ?? 'نامشخص' }}
                                            @if($account->system->name_en)
                                                <span class="text-muted small">({{ $account->system->name_en }})</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- نام کاربری -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">نام کاربری</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-person text-success"></i>
                                            <code>{{ $account->username }}</code>
                                        </div>
                                    </div>

                                    <!-- پسورد -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">پسورد</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-key text-warning"></i>
                                            @if($account->password)
                                                <span class="password-mask" id="password-{{ $account->id }}">
                                                    ••••••••
                                                </span>
                                                <button class="btn btn-sm btn-outline-secondary ms-2 toggle-password" 
                                                        data-target="password-{{ $account->id }}"
                                                        data-password="{{ $account->password }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- ⭐ Passphrase -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">پسورد کلید خصوصی</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-shield-lock text-warning"></i>
                                            @if($account->passphrase)
                                                <span class="password-mask" id="passphrase-{{ $account->id }}">
                                                    ••••••••
                                                </span>
                                                <button class="btn btn-sm btn-outline-secondary ms-2 toggle-password" 
                                                        data-target="passphrase-{{ $account->id }}"
                                                        data-password="{{ $account->passphrase }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- ستون دوم -->
                                <div class="col-md-6">
                                    <!-- هاست -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">هاست</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-server text-secondary"></i>
                                            {{ $account->host ?? '10.10.10.9' }}
                                            <span class="text-muted small">:{{ $account->port ?? 32 }}</span>
                                        </div>
                                    </div>

                                    <!-- نوع دیتابیس -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">نوع دیتابیس</label>
                                        <div class="fw-bold">
                                            {!! $this->getDatabaseBadge($account->database_type) !!}
                                        </div>
                                    </div>

                                    <!-- روزهای بکاپ -->
                                    <div class="info-item">
                                        <label class="text-muted small d-block">روزهای بکاپ‌گیری</label>
                                        <div class="fw-bold">
                                            <i class="bi bi-calendar-week text-danger"></i>
                                            @php
                                                $days = $account->days_of_week;
                                                if (is_string($days)) {
                                                    $days = json_decode($days, true);
                                                }
                                                $daysMap = [
                                                    0 => 'یکشنبه',
                                                    1 => 'دوشنبه',
                                                    2 => 'سه‌شنبه',
                                                    3 => 'چهارشنبه',
                                                    4 => 'پنج‌شنبه',
                                                    5 => 'جمعه',
                                                    6 => 'شنبه',
                                                ];
                                                $persianDays = [];
                                                if (is_array($days)) {
                                                    foreach ($days as $day) {
                                                        $persianDays[] = $daysMap[$day] ?? $day;
                                                    }
                                                }
                                            @endphp
                                            @if(!empty($persianDays))
                                                <span class="badge bg-danger me-1" style="font-size: 0.85rem;">
                                                    {{ implode(' | ', $persianDays) }}
                                                </span>
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- تاریخ‌ها (تمام عرض) -->
                                <div class="col-12">
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span>
                                            <i class="bi bi-calendar-plus"></i>
                                            ایجاد: {{ \Morilog\Jalali\Jalalian::fromCarbon($account->created_at)->format('Y/m/d H:i') }}
                                        </span>
                                        <span>
                                            <i class="bi bi-pencil-square"></i>
                                            آخرین بروزرسانی: {{ \Morilog\Jalali\Jalalian::fromCarbon($account->updated_at)->format('Y/m/d H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ⭐ دکمه دانلود کلید خصوصی -->
                        <div class="card-footer bg-white">
                            @if(!empty($account->private_key))
                                <button type="button" 
                                        class="btn btn-sm btn-outline-success w-100"
                                        wire:click="downloadPrivateKey({{ $account->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="downloadPrivateKey({{ $account->id }})">
                                    <span wire:loading.remove wire:target="downloadPrivateKey({{ $account->id }})">
                                        <i class="bi bi-download"></i>
                                        دانلود کلید خصوصی
                                    </span>
                                    <span wire:loading wire:target="downloadPrivateKey({{ $account->id }})">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        در حال دانلود...
                                    </span>
                                </button>
                            @else
                                <div class="text-center text-muted small">
                                    <i class="bi bi-info-circle"></i>
                                    کلید خصوصی برای این اکانت ثبت نشده است
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // نمایش/مخفی کردن پسورد
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const passwordSpan = document.getElementById(targetId);
                const currentText = passwordSpan.textContent;
                const password = this.dataset.password;
                
                if (currentText === '••••••••') {
                    passwordSpan.textContent = password;
                    this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                } else {
                    passwordSpan.textContent = '••••••••';
                    this.innerHTML = '<i class="bi bi-eye"></i>';
                }
            });
        });
    });
</script>
@endsection