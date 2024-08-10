<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\Detail_Presensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Presensi;

class DashboardAdminController extends Controller
{
    public function dashboardAdmin()
    {
        // Check if admin id exists in session
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Get id_admin from session
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_admin', $adminId)
            ->where('status', 'Belum')
            ->where('time_end', '<', Carbon::now())
            ->update(['status' => 'Selesai']);

        // Count the number of presensi with status 'Belum' for the admin
        $belumCount = Presensi::where('id_admin', $adminId)
            ->where('status', 'Belum')
            ->count();

        // Count the number of presensi with status 'Selesai' for the admin
        $selesaiCount = Presensi::where('id_admin', $adminId)
            ->where('status', 'Selesai')
            ->count();

        // Count the total number of presensi for the admin
        $totalPresensi = Presensi::where('id_admin', $adminId)->count();
        $organisasi = Organisasi::all();
        // $admin = Admin::where('id_admin', $id)->firstOrFail();

        // Calculate the percentage of presensi 'Belum' from total
        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;
        $belumPercentage = number_format($belumPercentage, 2);

        // Calculate the percentage of presensi 'Selesai' from total
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;
        $selesaiPercentage = number_format($selesaiPercentage, 2);

        // Retrieve all presensi data for the admin with status 'Belum'
        $presensiData = Presensi::join('organisasi', 'presensi.id_organisasi', '=', 'organisasi.id_organisasi')
            ->where('presensi.id_admin', $adminId)
            ->where('presensi.status', 'Belum')
            ->orderBy('presensi.id_presensi', 'desc')
            ->select('presensi.*', 'organisasi.nama as nama_organisasi')
            ->get();

        // Query untuk menghitung jumlah kegiatan per organisasi
        $organisasi = Organisasi::select('id_organisasi', 'nama')
            ->withCount('presensi')
            ->get();

        // Grafik Jumlah Presensi Organisasi Tiap Minggu
        $organisasi_id = request('organisasi_id', $organisasi->first()->id);
        $bulan = request('bulan', date('n'));

        $jumlah_absensi_per_minggu = DB::table('detail_presensi')
            ->join('presensi', 'detail_presensi.id_presensi', '=', 'presensi.id_presensi')
            ->where('presensi.id_organisasi', $organisasi_id)
            ->whereMonth('presensi.time_start', $bulan)
            ->selectRaw('WEEK(detail_presensi.created_at) - WEEK(DATE_SUB(detail_presensi.created_at, INTERVAL DAYOFMONTH(detail_presensi.created_at)-1 DAY)) + 1 as minggu, count(distinct detail_presensi.id_anggota) as jumlah_anggota')
            ->groupBy('minggu')
            ->get();

        $hasil_per_minggu = [
            ['minggu' => 1, 'jumlah_anggota' => 0],
            ['minggu' => 2, 'jumlah_anggota' => 0],
            ['minggu' => 3, 'jumlah_anggota' => 0],
            ['minggu' => 4, 'jumlah_anggota' => 0],
            ['minggu' => 5, 'jumlah_anggota' => 0],
        ];

        foreach ($jumlah_absensi_per_minggu as $jumlah) {
            $hasil_per_minggu[$jumlah->minggu - 1]['jumlah_anggota'] = $jumlah->jumlah_anggota;
        }

        return view('dashboardAdmin', compact(
            'organisasi',
            'presensiData',
            'belumCount',
            'selesaiCount',
            'totalPresensi',
            'belumPercentage',
            'selesaiPercentage',
            'hasil_per_minggu',
            'organisasi_id',
            'bulan'
        ));
    }


    public function showSelectedData(Request $request)
    {
        $organisasi_id = $request->input('organisasi_id');
        $bulan = $request->input('bulan');

        // Validasi input
        $request->validate([
            'organisasi_id' => 'required|exists:organisasi,id_organisasi',
            'bulan' => 'required|integer|between:1,12',
        ]);

        // Ambil nama organisasi
        $organisasi = Organisasi::find($organisasi_id);

        // Query untuk menghitung jumlah detail_presensi per minggu
        $jumlah_absensi_per_minggu = DB::table('detail_presensi')
            ->join('presensi', 'detail_presensi.id_presensi', '=', 'presensi.id_presensi')
            ->where('presensi.id_organisasi', $organisasi_id)
            ->whereMonth('presensi.time_start', $bulan)
            ->selectRaw('WEEK(detail_presensi.created_at) - WEEK(DATE_SUB(detail_presensi.created_at, INTERVAL DAYOFMONTH(detail_presensi.created_at)-1 DAY)) + 1 as minggu, count(distinct detail_presensi.id_anggota) as jumlah_anggota')
            ->groupBy('minggu')
            ->get();

        // Query untuk menghitung jumlah kegiatan per minggu
        $jumlah_kegiatan_per_minggu = DB::table('presensi')
            ->where('id_organisasi', $organisasi_id)
            ->whereMonth('time_start', $bulan)
            ->selectRaw('WEEK(time_start) - WEEK(DATE_SUB(time_start, INTERVAL DAYOFMONTH(time_start)-1 DAY)) + 1 as minggu, count(id_presensi) as jumlah_kegiatan')
            ->groupBy('minggu')
            ->get();

        // Menginisialisasi array untuk semua minggu dalam bulan
        $hasil_per_minggu = [
            ['minggu' => 1, 'jumlah_anggota' => 0, 'jumlah_kegiatan' => 0],
            ['minggu' => 2, 'jumlah_anggota' => 0, 'jumlah_kegiatan' => 0],
            ['minggu' => 3, 'jumlah_anggota' => 0, 'jumlah_kegiatan' => 0],
            ['minggu' => 4, 'jumlah_anggota' => 0, 'jumlah_kegiatan' => 0],
            ['minggu' => 5, 'jumlah_anggota' => 0, 'jumlah_kegiatan' => 0],
        ];

        // Memasukkan hasil query ke dalam array hasil_per_minggu jumlah anggota
        foreach ($jumlah_absensi_per_minggu as $jumlah) {
            $hasil_per_minggu[$jumlah->minggu - 1]['jumlah_anggota'] = $jumlah->jumlah_anggota;
        }

        // Memasukkan hasil query ke dalam array hasil_per_minggu jumlah kegiatan
        foreach ($jumlah_kegiatan_per_minggu as $jumlah) {
            $hasil_per_minggu[$jumlah->minggu - 1]['jumlah_kegiatan'] = $jumlah->jumlah_kegiatan;
        }

        // Dapatkan nama bulan
        $nama_bulan = date('F', mktime(0, 0, 0, $bulan, 1));


        return view('selectedData', compact('organisasi', 'nama_bulan', 'hasil_per_minggu'));
    }
}
