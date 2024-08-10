<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisasiController extends Controller
{
    // Menampilkan semua data organisasi
    public function index()
    {
        $organisasis = Organisasi::all();

        return response()->json($organisasis);
    }

    // Menampilkan data organisasi berdasarkan ID
    public function show($id)
    {
        $organisasi = Organisasi::find($id);
        if ($organisasi) {
            return response()->json($organisasi);
        } else {
            return response()->json(['message' => 'Organisasi not found'], 404);
        }
    }

    // Menambah data organisasi baru
    // Menambah data organisasi baru
    public function store(Request $request)
    {
        // Validasi data nama dan deskripsi terlebih dahulu
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        // Simpan data organisasi (nama dan deskripsi) terlebih dahulu ke database
        $organisasi = Organisasi::create([
            'nama' => $validatedData['nama'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'assets/img';
            $file->move(public_path($filePath), $fileName); // Simpan file di direktori yang ditentukan

            // Update data organisasi dengan path foto yang diunggah
            $organisasi->foto = $filePath . '/' . $fileName;
            $organisasi->save(); // Simpan perubahan
        }

        // Redirect ke halaman organisasi dengan pesan sukses
        return redirect('/organisasi')->with('success', 'Data organisasi berhasil ditambahkan!');
    }



    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
        ]);

        // Temukan data organisasi berdasarkan ID
        $organisasi = Organisasi::findOrFail($id);

        // Perbarui data nama dan deskripsi terlebih dahulu
        $organisasi->update($validatedData);

        // Jika ada file foto yang diupload, proses upload dan simpan
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $filePath = 'assets/img/organisasi'; // Direktori penyimpanan foto
            $file->move(public_path($filePath), $fileName); // Pindahkan file ke direktori tujuan
            $organisasi->foto = $filePath . '/' . $fileName; // Set nama file pada model

            $organisasi->save(); // Simpan perubahan
        }

        return redirect('/organisasi')->with('success', 'Data organisasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Nonaktifkan pemeriksaan foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Temukan organisasi dan hapus
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->delete();

        // Aktifkan kembali pemeriksaan foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect('/organisasi')->with('success', 'Data organisasi berhasil dihapus!');
    }
}
