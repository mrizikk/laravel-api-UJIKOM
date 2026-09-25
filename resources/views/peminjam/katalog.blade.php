@extends('layouts.app')

@section('title', 'Katalog Alat – Peminjam')
@section('header-title', 'Katalog Alat Tersedia')

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

    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg shadow-sm text-sm">
        Ingin meminjam alat? Ajukan peminjaman dari halaman
        <a href="{{ route('peminjam.riwayat') }}" class="font-semibold underline">Riwayat & Pengembalian</a>.
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="py-3 px-4 border-b">Nama Alat</th>
                        <th class="py-3 px-4 border-b">Kategori</th>
                        <th class="py-3 px-4 border-b">Stok Tersedia</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700 text-sm">
                    @forelse($alats as $alat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 border-b font-medium text-gray-900">{{ $alat->nama_alat }}</td>
                            <td class="py-3 px-4 border-b">{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                            <td class="py-3 px-4 border-b">{{ $alat->stok }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                Tidak ada alat yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection