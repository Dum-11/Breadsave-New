<?php

namespace App\Observers;

use App\Models\Pesanan;
use App\Services\BreadSaverNotification;

/**
 * Pegawai memakai Pesanan::save() pada route proses/selesai.
 * Scanner pickup juga memakai Pesanan::save().
 * Perubahan tersebut dipantau di sini agar route dan scanner lama aman.
 */
class PesananNotificationObserver
{
    public function updated(Pesanan $pesanan): void
    {
        $id = $pesanan->getKey();

        if ($pesanan->wasChanged('status')) {
            if ($pesanan->status === 'Diproses') {
                BreadSaverNotification::processing($id);
            } elseif ($pesanan->status === 'Selesai') {
                // Di BreadSaver, status Selesai berarti roti siap diambil.
                BreadSaverNotification::ready($id);
            } elseif ($pesanan->status === 'Dibatalkan') {
                BreadSaverNotification::cancelled($id);
            }
        }

        if ($pesanan->wasChanged('picked_up_at') && $pesanan->picked_up_at !== null) {
            BreadSaverNotification::pickedUp($id);
        }
    }
}
