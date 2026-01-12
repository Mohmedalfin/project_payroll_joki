<div x-show="openModal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div @click.away="openModal = false" x-show="openModal" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
        
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold" x-text="isEdit ? 'Edit Data Karyawan' : 'Tambah Jabatan Baru'"></h2>
                <p class="text-blue-100 text-xs mt-1">Kelola informasi profil dan penempatan kerja.</p>
            </div>
            <button @click="openModal = false" class="text-white hover:bg-blue-700 p-1 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-8">
            <form :action="actionUrl" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_karyawan" x-model="formData.nama_karyawan" required 
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                        <select name="jabatan_id" x-model="formData.jabatan_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatan as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" x-model="formData.jenis_kelamin" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Shift Kerja</label>
                        <select name="shift" x-model="formData.shift" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                            <option value="">Shift Kerja</option>
                            <option value="1">Shift 1</option>
                            <option value="2">Shift 2</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" x-model="formData.tgl_lahir" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="no_tlpn" x-model="formData.no_tlpn" required 
                            placeholder="08xxxx"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Domisili</label>
                    <textarea name="alamat" x-model="formData.alamat" rows="2" 
                        placeholder="Alamat lengkap..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                    <button type="button" @click="openModal = false" 
                        class="px-6 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition flex items-center">
                        <span x-text="isEdit ? 'Simpan Data' : 'Simpan Data'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>