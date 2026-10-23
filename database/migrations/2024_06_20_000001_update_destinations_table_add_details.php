<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'duration')) $table->string('duration')->nullable();
            if (!Schema::hasColumn('destinations', 'max_people')) $table->integer('max_people')->nullable();
            if (!Schema::hasColumn('destinations', 'accommodation')) $table->text('accommodation')->nullable();
            if (!Schema::hasColumn('destinations', 'facilities')) $table->text('facilities')->nullable();
            if (!Schema::hasColumn('destinations', 'itinerary')) $table->longText('itinerary')->nullable();
            if (!Schema::hasColumn('destinations', 'price_include')) $table->text('price_include')->nullable();
            if (!Schema::hasColumn('destinations', 'price_exclude')) $table->text('price_exclude')->nullable();
            if (!Schema::hasColumn('destinations', 'what_to_bring')) $table->text('what_to_bring')->nullable();
            if (!Schema::hasColumn('destinations', 'cancellation_policy')) $table->text('cancellation_policy')->nullable();
            if (!Schema::hasColumn('destinations', 'additional_info')) $table->text('additional_info')->nullable();
        });
    }
    public function down()
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'duration',
                'max_people',
                'accommodation',
                'facilities',
                'itinerary',
                'price_include',
                'price_exclude',
                'what_to_bring',
                'cancellation_policy',
                'additional_info',
            ]);
        });
    }
};