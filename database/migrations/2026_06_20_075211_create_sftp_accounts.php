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
            $table->string('account_name'); // مانند h_afshar
            $table->string('host');
            $table->integer('port')->default(32);
            $table->string('username');
            $table->text('password')->nullable(); // برای احراز هویت با پسورد
            $table->text('private_key')->nullable(); // کلید خصوصی SSH
            $table->text('public_key')->nullable(); // کلید عمومی SSH
            $table->string('passphrase')->nullable(); // رمز عبور کلید خصوصی (در صورت وجود)
            $table->string('root_path')->nullable(); // مسیر پایه روی سرور
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['user_id', 'account_name']);
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
