<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder // <--- Pastikan namanya KaryawanSeeder (sesuai nama file)
{
    public function run()
    {
        // 1. Matikan Foreign Key Check sebentar untuk Truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 2. Kosongkan tabel & Reset ID ke 1
        DB::table('karyawan')->truncate();
        
        // 3. Hidupkan kembali Foreign Key Check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Insert Data
        DB::table('karyawan')->insert([
            [
                'id'            => 1,
                'jabatan_id'    => 1,
                'nama_karyawan' => 'Diah Ratri',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir'     => '1990-05-15',
                'no_tlpn'       => '081234567890',
                'alamat'        => 'Ngaglik, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 2,
                'jabatan_id'    => 2,
                'nama_karyawan' => 'Ratna Dewi',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir'     => '1992-08-20',
                'no_tlpn'       => '081234567891',
                'alamat'        => 'Pandowoharjo, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 3,
                'jabatan_id'    => 3,
                'nama_karyawan' => 'Sulistyani',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir'     => '1995-03-10',
                'no_tlpn'       => '081234567892',
                'alamat'        => 'Ngaglik, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 4,
                'jabatan_id'    => 3,
                'nama_karyawan' => 'Maryam',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir'     => '1994-11-25',
                'no_tlpn'       => '081234567893',
                'alamat'        => 'Pandowoharjo, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 5,
                'jabatan_id'    => 4,
                'nama_karyawan' => 'Naufal',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir'     => '1997-01-12',
                'no_tlpn'       => '081234567894',
                'alamat'        => 'Ngaglik, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 6,
                'jabatan_id'    => 4,
                'nama_karyawan' => 'Wawan',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir'     => '1996-07-30',
                'no_tlpn'       => '081234567895',
                'alamat'        => 'Pandowoharjo, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 7,
                'jabatan_id'    => 4,
                'nama_karyawan' => 'Bagas',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir'     => '1998-09-05',
                'no_tlpn'       => '081234567896',
                'alamat'        => 'Ngaglik, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 8,
                'jabatan_id'    => 4,
                'nama_karyawan' => 'Yuni',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir'     => '1999-12-14',
                'no_tlpn'       => '081234567897',
                'alamat'        => 'Pandowoharjo, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 9,
                'jabatan_id'    => 4,
                'nama_karyawan' => 'Ahmad',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir'     => '2000-04-22',
                'no_tlpn'       => '081234567898',
                'alamat'        => 'Ngaglik, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 10,
                'jabatan_id'    => 4,
                'nama_karyawan' => 'Budi',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir'     => '1997-06-18',
                'no_tlpn'       => '081234567899',
                'alamat'        => 'Pandowoharjo, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 11,
                'jabatan_id'    => 5,
                'nama_karyawan' => 'Tachwin',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir'     => '1985-05-15',
                'no_tlpn'       => '081234567888',
                'alamat'        => 'Ngaglik, Sleman',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}