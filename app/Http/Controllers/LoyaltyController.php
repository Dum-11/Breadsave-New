<?php

namespace App\Http\Controllers;

use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LoyaltyController extends Controller
{
    private function buyer(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pembeli', 403);
    }

    public function index()
    {
        $this->buyer();
        $id = (int) auth()->id();
        $points = LoyaltyService::points($id);
        $ledger = DB::table('loyalty_ledger')->where('id_user', $id)->orderByDesc('id')->limit(50)->get();
        $vouchers = DB::table('loyalty_vouchers')->where('id_user', $id)->orderByDesc('id')->get();
        return view('breadsaver_advanced.loyalty', compact('points','ledger','vouchers'));
    }

    public function exchange(Request $request)
    {
        $this->buyer();
        try {
            $code = LoyaltyService::exchange((int) auth()->id());
            return back()->with('success', 'Voucher berhasil dibuat: ' . $code);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
