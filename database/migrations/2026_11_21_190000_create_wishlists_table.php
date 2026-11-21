<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Tidak memaksa foreign key: tipe PK users/produks pada DB lama bisa berbeda.
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_produk');
            $table->timestamps();
            $table->unique(['id_user', 'id_produk'], 'wishlist_user_produk_unique');
            $table->index('id_produk', 'wishlist_produk_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}
