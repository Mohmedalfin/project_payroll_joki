@extends('admin.layout.app')

@section('title', 'Data Pengguna')

@section('content')
    <div x-data="penggunaModal()" class="w-full">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h2 class="text-xl font-bold text-gray-800">Data Pengguna</h2>
                    <p class="text-sm text-gray-500">Manajemen akses login aplikasi</p>
                </div>
                <button @click="resetForm(); openModal = true" 
                    class="w-full sm:w-auto px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center text-sm font-medium shadow-lg shadow-blue-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah User
                </button>
            </div>

            <div class="hidden sm:block overflow-x-auto w-full">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 w-16">No</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Role / Hak Akses</th>
                            <th class="px-6 py-4">Karyawan Terkait</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengguna as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item->username }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $item->role == 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                        {{ $item->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->karyawan->nama_karyawan ?? 'Tidak Terhubung' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button @click='editData(@json($item))' class="p-2 text-yellow-600 hover:bg-yellow-50 rounded transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.pengguna.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus akses pengguna ini?');">
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
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('admin.layout.form-pengguna')

    </div>

    <script>
        function penggunaModal() {
            return {
                openModal: false,
                isEdit: false,
                actionUrl: "{{ route('admin.pengguna.store') }}",
                formData: {
                    id: '',
                    username: '',
                    role: '',
                    karyawan_id: '',
                    password: ''
                },
                resetForm() {
                    this.isEdit = false;
                    this.actionUrl = "{{ route('admin.pengguna.store') }}";
                    this.formData = { id: '', username: '', role: '', karyawan_id: '', password: '' };
                },
                editData(data) {
                    this.isEdit = true;
                    this.openModal = true;
                    this.actionUrl = `{{ route('admin.pengguna.index') }}/${data.id}`;
                    this.formData = {
                        id: data.id,
                        username: data.username,
                        role: data.role,
                        karyawan_id: data.karyawan_id,
                        password: '' // Kosongkan password saat edit kecuali ingin diubah
                    };
                }
            }
        }
    </script>
@endsection