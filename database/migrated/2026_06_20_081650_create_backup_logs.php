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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sftp_account_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['success', 'failed']);
            $table->timestamp('uploaded_at');
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable(); // به بایت
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
