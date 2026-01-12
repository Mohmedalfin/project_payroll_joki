<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        DB::table('jabatan')->insert([
            [
                'nama_jabatan'       => 'Bagian Keuangan',
                'gaji_pokok'         => 70000,
                'upah_lembur'        => 10000,
                'potongan_terlambat' => 10000,
                'tunjangan_jabatan'  => 15000,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'nama_jabatan'       => 'Admin',
                'gaji_pokok'         => 60000,
                'upah_lembur'        => 10000,
                'potongan_terlambat' => 10000,
                'tunjangan_jabatan'  => 15000,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'nama_jabatan'       => 'Kasir',
                'gaji_pokok'         => 50000,
                'upah_lembur'        => 10000,
                'potongan_terlambat' => 10000,
                'tunjangan_jabatan'  => 15000,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'nama_jabatan'       => 'Karyawan',
                'gaji_pokok'         => 50000,
                'upah_lembur'        => 10000,
                'potongan_terlambat' => 10000,
                'tunjangan_jabatan'  => 15000,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'nama_jabatan'       => 'Direktur',
                'gaji_pokok'         => 80000,
                'upah_lembur'        => 10000,
                'potongan_terlambat' => 10000,
                'tunjangan_jabatan'  => 20000,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}
