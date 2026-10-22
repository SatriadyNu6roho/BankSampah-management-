<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Admin (Pak RT)
        $admin = User::create([
            'username' => 'admin',
            'name'     => 'Pak RT 01 (Pak Ahmad)',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // 2. Buat Data User (Kepala Keluarga)
        $user1 = User::create([
            'username' => 'kk001',
            'name'     => 'Budi Santoso',
            'password' => Hash::make('123456'),
            'role'     => 'user',
        ]);

        $user2 = User::create([
            'username' => 'kk002',
            'name'     => 'Joko Susilo',
            'password' => Hash::make('joko123'),
            'role'     => 'user',
        ]);

        $user2 = User::create([
            'username' => 'kkoo3',
            'name'     => 'Satriow',
            'password' => Hash::make('123satt'),
            'role'     => 'user',
        ]);

        // 3. Buat Data Contoh Laporan Sampah (Reports)
        Report::create([
            'user_id'      => $user1->id,
            'jenis_sampah' => 'Organik',
            'foto'         => 'contoh_organik.jpg',
        ]);

        Report::create([
            'user_id'      => $user2->id,
            'jenis_sampah' => 'Non-Organik',
            'foto'         => 'contoh_non_organik.jpg',
        ]);
    }
}