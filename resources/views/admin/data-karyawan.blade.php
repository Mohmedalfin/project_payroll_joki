@extends('admin.layout.app')

@section('title', 'Data Karyawan')

@section('content')
    <div x-data="karyawanModal()" class="w-full">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h2 class="text-xl font-bold text-gray-800">Data Karyawan</h2>
                    <p class="text-sm text-gray-500">Kelola informasi profil dan penempatan kerja.</p>
                </div>
                
                <button @click="resetForm(); openModal = true" 
                    class="w-full sm:w-auto px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Karyawan
                </button>
            </div>

            <div class="hidden sm:block overflow-x-auto w-full">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold text-xs border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Karyawan</th>
                            <th class="px-6 py-4">Jabatan</th>
                            <th class="px-6 py-4">Shift Kerja</th>
                            <th class="px-6 py-4">Jenis Kelamin</th>
                            <th class="px-6 py-4">Tgl/Bln Lahir</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($karyawan as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3 text-xs">
                                            {{ strtoupper(substr($item->nama_karyawan, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $item->nama_karyawan }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-[10px] font-bold uppercase">
                                        {{ $item->jabatan->nama_jabatan ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">Shift {{ $item->shift ?? '-'}}</td>

                                <td class="px-6 py-4 text-gray-600">{{ $item->jenis_kelamin }}</td>
                                
                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->tgl_lahir)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->no_tlpn }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button @click='editData(@json($item))' class="p-2 text-yellow-600 hover:bg-yellow-50 rounded transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.karyawan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data karyawan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('admin.layout.form-karyawan')

    </div>

    <script>
        function karyawanModal() {
            return {
                openModal: false,
                isEdit: false,
                actionUrl: "{{ route('admin.karyawan.store') }}",
                formData: {
                    id: '',
                    nama_karyawan: '',
                    jabatan_id: '',
                    shift:'',
                    jenis_kelamin: '',
                    tgl_lahir: '',
                    no_tlpn: '',
                    alamat: ''
                },
                resetForm() {
                    this.isEdit = false;
                    this.actionUrl = "{{ route('admin.karyawan.store') }}";
                    this.formData = { id: '', nama_karyawan: '', jabatan_id: '', shift:'', jenis_kelamin: '', tgl_lahir: '', no_tlpn: '', alamat: '' };
                },
                editData(data) {
                    this.isEdit = true;
                    this.openModal = true;
                    this.actionUrl = `{{ url('admin/data-karyawan') }}/${data.id}`;
                    this.formData = {
                        id: data.id,
                        nama_karyawan: data.nama_karyawan,
                        jabatan_id: data.jabatan_id,
                        jenis_kelamin: data.jenis_kelamin,
                        shift: data.shift,
                        tgl_lahir: data.tgl_lahir,
                        no_tlpn: data.no_tlpn,
                        alamat: data.alamat
                    };
                }
            }
        }
    </script>
@endsection