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
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'includes')) {
                $table->longText('includes')->nullable()->after('description');
            }
            if (!Schema::hasColumn('destinations', 'excludes')) {
                $table->longText('excludes')->nullable()->after('includes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['includes', 'excludes']);
        });
    }
};
