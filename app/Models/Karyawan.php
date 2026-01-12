<?php

// app/Models/Karyawan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'jabatan_id', // FK
        'shift',
        'nama_karyawan',
        'jenis_kelamin',
        'tgl_lahir',
        'no_tlpn',
        'alamat',
    ];

    // Relasi: Karyawan milik satu Jabatan
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // Relasi: Karyawan punya satu Akun Pengguna
    public function pengguna(): HasOne
    {
        return $this->hasOne(Pengguna::class, 'karyawan_id');
    }

    // Relasi: Karyawan punya banyak log Kehadiran
    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class, 'karyawan_id');
    }

    // Relasi: Karyawan punya banyak riwayat Gaji
    public function gaji(): HasMany
    {
        return $this->hasMany(Gaji::class, 'karyawan_id');
    }
}
