<div x-show="openModal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div @click.away="openModal = false" x-show="openModal" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden relative">
        
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold" x-text="isEdit ? 'Edit Data Gaji' : 'Tambah Data Gaji'"></h2>
                <p class="text-blue-100 text-xs mt-1">Kelola pemberian bonus dan periode pembayaran.</p>
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Karyawan</label>
                    <select name="karyawan_id" x-model="formData.karyawan_id" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_karyawan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bonus (Rp)</label>
                        <div class="relative flex items-center">
                            <input type="number" name="bonus" x-model="formData.bonus" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Periode Pembayaran</label>
                        <input type="date" 
                            name="periode" 
                            x-model="formData.periode" 
                            required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100 mt-4">
                    <button type="button" @click="openModal = false" 
                        class="px-6 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Data'"></span>
                    </button>
                </div>
            </form>
        </div>

       
    </div>
</div>