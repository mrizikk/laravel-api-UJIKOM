<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamController extends Controller
{
    public function katalogAlat()
    {
        $alats = Alat::with('kategori')->where('stok', '>', 0)->get();
        return view('peminjam.katalog', compact('alats'));
    }

    public function ajukanPeminjaman(Request $request)
    {
        $request->validate([
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_plan' => 'required|date|after:tgl_pinjam',
            'alat_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            foreach ($request->alat_id as $index => $alatId) {
                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('peminjam.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    public function riwayatPeminjaman()
    {
        $peminjamans = Peminjaman::with(['detailPinjams.alat', 'pengembalian'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $alats = Alat::with('kategori')->where('stok', '>', 0)->get();

        return view('peminjam.riwayat', compact('peminjamans', 'alats'));
    }

    public function ajukanPengembalian($id)
    {
        $peminjaman = Peminjaman::where('user_id', auth()->id())->findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return redirect()->back()->with('error', 'Peminjaman ini tidak dapat diajukan pengembaliannya.');
        }

        $peminjaman->update(['status' => 'menunggu_konfirmasi']);

        return redirect()->route('peminjam.riwayat')->with('success', 'Pengajuan pengembalian berhasil dikirim, menunggu konfirmasi petugas.');
    }
}