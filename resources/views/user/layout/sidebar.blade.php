<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:fixed flex flex-col">
    <div class="flex items-center justify-center h-16 border-b border-gray-200">
        <span class="text-xl font-bold text-gray-800">Namira Mart</span>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
        
        {{-- MENU ABSENSI --}}
        {{-- Aktif jika route diawali dengan 'user.absensi' --}}
        <a href="{{ route('user.absensi') }}" 
           class="flex items-center px-4 py-2.5 rounded-lg group transition-colors {{ request()->routeIs('user.absensi*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('user.absensi*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Absensi
        </a>

        {{-- MENU LAPORAN --}}
        {{-- PERBAIKAN: Ubah pengecekan menjadi 'user.gaji*' karena linknya ke user.gaji.index --}}
        <a href="{{ route('user.gaji.index') }}" 
           class="flex items-center px-4 py-2.5 rounded-lg group transition-colors {{ request()->routeIs('user.gaji*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('user.gaji*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Laporan
        </a>

    </nav>

    <div class="p-4 border-t border-gray-200">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>