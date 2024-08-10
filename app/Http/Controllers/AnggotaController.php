<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Presensi;
use App\Models\Anggota;
use App\Models\Organisasi;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class AnggotaController extends Controller
{
    use  HasFactory, Notifiable, CanResetPassword;

    public function getdata()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }
        $organisasi = Organisasi::where('id_organisasi', '>', 0)->get();
        $adminId = session('id');
        $users = Anggota::all();
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

        // Calculate the percentage of presensi 'Belum' from total
        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;

        // Calculate the percentage of presensi 'Selesai' from total
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;

        // Retrieve all presensi data for the admin with status 'Belum'
        $presensiData = Presensi::where('id_admin', $adminId)
            ->where('status', 'Belum')
            ->orderBy('id_presensi', 'desc')
            ->get();


        return view('userdata', compact('users', 'organisasi', 'presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }

    public function updateProfile(Request $request)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            //'id_anggota' => 'required', // Tambahkan validasi untuk bidang ID
            'name' => 'required',
            'jabatan' => 'required',
            'departemen' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:anggota,email,' . $request->id . ',id_anggota', // Menggunakan id_anggota$request->id
            //'password' => 'nullable', // Password menjadi opsional
        ]);

        // Ambil data pengguna berdasarkan ID
        $user = Anggota::findOrFail($request->id);
        $organisasi = Organisasi::where('id_organisasi', '>', 0)->get();
        // Perbarui data pengguna
        $user->name = $validatedData['name'];
        $user->jabatan = $validatedData['jabatan'];
        $user->departemen = $validatedData['departemen'];
        $user->address = $validatedData['address'];
        $user->phone = $validatedData['phone'];
        $user->email = $validatedData['email'];

        // Periksa apakah password baru dimasukkan
        // if ($request->filled('password')) {
        //     $user->password = bcrypt($validatedData['password']);
        // }

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman userdata dengan pesan sukses
        return redirect('/userdata')->with('success', 'Profil berhasil diperbarui.');
    }


    public function deleteProfile($id)
    {
        try {
            // Nonaktifkan pemeriksaan foreign key constraints
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Temukan data pengguna berdasarkan ID
            $user = Anggota::find($id);

            // Periksa apakah data pengguna ditemukan
            if (!$user) {
                // Jika tidak ditemukan, aktifkan kembali pemeriksaan foreign key constraints
                \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
            }

            // Hapus data pengguna
            $user->delete();

            // Aktifkan kembali pemeriksaan foreign key constraints
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Redirect ke halaman yang sesuai dengan pesan sukses
            return redirect('/userdata')->with('success', 'Data pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            // Aktifkan kembali pemeriksaan foreign key constraints jika terjadi kesalahan
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Log error jika penghapusan gagal
            \Log::error('Error deleting user:', ['id_anggota' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menghapus data pengguna.');
        }
    }


    public function register(Request $request)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|max:50|unique:anggota',
            'password' => 'required|string', // Pastikan password memiliki panjang minimal dan dikonfirmasi
            'jabatan' => 'required|string|max:20',
            'departemen' => 'required|string|max:50',
            'address' => 'required|string|max:80',
            'phone' => 'required|string|max:13|unique:anggota', // Validasi nomor telepon sebagai string untuk menangani berbagai format nomor
        ]);

        // Enkripsi password
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Membuat pengguna baru
        $user = Anggota::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'jabatan' => $validatedData['jabatan'],
            'departemen' => $validatedData['departemen'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'foto' => 'assets/img/profile-img.jpg', // Pastikan panjang nilai ini sesuai dengan kolom database
        ]);

        Alert::success('Sukses', 'Pendaftaran berhasil!');
        // Redirect ke halaman yang sesuai setelah pendaftaran berhasil
        return redirect('/userdata');
    }


    public function create()
    {
        return view('login'); // Ganti 'login' dengan nama view login Anda
    }

    public function store()
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        // Check if the user exists in the users table
        $user = Anggota::where('email', $credentials['email'])->first();
        if ($user && password_verify($credentials['password'], $user->password)) {
            // If the user exists and the password is correct, log in the user
            session(['name' => $user->name]);
            session(['id' => $user->id_anggota]);
            session(['foto' => $user->foto ?: 'assets/img/profile-img.jpg']);
            session(['level' => "user"]);
            return redirect('/user');
        }
        // dd($user);

        // Check if the user exists in the admins table
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && password_verify($credentials['password'], $admin->password)) {
            session(['name' => $admin->name]);
            session(['id' => $admin->id_admin]);
            session(['isAdmin' => $admin->isAdmin]);
            session(['foto' => $admin->foto ?: 'assets/img/profile-img.jpg']);
            session(['idOrganisasiTersimpan' => $admin->id_organisasi]);
            session(['level' => "admin"]);
            return redirect('/dashboardAdmin'); // Redirect ke halaman dashboard admin
        }

        // dd(bcrypt($credentials['password']));
        if ($admin && password_verify($credentials['password'], $admin->password)) {
            // If the admin exists and the password is correct, log in the admin
            session(['name' => $admin->name]);
            session(['id' => $admin->id_admin]);
            session(['foto' => $admin->foto ?: 'assets/img/profile-img.jpg']);
            session(['idOrganisasiTersimpan' => $admin->id_organisasi]);
            session(['level' => "admin"]);
            // dd( password_verify($credentials['password'], $admin->password));

            return redirect()->route('admin.dashboard');
        }

        // If no user or admin with the provided email and password is found
        return redirect('/sign-in')->with('error', 'Invalid email or password.');
    }


    public function logout(Request $request)
    {
        // Hapus semua data dari session
        $request->session()->flush();

        // Redirect ke halaman login
        return redirect('/');
    }

    public function redirectToProfile()
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        $level = session('level'); // Mendapatkan level dari session
        $organisasi = Organisasi::where('id_organisasi', '>', 0)->get(); // Mengambil semua organisasi

        // Lakukan pengecekan level
        if ($level === 'user') {
            // Jika level adalah user, temukan data user dan redirect ke halaman profile user
            $user = Anggota::find(session('id'));
            if ($user) {
                return view('profileuser', compact('user', 'organisasi'));
            } else {
                return redirect('/login')->with('error', 'User not found.');
            }
        } elseif ($level === 'admin') {
            // Jika level adalah admin, temukan data admin dan redirect ke halaman profile admin
            $admin = Admin::find(session('id'));
            if ($admin) {
                return view('profileadmin', compact('admin', 'organisasi'));
            } else {
                return redirect('/login')->with('error', 'Admin not found.');
            }
        } else {
            // Jika level tidak sesuai, redirect ke halaman login atau halaman lainnya
            return redirect('/login')->with('error', 'Invalid user level.');
        }
    }


    public function update(Request $request, $id)
    {

        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'jabatan' => 'required|string|max:20',
            'departemen' => 'required',
            'address' => 'required|string|max:80',
            'phone' => 'required|string|max:13',
            'email' => 'required|email|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
        ]);

        // Cari data admin berdasarkan ID
        // $user = Anggota::find($request->userId);
        // Cari data admin berdasarkan ID
        // $anggota = Anggota::find($request->userId);

        // Temukan data admin berdasarkan ID
        $user = Anggota::where('id_anggota', $id)->firstOrFail();

        // Perbarui data admin
        $user->name = $validatedData['name'];
        $user->jabatan = $validatedData['jabatan'];
        $user->id_organisasi = $validatedData['departemen'];
        $user->address = $validatedData['address'];
        $user->phone = $validatedData['phone'];
        $user->email = $validatedData['email'];


        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension(); // Gunakan ekstensi file asli
            $filePath = 'assets/img';

            // Pindahkan file ke direktori publik yang ditentukan
            $file->move(public_path($filePath), $fileName);

            // Hapus gambar lama jika ada
            if ($user->foto) {
                $oldImage = public_path($user->foto);
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Set jalur file gambar baru ke model user
            $user->foto = "$filePath/$fileName";

            // Perbarui data foto di session jika diperlukan
            session()->put('foto', $user->foto);
        }


        // Simpan perubahan data admin
        $user->save();

        // Redirect ke halaman yang sesuai setelah update berhasil
        return back()->with('successs', 'Profile admin has been updated successfully.');
    }

    public function updatepass(Request $request)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // dd("aa");
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|string|confirmed',
        ]);

        $id = session('id');

        // Mendapatkan user yang sedang login
        $user = Anggota::where('id_anggota', $id)->first();
        // dd($user);

        // Memeriksa apakah password lama yang dimasukkan benar
        if (!password_verify($validatedData['currentPassword'], $user->password)) {
            return back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        // Update password user dengan password baru
        $user->password = bcrypt($validatedData['newPassword']);
        $user->save();

        // Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
