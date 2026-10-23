<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->unsignedInteger('wishlist_count')->default(0)->after('total_reviews');
        });
        
        // Update existing records with current wishlist counts
        // This is a raw SQL approach since we can't use models in migrations
        DB::statement("
            UPDATE destinations d 
            SET wishlist_count = (
                SELECT COUNT(*) 
                FROM wishlists w 
                WHERE w.destination_id = d.id
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn('wishlist_count');
        });
    }
};
