<?php

use App\Models\User;
use App\Models\Unit;
use App\Models\System;
use App\Models\SftpAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

new class extends Component
{
    use WithPagination;

    // ===== تب فعال =====
    public $activeTab = 'users';

    // ===== فیلترهای جستجو =====
    public $search = '';

    // ===== متغیرهای فرم کاربر =====
    public $userId = null;
    public $userName = '';
    public $userFamily = '';
    public $userUsername = '';
    public $userPassword = '';
    public $userType = 'user';
    public $userStatus = 'active';
    public $isEditingUser = false;

    // ===== متغیرهای فرم واحد =====
    public $unitId = null;
    public $unitName = '';
    public $isEditingUnit = false;

    // ===== متغیرهای فرم سیستم =====
    public $systemId = null;
    public $systemNameFa = '';
    public $systemNameEn = '';
    public $isEditingSystem = false;

    // ===== متغیرهای فرم اکانت SFTP =====
    public $accountId = null;
    public $accountUserId = '';
    public $accountUnitId = '';
    public $accountSystemId = '';
    public $accountUsername = '';
    public $accountPassword = '';
    public $accountHost = '10.10.10.9';
    public $accountPort = 32;
    public $accountRootPath = '';
    public $accountDatabaseType = 'sqlsrv';
    public $accountDaysOfWeek = [];
    public $accountIsActive = true;
    public $accountPassphrase = '';
    public $isEditingAccount = false;

    // ===== متغیرهای مربوط به حذف =====
    public $deleteModalType = '';
    public $deleteModalId = null;
    public $deleteModalTitle = '';
    public $deleteModalMessage = '';
    public $showDeleteModal = false;

    // ===== متغیرهای مربوط به تولید کلید =====
    public $keyGenerateAccountId = null;
    public $keyComment = '';
    public $keyPassphrase = '';
    public $keyConfirmPassphrase = '';
    public $keyGeneratedPrivate = '';
    public $keyGeneratedPublic = '';
    public $keyGenerationError = '';

     /**
     * باز کردن مودال تولید کلید
     */
    public function openKeyGenerationModal($accountId)
    {
        $this->keyGenerateAccountId = $accountId;
        $this->keyComment = 'rsa-key-' . now()->format('Ymd');
        $this->keyPassphrase = '';
        $this->keyConfirmPassphrase = '';
        $this->keyGeneratedPrivate = '';
        $this->keyGeneratedPublic = '';
        $this->keyGenerationError = '';
        
        $this->dispatch('show-key-modal');
    }

    /**
     * بستن مودال تولید کلید
     */
    public function closeKeyModal()
    {
        $this->reset([
            'keyGenerateAccountId',
            'keyComment',
            'keyPassphrase',
            'keyConfirmPassphrase',
            'keyGeneratedPrivate',
            'keyGeneratedPublic',
            'keyGenerationError'
        ]);
        $this->dispatch('hide-key-modal');
    }

   /**
     * تولید کلید RSA با 4096 بیت (روش جایگزین)
     */
    public function generateKeysForAccount()
    {
        // اعتبارسنجی
        $this->validate([
            'keyComment' => 'required|string|max:255',
            'keyPassphrase' => 'required|string|min:4|max:255',
            'keyConfirmPassphrase' => 'required|string|min:4|max:255',
        ]);

        // بررسی تطابق پسوردها
        if ($this->keyPassphrase !== $this->keyConfirmPassphrase) {
            $this->keyGenerationError = 'رمز عبور و تکرار آن مطابقت ندارند!';
            return;
        }

        try {
            // پیدا کردن اکانت
            $account = SftpAccount::find($this->keyGenerateAccountId);
            if (!$account) {
                $this->keyGenerationError = 'اکانت مورد نظر یافت نشد!';
                return;
            }

            // ✅ تولید کلید RSA با 4096 بیت
            $rsa = RSA::createKey(4096);

            // ✅ دریافت کلید خصوصی با پسورد
            $privateKey = $rsa->toString('PKCS8', ['password' => $this->keyPassphrase]);
            
            // ✅ دریافت کلید عمومی
            $publicKey = $rsa->getPublicKey()->toString('PKCS8');

            // ✅ اضافه کردن Comment به صورت دستی به کلید عمومی
            // (در برخی نسخه‌ها Comment به انتهای کلید عمومی اضافه میشه)
            $publicKeyWithComment = $publicKey . ' ' . $this->keyComment;

            // ذخیره در دیتابیس
            $account->private_key = $privateKey;
            $account->public_key = $publicKeyWithComment;
            $account->passphrase = $this->keyPassphrase;
            $account->save();

            // ذخیره در متغیرهای کامپوننت برای نمایش در مودال
            $this->keyGeneratedPrivate = $privateKey;
            $this->keyGeneratedPublic = $publicKeyWithComment;
            $this->keyGenerationError = '';

            $this->dispatch('notify', message: '✅ کلیدهای SSH با موفقیت تولید و ذخیره شدند!', type: 'success');

        } catch (\Exception $e) {
            $this->keyGenerationError = 'خطا در تولید کلید: ' . $e->getMessage();
        }
    }

    public function confirmDelete($type, $id, $title = '')
    {
        $this->deleteModalType = $type;
        $this->deleteModalId = $id;
        $this->deleteModalTitle = $title;
        
        $messages = [
            'user' => 'آیا از حذف این کاربر مطمئن هستید؟ تمام اکانت‌های مرتبط نیز حذف خواهند شد.',
            'unit' => 'آیا از حذف این واحد مطمئن هستید؟ تمام اکانت‌های مرتبط نیز حذف خواهند شد.',
            'system' => 'آیا از حذف این نرم‌افزار مطمئن هستید؟ تمام اکانت‌های مرتبط نیز حذف خواهند شد.',
            'account' => 'آیا از حذف این اکانت SFTP مطمئن هستید؟',
        ];
        
        $this->deleteModalMessage = $messages[$type] ?? 'آیا از حذف این آیتم مطمئن هستید؟';
        
        // ارسال رویداد برای نمایش مودال
        $this->dispatch('show-delete-modal');
    }

    public function performDelete()
    {
        try {
            $type = $this->deleteModalType;
            $id = $this->deleteModalId;
            $message = '';

            switch ($type) {
                case 'user':
                    $user = User::find($id);
                    if ($user && $user->id !== auth()->id()) {
                        $user->delete();
                        $message = 'کاربر با موفقیت حذف شد';
                    } else {
                        $message = 'نمی‌توانید خودتان را حذف کنید';
                        $this->dispatch('notify', ['message' => $message, 'type' => 'error']);
                        $this->reset(['deleteModalType', 'deleteModalId', 'deleteModalTitle', 'deleteModalMessage']);
                        $this->dispatch('hide-delete-modal');
                        return;
                    }
                    break;
                case 'unit':
                    $unit = Unit::find($id);
                    if ($unit) {
                        $unit->delete();
                        $message = 'واحد با موفقیت حذف شد';
                    }
                    break;
                case 'system':
                    $system = System::find($id);
                    if ($system) {
                        $system->delete();
                        $message = 'نرم‌افزار با موفقیت حذف شد';
                    }
                    break;
                case 'account':
                    $account = SftpAccount::find($id);
                    if ($account) {
                        $account->delete();
                        $message = 'اکانت SFTP با موفقیت حذف شد';
                    }
                    break;
            }

            $this->reset(['deleteModalType', 'deleteModalId', 'deleteModalTitle', 'deleteModalMessage']);
            $this->dispatch('hide-delete-modal');
            $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function cancelDelete()
    {
        $this->reset(['deleteModalType', 'deleteModalId', 'deleteModalTitle', 'deleteModalMessage']);
        $this->dispatch('hide-delete-modal');
    }

    // ===== روزهای هفته =====
    public $weekDays = [
        ['value' => 0, 'label' => 'یکشنبه'],
        ['value' => 1, 'label' => 'دوشنبه'],
        ['value' => 2, 'label' => 'سه‌شنبه'],
        ['value' => 3, 'label' => 'چهارشنبه'],
        ['value' => 4, 'label' => 'پنج‌شنبه'],
        ['value' => 5, 'label' => 'جمعه'],
        ['value' => 6, 'label' => 'شنبه'],
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'users'],
    ];

    // ============================================
    // قوانین اعتبارسنجی
    // ============================================
    protected function rules()
    {
        $rules = [];

        if ($this->activeTab === 'users') {
            $rules = [
                'userName' => 'required|string|max:100',
                'userFamily' => 'required|string|max:100',
                'userUsername' => 'required|string|max:100|unique:users,username,' . ($this->userId ?? 'NULL'),
                'userType' => 'required|in:admin,manager,user',
                'userStatus' => 'required|in:active,deactive',
            ];
            if (!$this->isEditingUser) {
                $rules['userPassword'] = 'required|string|min:6';
            } else {
                $rules['userPassword'] = 'nullable|string|min:6';
            }
        }

        if ($this->activeTab === 'units') {
            $rules = [
                'unitName' => 'required|string|max:255|unique:units,name,' . ($this->unitId ?? 'NULL'),
            ];
        }

        if ($this->activeTab === 'systems') {
            $rules = [
                'systemNameFa' => 'required|string|max:255|unique:systems,name_fa,' . ($this->systemId ?? 'NULL'),
                'systemNameEn' => 'nullable|string|max:100|unique:systems,name_en,' . ($this->systemId ?? 'NULL'),
            ];
        }

        if ($this->activeTab === 'accounts') {
            $rules = [
                'accountUserId' => 'required|exists:users,id',
                'accountUnitId' => 'required|exists:units,id',
                'accountSystemId' => 'required|exists:systems,id',
                'accountUsername' => 'required|string|max:100|unique:sftp_accounts,username,' . ($this->accountId ?? 'NULL'),
                'accountPassword' => 'nullable|string|max:255',  // ✅ nullable شده
                'accountHost' => 'required|string|max:255',
                'accountPort' => 'required|integer|min:1|max:65535',
                'accountRootPath' => 'nullable|string|max:500',
                'accountDatabaseType' => 'required|in:mysql,pgsql,sqlite,sqlsrv',
                'accountDaysOfWeek' => 'required|array|min:1',
                'accountIsActive' => 'boolean',
                'accountPassphrase' => 'nullable|string|max:255',
            ];
            // شرط اجباری بودن رمز برای ایجاد جدید حذف شد
        }

        return $rules;
    }

    // ============================================
    // متدهای راه‌اندازی
    // ============================================
    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        // ریست فرم کاربر
        $this->userId = null;
        $this->userName = '';
        $this->userFamily = '';
        $this->userUsername = '';
        $this->userPassword = '';
        $this->userType = 'user';
        $this->userStatus = 'active';
        $this->isEditingUser = false;

        // ریست فرم واحد
        $this->unitId = null;
        $this->unitName = '';
        $this->isEditingUnit = false;

        // ریست فرم سیستم
        $this->systemId = null;
        $this->systemNameFa = '';
        $this->systemNameEn = '';
        $this->isEditingSystem = false;

        // ریست فرم اکانت
        $this->accountId = null;
        $this->accountUserId = '';
        $this->accountUnitId = '';
        $this->accountSystemId = '';
        $this->accountUsername = '';
        $this->accountPassword = '';
        $this->accountHost = '10.10.10.9';
        $this->accountPort = 32;
        $this->accountRootPath = '';
        $this->accountDatabaseType = 'sqlsrv';
        $this->accountDaysOfWeek = [];
        $this->accountIsActive = true;
        $this->accountPassphrase = '';
        $this->isEditingAccount = false;

        $this->resetValidation();
    }

    // ============================================
    // متدهای تغییر تب
    // ============================================
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetForm();
        $this->resetPage();
    }

    public function filter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filter();
    }

    // ============================================
    // متدهای مدیریت کاربران
    // ============================================
    public function saveUser()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'name' => $this->userName,
                'family' => $this->userFamily,
                'username' => $this->userUsername,
                'user_type' => $this->userType,
                'status' => $this->userStatus,
            ];

            if ($this->userPassword) {
                $data['password'] = Hash::make($this->userPassword);
            }

            if ($this->isEditingUser && $this->userId) {
                $user = User::find($this->userId);
                $user->update($data);
                $message = 'کاربر با موفقیت ویرایش شد';
            } else {
                $data['password'] = Hash::make($this->userPassword);
                User::create($data);
                $message = 'کاربر با موفقیت ایجاد شد';
            }

            DB::commit();
            $this->resetForm();
            $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function editUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $this->userId = $user->id;
            $this->userName = $user->name;
            $this->userFamily = $user->family;
            $this->userUsername = $user->username;
            $this->userType = $user->user_type;
            $this->userStatus = $user->status;
            $this->userPassword = '';
            $this->isEditingUser = true;
            $this->dispatch('show-edit-modal');
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::find($id);
            if ($user && $user->id !== auth()->id()) {
                $user->delete();
                $this->dispatch('notify', ['message' => 'کاربر با موفقیت حذف شد', 'type' => 'success']);
            } else {
                $this->dispatch('notify', ['message' => 'نمیتوانید خودتان را حذف کنید', 'type' => 'error']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function getUsers()
    {
        $query = User::query()->withCount('sftpAccounts');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('family', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    // ============================================
    // متدهای مدیریت واحدها
    // ============================================
    public function saveUnit()
    {
        $this->validate();

        try {
            if ($this->isEditingUnit && $this->unitId) {
                $unit = Unit::find($this->unitId);
                $unit->update(['name' => $this->unitName]);
                $message = 'واحد با موفقیت ویرایش شد';
            } else {
                Unit::create(['name' => $this->unitName]);
                $message = 'واحد با موفقیت ایجاد شد';
            }

            $this->resetForm();
            $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function editUnit($id)
    {
        $unit = Unit::find($id);
        if ($unit) {
            $this->unitId = $unit->id;
            $this->unitName = $unit->name;
            $this->isEditingUnit = true;
            $this->dispatch('show-edit-modal');
        }
    }

    public function deleteUnit($id)
    {
        try {
            $unit = Unit::find($id);
            if ($unit) {
                $unit->delete();
                $this->dispatch('notify', ['message' => 'واحد با موفقیت حذف شد', 'type' => 'success']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function getUnits()
    {
        $query = Unit::query()->withCount('sftpAccounts');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('name')->paginate(10);
    }

    // ============================================
    // متدهای مدیریت سیستم‌ها
    // ============================================
    public function saveSystem()
    {
        $this->validate();

        try {
            $data = [
                'name_fa' => $this->systemNameFa,
                'name_en' => $this->systemNameEn,
            ];

            if ($this->isEditingSystem && $this->systemId) {
                $system = System::find($this->systemId);
                $system->update($data);
                $message = 'نرم‌افزار با موفقیت ویرایش شد';
            } else {
                System::create($data);
                $message = 'نرم‌افزار با موفقیت ایجاد شد';
            }

            $this->resetForm();
            $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function editSystem($id)
    {
        $system = System::find($id);
        if ($system) {
            $this->systemId = $system->id;
            $this->systemNameFa = $system->name_fa;
            $this->systemNameEn = $system->name_en;
            $this->isEditingSystem = true;
            $this->dispatch('show-edit-modal');
        }
    }

    public function deleteSystem($id)
    {
        try {
            $system = System::find($id);
            if ($system) {
                $system->delete();
                $this->dispatch('notify', ['message' => 'نرم‌افزار با موفقیت حذف شد', 'type' => 'success']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function getSystems()
    {
        $query = System::query()->withCount('sftpAccounts');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name_fa', 'like', '%' . $this->search . '%')
                    ->orWhere('name_en', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('name_fa')->paginate(10);
    }

    // ============================================
    // متدهای مدیریت اکانت‌های SFTP
    // ============================================
    public function saveAccount()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'user_id' => $this->accountUserId,
                'unit_id' => $this->accountUnitId,
                'system_id' => $this->accountSystemId,
                'username' => $this->accountUsername,
                'password' => $this->accountPassword,
                'host' => $this->accountHost,
                'port' => $this->accountPort,
                'root_path' => $this->accountRootPath,
                'database_type' => $this->accountDatabaseType,
                'days_of_week' => json_encode($this->accountDaysOfWeek),
                'is_active' => $this->accountIsActive,
                'passphrase' => $this->accountPassphrase,
            ];

            if ($this->isEditingAccount && $this->accountId) {
                $account = SftpAccount::find($this->accountId);
                $account->update($data);
                $message = 'اکانت SFTP با موفقیت ویرایش شد';
            } else {
                SftpAccount::create($data);
                $message = 'اکانت SFTP با موفقیت ایجاد شد';
            }

            DB::commit();
            $this->resetForm();
            $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function editAccount($id)
    {
        $account = SftpAccount::with(['user', 'unit', 'system'])->find($id);
        if ($account) {
            $this->accountId = $account->id;
            $this->accountUserId = $account->user_id;
            $this->accountUnitId = $account->unit_id;
            $this->accountSystemId = $account->system_id;
            $this->accountUsername = $account->username;
            $this->accountPassword = $account->password;
            $this->accountHost = $account->host;
            $this->accountPort = $account->port;
            $this->accountRootPath = $account->root_path;
            $this->accountDatabaseType = $account->database_type;
            $this->accountDaysOfWeek = json_decode($account->days_of_week, true) ?? [];
            $this->accountIsActive = $account->is_active;
            $this->accountPassphrase = $account->passphrase;
            $this->isEditingAccount = true;
            $this->dispatch('show-edit-modal');
        }
    }

    public function deleteAccount($id)
    {
        try {
            $account = SftpAccount::find($id);
            if ($account) {
                $account->delete();
                $this->dispatch('notify', ['message' => 'اکانت با موفقیت حذف شد', 'type' => 'success']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'خطا: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function getAccounts()
    {
        $query = SftpAccount::query()->with(['user', 'unit', 'system']);

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

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    // ============================================
    // متدهای دانلود کلیدها
    // ============================================

    /**
     * دانلود کلید خصوصی
     */
    public function downloadPrivateKey($accountId)
    {
        $account = SftpAccount::find($accountId);
        
        if (!$account) {
            $this->dispatch('notify', message: 'اکانت مورد نظر یافت نشد', type: 'error');
            return;
        }
        
        if (empty($account->private_key)) {
            $this->dispatch('notify', message: 'کلید خصوصی برای این اکانت وجود ندارد', type: 'error');
            return;
        }

        // نام فایل
        $filename = 'private_key_' . $account->username . '_' . now()->format('Ymd_His') . '.ppk';
        
        // محتوای کلید
        $content = $account->private_key;

        // دانلود فایل
        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'application/x-putty',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * دانلود کلید عمومی
     */
    public function downloadPublicKey($accountId)
    {
        $account = SftpAccount::find($accountId);
        
        if (!$account) {
            $this->dispatch('notify', message: 'اکانت مورد نظر یافت نشد', type: 'error');
            return;
        }
        
        if (empty($account->public_key)) {
            $this->dispatch('notify', message: 'کلید عمومی برای این اکانت وجود ندارد', type: 'error');
            return;
        }

        // نام فایل (بدون پسوند)
        $filename = 'public_key_' . $account->username . '_' . now()->format('Ymd_His');
        
        // محتوای کلید
        $content = $account->public_key;

        // دانلود فایل بدون پسوند
        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ============================================
    // متدهای کمکی
    // ============================================
    public function getUsersList()
    {
        return User::orderBy('name')->get();
    }

    public function getUnitsList()
    {
        return Unit::orderBy('name')->get();
    }

    public function getSystemsList()
    {
        return System::orderBy('name_fa')->get();
    }

    public function getRoleLabel($role)
    {
        $labels = ['admin' => 'مدیر', 'manager' => 'مدیر سیستم', 'user' => 'کاربر عادی'];
        return $labels[$role] ?? $role;
    }

    public function getRoleColor($role)
    {
        $colors = ['admin' => 'danger', 'manager' => 'warning', 'user' => 'info'];
        return $colors[$role] ?? 'secondary';
    }

    public function getDatabaseTypeLabel($type)
    {
        $labels = [
            'mysql' => 'MySQL',
            'pgsql' => 'PostgreSQL',
            'sqlite' => 'SQLite',
            'sqlsrv' => 'SQL Server',
        ];
        return $labels[$type] ?? $type;
    }

    public function getDatabaseTypeColor($type)
    {
        $colors = [
            'mysql' => 'primary',
            'pgsql' => 'info',
            'sqlite' => 'warning',
            'sqlsrv' => 'secondary',
        ];
        return $colors[$type] ?? 'secondary';
    }

    public function render()
    {
        return view('pages.admin.management.management', [
            'users' => $this->getUsers(),
            'units' => $this->getUnits(),
            'systems' => $this->getSystems(),
            'accounts' => $this->getAccounts(),
            'usersList' => $this->getUsersList(),
            'unitsList' => $this->getUnitsList(),
            'systemsList' => $this->getSystemsList(),
        ]);
    }
};