<?php

namespace App\Console\Commands;

use App\Services\BatchInventoryService;
use App\Services\LoyaltyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AdvancedBreadSaverCommand extends Command
{
    protected $signature = 'breadsaver:advanced {--dry-run : Hitung saja, tanpa menulis database}';
    protected $description = 'FEFO, food waste aktual, poin pickup, dan pengembalian voucher secara idempoten';

    public function handle()
    {
        if ($this->option('dry-run')) {
            $this->info('SIMULASI: tidak ada perubahan database.');
            $this->line('Produk belum mempunyai batch: ' . DB::table('produks as p')
                ->leftJoin('produk_batches as b', 'b.id_produk', '=', 'p.id_produk')
                ->whereNull('b.id')->count());
            $this->line('Batch kedaluwarsa masih tersedia: ' . DB::table('produk_batches')
                ->where('stok', '>', 0)->where('expired_at', '<=', now())->count());
            $this->line('Pesanan pickup lunas: ' . DB::table('pesanans as p')
                ->join('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
                ->whereNotNull('p.picked_up_at')->where('b.status', 'Berhasil')->count());
            return 0;
        }
        $restored = BatchInventoryService::reconcileCancelled();
        $products = DB::table('produks')->orderBy('id_produk')->pluck('id_produk');
        foreach ($products as $id) {
            DB::transaction(function () use ($id) { BatchInventoryService::syncProduct((int) $id); }, 3);
        }
        $expired = BatchInventoryService::expireBatches();
        $points = LoyaltyService::rewardPickedUp();
        $vouchers = LoyaltyService::releaseCancelledVouchers();
        $this->info('Batch sinkron: ' . $products->count() . '; alokasi dilepas: ' . $restored .
            '; roti dibuang (kedaluwarsa): ' . $expired . '; poin baru: ' . $points .
            '; voucher dikembalikan: ' . $vouchers . '.');
        return 0;
    }
}
