<?php

// app/Models/Jabatan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan'; // Nama tabel eksplisit

    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
        'upah_lembur',
        'potongan_terlambat',
        'tunjangan_jabatan',
    ];

    // Relasi: Satu Jabatan memiliki banyak Karyawan
    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'jabatan_id');
    }
}