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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom yang diperlukan untuk registrasi
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('email_verified_at');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('is_admin');
            }
            
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('status');
            }
            
            // Pastikan kolom role ada dan tipenya benar
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['user', 'admin'])->default('user')->after('date_of_birth');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu menghapus kolom untuk menghindari kehilangan data
    }
};
