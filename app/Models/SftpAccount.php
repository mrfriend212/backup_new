<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SftpAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'unit_id',
        'system_id',
        'database_type',
        'username',
        'password',
        'host',
        'port',
        'root_path',
        'days_of_week',
        'private_key',
        'public_key',
        'passphrase',
        'is_active'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'port' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'private_key',
        'public_key',
        'passphrase',
    ];

    // رابطه با user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // رابطه با unit
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // رابطه با system
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    // رابطه با backup_logs
    public function backupLogs(): HasMany
    {
        return $this->hasMany(BackupLog::class);
    }

    // متد کمکی برای چک کردن اینکه آیا روز مشخصی فعال است
    public function hasDay($day): bool
    {
        if (empty($this->days_of_week)) {
            return false;
        }
        return in_array($day, $this->days_of_week);
    }

    // متد کمکی برای دریافت نام روزهای فعال به صورت شفاف
    public function getActiveDaysNamesAttribute(): array
    {
        $daysMap = [
            0 => 'یکشنبه',
            1 => 'دوشنبه',
            2 => 'سه‌شنبه',
            3 => 'چهارشنبه',
            4 => 'پنج‌شنبه',
            5 => 'جمعه',
            6 => 'شنبه',
        ];

        return array_map(function ($day) use ($daysMap) {
            return $daysMap[$day] ?? $day;
        }, $this->days_of_week ?? []);
    }
}
