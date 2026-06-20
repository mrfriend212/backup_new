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
        Schema::create('backup_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sftp_account_id')->constrained()->onDelete('cascade');
            $table->string('project_name'); // مانند HIS
            $table->enum('database_type', ['mysql', 'pgsql', 'sqlite', 'sqlsrv'])->default('sqlsrv');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_projects');
    }
};
