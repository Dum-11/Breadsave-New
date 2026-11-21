<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreadsaverAdvancedTables extends Migration
{
    public function up()
    {
        Schema::create('produk_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_produk');
            $table->string('kode_batch', 80);
            $table->unsignedInteger('stok')->default(0);
            $table->dateTime('expired_at');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->unique(['id_produk', 'kode_batch'], 'bs_batch_kode_unique');
            $table->index(['id_produk', 'expired_at'], 'bs_batch_fefo_idx');
        });
        Schema::create('order_batch_allocations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_batch');
            $table->unsignedInteger('jumlah');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            $table->unique(['id_pesanan', 'id_batch'], 'bs_alloc_order_batch_unique');
            $table->index(['id_pesanan', 'released_at'], 'bs_alloc_release_idx');
        });
        Schema::create('pickup_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('slot_start')->unique();
            $table->unsignedInteger('kapasitas')->default(5);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
        Schema::create('rescue_box_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pesanan')->unique();
            $table->unsignedTinyInteger('jumlah_roti');
            $table->decimal('total_sebelum', 12, 2);
            $table->decimal('diskon_box', 12, 2);
            $table->timestamps();
        });
        Schema::create('loyalty_ledger', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_pesanan')->nullable();
            $table->integer('poin');
            $table->string('event_key', 130);
            $table->string('keterangan', 255);
            $table->timestamps();
            $table->unique(['id_user', 'event_key'], 'bs_loyalty_event_unique');
        });
        Schema::create('loyalty_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->string('kode', 32)->unique();
            $table->unsignedInteger('nominal');
            $table->unsignedInteger('poin_dipakai');
            $table->unsignedBigInteger('dipakai_pesanan')->nullable();
            $table->dateTime('kadaluwarsa_at');
            $table->timestamps();
            $table->index(['id_user', 'dipakai_pesanan'], 'bs_voucher_user_idx');
        });
        Schema::table('pesanans', function (Blueprint $table) {
            $table->decimal('voucher_discount', 12, 2)->default(0);
        });
        Schema::create('employee_activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->string('aksi', 70);
            $table->string('entitas', 70);
            $table->unsignedBigInteger('id_entitas')->nullable();
            $table->text('ringkasan')->nullable();
            $table->timestamps();
            $table->index(['id_user', 'created_at'], 'bs_activity_user_idx');
        });
        Schema::create('food_waste_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_batch');
            $table->unsignedInteger('jumlah');
            $table->string('alasan', 32);
            $table->string('sumber', 20)->default('manual');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('tanggal_buang');
            $table->string('event_key', 120)->nullable()->unique();
            $table->timestamps();
            $table->index(['tanggal_buang', 'alasan'], 'bs_waste_date_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('food_waste_records');
        Schema::dropIfExists('employee_activity_logs');
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('voucher_discount');
        });
        Schema::dropIfExists('loyalty_vouchers');
        Schema::dropIfExists('loyalty_ledger');
        Schema::dropIfExists('rescue_box_orders');
        Schema::dropIfExists('pickup_slots');
        Schema::dropIfExists('order_batch_allocations');
        Schema::dropIfExists('produk_batches');
    }
}
