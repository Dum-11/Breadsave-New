<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $data = $request->validate(['tanggal' => 'nullable|date_format:Y-m-d']);
        $query = DB::table('employee_activity_logs as l')
            ->leftJoin('users as u', 'u.id_user', '=', 'l.id_user')
            ->select('l.*', 'u.nama as nama_pegawai');
        if (isset($data['tanggal'])) $query->whereDate('l.created_at', $data['tanggal']);
        $logs = $query->orderByDesc('l.id')->limit(500)->get();
        return view('breadsaver_advanced.activity', compact('logs','data'));
    }
}
