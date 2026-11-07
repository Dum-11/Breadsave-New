<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdTokoToProduksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('produks', 'id_toko')) {

            Schema::table('produks', function (Blueprint $table) {

                $table->unsignedBigInteger('id_toko')
                      ->nullable()
                      ->after('id_produk');

                $table->foreign('id_toko')
                      ->references('id_user')
                      ->on('users')
                      ->onDelete('set null');

            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('produks', 'id_toko')) {

            Schema::table('produks', function (Blueprint $table) {

                $table->dropForeign(['id_toko']);
                $table->dropColumn('id_toko');

            });
        }
    }
}
