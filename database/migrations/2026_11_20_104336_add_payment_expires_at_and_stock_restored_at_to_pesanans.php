<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentExpiresAtAndStockRestoredAtToPesanans extends Migration
{
    public function up()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dateTime('payment_expires_at')->nullable();
            $table->dateTime('stock_restored_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn([
                'payment_expires_at',
                'stock_restored_at'
            ]);
        });
    }
}
