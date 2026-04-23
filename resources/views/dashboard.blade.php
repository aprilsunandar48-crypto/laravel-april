<x-app-layout>
    <x-slot name="header">
        <h2 class="comic-title text-3xl text-gray-900 leading-tight">
            <span class="bg-white comic-border px-6 py-1 shadow-[6px_6px_0_0_rgba(0,0,0,1)]">
                ⚡ DASHBOARD UTAMA
            </span>
        </h2>
    </x-slot>

    <div class="py-8 comic-body">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="relative bg-cyan-400 comic-border comic-shadow p-8 overflow-hidden">
                <div class="relative z-10">
                    <h3 class="comic-title text-4xl text-black uppercase tracking-tighter">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-black font-black text-lg mt-2 italic bg-white inline-block px-2">
                        @if(auth()->user()->isAdmin())
                            STATUS: ADMINISTRATOR PERPUSTAKAAN - SIAP BERTUGAS!
                        @else
                            STATUS: ANGGOTA AKTIF - AYO BACA BUKU HARI INI!
                        @endif
                    </p>
                </div>
                <div class="absolute top-[-20px] right-[-20px] bg-yellow-400 comic-border w-32 h-32 rotate-12 flex items-center justify-center">
                    <span class="comic-title text-black text-2xl -rotate-12 uppercase">POW!</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Total Buku --}}
                <div class="bg-white comic-border comic-shadow-sm p-6 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-400 comic-border p-3 shadow-[3px_3px_0_0_rgba(0,0,0,1)]">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <p class="comic-title text-gray-500 uppercase text-xs">Arsip Buku</p>
                            <p class="text-4xl font-black text-black">{{ \App\Models\Buku::count() }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kolom Tengah (Kondisional) --}}
                @php
                    $anggotaLogin = \App\Models\Anggota::where('email', auth()->user()->email)->first();
                @endphp
                
                <div class="bg-white comic-border comic-shadow-sm p-6 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-400 comic-border p-3 shadow-[3px_3px_0_0_rgba(0,0,0,1)]">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="comic-title text-gray-500 uppercase text-xs">
                                {{ auth()->user()->isAdmin() ? 'Total Pasukan' : 'Buku Di Tangan' }}
                            </p>
                            <p class="text-4xl font-black text-black">
                                @if(auth()->user()->isAdmin())
                                    {{ \App\Models\Anggota::count() }}
                                @else
                                    {{ $anggotaLogin ? \App\Models\Peminjaman::where('anggota_id', $anggotaLogin->id)->whereIn('status',['menunggu','disetujui','dipinjam'])->count() : 0 }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Status Peminjaman --}}
                <div class="bg-white comic-border comic-shadow-sm p-6 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-400 comic-border p-3 shadow-[3px_3px_0_0_rgba(0,0,0,1)]">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="comic-title text-gray-500 uppercase text-xs">
                                {{ auth()->user()->isAdmin() ? 'Sedang Dipinjam' : 'Menunggu Approval' }}
                            </p>
                            <p class="text-4xl font-black text-black">
                                @if(auth()->user()->isAdmin())
                                    {{ \App\Models\Peminjaman::where('status','dipinjam')->count() }}
                                @else
                                    {{ $anggotaLogin ? \App\Models\Peminjaman::where('anggota_id', $anggotaLogin->id)->where('status','menunggu')->count() : 0 }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white comic-border comic-shadow p-6">
                <h3 class="comic-title text-2xl text-black mb-6 uppercase">Misi Cepat:</h3>
                <div class="flex flex-wrap gap-4">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('buku.create') }}" class="comic-btn bg-indigo-400">TAMBAH BUKU</a>
                        <a href="{{ route('anggota.create') }}" class="comic-btn bg-green-400">TAMBAH ANGGOTA</a>
                        <a href="{{ route('peminjaman.index') }}" class="comic-btn bg-yellow-400">KELOLA PINJAMAN</a>
                    @else
                        <a href="{{ route('buku.index') }}" class="comic-btn bg-indigo-400">CARI BUKU</a>
                        <a href="{{ route('peminjaman.create') }}" class="comic-btn bg-green-400">PINJAM SEKARANG!</a>
                        <a href="{{ route('peminjaman.index') }}" class="comic-btn bg-yellow-400">RIWAYAT SAYA</a>
                    @endif
                </div>
            </div>

            <div class="bg-white comic-border comic-shadow p-6">
                <h3 class="comic-title text-2xl text-black mb-6 uppercase italic underline">
                    {{ auth()->user()->isAdmin() ? 'Laporan Aktivitas Terbaru' : 'Jurnal Bacaan Saya' }}
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-black">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-black text-white uppercase italic">Siapa</th>
                                <th class="px-4 py-3 text-left text-xs font-black text-white uppercase italic">Judul Buku</th>
                                <th class="px-4 py-3 text-left text-xs font-black text-white uppercase italic">Tanggal</th>
                                <th class="px-4 py-3 text-center text-xs font-black text-white uppercase italic">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-black bg-white">
                            @php
                                if (auth()->user()->isAdmin()) {
                                    $recentPinjam = \App\Models\Peminjaman::with(['anggota','buku'])->latest()->take(5)->get();
                                } else {
                                    $recentPinjam = $anggotaLogin ? \App\Models\Peminjaman::with(['anggota','buku'])->where('anggota_id', $anggotaLogin->id)->latest()->take(5)->get() : collect();
                                }
                            @endphp
                            @forelse($recentPinjam as $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 font-bold uppercase">{{ $p->anggota->nama }}</td>
                                <td class="px-4 py-4 font-bold text-indigo-600">{{ $p->buku->judul }}</td>
                                <td class="px-4 py-4 font-bold">{{ $p->tanggal_pinjam->format('d/M/Y') }}</td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-block px-3 py-1 comic-border comic-shadow-sm text-[10px] font-black uppercase {{ $p->status == 'dipinjam' ? 'bg-green-400' : 'bg-yellow-300' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center font-black text-gray-400 uppercase italic text-xl">Belum ada pergerakan...</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .comic-border { border: 4px solid black !important; }
        .comic-shadow { box-shadow: 12px 12px 0px 0px rgba(0,0,0,1); }
        .comic-shadow-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .comic-title { font-family: 'Bangers', cursive; letter-spacing: 1px; }
        .comic-body { font-family: 'Patrick Hand', cursive; }
        
        .comic-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1.5rem;
            border: 3px solid black;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.025em;
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
            transition: all 0.1s;
        }
        .comic-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);
        }
        .comic-btn:active {
            transform: translate(2px, 2px);
            box-shadow: none;
        }
    </style>
</x-app-layout>