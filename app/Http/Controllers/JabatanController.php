<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatan = Jabatan::all();
        return view('admin.data-jabatan', compact('jabatan'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'gaji_pokok' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
            'upah_lembur' => 'required|numeric',
            'potongan_terlambat' => 'required|numeric',
        ]);

        // Simpan ke Database
        Jabatan::create($validated);

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cari data berdasarkan ID
        $jabatan = Jabatan::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'gaji_jabatan' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
            'upah_lembur' => 'required|numeric',
            'potongan_terlambat' => 'required|numeric',
        ]);

        // Update data
        $jabatan->update($validated);

        return redirect()->route('admin.jabatan.index')->with('success', 'Data jabatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil dihapus!');
    }
}
