<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Jabatan;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = Karyawan::with('jabatan')->get();
        
        $jabatan = Jabatan::all();

        return view('admin.data-karyawan', compact('karyawan', 'jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jabatan_id'    => 'required|exists:jabatan,id',            
            'nama_karyawan' => 'required|string|max:255',            
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',            
            'tgl_lahir'     => 'required|date',            
            'no_tlpn'       => 'required|string|max:20',           
            'alamat'        => 'required|string', 
        ]);

        Karyawan::create($validated);

        return redirect()->route('admin.karyawan.index')->with('success', 'Jabatan berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'jabatan_id'    => 'required|exists:jabatan,id',            
            'nama_karyawan' => 'required|string|max:255',            
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',            
            'shift'         => 'required|in:1,2',            
            'tgl_lahir'     => 'required|date',            
            'no_tlpn'       => 'required|string|max:20',           
            'alamat'        => 'required|string', 
        ]);

        $karyawan->update($validated);

        return redirect()->route('admin.karyawan.index')->with('success', 'Data jabatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')->with('success', 'Data jabatan berhasil dihapus!');
    }
}
