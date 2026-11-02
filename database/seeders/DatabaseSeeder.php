<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. Buat 2 data Kelurahan ---
        $kelurahanHeledulaa = Kelurahan::create([
            'nama_kelurahan' => 'Heledulaa',
        ]);

        $kelurahanIpilo = Kelurahan::create([
            'nama_kelurahan' => 'Ipilo',
        ]);

        // --- 2. Buat 1 User dengan role 'industri' (tanpa kelurahan) ---
        User::create([
            'name'     => 'Admin Industri',
            'email'    => 'industri@ikm.test',
            'password' => Hash::make('password'),
            'role'     => 'industri',
        ]);

        // --- 3. Buat 2 User dengan role 'kelurahan' ---
        User::create([
            'name'         => 'Petugas Heledulaa',
            'email'        => 'heledulaa@ikm.test',
            'password'     => Hash::make('password'),
            'role'         => 'kelurahan',
            'kelurahan_id' => $kelurahanHeledulaa->id,
        ]);

        User::create([
            'name'         => 'Petugas Ipilo',
            'email'        => 'ipilo@ikm.test',
            'password'     => Hash::make('password'),
            'role'         => 'kelurahan',
            'kelurahan_id' => $kelurahanIpilo->id,
        ]);

        // --- 4. Generate akun kelurahan dari data tabel kelurahans ---
        $this->call(KelurahanUserSeeder::class);
    }
}
