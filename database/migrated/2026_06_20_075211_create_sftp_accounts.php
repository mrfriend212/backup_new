<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sftp_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('system_id')->constrained()->onDelete('cascade');
            $table->enum('database_type', ['mysql', 'pgsql', 'sqlite', 'sqlsrv'])->default('sqlsrv');
            $table->string('username');
            $table->text('password')->nullable(); // برای احراز هویت با پسورد
            $table->string('host')->default('10.10.10.9');
            $table->integer('port')->default(32);
            $table->string('root_path')->nullable(); // مسیر پایه روی سرور
            $table->json('days_of_week'); // مثلاً [0,2] برای شنبه و دوشنبه
            $table->text('private_key')->nullable(); // کلید خصوصی SSH
            $table->text('public_key')->nullable(); // کلید عمومی SSH
            $table->string('passphrase')->nullable(); // رمز عبور کلید خصوصی (در صورت وجود)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sftp_accounts');
    }
};
