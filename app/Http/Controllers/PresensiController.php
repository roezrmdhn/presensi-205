<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Carbon\Carbon;
use PDF; // Import alias PDF
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class PresensiController extends Controller
{
    public function insertPresensi(Request $request)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Validasi inputan form
        $validatedData = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'time_start' => 'required|date|after_or_equal:now', // Waktu mulai harus setelah atau sama dengan sekarang
            'time_end' => 'required|date|after:time_start',
            'id_organisasi' => 'required', // Validasi untuk id_organisasi
        ], [
            'time_start.after_or_equal' => 'Tanggal dan waktu tidak boleh mundur dari waktu sekarang.',
            'time_end.after' => 'Time end tidak boleh mundur dari time start.',
        ]);

        // Generate kode acak untuk presensi
        $kodeAcak = Str::random(10);

        // Dapatkan id_users dari sesi
        $adminId = session('id');

        // Simpan presensi baru
        Presensi::create([
            'kode_acak' => $kodeAcak,
            'id_users' => $adminId,
            'event_name' => $validatedData['event_name'],
            'description' => $validatedData['description'],
            'time_start' => $validatedData['time_start'],
            'time_end' => $validatedData['time_end'],
            'status' => "Belum",
            'id_organisasi' => $validatedData['id_organisasi'],
        ]);

        // Redirect dengan pesan sukses dan kode acak
        $success = true;
        return redirect('/admin')->with('success', $success)->with('kodeAcak', $kodeAcak);
    }



    public function updatePresensi(Request $request, $id)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'time_start' => 'required|date',
            'time_end' => 'required|date',
            'status' => 'required|string',
            'organisasi_id' => 'required|exists:organisasi,id_organisasi', // Pastikan id organisasi ada di tabel organisasi
        ]);

        // Mendapatkan id_users dari sesi
        $adminId = session('id');

        // Cari presensi berdasarkan ID dan id_users
        $presensi = Presensi::where('id_presensi', $id)
            ->where('id_users', $adminId)
            ->first();

        if ($presensi) {
            // Perbarui data presensi
            $presensi->event_name = $validatedData['event_name'];
            $presensi->description = $validatedData['description'];
            $presensi->time_start = $validatedData['time_start'];
            $presensi->time_end = $validatedData['time_end'];
            $presensi->status = $validatedData['status'];
            $presensi->id_organisasi = $validatedData['organisasi_id']; // Perbarui id_organisasi
            $presensi->save();

            // Redirect back with success message
            return redirect()->back()->with('update', true);
        } else {
            // Redirect back with error message
            return redirect()->back()->withErrors(['update' => 'Presensi tidak ditemukan atau Anda tidak memiliki izin untuk mengubah presensi ini.']);
        }
    }


    public function deletePresensi($id)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Cari data presensi berdasarkan ID
        $presensi = Presensi::find($id);

        // Periksa apakah data presensi ditemukan
        if (!$presensi) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data presensi tidak ditemukan.');
        }

        // Hapus data terkait di tabel detail_presensi terlebih dahulu
        DB::table('detail_presensi')->where('id_presensi', $id)->delete();

        // Hapus data presensi
        $presensi->delete();

        // Redirect kembali ke halaman admin dashboard dengan pesan sukses
        return redirect()->back()->with('delete', true);
    }


    public function getdata()
    {
        // Check if admin id exists in session
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Get id_users from session
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->where('time_end', '<', Carbon::now())
            ->get();


        // Count the number of presensi with status 'Belum' for the admin
        $belumCount = Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->count();

        // Count the number of presensi with status 'Selesai' for the admin
        $selesaiCount = Presensi::where('id_users', $adminId)
            ->where('status', 'Selesai')
            ->count();

        // Count the total number of presensi for the admin
        $totalPresensi = Presensi::where('id_users', $adminId)->count();
        $organisasi = Organisasi::all();

        // Calculate the percentage of presensi 'Belum' from total
        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;

        // Calculate the percentage of presensi 'Selesai' from total
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;

        // Retrieve all presensi data for the admin with status 'Belum'
        $presensiData = Presensi::join('organisasi', 'presensi.id_organisasi', '=', 'organisasi.id_organisasi')
            // ->where('presensi.id_users', $adminId)
            ->where('presensi.status', 'Belum')
            ->orderBy('presensi.id_presensi', 'desc')
            ->select('presensi.*', 'organisasi.nama as nama_organisasi')
            ->get();


        // Return the presensi data along with counts and percentages
        return view('adminDashboard', compact('organisasi', 'presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }

    public function getOrganisasi()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }

        $adminId = session('id');
        $user = Organisasi::all();

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->where('time_end', '<', Carbon::now())
            ->update(['status' => 'Selesai']);

        // Count the number of presensi with status 'Belum' for the admin
        $belumCount = Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->count();

        // Count the number of presensi with status 'Selesai' for the admin
        $selesaiCount = Presensi::where('id_users', $adminId)
            ->where('status', 'Selesai')
            ->count();

        // Count the total number of presensi for the admin
        $totalPresensi = Presensi::where('id_users', $adminId)->count();

        // Calculate the percentage of presensi 'Belum' from total
        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;

        // Calculate the percentage of presensi 'Selesai' from total
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;

        // Retrieve all presensi data for the admin with status 'Belum'
        $presensiData = Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->orderBy('id_presensi', 'desc')
            ->get();

        return view('organisasidata', compact('user', 'presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }


    public function getdatahistory()
    {
        // Check if admin id exists in session
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect('/login');
        }

        // Get id_users from session
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->where('time_end', '<', Carbon::now())
            ->update(['status' => 'Selesai']);

        $belumCount = Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->count();

        $selesaiCount = Presensi::where('id_users', $adminId)
            ->where('status', 'Selesai')
            ->count();

        $totalPresensi = Presensi::where('id_users', $adminId)->count();

        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;

        $presensiData = Presensi::join('organisasi', 'presensi.id_organisasi', '=', 'organisasi.id_organisasi')
            ->where('presensi.id_users', $adminId)
            ->where('status', 'Selesai')
            ->orderBy('presensi.id_presensi', 'desc')
            ->select('presensi.*', 'organisasi.nama as nama_organisasi')
            ->get();

        // Ambil semua event_name dari tabel presensi
        $eventNames = Presensi::select('event_name')->distinct()->get();

        $riwayatjadwal = Organisasi::all();

        return view('riwayat', compact('riwayatjadwal', 'presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage', 'eventNames'));
    }


    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date');
        $eventName = $request->input('event_name');

        // Validasi input
        $request->validate([
            'start_date' => 'required',
            'event_name' => 'required|string'
        ]);

        // Check if admin id exists in session
        if (!session('id')) {
            return redirect('/login');
        }

        // Get id_users from session
        $adminId = session('id');
        $organisasi_id = session('idOrganisasiTersimpan');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_users', $adminId)
            ->where('status', 'Belum')
            ->where('time_end', '<', Carbon::now())
            ->update(['status' => 'Selesai']);

        $presensiData = Presensi::where('id_users', $adminId)
            ->where('status', 'Selesai')
            ->where('event_name', $eventName)
            ->orderBy('id_presensi', 'desc')
            ->get();

        // Ambil nama organisasi dan foto
        $organisasi = Organisasi::find($organisasi_id);
        $riwayatjadwal = Organisasi::all();

        $startDate = date('Y-m-d', strtotime($startDate));

        // Query untuk mengambil kode_acak dan event_name dari presensi
        $kode_acak = DB::table('detail_presensi')
            ->join('presensi', 'detail_presensi.id_presensi', '=', 'presensi.id_presensi')
            ->select('presensi.kode_acak', 'presensi.event_name', 'presensi.id_presensi')
            ->where('presensi.id_organisasi', $organisasi_id)
            ->where('presensi.event_name', $eventName)
            ->whereDate('presensi.time_start', '<=', $startDate)
            ->first();

        if (!$kode_acak) {
            $data = [];
            return view('riwayat', compact('data', 'riwayatjadwal', 'presensiData'))->with('warning', true);
        }

        $data = DB::table('detail_presensi')
            ->join('users', 'detail_presensi.id_users', '=', 'users.id_users')
            ->select('users.name', 'users.jabatan', 'users.departemen', 'users.phone', 'detail_presensi.created_at as waktu_presensi')
            ->where('detail_presensi.id_presensi', $kode_acak->id_presensi)
            ->get();

        if ($data->isEmpty()) {
            return view('riwayat', compact('data', 'riwayatjadwal', 'presensiData'))->with('warning', true);
        }

        $countAbsen = DB::table('detail_presensi')
            ->join('presensi', 'detail_presensi.id_presensi', '=', 'presensi.id_presensi')
            ->where('presensi.id_organisasi', $organisasi_id)
            ->where('presensi.event_name', $eventName)
            ->whereDate('presensi.time_start', '<=', $startDate)
            ->count();

        $event_name = $kode_acak->event_name;

        return view('cetakanggota', compact('organisasi', 'countAbsen', 'kode_acak', 'data', 'event_name'));
    }
}
