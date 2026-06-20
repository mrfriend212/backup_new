<?php

namespace App\Imports;

use App\Models\BackupLog;
use App\Mappings\HospitalMapping;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Log;

class BackupLogsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        Log::info("تعداد کل ردیف‌ها: " . $rows->count());
        
        // نمایش 5 ردیف اول برای دیباگ
        for ($i = 0; $i < min(5, $rows->count()); $i++) {
            Log::info("ردیف {$i}: " . json_encode($rows[$i]->toArray()));
        }
        
        $columns = HospitalMapping::getExcelColumns();
        $sftpIds = HospitalMapping::getSftpAccountIdMapping();

        // از ردیف 8 شروع کن (ایندکس 7 چون از 0 شروع می‌شود)
        // ردیف 8 = اولین ردیف داده (بعد از هدر در ردیف 7)
        for ($i = 7; $i < $rows->count(); $i++) {
            $row = $rows[$i];
            $rowArray = $row->toArray();
            
            // ستون B = ایندکس 1 (تاریخ)
            $persianDate = $rowArray[1] ?? null;
            
            // Log برای دیباگ
            Log::info("ردیف {$i}: تاریخ = {$persianDate}");
            
            if (empty($persianDate) || $persianDate === 'تاریخ / بیمارستان') {
                Log::warning("ردیف {$i}: تاریخ خالی یا هدر است، رد می‌شود");
                continue;
            }

            // تبدیل تاریخ شمسی به میلادی
            try {
                // پاکسازی تاریخ (حذف فاصله‌های اضافی)
                $persianDate = trim($persianDate);
                
                // بررسی فرمت تاریخ
                if (!preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $persianDate)) {
                    Log::warning("ردیف {$i}: فرمت تاریخ نامعتبر: {$persianDate}");
                    continue;
                }
                
                $carbonDate = Jalalian::fromFormat('Y/m/d', $persianDate)->toCarbon();
                $uploadedAt = $carbonDate->toDateTimeString();
                Log::info("تاریخ تبدیل شده: {$uploadedAt}");
            } catch (\Exception $e) {
                Log::error("خطا در تبدیل تاریخ '{$persianDate}': " . $e->getMessage());
                continue;
            }

            // ستون‌های C تا P = ایندکس 2 تا 15
            // C=2, D=3, E=4, F=5, G=6, H=7, I=8, J=9, K=10, L=11, M=12, N=13, O=14, P=15
            $columnIndex = 2; // از ستون C شروع می‌شود
            
            foreach ($columns as $columnLetter => $hospitalName) {
                $value = $rowArray[$columnIndex] ?? null;
                $columnIndex++;
                
                // Log برای دیباگ (فقط برای مقادیر غیرخالی)
                if ($value !== null && $value !== '') {
                    Log::info("ستون {$columnLetter} ({$hospitalName}): مقدار = {$value}");
                }
                
                // اگر مقدار خالی بود، یعنی آن روز برای این واحد برنامه بکاپ نداشته
                if ($value === null || $value === '') {
                    continue;
                }

                // دریافت sftp_account_id
                $sftpAccountId = $sftpIds[$hospitalName] ?? null;
                if (!$sftpAccountId) {
                    Log::warning("sftp_account_id برای {$hospitalName} پیدا نشد");
                    continue;
                }

                // تبدیل مقدار به عدد
                $status = (int) $value;

                // بررسی وجود رکورد تکراری با updateOrCreate
                BackupLog::updateOrCreate(
                    [
                        'sftp_account_id' => $sftpAccountId,
                        'uploaded_at' => $uploadedAt,
                    ],
                    [
                        'status' => $status === 1 ? 'success' : 'failed',
                        'file_name' => null,
                        'file_size' => null,
                    ]
                );

                Log::info("✅ ثبت شد: {$hospitalName} - تاریخ {$persianDate} - وضعیت " . ($status === 1 ? 'موفق' : 'ناموفق'));
            }
        }
        
        Log::info("✅ ایمپورت کامل شد!");
    }
}