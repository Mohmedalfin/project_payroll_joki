<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 sticky top-0 z-10">
    <button id="openSidebar" class="text-gray-500 hover:text-blue-600 focus:outline-none p-2 rounded-md hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
    </button>

    <h2 class="text-lg font-semibold text-gray-700 lg:hidden">Dashboard</h2>

    <div class="flex items-center space-x-3">
        @php
            $user = Auth::user();
            $karyawan = $user->karyawan ?? null;
        @endphp

        <span class="text-sm font-semibold text-gray-800">
            {{ $karyawan->nama_karyawan ?? $user->username ?? 'User' }}
        </span>
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200">
            A
        </div>
    </div>
</header>