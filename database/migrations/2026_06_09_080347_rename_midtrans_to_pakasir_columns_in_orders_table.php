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
            if (Schema::hasColumn('orders', 'midtrans_order_id') && !Schema::hasColumn('orders', 'pakasir_order_id')) {
                $table->renameColumn('midtrans_order_id', 'pakasir_order_id');
            }
            if (Schema::hasColumn('orders', 'midtrans_payment_type') && !Schema::hasColumn('orders', 'pakasir_payment_method')) {
                $table->renameColumn('midtrans_payment_type', 'pakasir_payment_method');
            }
            if (Schema::hasColumn('orders', 'midtrans_transaction_status') && !Schema::hasColumn('orders', 'pakasir_transaction_status')) {
                $table->renameColumn('midtrans_transaction_status', 'pakasir_transaction_status');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'pakasir_payment_url')) {
                $table->text('pakasir_payment_url')->nullable()->after('pakasir_payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'pakasir_payment_url')) {
                $table->dropColumn('pakasir_payment_url');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'pakasir_order_id') && !Schema::hasColumn('orders', 'midtrans_order_id')) {
                $table->renameColumn('pakasir_order_id', 'midtrans_order_id');
            }
            if (Schema::hasColumn('orders', 'pakasir_payment_method') && !Schema::hasColumn('orders', 'midtrans_payment_type')) {
                $table->renameColumn('pakasir_payment_method', 'midtrans_payment_type');
            }
            if (Schema::hasColumn('orders', 'pakasir_transaction_status') && !Schema::hasColumn('orders', 'midtrans_transaction_status')) {
                $table->renameColumn('pakasir_transaction_status', 'midtrans_transaction_status');
            }
        });
    }
};
