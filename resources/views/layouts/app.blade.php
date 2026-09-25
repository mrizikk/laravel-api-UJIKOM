<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/tema-peminjaman-alat.css') }}">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-gray-900 text-white flex flex-col hidden md:flex">
            <div class="p-5 border-b border-gray-800 flex flex-col items-center text-center">
                @if(auth()->user()->role === 'admin')
                    <img src="{{ asset('images/logo-admin.png') }}" alt="Logo Admin" class="w-16 h-16 object-contain mb-2">
                    <span class="text-lg font-bold tracking-wider">Admin</span>
                @elseif(auth()->user()->role === 'petugas')
                    <img src="{{ asset('images/logo-admin.png') }}" alt="Logo Petugas" class="w-16 h-16 object-contain mb-2">
                    <span class="text-lg font-bold tracking-wider">Petugas</span>
                @elseif(auth()->user()->role === 'peminjam')
                    <img src="{{ asset('images/logo-admin.png') }}" alt="Logo Peminjam" class="w-16 h-16 object-contain mb-2">
                    <span class="text-lg font-bold tracking-wider">Peminjam</span>
                @else
                    <span class="text-xl font-bold tracking-wider">DASHBOARD</span>
                @endif
            </div>
            <nav class="flex-1 p-4 space-y-2">

                @if(auth()->user()->role === 'admin')
                    {{-- MENU KHUSUS ADMIN --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Dashboard</a>
                    <a href="{{ route('admin.user.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.user*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Kelola User</a>
                    <a href="{{ route('admin.kategori.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.kategori*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Kelola Kategori</a>
                    <a href="{{ route('admin.alat.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.alat*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Kelola Alat</a>
                    <a href="{{ route('admin.peminjaman.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.peminjaman*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Kelola Peminjaman</a>
                    <a href="{{ route('admin.pengembalian.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.pengembalian*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Kelola Pengembalian</a>

                @elseif(auth()->user()->role === 'petugas')
                    {{-- MENU KHUSUS PETUGAS --}}
                    <a href="{{ route('petugas.peminjaman.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('petugas.peminjaman*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Persetujuan Peminjaman</a>
                    <a href="{{ route('petugas.pengembalian.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('petugas.pengembalian*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Pemantauan Pengembalian</a>
                    <a href="{{ route('petugas.laporan.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('petugas.laporan*') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Cetak Laporan</a>

                @elseif(auth()->user()->role === 'peminjam')
                    {{-- MENU KHUSUS PEMINJAM --}}
                    <a href="{{ route('peminjam.katalog') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('peminjam.katalog') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Katalog Alat</a>
                    <a href="{{ route('peminjam.riwayat') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('peminjam.riwayat') ? 'bg-gray-800 text-white font-medium shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        Riwayat & Pengembalian</a>
                @endif

            </nav>
            <div class="p-4 border-t border-gray-800 text-sm text-gray-400">
                Logged in as: <span class="text-white font-semibold">{{ auth()->user()->name }}</span>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">

            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
                <div class="text-lg font-semibold text-gray-800">
                    @yield('header-title', 'Dashboard')
                </div>
                <div>
                    <button type="button" onclick="document.getElementById('logoutModal').classList.remove('hidden')"
                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                        Logout
                    </button>

                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </header>

            <main class="flex-1 p-6">
                @yield('content')
            </main>

        </div>
    </div>
    <!-- Modal Konfirmasi Logout -->
    <div id="logoutModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 overflow-hidden">
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Konfirmasi Logout</h3>
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin logout dari sistem?</p>
            </div>
            <div class="flex border-t border-gray-200">
                <button type="button" onclick="document.getElementById('logoutModal').classList.add('hidden')"
                    class="flex-1 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition border-r border-gray-200">
                    Batal
                </button>
                <button type="button" onclick="document.getElementById('logoutForm').submit()"
                    class="flex-1 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>

</body>
</html>