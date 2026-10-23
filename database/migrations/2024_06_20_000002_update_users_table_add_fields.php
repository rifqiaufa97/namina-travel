<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) $table->enum('role', ['user', 'admin'])->default('user')->after('date_of_birth');
            if (!Schema::hasColumn('users', 'avatar')) $table->string('avatar')->nullable()->after('role');
            if (!Schema::hasColumn('users', 'last_login_at')) $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
        });
    }
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','avatar','last_login_at']);
        });
    }
}; 