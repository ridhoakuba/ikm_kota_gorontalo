<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KelurahanUserSeeder extends Seeder
{
    /**
     * Buat atau perbarui akun petugas untuk setiap kelurahan yang ada di database.
     * Aman dijalankan berulang kali — tidak akan membuat duplikat.
     */
    public function run(): void
    {
        $kelurahans = Kelurahan::all();

        foreach ($kelurahans as $kelurahan) {
            $email = strtolower(str_replace(' ', '', $kelurahan->nama_kelurahan))
                     . '@gorontalokota.go.id';

            User::updateOrCreate(
                // Kondisi pencarian — pastikan satu akun per kelurahan
                ['kelurahan_id' => $kelurahan->id, 'role' => 'kelurahan'],
                // Data yang dibuat/diperbarui
                [
                    'name'         => 'Admin Kelurahan ' . $kelurahan->nama_kelurahan,
                    'email'        => $email,
                    'password'     => Hash::make('password'),
                    'role'         => 'kelurahan',
                    'kelurahan_id' => $kelurahan->id,
                ]
            );

            $this->command->info("✓ Akun kelurahan [{$kelurahan->nama_kelurahan}] → {$email}");
        }

        $this->command->info('');
        $this->command->info('Selesai! Default password: password');
    }
}
