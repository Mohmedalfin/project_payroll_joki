@extends('admin.layout.app')

@section('title', 'Data Jabatan')

@section('content')
    <div x-data="jabatanModal()" class="w-full">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h2 class="text-xl font-bold text-gray-800">Data Jabatan</h2>
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <p class="text-sm text-gray-500">Pengaturan gaji pokok dan tunjangan</p>
                </div>
                
                <button @click="resetForm(); openModal = true" 
                    class="w-full sm:w-auto px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Jabatan
                </button>
            </div>

            <div class="hidden sm:block overflow-x-auto w-full">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold text-xs border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Jabatan</th>
                            <th class="px-6 py-4">Gaji Pokok</th>
                            <th class="px-6 py-4">Tunjangan</th>
                            <th class="px-6 py-4">Lembur/Jam</th>
                            <th class="px-6 py-4 text-red-600">Potongan Telat</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($jabatan as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item->nama_jabatan }}</td>
                                <td class="px-6 py-4 text-gray-700">Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-green-600">+ Rp {{ number_format($item->tunjangan_jabatan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item->upah_lembur, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-red-500">- Rp {{ number_format($item->potongan_terlambat, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button @click='editData(@json($item))' class="p-2 text-yellow-600 hover:bg-yellow-50 rounded transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.jabatan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jabatan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data jabatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('admin.layout.form-jabatan')

    </div>

    <script>
        function jabatanModal() {
            return {
                openModal: false, // Wajib FALSE agar tidak muncul otomatis
                isEdit: false,
                actionUrl: "{{ route('admin.jabatan.store') }}",
                
                formData: {
                    id: null,
                    nama_jabatan: '',
                    gaji_pokok: '',
                    tunjangan_jabatan: '',
                    upah_lembur: '',
                    potongan_terlambat: ''
                },

                resetForm() {
                    this.isEdit = false;
                    this.openModal = false; // Pastikan tertutup dulu saat reset
                    this.actionUrl = "{{ route('admin.jabatan.store') }}";
                    this.formData = {
                        id: null,
                        nama_jabatan: '',
                        gaji_pokok: '',
                        tunjangan_jabatan: '',
                        upah_lembur: '',
                        potongan_terlambat: ''
                    };
                },

                editData(data) {
                    this.resetForm();
                    this.isEdit = true;
                    
                    this.$nextTick(() => {
                        this.openModal = true;
                        this.actionUrl = `{{ url('admin/data-jabatan') }}/${data.id}`;
                        
                        // Isi data satu per satu
                        this.formData.id = data.id;
                        this.formData.nama_jabatan = data.nama_jabatan;
                        this.formData.gaji_pokok = data.gaji_pokok; // <--- Cek di console log apakah data ini ada
                        this.formData.tunjangan_jabatan = data.tunjangan_jabatan;
                        this.formData.upah_lembur = data.upah_lembur;
                        this.formData.potongan_terlambat = data.potongan_terlambat;
                    });
                } 
            }
        }
    </script>
@endsection