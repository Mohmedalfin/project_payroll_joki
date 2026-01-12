<?php

// app/Models/Pengguna.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting untuk Auth!
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'pengguna';

    protected $fillable = [
        'karyawan_id', 
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password', 
    ];

    protected $casts = [
        'password' => 'hashed', 
    ];

    // Relasi: Pengguna adalah milik satu Karyawan
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}