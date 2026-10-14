<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreadsaverTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['pembeli', 'toko', 'admin']);
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('produks', function (Blueprint $table) {
            $table->id('id_produk');
            $table->foreignId('id_toko')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nama_produk', 100);
            $table->decimal('harga_asli', 10, 2);
            $table->decimal('harga_diskon', 10, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->dateTime('expired_at');
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->dateTime('tanggal_pesan')->useCurrent();
            $table->enum('status', ['Menunggu Konfirmasi', 'Diproses', 'Selesai', 'Dibatalkan'])->default('Menunggu Konfirmasi');
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();
        });

        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_pesanan')->constrained('pesanans', 'id_pesanan')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produks', 'id_produk')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('ratings', function (Blueprint $table) {
            $table->id('id_rating');
            $table->foreignId('id_pesanan')->unique()->constrained('pesanans', 'id_pesanan')->onDelete('cascade');
            $table->tinyInteger('bintang');
            $table->text('ulasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop tabel dengan urutan terbalik agar tidak error Foreign Key
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('detail_pesanans');
        Schema::dropIfExists('pesanans');
        Schema::dropIfExists('produks');
        Schema::dropIfExists('users');
    }
} 