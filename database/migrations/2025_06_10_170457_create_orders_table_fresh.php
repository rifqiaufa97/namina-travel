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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('destination_id')->constrained()->onDelete('cascade');
                $table->string('invoice_number')->unique();
                $table->integer('quantity')->default(1);
                $table->decimal('total', 15, 2)->default(0);
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->date('departure_date');
                $table->text('notes')->nullable();
                $table->string('customer_name');
                $table->string('customer_email');
                $table->string('customer_phone');
                $table->string('payment_proof')->nullable();
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
                $table->string('payment_method')->nullable();
                $table->timestamp('payment_date')->nullable();
                $table->text('cancellation_reason')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            // Add index for better performance on frequently queried columns
            Schema::table('orders', function (Blueprint $table) {
                $table->index('status');
                $table->index('payment_status');
                $table->index('departure_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
