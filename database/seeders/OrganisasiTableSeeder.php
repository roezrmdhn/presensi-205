<?php

namespace Database\Seeders;

use App\Models\Organisasi;
use Illuminate\Database\Seeder;

class OrganisasiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organisasi::insert([
            [
                'nama' => 'Badan Eksekutif Mahasiswa(BEM)',
                'deskripsi' => 'Penaung Univ',
            ]
        ]);
    }
}
