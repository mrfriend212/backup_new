<?php

namespace App\Console\Commands;

use App\Imports\BackupLogsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportBackupLogs extends Command
{
    protected $signature = 'backup:import {file?}';
    protected $description = 'دریافت لاگ‌های بکاپ از فایل اکسل';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!$filePath) {
            // اگر مسیر فایل داده نشد، از فایل پیش‌فرض استفاده کن
            $filePath = storage_path('app/imports/Final_1405_Report_HIS_Offsite_Manual_Backup.xlsx');
        }

        if (!file_exists($filePath)) {
            $this->error("فایل در مسیر {$filePath} وجود ندارد!");
            return 1;
        }

        $this->info("شروع ایمپورت فایل: {$filePath}");
        
        try {
            Excel::import(new BackupLogsImport, $filePath);
            $this->info("✅ ایمپورت با موفقیت انجام شد!");
            Log::info("ایمپورت فایل بکاپ با موفقیت انجام شد");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ خطا در ایمپورت: " . $e->getMessage());
            Log::error("خطا در ایمپورت فایل بکاپ: " . $e->getMessage());
            return 1;
        }
    }
}