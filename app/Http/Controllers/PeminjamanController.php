<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Ambil data anggota milik user yang sedang login.
     */
    private function getAnggotaLogin(): ?Anggota
    {
        return Anggota::where('email', auth()->user()->email)->first();
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            // Admin: lihat semua
            $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->paginate(10);
        } else {
            // Anggota: hanya milik sendiri
            $anggota = $this->getAnggotaLogin();
            if (!$anggota) {
                return view('peminjaman.index', ['peminjamans' => collect()->paginate(10)]);
            }
            $peminjamans = Peminjaman::with(['anggota', 'buku'])
                ->where('anggota_id', $anggota->id)
                ->latest()->paginate(10);
        }
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $anggotas     = Anggota::all();
        $bukus        = Buku::where('stok', '>', 0)->get();
        $anggotaLogin = $this->getAnggotaLogin();
        return view('peminjaman.create', compact('anggotas', 'bukus', 'anggotaLogin'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            $anggotaLogin = $this->getAnggotaLogin();
            if (!$anggotaLogin) {
                return back()->with('error', 'Data anggota tidak ditemukan. Hubungi admin.');
            }
            $request->merge(['anggota_id' => $anggotaLogin->id]);
        }

        $request->validate([
            'anggota_id'     => 'required|exists:anggotas,id',
            'buku_id'        => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        if (Buku::findOrFail($request->buku_id)->stok <= 0) {
            return back()->with('error', 'Stok buku tidak mencukupi.');
        }

        Peminjaman::create([
            'anggota_id'     => $request->anggota_id,
            'buku_id'        => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status'         => 'menunggu',
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function show(Peminjaman $peminjaman)
    {
        // Anggota hanya boleh lihat milik sendiri
        if (!auth()->user()->isAdmin()) {
            $anggota = $this->getAnggotaLogin();
            abort_unless($anggota && $peminjaman->anggota_id === $anggota->id, 403);
        }

        $denda = $peminjaman->hitungDenda();
        return view('peminjaman.show', compact('peminjaman', 'denda'));
    }

    public function setujui(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }
        $buku = $peminjaman->buku;
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }
        $buku->decrement('stok');
        $peminjaman->update([
            'status' => 'disetujui',
            'token'  => Peminjaman::generateToken(),
        ]);
        return back()->with('success', 'Peminjaman disetujui. Token telah digenerate.');
    }

    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }
        $peminjaman->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function ambil(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman harus disetujui dulu.');
        }
        $peminjaman->update(['status' => 'dipinjam']);
        return back()->with('success', 'Buku ditandai sudah diambil.');
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }
        $peminjaman->buku->increment('stok');
        $peminjaman->update([
            'tanggal_kembali' => Carbon::now(),
            'status'          => 'dikembalikan',
            'denda'           => $peminjaman->hitungDenda(),
        ]);
        return back()->with('success', 'Buku dikembalikan. Denda: Rp ' . number_format($peminjaman->denda, 0, ',', '.'));
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        if ($peminjaman->status === 'dipinjam') {
            $peminjaman->buku->increment('stok');
        }
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function print(Peminjaman $peminjaman)
    {
        // Anggota hanya boleh print milik sendiri
        if (!auth()->user()->isAdmin()) {
            $anggota = $this->getAnggotaLogin();
            abort_unless($anggota && $peminjaman->anggota_id === $anggota->id, 403);
        }
        abort_unless($peminjaman->token, 403, 'Peminjaman belum disetujui.');
        return view('peminjaman.print', compact('peminjaman'));
    }
}