<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Namira Mart</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sembunyikan elemen yang punya atribut x-cloak */
        [x-cloak] { 
            display: none !important; 
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden transition-opacity"></div>

    @include('admin.layout.sidebar')

    <div id="mainContent" class="flex-1 flex flex-col min-h-screen transition-all duration-300 lg:ml-64">
        
        @include('admin.layout.navbar')

        <main class="p-6">
            @yield('content')
        </main>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            const openSidebarBtn = document.getElementById('openSidebar');
            
            // Toggle Sidebar Mobile & Desktop
            window.toggleSidebar = function() {
                if (window.innerWidth < 1024) {
                    // Logic Mobile
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                } else {
                    // Logic Desktop (Minimize)
                    sidebar.classList.toggle('lg:hidden');
                    if (sidebar.classList.contains('lg:hidden')) {
                        mainContent.classList.remove('lg:ml-64');
                    } else {
                        mainContent.classList.add('lg:ml-64');
                    }
                }
            }

            // Close Sidebar ketika klik overlay (Mobile)
            overlay.addEventListener('click', window.toggleSidebar);
            if(openSidebarBtn) openSidebarBtn.addEventListener('click', window.toggleSidebar);
        });

        // Toggle Dropdown Menu
        function toggleDropdown(id) {
            const menu = document.getElementById(id);
            const arrow = document.getElementById(id + '-arrow');
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>