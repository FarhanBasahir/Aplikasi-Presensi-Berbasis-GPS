<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hari_ini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('tb_presensi')->where('tgl_presensi', $hari_ini)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {

        //validasi hari kerja
        // $hari_ini = date('N');
        // if ($hari_ini >= 6) {
        //     echo "error|Maaf, presensi hanya bisa dilakukan pada hari kerja (Senin - Jumat)|";
        //     return;
        // }

        // validasi jam kerja
        $jam_sekarang = date("H:i:s");
        $jam_mulai = "08:00:00";
        $jam_selesai = "16:00:00";
        if ($jam_sekarang < $jam_mulai || $jam_sekarang > $jam_selesai) {
            echo "error|Maaf, presensi hanya bisa dilakukan pada jam kerja (08:00 - 16:00)|";
            return;
        }

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");

        // Lokasi Kantor Poliban
        // $latitude_kantor = -3.296579;
        // $longitude_kantor = 114.582140;

        // Lokasi Kantor Contoh
        $latitude_kantor = -3.291274;
        $longitude_kantor = 114.590917;

        $lokasi = $request->lokasi;
        $lokasi_user = explode(",", $lokasi);
        $latitude_user = $lokasi_user[0];
        $longitude_user = $lokasi_user[1];

        // $latitude_user = -3.291274;
        // $longitude_user = 114.590917;

        $jarak = $this->distance($latitude_kantor, $longitude_kantor, $latitude_user, $longitude_user);
        $radius = round($jarak['meters']);
        
        $cek = DB::table('tb_presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if ($cek > 0) {
            $keterangan = "out";
        } else {
            $keterangan = "in";
        }

        $image = $request->image;
        $folderPath = "public/uploads/presensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $keterangan;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($radius > 50) {
            echo "error|Maaf, anda diluar radius kantor. Jarak anda " . $radius . " meter dari kantor|";
        } else {
            if ($cek > 0) {
                $data_pulang = [
                    'jam_keluar' => $jam,
                    'foto_keluar' => $fileName,
                    'lokasi_keluar' => $lokasi,
                ];
                $update = DB::table('tb_presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    echo "success|Terima kasih, hati-hati di jalan|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf, presensi gagal, silakan hubungi admin|out";
                }
            } else {
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_masuk' => $jam,
                    'foto_masuk' => $fileName,
                    'lokasi_masuk' => $lokasi,
                ];
        
                $simpan = DB::table('tb_presensi')->insert($data);
                if ($simpan) {
                    echo "success|Terima kasih, selamat bekerja|in";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf, proses presensi gagal, silakan hubungi admin|out";
                }
            }
        }
    }



    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }
}
