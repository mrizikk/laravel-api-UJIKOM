@extends('layouts.app')

@section('title', 'Edit Pengembalian')
@section('header-title', 'Koreksi Data Pengembalian')

@section('content')

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-gray-800 mb-1">Peminjaman #{{ $pengembalian->peminjaman_id }}</h2>
    <p class="text-sm text-gray-500">
        Peminjam:
        <span class="font-semibold text-gray-800">{{ $pengembalian->peminjaman?->user?->name ?? 'N/A' }}</span>
    </p>
    <p class="text-sm text-gray-500 mb-6">
        Alat:
        @forelse($pengembalian->peminjaman?->detailPinjams ?? [] as $detail)
            {{ $detail->alat?->nama_alat ?? 'Alat' }} ({{ $detail->jumlah }}){{ !$loop->last ? ',' : '' }}
        @empty
            -
        @endforelse
    </p>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi Alat Saat Dikembalikan</label>
            <input type="text" name="kondisi_kembali" required maxlength="255"
                   value="{{ old('kondisi_kembali', $pengembalian->kondisi_kembali) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Denda (Rp)</label>
            <input type="number" name="denda" min="0"
                   value="{{ old('denda', $pengembalian->denda) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-6 py-2 rounded-lg transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.pengembalian.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800">Batal</a>
        </div>
    </form>
</div>
@endsection