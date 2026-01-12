<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Karyawan;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = Jabatan::first();
        if (!$jabatan) {
            $jabatan = Jabatan::create([
                'nama_jabatan' => 'Administrator',
                'gaji_pokok' => 5000000,
                'tunjangan_jabatan' => 1000000
            ]);
        }

        $karyawan = Karyawan::create([
            'nama_karyawan' => 'Super Administrator',
            'alamat'        => 'Server Room',
            'no_tlpn'       => '08000000000',
            'jenis_kelamin' => 'Laki-laki', 
            'jabatan_id'    => $jabatan->id,
            'tgl_lahir' => now(),
        ]);

        Pengguna::create([
            'karyawan_id' => $karyawan->id,
            'username'    => 'admin',
            'password'    => Hash::make('password'),
            'role'        => 'admin',
        ]);
    }
}