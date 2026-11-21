<?php

namespace App\Providers;

use App\Models\Pesanan;
use App\Observers\PesananNotificationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Notifikasi saat pegawai mengubah status dan saat QR pickup dipakai.
        Pesanan::observe(PesananNotificationObserver::class);
    }
}
