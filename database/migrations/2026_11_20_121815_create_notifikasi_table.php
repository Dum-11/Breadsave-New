<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiTable extends Migration
{
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Akun penerima notifikasi
            $table->unsignedBigInteger('id_user');

            // Pesanan terkait (jika ada)
            $table->unsignedBigInteger('id_pesanan')
                  ->nullable();

            // Jenis pemberitahuan
            $table->string('jenis', 50);

            // Informasi notifikasi
            $table->string('judul', 150);
            $table->text('pesan');

            // Halaman tujuan saat notifikasi diklik
            $table->string('tautan', 255)->nullable();

            // Mencegah notifikasi kejadian yang sama
            // terkirim berulang ke pengguna yang sama
            $table->string('event_key', 150);

            // Status dibaca
            $table->timestamp('dibaca_pada')->nullable();

            $table->timestamps();

            // Satu kejadian hanya sekali per pengguna
            $table->unique(
                ['id_user', 'event_key'],
                'notifikasi_user_event_unique'
            );

            $table->index(
                ['id_user', 'dibaca_pada'],
                'notifikasi_user_dibaca_index'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasi');
    }
}
