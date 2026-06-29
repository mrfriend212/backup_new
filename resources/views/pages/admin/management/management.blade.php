@section('css')
<!-- TomSelect CSS -->
<link href="{{asset('assets/lib/tom-select/tom-select.bootstrap5.min.css')}}" rel="stylesheet">

<style>
    [x-cloak] {
        display: none !important;
    }

    /* دکمه‌های عملیات */
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.75rem;
        border-radius: 4px;
    }

    .btn-sm i {
        font-size: 0.9rem;
    }

    /* دکمه‌های دانلود کلید */
    .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #000;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }
    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: #000;
    }
</style>
@endsection

<div>
    <!-- ============================================ -->
    <!-- تاپ‌های مدیریت -->
    <!-- ============================================ -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link {{ $activeTab == 'users' ? 'active' : '' }}" 
                    wire:click="setTab('users')" type="button">
                <i class="bi bi-people"></i> کاربران
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab == 'units' ? 'active' : '' }}" 
                    wire:click="setTab('units')" type="button">
                <i class="bi bi-building"></i> واحدها
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab == 'systems' ? 'active' : '' }}" 
                    wire:click="setTab('systems')" type="button">
                <i class="bi bi-code-square"></i> نرم‌افزارها
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab == 'accounts' ? 'active' : '' }}" 
                    wire:click="setTab('accounts')" type="button">
                <i class="bi bi-server"></i> اکانت‌های SFTP
            </button>
        </li>
    </ul>

    <!-- ============================================ -->
    <!-- محتوای تب‌ها -->
    <!-- ============================================ -->

    {{-- ===== تب کاربران ===== --}}
    @if($activeTab == 'users')
    <div class="card shadow mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-plus-circle"></i>
                {{ $isEditingUser ? 'ویرایش کاربر' : 'افزودن کاربر جدید' }}
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveUser">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">نام <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('userName') is-invalid @enderror" 
                                wire:model="userName" placeholder="نام">
                        @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">نام‌خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('userFamily') is-invalid @enderror" 
                                wire:model="userFamily" placeholder="نام‌خانوادگی">
                        @error('userFamily') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">نام کاربری <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('userUsername') is-invalid @enderror" 
                                wire:model="userUsername" placeholder="نام کاربری">
                        @error('userUsername') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">رمز عبور {{ $isEditingUser ? '(اختیاری)' : '' }} <span class="text-danger">@if(!$isEditingUser)*@endif</span></label>
                        <input type="password" class="form-control @error('userPassword') is-invalid @enderror" 
                                wire:model="userPassword" placeholder="رمز عبور">
                        @error('userPassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">نقش</label>
                        <select class="form-select @error('userType') is-invalid @enderror" wire:model="userType">
                            <option value="user">کاربر</option>
                            <option value="manager">مدیر سیستم</option>
                            <option value="admin">مدیر</option>
                        </select>
                        @error('userType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">وضعیت</label>
                        <select class="form-select @error('userStatus') is-invalid @enderror" wire:model="userStatus">
                            <option value="active">فعال</option>
                            <option value="deactive">غیرفعال</option>
                        </select>
                        @error('userStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-save"></i> {{ $isEditingUser ? 'ویرایش' : 'افزودن' }}</span>
                            <span wire:loading><span class="spinner-border spinner-border-sm"></span> در حال ذخیره...</span>
                        </button>
                        @if($isEditingUser)
                            <button type="button" class="btn btn-secondary" wire:click="resetForm">
                                <i class="bi bi-x-circle"></i> انصراف
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- لیست کاربران -->
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-list-ul"></i> لیست کاربران</h5>
            <div class="col-md-3">
                <input type="text" class="form-control" wire:model.live="search" placeholder="جستجو...">
            </div>
        </div>
        <div class="card-body">
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
                                <td><span class="badge bg-{{ $this->getRoleColor($user->user_type) }}">{{ $this->getRoleLabel($user->user_type) }}</span></td>
                                <td class="text-center"><span class="badge bg-primary">{{ $user->sftp_accounts_count }}</span></td>
                                <td><span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">{{ $user->status == 'active' ? 'فعال' : 'غیرفعال' }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editUser({{ $user->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="confirmDelete('user', {{ $user->id }}, 'حذف کاربر {{ $user->name }} {{ $user->family }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4">هیچ کاربری یافت نشد</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">نمایش {{ $users->firstItem() ?? 0 }} تا {{ $users->lastItem() ?? 0 }} از {{ $users->total() }}</small>
                {{ $users->links() }}
            </div>
        </div>
    </div>
    @endif

    {{-- ===== تب واحدها ===== --}}
    @if($activeTab == 'units')
    <div class="card shadow mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-plus-circle"></i>
                {{ $isEditingUnit ? 'ویرایش واحد' : 'افزودن واحد جدید' }}
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveUnit">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">نام واحد <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unitName') is-invalid @enderror" 
                                wire:model="unitName" placeholder="مثلاً: بیمارستان فاطمه الزهرا مهریز">
                        @error('unitName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-save"></i> {{ $isEditingUnit ? 'ویرایش' : 'افزودن' }}</span>
                            <span wire:loading><span class="spinner-border spinner-border-sm"></span></span>
                        </button>
                        @if($isEditingUnit)
                            <button type="button" class="btn btn-secondary ms-2" wire:click="resetForm">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-list-ul"></i> لیست واحدها</h5>
            <div class="col-md-3">
                <input type="text" class="form-control" wire:model.live="search" placeholder="جستجو...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>نام واحد</th>
                            <th class="text-center">تعداد اکانت</th>
                            <th class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                            <tr>
                                <td>{{ $units->firstItem() + $loop->index }}</td>
                                <td>{{ $unit->name }}</td>
                                <td class="text-center"><span class="badge bg-primary">{{ $unit->sftp_accounts_count }}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editUnit({{ $unit->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="confirmDelete('unit', {{ $unit->id }}, 'حذف واحد {{ $unit->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">هیچ واحدی یافت نشد</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">نمایش {{ $units->firstItem() ?? 0 }} تا {{ $units->lastItem() ?? 0 }} از {{ $units->total() }}</small>
                {{ $units->links() }}
            </div>
        </div>
    </div>
    @endif

    {{-- ===== تب نرم‌افزارها ===== --}}
    @if($activeTab == 'systems')
    <div class="card shadow mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-plus-circle"></i>
                {{ $isEditingSystem ? 'ویرایش نرم‌افزار' : 'افزودن نرم‌افزار جدید' }}
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveSystem">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">نام فارسی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('systemNameFa') is-invalid @enderror" 
                                wire:model="systemNameFa" placeholder="مثلاً: سیستم مدیریت بیمارستان">
                        @error('systemNameFa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">نام انگلیسی</label>
                        <input type="text" class="form-control @error('systemNameEn') is-invalid @enderror" 
                                wire:model="systemNameEn" placeholder="مثلاً: HIS">
                        @error('systemNameEn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-save"></i> {{ $isEditingSystem ? 'ویرایش' : 'افزودن' }}</span>
                            <span wire:loading><span class="spinner-border spinner-border-sm"></span></span>
                        </button>
                        @if($isEditingSystem)
                            <button type="button" class="btn btn-secondary ms-2" wire:click="resetForm">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-list-ul"></i> لیست نرم‌افزارها</h5>
            <div class="col-md-3">
                <input type="text" class="form-control" wire:model.live="search" placeholder="جستجو...">
            </div>
        </div>
        <div class="card-body">
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
                                <td><span class="badge bg-info text-dark">{{ $system->name_en ?? '---' }}</span></td>
                                <td class="text-center"><span class="badge bg-primary">{{ $system->sftp_accounts_count }}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editSystem({{ $system->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="confirmDelete('system', {{ $system->id }}, 'حذف نرم‌افزار {{ $system->name_fa }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">هیچ نرم‌افزاری یافت نشد</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">نمایش {{ $systems->firstItem() ?? 0 }} تا {{ $systems->lastItem() ?? 0 }} از {{ $systems->total() }}</small>
                {{ $systems->links() }}
            </div>
        </div>
    </div>
    @endif

    {{-- ===== تب اکانت‌های SFTP ===== --}}
    @if($activeTab == 'accounts')
    <div class="card shadow mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-plus-circle"></i>
                {{ $isEditingAccount ? 'ویرایش اکانت SFTP' : 'افزودن اکانت SFTP جدید' }}
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveAccount">
                <div class="row g-3">
                    <div class="col-md-4" x-data="{ initSelect() { 
                        setTimeout(() => {
                            const el = document.getElementById('accountUserIdSelect');
                            if (el && !el.tomselect) {
                                new TomSelect(el, {
                                    plugins: ['dropdown_input', 'clear_button'],
                                    placeholder: 'جستجوی کاربر...',
                                    maxItems: 1,
                                    searchField: ['text']
                                });
                            }
                        }, 300);
                    } }" x-init="initSelect()">
                        <label class="form-label">کاربر <span class="text-danger">*</span></label>
                        <select class="form-select @error('accountUserId') is-invalid @enderror" 
                                wire:model="accountUserId" 
                                id="accountUserIdSelect">
                            <option value="">انتخاب کاربر...</option>
                            @foreach($usersList as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->family }} ({{ $user->username }})</option>
                            @endforeach
                        </select>
                        @error('accountUserId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4" x-data="{ initSelect() { 
                        setTimeout(() => {
                            const el = document.getElementById('accountUnitIdSelect');
                            if (el && !el.tomselect) {
                                new TomSelect(el, {
                                    plugins: ['dropdown_input', 'clear_button'],
                                    placeholder: 'جستجوی واحد...',
                                    maxItems: 1,
                                    searchField: ['text']
                                });
                            }
                        }, 300);
                    } }" x-init="initSelect()">
                        <label class="form-label">واحد <span class="text-danger">*</span></label>
                        <select class="form-select @error('accountUnitId') is-invalid @enderror" 
                                wire:model="accountUnitId" 
                                id="accountUnitIdSelect">
                            <option value="">انتخاب واحد...</option>
                            @foreach($unitsList as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('accountUnitId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4" x-data="{ initSelect() { 
                        setTimeout(() => {
                            const el = document.getElementById('accountSystemIdSelect');
                            if (el && !el.tomselect) {
                                new TomSelect(el, {
                                    plugins: ['dropdown_input', 'clear_button'],
                                    placeholder: 'جستجوی نرم‌افزار...',
                                    maxItems: 1,
                                    searchField: ['text']
                                });
                            }
                        }, 300);
                    } }" x-init="initSelect()">
                        <label class="form-label">نرم‌افزار <span class="text-danger">*</span></label>
                        <select class="form-select @error('accountSystemId') is-invalid @enderror" 
                                wire:model="accountSystemId" 
                                id="accountSystemIdSelect">
                            <option value="">انتخاب نرم‌افزار...</option>
                            @foreach($systemsList as $system)
                                <option value="{{ $system->id }}">{{ $system->name_fa }} ({{ $system->name_en ?? '---' }})</option>
                            @endforeach
                        </select>
                        @error('accountSystemId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">نام کاربری <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('accountUsername') is-invalid @enderror" 
                                wire:model="accountUsername" placeholder="نام کاربری SFTP">
                        @error('accountUsername') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">رمز عبور</label>
                        <input type="password" class="form-control @error('accountPassword') is-invalid @enderror" 
                                wire:model="accountPassword" placeholder="رمز عبور">
                        @error('accountPassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">هاست</label>
                        <input type="text" class="form-control @error('accountHost') is-invalid @enderror" 
                                wire:model="accountHost" placeholder="10.10.10.9">
                        @error('accountHost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">پورت</label>
                        <input type="number" class="form-control @error('accountPort') is-invalid @enderror" 
                                wire:model="accountPort" placeholder="32">
                        @error('accountPort') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">مسیر ریشه</label>
                        <input type="text" class="form-control @error('accountRootPath') is-invalid @enderror" 
                                wire:model="accountRootPath" placeholder="/backups/">
                        @error('accountRootPath') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">نوع دیتابیس</label>
                        <select class="form-select @error('accountDatabaseType') is-invalid @enderror" wire:model="accountDatabaseType">
                            <option value="mysql">MySQL</option>
                            <option value="pgsql">PostgreSQL</option>
                            <option value="sqlite">SQLite</option>
                            <option value="sqlsrv">SQL Server</option>
                        </select>
                        @error('accountDatabaseType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">روزهای بکاپ <span class="text-danger">*</span></label>
                        <select class="form-select @error('accountDaysOfWeek') is-invalid @enderror" 
                                wire:model="accountDaysOfWeek" multiple size="4">
                            @foreach($weekDays as $day)
                                <option value="{{ $day['value'] }}">{{ $day['label'] }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">برای انتخاب چند روز، کلید Ctrl را نگه دارید</small>
                        @error('accountDaysOfWeek') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">پسورد کلید خصوصی</label>
                        <input type="text" class="form-control @error('accountPassphrase') is-invalid @enderror" 
                                wire:model="accountPassphrase" placeholder="passphrase">
                        @error('accountPassphrase') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model="accountIsActive" id="accountIsActive">
                            <label class="form-check-label" for="accountIsActive">
                                {{ $accountIsActive ? 'فعال' : 'غیرفعال' }}
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-save"></i> {{ $isEditingAccount ? 'ویرایش' : 'افزودن' }}</span>
                            <span wire:loading><span class="spinner-border spinner-border-sm"></span></span>
                        </button>
                        @if($isEditingAccount)
                            <button type="button" class="btn btn-secondary" wire:click="resetForm">
                                <i class="bi bi-x-circle"></i> انصراف
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-list-ul"></i> لیست اکانت‌های SFTP</h5>
            <div class="col-md-3">
                <input type="text" class="form-control" wire:model.live="search" placeholder="جستجو...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">کاربر</th>
                            <th style="width: 20%;">واحد</th>
                            <th style="width: 15%;">نرم‌افزار</th>
                            <th style="width: 15%;">نام کاربری</th>
                            <th style="width: 10%;">وضعیت</th>
                            <th style="width: 20%;" class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td>{{ $accounts->firstItem() + $loop->index }}</td>
                                <td>{{ $account->user?->name ?? 'نامشخص' }} {{ $account->user?->family ?? '' }}</td>
                                <td>{{ $account->unit?->name ?? 'نامشخص' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $account->system?->name_en ?? '---' }}</span></td>
                                <td><code>{{ $account->username }}</code></td>
                                <td><span class="badge bg-{{ $account->is_active ? 'success' : 'danger' }}">{{ $account->is_active ? 'فعال' : 'غیرفعال' }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editAccount({{ $account->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="confirmDelete('account', {{ $account->id }}, 'حذف اکانت {{ $account->username }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <!-- دانلود کلید خصوصی (فقط اگر وجود داشته باشد) -->
                                    @if(!empty($account->private_key))
                                        <button class="btn btn-sm btn-outline-warning" 
                                                wire:click="downloadPrivateKey({{ $account->id }})" 
                                                title="دانلود کلید خصوصی (.ppk)"
                                                wire:loading.attr="disabled"
                                                wire:target="downloadPrivateKey({{ $account->id }})">
                                            <span wire:loading.remove wire:target="downloadPrivateKey({{ $account->id }})">
                                                <i class="bi bi-key-fill"></i>
                                            </span>
                                            <span wire:loading wire:target="downloadPrivateKey({{ $account->id }})">
                                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                            </span>
                                        </button>
                                    @endif
                                    
                                    <!-- دانلود کلید عمومی (فقط اگر وجود داشته باشد) -->
                                    @if(!empty($account->public_key))
                                        <button class="btn btn-sm btn-outline-info" 
                                                wire:click="downloadPublicKey({{ $account->id }})" 
                                                title="دانلود کلید عمومی"
                                                wire:loading.attr="disabled"
                                                wire:target="downloadPublicKey({{ $account->id }})">
                                            <span wire:loading.remove wire:target="downloadPublicKey({{ $account->id }})">
                                                <i class="bi bi-file-lock"></i>
                                            </span>
                                            <span wire:loading wire:target="downloadPublicKey({{ $account->id }})">
                                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                            </span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4">هیچ اکانتی یافت نشد</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">نمایش {{ $accounts->firstItem() ?? 0 }} تا {{ $accounts->lastItem() ?? 0 }} از {{ $accounts->total() }}</small>
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
    @endif

    <!-- ============================================ -->
    <!-- مودال تأیید حذف با Alpine.js -->
    <!-- ============================================ -->
    <div x-data="{ 
        show: false,
        title: '',
        message: '',
        type: '',
        id: null,
        loading: false
    }"
    x-init="() => {
        window.addEventListener('show-delete-modal', () => {
            show = true;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
        window.addEventListener('hide-delete-modal', () => {
            show = false;
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (modal) modal.hide();
        });
    }"
    style="display: none;"
    x-show="show"
    x-cloak>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle"></i>
                        تأیید حذف
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" @click="show = false"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <i class="bi bi-trash3 text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">{{ $deleteModalTitle }}</h5>
                        <p class="text-muted">{{ $deleteModalMessage }}</p>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="cancelDelete">
                        <i class="bi bi-x-circle"></i> انصراف
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="performDelete" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-trash"></i> تأیید حذف</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm"></span> در حال حذف...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@section('script')
<!-- TomSelect JS -->
<script src="{{asset('assets/lib/tom-select/tom-select.complete.min.js')}}"></script>

<script>
// ===== فعال‌سازی TomSelect برای سلکت‌باکس‌ها =====
function initTomSelect() {
    try {
        // بررسی اینکه آیا در تب اکانت‌ها هستیم
        const isAccountsTab = document.querySelector('.nav-link.active')?.textContent?.includes('اکانت‌های SFTP') || 
                              document.querySelector('.nav-link.active')?.getAttribute('wire:click')?.includes('accounts');
        
        // اگر در تب اکانت‌ها نیستیم، سلکت‌ها را غیرفعال کن
        if (!isAccountsTab) {
            // اگر TomSelect قبلاً روی سلکت‌ها فعال شده، آنها را نابود کن
            ['accountUserIdSelect', 'accountUnitIdSelect', 'accountSystemIdSelect'].forEach(id => {
                const el = document.getElementById(id);
                if (el && el.tomselect) {
                    el.tomselect.destroy();
                }
            });
            return;
        }

        setTimeout(() => {
            // سلکت کاربران
            const userSelect = document.getElementById('accountUserIdSelect');
            if (userSelect && !userSelect.tomselect) {
                new TomSelect(userSelect, {
                    plugins: ['dropdown_input', 'clear_button'],
                    placeholder: 'جستجوی کاربر...',
                    maxItems: 1,
                    searchField: ['text'],
                    render: {
                        option: function(data, escape) {
                            return '<div class="py-1 px-2">' + escape(data.text) + '</div>';
                        }
                    }
                });
                console.log('✅ TomSelect - کاربران فعال شد');
            }

            // سلکت واحدها
            const unitSelect = document.getElementById('accountUnitIdSelect');
            if (unitSelect && !unitSelect.tomselect) {
                new TomSelect(unitSelect, {
                    plugins: ['dropdown_input', 'clear_button'],
                    placeholder: 'جستجوی واحد...',
                    maxItems: 1,
                    searchField: ['text']
                });
                console.log('✅ TomSelect - واحدها فعال شد');
            }

            // سلکت نرم‌افزارها
            const systemSelect = document.getElementById('accountSystemIdSelect');
            if (systemSelect && !systemSelect.tomselect) {
                new TomSelect(systemSelect, {
                    plugins: ['dropdown_input', 'clear_button'],
                    placeholder: 'جستجوی نرم‌افزار...',
                    maxItems: 1,
                    searchField: ['text']
                });
                console.log('✅ TomSelect - نرم‌افزارها فعال شد');
            }
        }, 400);
        
    } catch (e) {
        console.warn('⚠️ خطا در فعال‌سازی TomSelect:', e);
    }
}

// اجرا بعد از هر بار رندر Livewire
document.addEventListener('livewire:update', function() {
    // با تاخیر کوتاه تا DOM کامل به‌روز شود
    setTimeout(initTomSelect, 300);
});

// اجرا در بارگذاری اولیه
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initTomSelect, 500);
});

// اجرا بعد از تغییر تب (با MutationObserver)
const observer = new MutationObserver(function() {
    // بررسی تغییرات در تب‌ها
    const activeTab = document.querySelector('.nav-link.active');
    if (activeTab) {
        setTimeout(initTomSelect, 300);
    }
});
observer.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: true,
    attributeFilter: ['class']
});

// ============================================
// نمایش نوتیفیکیشن با Livewire.on (مستقل از Alpine.js)
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    
    Livewire.on('notify', (data) => {
        
        // استخراج پیام و نوع
        const message = data[0]?.message || data.message || 'پیام';
        const type = data[0]?.type || data.type || 'success';
        
        // ایجاد المان نوتیفیکیشن
        const notification = document.createElement('div');
        notification.id = 'livewire-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999999;
            width: 90%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            padding: 12px 20px;
            border: none;
            background-color: ${type === 'success' ? '#d4edda' : '#f8d7da'};
            border-right: 5px solid ${type === 'success' ? '#28a745' : '#dc3545'};
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        `;
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger'}" 
                   style="font-size: 1.5rem; margin-left: 10px;"></i>
                <div class="flex-grow-1" style="font-size: 0.95rem; color: ${type === 'success' ? '#155724' : '#721c24'};">
                    ${message}
                </div>
                <button type="button" class="btn-close" 
                        onclick="this.closest('#livewire-notification').remove()"
                        style="font-size: 0.8rem;"></button>
            </div>
        `;
        
        // حذف نوتیفیکیشن قبلی (اگر وجود داشت)
        const oldNotification = document.getElementById('livewire-notification');
        if (oldNotification) {
            oldNotification.remove();
        }
        
        // اضافه کردن به صفحه
        document.body.prepend(notification);
        
        // نمایش با انیمیشن
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(-50%) translateY(0)';
        }, 50);
        
        // حذف خودکار بعد از 5 ثانیه
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(-50%) translateY(-20px)';
                setTimeout(() => {
                    if (notification && notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
});
</script>
@endsection