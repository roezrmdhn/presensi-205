<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => NULL,
                'institusi' => 'Universitas',
                'departemen' => 'FK',
                'address' => 'Jl. Wijaya Kusuma No.7',
                'phone' => '08123761237',
                'more' => '',
                'password' => Hash::make('admin'),
                'foto' => 'https://via.placeholder.com/400x400.png/007777?text=admin',
            ]
        ]);
    }
}
