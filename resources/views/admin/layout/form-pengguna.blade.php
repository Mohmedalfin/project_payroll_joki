<div x-show="openModal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div @click.away="openModal = false" x-show="openModal" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden relative">
        
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold" x-text="isEdit ? 'Edit Akun Pengguna' : 'Tambah User Baru'"></h2>
                <p class="text-blue-100 text-xs mt-1">Atur kredensial login dan hak akses sistem.</p>
            </div>
            <button @click="openModal = false" class="text-white hover:bg-blue-700 p-1 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-8">
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form :action="actionUrl" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" x-model="formData.username" required 
                            placeholder="Masukkan username login"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Role / Hak Akses</label>
                        <select name="role" x-model="formData.role" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                            <option value="">Pilih Role</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hubungkan Karyawan</label>
                        <select name="karyawan_id" x-model="formData.karyawan_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer">
                            <option value="">Pilih Karyawan</option>
                            @foreach($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span x-text="isEdit ? 'Password Baru (Kosongkan jika tidak diubah)' : 'Password Login'"></span>
                        </label>
                        <input type="password" name="password" x-model="formData.password" 
                            :required="!isEdit"
                            placeholder="••••••••"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <p class="text-[10px] text-gray-400 mt-1 italic" x-show="isEdit">*Biarkan kosong jika tidak ingin mengganti password</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100 mt-4">
                    <button type="button" @click="openModal = false" 
                        class="px-6 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition text-sm flex items-center">
                        <span x-text="isEdit ? 'Update Akun' : 'Buat Akun'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>