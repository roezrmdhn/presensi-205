<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Models\Organisasi;
// use App\Models\Presensi;
// use Carbon\Carbon;

class AdminController extends Controller
{

    public function getdata()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }
        $organisasi = Organisasi::where('id_organisasi', '>', 0)->get();
        $users = Users::with('organisasi')->get();

        return view('admindata', compact('users', 'organisasi'));
    }

    public function register(Request $request)
    {

        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string',
            'jabatan' => 'required|string|max:20',
            'departemen' => 'required',
            'address' => 'required|string|max:80',
            'phone' => 'required|string|max:13 |unique:users',
            'more' => 'nullable',
            // 'role' => 0,

        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required',
            'jabatan' => 'required|string|max:20',
            'departemen' => 'required',
            'address' => 'required|string|max:80',
            'phone' => 'required|string|max:13|unique:users',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed');
        }

        // Membuat admin baru
        $admin = Users::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'jabatan' => $validatedData['jabatan'],
            'id_organisasi' => $validatedData['departemen'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'more' => $validatedData['more'],
            // 'role' => $validatedData['role'],
            'foto' => 'assets/img/profile-img.jpg',
        ]);

        // Redirect ke halaman yang sesuai setelah pendaftaran berhasil
        return redirect('/admindata');
    }

    public function update(Request $request)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'departemen' => 'required',
            'jabatan' => 'required|string|max:20',
            'email' => 'required|string|email|max:50',
            'address' => 'required|string|max:80',
            'phone' => 'required|string|max:13',
            'more' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Jika ingin membatasi ukuran gambar
            // 'role' => 0,
        ]);

        // Cari data admin berdasarkan ID
        $admin = Users::find($request->userId);

        // Jika admin tidak ditemukan, kembalikan response error
        if (!$admin) {
            return redirect()->back()->with('error', 'Users not found.');
        }

        // Update data admin dengan data yang diterima dari form
        $admin->name = $validatedData['name'];
        $admin->departemen = $validatedData['departemen'];
        $admin->jabatan = $validatedData['jabatan'];
        $admin->email = $validatedData['email'];
        $admin->address = $validatedData['address'];
        $admin->phone = $validatedData['phone'];
        $admin->more = $validatedData['more'];
        // $admin->role = 0;

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $filePath = 'assets/img';
            $file->move(public_path($filePath), $fileName); // Move the file to the specified directory
            $admin->foto = "assets/img/" . $fileName; // Set the filename to the admin model

            // Perbarui data foto di session
            session()->put('foto', $admin->foto);
        }

        // Simpan perubahan data admin
        $admin->save();

        // Redirect ke halaman yang sesuai setelah update berhasil
        return redirect('/redirect')->with('successs', 'Profile admin has been updated successfully.');
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
        $admin = Users::where('id_users', $id)->first();
        // dd($admin);

        // Memeriksa apakah password lama yang dimasukkan benar
        if (!password_verify($validatedData['currentPassword'], $admin->password)) {
            return back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        // Update password user dengan password baru
        $admin->password = bcrypt($validatedData['newPassword']);
        $admin->save();

        // Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function edit($id)
    {
        $data = Users::findOrFail($id);
        $organisasi = Organisasi::where('id_organisasi', '>', 0)->get(); // Mengambil semua organisasi kecuali id=0
        return view('admindata', compact('data', 'organisasi'));
    }

    public function updateData(Request $request, $id)
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

        // Temukan data admin berdasarkan ID
        $admin = Users::where('id_users', $id)->firstOrFail();

        // Perbarui data admin
        $admin->name = $validatedData['name'];
        $admin->jabatan = $validatedData['jabatan'];
        $admin->id_organisasi = $validatedData['departemen'];
        $admin->address = $validatedData['address'];
        $admin->phone = $validatedData['phone'];
        $admin->email = $validatedData['email'];

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension(); // Gunakan ekstensi file asli
            $filePath = 'assets/img';

            // Pindahkan file ke direktori publik yang ditentukan
            $file->move(public_path($filePath), $fileName);

            // Hapus gambar lama jika ada
            if ($admin->foto) {
                $oldImage = public_path($admin->foto);
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Set jalur file gambar baru ke model admin
            $admin->foto = "$filePath/$fileName";

            // Perbarui data foto di session jika diperlukan
            session()->put('foto', $admin->foto);
        }

        // Simpan perubahan
        $admin->save();

        // Redirect back to the edit page with success message
        return redirect()->route('admindata')->with('success', 'Data admin berhasil diperbarui.');
    }
}
