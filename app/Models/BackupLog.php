<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackupLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sftp_account_id',
        'status',
        'uploaded_at',
        'file_name',
        'file_size',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // رابطه با sftp_account
    public function sftpAccount(): BelongsTo
    {
        return $this->belongsTo(SftpAccount::class);
    }

    // متد کمکی برای تبدیل سایز فایل به فرمت خوانا
    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($this->file_size >= 1024 && $i < count($units) - 1) {
            $this->file_size /= 1024;
            $i++;
        }

        return round($this->file_size, 2) . ' ' . $units[$i];
    }
}
