<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    public function index()
    {
        // Gunakan nama $users agar cocok dengan file data-pengguna.blade.php Anda
        $pengguna= Pengguna::with('karyawan')->get();
        $karyawan = Karyawan::all();

        return view('admin.data-pengguna', compact('pengguna', 'karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',            
            'username'    => 'required|string|max:50|unique:pengguna,username',              
            'password'    => 'required|string|min:6|max:100',
            'role'        => 'required|string|max:50',         
        ]);

        // WAJIB: Enkripsi password sebelum simpan
        $validated['password'] = bcrypt($request->password);

        Pengguna::create($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',            
            'username'    => 'required|string|max:50|unique:pengguna,username,' . $id,            
            'password'    => 'nullable|string|min:6|max:100', 
            'role'        => 'required|string|max:50',         
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data Pengguna berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Data Pengguna berhasil dihapus!');
    }
}