<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeed extends Seeder
{
    public function run()
    {
        // Data mentah (Key array tetap 'id_pengguna' & 'id_karyawan' biar tidak perlu ubah data satu-satu)
        $data = [
            ['id_pengguna' => 1, 'username' => 'diahratri', 'id_karyawan' => 1],
            ['id_pengguna' => 2, 'username' => 'ratnadewi', 'id_karyawan' => 2],
            ['id_pengguna' => 3, 'username' => 'sulistyani', 'id_karyawan' => 3],
            ['id_pengguna' => 4, 'username' => 'maryam', 'id_karyawan' => 4],
            ['id_pengguna' => 5, 'username' => 'naufal', 'id_karyawan' => 5],
            ['id_pengguna' => 6, 'username' => 'wawan', 'id_karyawan' => 6],
            ['id_pengguna' => 7, 'username' => 'bagas', 'id_karyawan' => 7],
            ['id_pengguna' => 8, 'username' => 'yuni', 'id_karyawan' => 8],
            ['id_pengguna' => 9, 'username' => 'ahmad', 'id_karyawan' => 9],
            ['id_pengguna' => 10, 'username' => 'budi', 'id_karyawan' => 10],
            ['id_pengguna' => 11, 'username' => 'Tachwin', 'id_karyawan' => 11],
        ];

        foreach ($data as $item) {
            // Logika Role: Hanya Tachwin yang Admin, sisanya User
            $role = ($item['username'] === 'Tachwin') ? 'admin' : 'User';

            DB::table('pengguna')->insert([
                // MAPPING KOLOM DATABASE (Kiri) => DATA ARRAY (Kanan)
                'id'          => $item['id_pengguna'], // Kolom DB 'id'
                'username'    => $item['username'],
                'password'    => Hash::make('pass123'),
                'role'        => $role,
                'karyawan_id' => $item['id_karyawan'], // Kolom DB 'karyawan_id'
            ]);
        }
    }
}