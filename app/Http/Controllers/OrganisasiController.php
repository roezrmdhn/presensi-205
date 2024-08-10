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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Organisasi::create($validatedData);

        return redirect('/organisasi')->with('success', 'Data organisasi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $organisasi = Organisasi::findOrFail($id);
        // $organisasi = Organisasi::where('id_organisasi', $id)->firstOrFail();
        $organisasi->update($validatedData);

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
