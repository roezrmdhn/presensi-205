<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Users;
use Illuminate\Database\Seeder;

class AnggotaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::factory()->count(10)->create();
    }
}
