<?php

namespace App\Http\Controllers;

use App\Models\Sekretaris;
use App\Models\Presensi;
use App\Models\Organisasi;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SekretarisController extends Controller
{

    public function getAllDetailPresensi()
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Dapatkan id_anggota dari session
        $userId = session('id'); // Sesuaikan dengan key sesi yang Anda gunakan

        // Ambil semua data detail presensi yang terkait dengan id_anggota dari session
        $dataPresensi = Presensi::whereHas('sekretaris', function ($query) use ($userId) {
            $query->where('id_anggota', $userId); // Filter berdasarkan id_anggota yang sesuai dengan userId
        })
            ->with(['sekretaris' => function ($query) {
                $query->where('id_anggota', session('id')) // Filter berdasarkan session id
                    ->select(
                        'id_presensi',
                        DB::raw('DATE(created_at) as tanggal_presensi'),
                        DB::raw('TIME(DATE_ADD(created_at, INTERVAL 7 HOUR)) as jam_presensi')
                    );
            }])
            ->join('organisasi', 'presensi.id_organisasi', '=', 'organisasi.id_organisasi')
            ->select(
                'presensi.id_presensi',
                'presensi.kode_acak',
                'presensi.event_name',
                'presensi.description',
                'presensi.time_start',
                'presensi.time_end',
                'organisasi.nama as nama_organisasi'
            )
            ->get();

        return view('dashboard', ['dataPresensi' => $dataPresensi]);
    }

    public function getDetailPresensi($idPresensi)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Get detail presensi berdasarkan id presensi
        $detailPresensi = Sekretaris::where('id_presensi', $idPresensi)
            ->join('presensi', 'sekretaris.id_presensi', '=', 'presensi.id_presensi')
            ->join('anggota', 'sekretaris.id_anggota', '=', 'anggota.id_anggota')
            ->select('sekretaris.*', 'sekretaris.updated_at as absen', 'sekretaris.id as id_detail', 'presensi.*', 'anggota.*')
            ->get();

        $totalPresensi = Sekretaris::where('id_presensi', $idPresensi)->count();

        if ($detailPresensi->isEmpty()) {
            $kosong = true;
            return redirect()->back()->with('kosong', $kosong);
        }

        // Jika berhasil, kembalikan data detail presensi
        return view('detailpresensi', compact('detailPresensi', 'totalPresensi'));
    }
    public function deleteDetailPresensi($id)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Cari data detail presensi berdasarkan ID
        $detailPresensi = Sekretaris::find($id);

        // Jika data tidak ditemukan, kembalikan response error
        if (!$detailPresensi) {
            return redirect()->back()->with('error', 'Detail Presensi tidak ditemukan.');
        }

        // Hapus data detail presensi
        $detailPresensi->delete();

        // Periksa apakah setelah penghapusan data, jumlah data detail presensi menjadi kosong
        $totalDetailPresensi = Sekretaris::count();
        if ($totalDetailPresensi === 0) {
            $detail = true;
            // Jika kosong, arahkan ke route /admin
            return redirect('/admin')->with('detail', $detail);
        }


        // Jika tidak kosong, kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Detail Presensi berhasil dihapus.');
    }



    public function store($kode_acak)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Dapatkan presensi berdasarkan kode_acak yang diinputkan oleh pengguna
        $presensi = Presensi::where('kode_acak', $kode_acak)->first();

        // Pastikan presensi ditemukan
        if (!$presensi) {
            $error = true;
            return redirect()->back()->with('error', $error);
        }

        // Atur timezone ke Asia/Jakarta
        $timezone = 'Asia/Jakarta';

        // Dapatkan waktu saat ini dengan timezone WIB
        $currentTime = Carbon::now($timezone);

        // Parse time_start dan time_end dengan timezone WIB
        $timeStart = Carbon::parse($presensi->time_start, $timezone);
        $timeEnd = Carbon::parse($presensi->time_end, $timezone);

        // Periksa apakah saat ini di luar time_start dan time_end
        if ($currentTime->lt($timeStart) || $currentTime->gt($timeEnd)) {
            $notStart = true;
            return redirect()->back()->with('notStart', $notStart);
        }

        // Periksa status presensi
        if ($presensi->status == 'Selesai') {
            $gagal = true;
            return redirect()->back()->with('gagal', $gagal);
        }

        // Dapatkan id anggota dari session
        $userId = session('id'); // Sesuaikan dengan key sesi yang Anda gunakan

        // Dapatkan data anggota berdasarkan id anggota
        $anggota = Anggota::find($userId);

        // Dapatkan id organisasi berdasarkan nama departemen anggota
        $organisasi = Organisasi::where('nama', $anggota->departemen)->first();

        // Pastikan id_organisasi yang diizinkan sesuai
        if ($presensi->id_organisasi != 0 && $presensi->id_organisasi != $organisasi->id_organisasi) {
            $notAllowed = true;
            return redirect()->back()->with('notAllowed', $notAllowed);
        }

        // Periksa apakah anggota sudah melakukan presensi
        $userPresensi = Sekretaris::where('id_presensi', $presensi->id_presensi)
            ->where('id_anggota', $userId)
            ->exists();

        if ($userPresensi) {
            // Jika user sudah presensi, beri tahu pengguna
            $done = true;
            return redirect()->back()->with('done', $done);
        }

        // Simpan presensi baru
        $detailPresensi = new Sekretaris();
        $detailPresensi->id_presensi = $presensi->id_presensi; // Mengisi id_presensi dengan benar
        $detailPresensi->id_anggota = $userId;
        $detailPresensi->created_at = Carbon::now('Asia/Jakarta');
        $detailPresensi->save();

        // Redirect atau lakukan tindakan lain setelah berhasil menyimpan
        return redirect()->back()->with('success', true);
    }
}
