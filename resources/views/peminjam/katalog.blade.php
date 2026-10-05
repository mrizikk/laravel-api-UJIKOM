@extends('layouts.app')

@section('title', 'Katalog & Pengajuan Peminjaman – Peminjam')
@section('header-title', 'Katalog & Pengajuan Peminjaman')

@section('content')

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ASUMSI: nama route penyimpanan. Sesuaikan dengan route milikmu. --}}
    <form id="form-pinjam" method="POST" action="{{ route('peminjam.peminjaman.ajukan') }}">
        @csrf

        {{-- Pencarian & filter kategori --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <input id="cari" type="search" placeholder="Cari nama alat..."
                   class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">

            <select id="filter-kategori"
                    class="sm:w-60 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="">Semua Kategori</option>
                @foreach($alats->pluck('kategori.nama_kategori')->filter()->unique()->sort() as $kategori)
                    <option value="{{ $kategori }}">{{ $kategori }}</option>
                @endforeach
            </select>
        </div>

        {{-- Kartu alat --}}
        <div id="grid-alat" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 pb-8">
            @forelse($alats as $alat)
                <div class="kartu-alat bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm"
                     data-nama="{{ strtolower($alat->nama_alat) }}"
                     data-kategori="{{ $alat->kategori->nama_kategori ?? '' }}">

                    @if(!empty($alat->gambar))
                        <img src="{{ asset(implode('/', array_map('rawurlencode', explode('/', $alat->gambar)))) }}"
                            alt="{{ $alat->nama_alat }}"
                            class="w-full h-44 object-cover bg-gray-100" loading="lazy">
                    @else
                        <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-sm text-gray-400">
                            Belum ada foto
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-semibold text-gray-900 leading-snug">{{ $alat->nama_alat }}</h3>
                            <span class="shrink-0 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700">
                                Stok {{ $alat->stok }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $alat->kategori->nama_kategori ?? '-' }}</p>

                        <label class="flex items-center gap-2 mt-4 text-sm font-medium text-gray-800 cursor-pointer">
                            <input type="checkbox" name="alat_id[]" value="{{ $alat->id }}"
                                   class="pilih-alat h-4 w-4 rounded border-gray-300">
                            Pilih alat ini
                        </label>

                        <input type="number" name="jumlah[{{ $alat->id }}]" value="1" min="1" max="{{ $alat->stok }}"
                               disabled aria-label="Jumlah {{ $alat->nama_alat }}"
                               class="jumlah-alat mt-3 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm disabled:opacity-60 disabled:cursor-not-allowed">
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-8">
                    Tidak ada alat yang tersedia saat ini.
                </p>
            @endforelse
        </div>

        <p id="kosong" class="hidden text-center text-gray-500 py-8">
            Tidak ada alat yang cocok. Coba kata kunci atau kategori lain.
        </p>

        {{-- Bar pengajuan (menempel di bawah area konten) --}}
        <div class="sticky bottom-0 -mx-6 px-6 py-4 bg-white border-t border-gray-200 flex flex-wrap items-end gap-4">
            <div>
                <label for="tanggal_pinjam" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pinjam</label>
                <input id="tanggal_pinjam" type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam') }}" required
                       class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
            </div>
            <div>
                <label for="tanggal_kembali" class="block text-sm font-semibold text-gray-700 mb-1">Rencana Kembali</label>
                <input id="tanggal_kembali" type="date" name="tgl_kembali_plan" value="{{ old('tgl_kembali_plan') }}" required
                       class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
            </div>

            <span id="jumlah-dipilih" class="text-sm text-gray-500 pb-2">0 alat dipilih</span>

            <button id="btn-ajukan" type="submit" disabled
                    class="px-6 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
                Ajukan Peminjaman
            </button>
        </div>
    </form>

    <script>
        (function () {
            const kartu = document.querySelectorAll('.kartu-alat');
            const cari = document.getElementById('cari');
            const filter = document.getElementById('filter-kategori');
            const kosong = document.getElementById('kosong');
            const label = document.getElementById('jumlah-dipilih');
            const tombol = document.getElementById('btn-ajukan');
            const tglPinjam = document.getElementById('tanggal_pinjam');
            const tglKembali = document.getElementById('tanggal_kembali');

            const hariIni = new Date().toISOString().split('T')[0];
            tglPinjam.min = hariIni;
            tglKembali.min = hariIni;

            function terapkanFilter() {
                const kata = cari.value.trim().toLowerCase();
                const kategori = filter.value;
                let tampil = 0;

                kartu.forEach(k => {
                    const cocok = k.dataset.nama.includes(kata) && (!kategori || k.dataset.kategori === kategori);
                    k.classList.toggle('hidden', !cocok);
                    if (cocok) tampil++;
                });
                kosong.classList.toggle('hidden', tampil > 0 || kartu.length === 0);
            }

            function perbaruiForm() {
                const terpilih = document.querySelectorAll('.pilih-alat:checked').length;
                label.textContent = terpilih + ' alat dipilih';

                const tanggalValid = tglPinjam.value && tglKembali.value && tglKembali.value >= tglPinjam.value;
                tombol.disabled = !(terpilih > 0 && tanggalValid);
            }

            kartu.forEach(k => {
                const cek = k.querySelector('.pilih-alat');
                const jumlah = k.querySelector('.jumlah-alat');
                cek.addEventListener('change', () => {
                    jumlah.disabled = !cek.checked; // input disabled tidak ikut terkirim
                    perbaruiForm();
                });
                jumlah.addEventListener('input', () => {
                    const maks = parseInt(jumlah.max, 10);
                    if (jumlah.value > maks) jumlah.value = maks;
                    if (jumlah.value < 1 && jumlah.value !== '') jumlah.value = 1;
                });
            });

            tglPinjam.addEventListener('change', () => {
                tglKembali.min = tglPinjam.value || hariIni;
                perbaruiForm();
            });
            tglKembali.addEventListener('change', perbaruiForm);
            cari.addEventListener('input', terapkanFilter);
            filter.addEventListener('change', terapkanFilter);

            perbaruiForm();
        })();
    </script>

@endsection