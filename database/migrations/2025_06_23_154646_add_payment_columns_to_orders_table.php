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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('payment_proof');
            $table->string('midtrans_payment_type')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_transaction_status')->nullable()->after('midtrans_payment_type');
            $table->string('payment_status')->default('pending')->change();
            $table->timestamp('expired_at')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('expired_at');
            $table->timestamp('cancelled_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_payment_type',
                'midtrans_transaction_status',
                'expired_at',
                'paid_at',
                'cancelled_at'
            ]);
            $table->string('payment_status')->default(null)->change();
        });
    }
};
