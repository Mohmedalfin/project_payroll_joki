<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:fixed flex flex-col">
    <div class="flex items-center justify-center h-16 border-b border-gray-200">
        <span class="text-xl font-bold text-gray-800">Namira Mart</span>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-2.5 rounded-lg group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        @php
            $isMasterActive = request()->routeIs('admin.pengguna*', 'admin.karyawan*', 'admin.jabatan*', 'admin.data-*');
        @endphp

        <div>
            <button type="button" onclick="toggleDropdown('masterMenu')" 
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg focus:outline-none group {{ $isMasterActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Data Master
                </div>
                <svg id="masterMenu-arrow" class="w-4 h-4 transition-transform duration-200 {{ $isMasterActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            
            <div id="masterMenu" class="{{ $isMasterActive ? 'block' : 'hidden' }} pl-11 pr-2 mt-1 space-y-1 transition-all duration-200">
                
                <a href="{{ route('admin.pengguna.index') }}" 
                   class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.pengguna*') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                   Data Pengguna
                </a>
                
                <a href="{{ route('admin.karyawan.index') }}" 
                   class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.karyawan*') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                   Data Karyawan
                </a>
                
                <a href="{{ route('admin.jabatan.index') }}" 
                   class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.jabatan*') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                   Data Jabatan
                </a>
                
                <a href="{{ route('admin.data-kehadiran') }}" 
                   class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.data-kehadiran*') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                   Data Kehadiran
                </a>
            </div>
        </div>

        <a href="{{ route('admin.gaji.index') }}" 
           class="flex items-center px-4 py-2.5 rounded-lg group {{ request()->routeIs('admin.gaji*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Gaji
        </a>

        <a href="{{ route('admin.laporan.gaji') }}" 
           class="flex items-center px-4 py-2.5 rounded-lg group {{ request()->routeIs('admin.laporan*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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

<script>
    function toggleDropdown(menuId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(menuId + '-arrow');
        
        if (menu) {
            // Toggle visibility
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.classList.add('block');
            } else {
                menu.classList.add('hidden');
                menu.classList.remove('block');
            }
        }
        
        if (arrow) {
            arrow.classList.toggle('rotate-180');
        }
    }
</script>