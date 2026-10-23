<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('is_admin');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropColumn('is_admin');
            // $table->dropColumn('status');
            // $table->dropColumn('date_of_birth');
        });
    }
};
