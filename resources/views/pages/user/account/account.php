<?php

use App\Models\SftpAccount;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


new class extends Component
{
    public $accounts = [];

    public function mount()
    {
        $this->loadAccounts();
    }

    public function loadAccounts()
    {
        $this->accounts = SftpAccount::where('user_id', Auth::id())
            ->with(['unit', 'system'])
            ->orderBy('id')
            ->get();
    }

    /**
     * دریافت نام روزهای هفته به فارسی
     */
    public function getDaysOfWeek($daysArray)
    {
        if (empty($daysArray)) {
            return '---';
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

        // اگر JSON است، آن را دیکد کن
        if (is_string($daysArray)) {
            $daysArray = json_decode($daysArray, true);
        }

        if (!is_array($daysArray)) {
            return '---';
        }

        $persianDays = array_map(function($day) use ($daysMap) {
            return $daysMap[$day] ?? $day;
        }, $daysArray);

        return implode(' - ', $persianDays);
    }

    /**
     * دریافت وضعیت اکانت به صورت بج
     */
    public function getStatusBadge($isActive)
    {
        if ($isActive) {
            return '<span class="badge bg-success"><i class="bi bi-check-circle"></i> فعال</span>';
        }
        return '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> غیرفعال</span>';
    }

    /**
     * دریافت نوع دیتابیس به صورت بج
     */
    public function getDatabaseBadge($type)
    {
        $colors = [
            'mysql' => 'primary',
            'pgsql' => 'info',
            'sqlite' => 'warning',
            'sqlsrv' => 'secondary',
        ];

        $labels = [
            'mysql' => 'MySQL',
            'pgsql' => 'PostgreSQL',
            'sqlite' => 'SQLite',
            'sqlsrv' => 'SQL Server',
        ];

        $color = $colors[$type] ?? 'secondary';
        $label = $labels[$type] ?? $type;

        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    public function render()
    {
        return view('pages.user.account.account', [
            'accounts' => $this->accounts,
        ]);
    }
};