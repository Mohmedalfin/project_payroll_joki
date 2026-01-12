<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Karyawan;

class GajiController extends Controller
{
    /**
     * Menampilkan daftar gaji karyawan.
     */
    public function index()
    {
        $gaji = Gaji::with('karyawan')->get();
        $karyawan = Karyawan::all();

        return view('admin.gaji-karyawan', compact('gaji', 'karyawan'));
    }

    /**
     * Menyimpan data gaji baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',            
            'bonus'       => 'required|numeric|min:0', 
            'periode'     => 'required|string|max:50',
            // Catatan: Nama karyawan biasanya tidak divalidasi unique di tabel gaji 
            // karena satu karyawan bisa menerima gaji di periode yang berbeda.
        ]);

        Gaji::create($validated);

        return redirect()->route('admin.gaji.index')->with('success', 'Data Gaji berhasil ditambahkan!');
    }

    /**
     * Memperbarui data gaji.
     */
    public function update(Request $request, string $id)
    {
        $gaji = Gaji::findOrFail($id);

        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',            
            'bonus'       => 'required|numeric|min:0', 
            'periode'     => 'required|string|max:50',
        ]);

        $gaji->update($validated);

        return redirect()->route('admin.gaji.index')->with('success', 'Data Gaji berhasil diperbarui!');
    }

    /**
     * Menghapus data gaji.
     */
    public function destroy(string $id)
    {
        $gaji = Gaji::findOrFail($id);
        $gaji->delete();

        return redirect()->route('admin.gaji.index')->with('success', 'Data Gaji berhasil dihapus!');
    }
}