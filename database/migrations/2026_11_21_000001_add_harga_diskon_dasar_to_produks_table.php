<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddHargaDiskonDasarToProduksTable extends Migration
{
    public function up()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->decimal('harga_diskon_dasar', 12, 2)->nullable();
        });

        // Simpan harga diskon manual sebelum program diskon otomatis mengubah harga jual.
        DB::table('produks')->whereNull('harga_diskon_dasar')->update([
            'harga_diskon_dasar' => DB::raw('harga_diskon'),
        ]);
    }

    public function down()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('harga_diskon_dasar');
        });
    }
}
