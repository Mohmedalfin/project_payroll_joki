@extends('admin.layout.app')

@section('title', 'Kelola Gaji')

@section('content')
    <div x-data="gajiModal()" class="w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">
            
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Kelola Gaji</h2>
                    <p class="text-sm text-gray-500">Daftar riwayat bonus dan gaji karyawan</p>
                </div>
                
                <button @click="resetForm(); openModal = true" 
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center text-sm font-medium shadow-lg shadow-blue-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Data
                </button>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold text-xs border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Karyawan</th>
                            <th class="px-6 py-4">Bonus</th>
                            <th class="px-6 py-4">Periode/Tanggal</th>
                            <th class="px-6 py-4 text-center w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($gaji as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800">
                                    {{ $item->karyawan->nama_karyawan ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-green-600 font-bold">
                                    Rp {{ number_format($item->bonus, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->periode)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click='editData(@json($item))' class="p-2 text-yellow-600 hover:bg-yellow-50 rounded transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.gaji.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data riwayat gaji ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada riwayat pembayaran bonus/gaji.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('admin.layout.form-gaji')
    </div>

    <script>
        function gajiModal() {
            return {
                openModal: false,
                isEdit: false,
                actionUrl: "{{ route('admin.gaji.store') }}",
                formData: {
                    id: '',
                    karyawan_id: '',
                    bonus: '',
                    periode: ''
                },
                resetForm() {
                    this.isEdit = false;
                    this.actionUrl = "{{ route('admin.gaji.store') }}";
                    this.formData = { id: '', karyawan_id: '', bonus: '', periode: '' };
                },
                editData(data) {
                    this.isEdit = true;
                    this.openModal = true;
                    this.actionUrl = `{{ route('admin.gaji.index') }}/${data.id}`;
                    this.formData = {
                        id: data.id,
                        karyawan_id: data.karyawan_id,
                        bonus: data.bonus,
                        periode: data.periode
                    };
                }
            }
        }
    </script>
@endsection