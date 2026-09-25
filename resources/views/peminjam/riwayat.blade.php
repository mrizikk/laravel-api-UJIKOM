@extends('layouts.app')

@section('title', 'Riwayat & Pengembalian – Peminjam')
@section('header-title', 'Riwayat & Pengembalian Alat')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <details class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" {{ $errors->any() ? 'open' : '' }}>
        <summary class="cursor-pointer select-none p-5 bg-gray-50 font-semibold text-gray-800 flex items-center justify-between">
            Ajukan Peminjaman Baru
            <span class="text-sm font-normal text-blue-600">Klik untuk buka/tutup</span>
        </summary>

        <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST" class="border-t border-gray-200">
            @csrf

            <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Pinjam
                    </label>
                    <input type="date"
                           name="tgl_pinjam"
                           required
                           min="{{ now()->format('Y-m-d') }}"
                           value="{{ old('tgl_pinjam', now()->format('Y-m-d')) }}"
                           class="w-full md:w-72 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Rencana Tanggal Kembali
                    </label>
                    <input type="date"
                           name="tgl_kembali_plan"
                           required
                           class="w-full md:w-72 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="py-3 px-4 border-b w-14 text-center">Pilih</th>
                            <th class="py-3 px-4 border-b">Nama Alat</th>
                            <th class="py-3 px-4 border-b">Kategori</th>
                            <th class="py-3 px-4 border-b">Stok Tersedia</th>
                            <th class="py-3 px-4 border-b w-36">Jumlah Pinjam</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700 text-sm">
                        @forelse($alats as $alat)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 border-b text-center">
                                    <input type="checkbox"
                                           name="alat_id[]"
                                           value="{{ $alat->id }}"
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-3 px-4 border-b font-medium text-gray-900">{{ $alat->nama_alat }}</td>
                                <td class="py-3 px-4 border-b">{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                                <td class="py-3 px-4 border-b">{{ $alat->stok }}</td>
                                <td class="py-3 px-4 border-b">
                                    <input type="number"
                                           name="jumlah[]"
                                           value="1"
                                           min="1"
                                           max="{{ $alat->stok }}"
                                           class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">
                                    Tidak ada alat yang tersedia saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 bg-gray-50">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </details>

    @forelse($peminjamans as $peminjaman)
        @php
            $statusBadge = match($peminjaman->status) {
                'diajukan' => 'bg-amber-100 text-amber-800 border border-amber-200',
                'disetujui' => 'bg-blue-100 text-blue-800 border border-blue-200',
                'dipinjam' => 'bg-sky-100 text-sky-800 border border-sky-200',
                'menunggu_konfirmasi' => 'bg-purple-100 text-purple-800 border border-purple-200',
                'dikembalikan' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                'ditolak' => 'bg-red-100 text-red-800 border border-red-200',
                'terlambat' => 'bg-red-100 text-red-800 border border-red-200',
                default => 'bg-gray-100 text-gray-700 border border-gray-200',
            };

            $statusLabel = match($peminjaman->status) {
                'diajukan' => 'Diajukan',
                'disetujui' => 'Disetujui',
                'dipinjam' => 'Dipinjam',
                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                'dikembalikan' => 'Selesai',
                'ditolak' => 'Ditolak',
                'terlambat' => 'Telat Dikembalikan',
                default => ucfirst($peminjaman->status),
            };
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-5">

            <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                <div>
                    <div class="text-sm text-gray-400 mb-1">Peminjaman #{{ $peminjaman->id }}</div>
                    <div class="text-lg font-bold text-gray-800">
                        {{ $peminjaman->tgl_pinjam->translatedFormat('d-m-Y') }}
                        —
                        {{ $peminjaman->tgl_kembali_plan->translatedFormat('d-m-Y') }}
                    </div>
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge }}">
                    {{ $statusLabel }}
                </span>
            </div>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alat Dipinjam</div>
            <div class="flex flex-wrap gap-2 mb-4">
                @forelse($peminjaman->detailPinjams as $detail)
                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-sm font-medium px-3 py-1.5 rounded-full">
                        {{ $detail->alat->nama_alat ?? '-' }}
                        <span class="text-blue-400">×{{ $detail->jumlah }}</span>
                    </span>
                @empty
                    <span class="text-sm text-gray-500">Tidak ada detail alat.</span>
                @endforelse
            </div>

            <div class="bg-gray-50 rounded-lg px-4 py-3 flex items-center justify-between text-sm mb-4">
                <span class="text-gray-500">Denda</span>
                <span class="font-semibold text-gray-800">
                    Rp {{ number_format($peminjaman->pengembalian->denda ?? 0, 0, ',', '.') }}
                </span>
            </div>

            @if(in_array($peminjaman->status, ['dipinjam', 'terlambat']))
                <form action="{{ route('peminjam.peminjaman.ajukanPengembalian', $peminjaman->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg flex items-center justify-center gap-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        Ajukan Pengembalian
                    </button>
                </form>
            @elseif($peminjaman->status === 'menunggu_konfirmasi')
                <div class="bg-purple-50 text-purple-700 text-sm rounded-lg px-4 py-3">
                    Pengembalian sudah diajukan, menunggu konfirmasi petugas.
                </div>
            @endif

        </div>
    @empty
        <div class="bg-gray-50 border border-gray-200 text-gray-600 p-5 rounded-lg text-sm">
            Kamu belum pernah mengajukan peminjaman alat.
            <a href="{{ route('peminjam.katalog') }}" class="text-blue-600 font-medium hover:underline">Ajukan sekarang</a>.
        </div>
    @endforelse

@endsection