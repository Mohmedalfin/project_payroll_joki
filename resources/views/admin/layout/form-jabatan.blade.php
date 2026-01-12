<div x-show="openModal" x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50 bg-opacity-50 z-50 flex items-center justify-center p-4">

    <div @click.away="openModal = false"
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden relative">

        <div class="bg-blue-600 p-6 flex justify-between items-center text-white">
            <div>
                <h2 class="text-xl font-bold" x-text="isEdit ? 'Edit Data Jabatan' : 'Tambah Jabatan Baru'"></h2>
                <p class="text-blue-100 text-sm mt-1">Kelola informasi gaji dan tunjangan jabatan.</p>
            </div>
            <button @click="openModal = false" class="text-white hover:bg-blue-700 p-2 rounded-full transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-8">
            <form :action="actionUrl" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" x-model="formData.nama_jabatan" required placeholder="Contoh: Staff Gudang"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" x-model="formData.gaji_pokok" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tunjangan (Rp)</label>
                        <input type="number" name="tunjangan_jabatan" x-model="formData.tunjangan_jabatan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Lembur /Jam (Rp)</label>
                        <input type="number" name="upah_lembur" x-model="formData.upah_lembur" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-red-600 mb-2">Potongan Terlambat (Rp)</label>
                        <input type="number" name="potongan_terlambat" x-model="formData.potongan_terlambat" required
                            class="w-full px-4 py-2 border border-red-200 text-red-700 rounded-lg focus:ring-2 focus:ring-red-500 outline-none transition bg-red-50">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 mt-4 border-t border-gray-100">
                    <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition flex items-center">
                        <span x-text="isEdit ? 'Update Data' : 'Simpan Data'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>