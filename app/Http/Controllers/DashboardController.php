<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan; // Pastikan model Karyawan sudah di-import

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data waktu hari ini, bulan ini, dan tahun ini
        $hari_ini = date("Y-m-d");
        $bulan_ini = date('m');
        $tahun_ini = date('Y');

        // Mengambil user yang sedang login
        $karyawan = Auth::guard('karyawan')->user();

        // Query untuk menampilkan data presensi hari ini
        $presensi_hari_ini = DB::table('tb_presensi')
            ->where('nik', $karyawan->nik)
            ->where('tgl_presensi', $hari_ini)
            ->first();

        // Query untuk menampilkan riwayat presensi bulan ini
        $riwayat_bulan_ini = DB::table('tb_presensi')
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulan_ini])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahun_ini])
            ->orderBy('tgl_presensi')
            ->get();

        // Menampilkan view dashboard
        return view('dashboard.dashboard', compact('presensi_hari_ini', 'riwayat_bulan_ini', 'karyawan'));
    }
}
