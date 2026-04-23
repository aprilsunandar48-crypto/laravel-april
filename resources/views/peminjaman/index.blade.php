<x-app-layout>
    {{-- Tambahkan font Comic di sini jika memungkinkan --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bangers&family=Patrick+Hand&display=swap');
        .comic-title { font-family: 'Bangers', cursive; letter-spacing: 2px; }
        .comic-body { font-family: 'Patrick Hand', cursive; font-size: 1.1rem; }
        .comic-border { border: 3px solid black !important; }
        .comic-shadow { box-shadow: 8px 8px 0px 0px rgba(0,0,0,1); }
        .comic-shadow-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .comic-card { transition: transform 0.2s; }
        .comic-card:hover { transform: translate(-2px, -2px); box-shadow: 10px 10px 0px 0px rgba(0,0,0,1); }
    </style>

    <x-slot name="header">
        <h2 class="comic-title text-3xl text-gray-900 leading-tight flex items-center gap-2">
            <span class="bg-yellow-400 comic-border px-4 py-1 -rotate-2">🔄 DATA PEMINJAMAN!!</span>
        </h2>
    </x-slot>

    <div class="py-8 comic-body">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-400 comic-border comic-shadow p-4 mb-6 rounded-none">
                    <p class="text-black font-bold uppercase italic">BOOM! {{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-500 comic-border comic-shadow p-4 mb-6 rounded-none text-white">
                    <p class="font-bold uppercase">Ouch! {{ session('error') }}</p>
                </div>
            @endif

            {{-- Tabel Menunggu Validasi (Khusus Admin) --}}
            @if(auth()->user()->isAdmin())
            @php $menunggu = $peminjamans->getCollection()->where('status','menunggu'); @endphp
            @if($menunggu->count() > 0)
            <div class="bg-yellow-300 comic-border comic-shadow mb-10 overflow-hidden">
                <div class="px-6 py-4 border-b-4 border-black flex items-center gap-2 bg-white">
                    <span class="w-4 h-4 comic-border bg-red-500 animate-bounce"></span>
                    <h3 class="comic-title text-2xl text-black">PERLU PERSETUJUAN! ({{ $menunggu->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-black text-white">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm uppercase">Anggota</th>
                                <th class="px-4 py-3 text-left text-sm uppercase">Buku</th>
                                <th class="px-4 py-3 text-left text-sm uppercase">Tgl Pinjam</th>
                                <th class="px-4 py-3 text-center text-sm uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-black bg-white">
                            @foreach($menunggu as $p)
                            <tr class="hover:bg-yellow-100">
                                <td class="px-4 py-3 font-bold uppercase underline">{{ $p->anggota->nama }}</td>
                                <td class="px-4 py-3 italic font-semibold italic">{{ $p->buku->judul }}</td>
                                <td class="px-4 py-3">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <form action="{{ route('peminjaman.setujui', $p) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" onclick="return confirm('Setujui?')"
                                                class="px-4 py-1 bg-green-500 comic-border comic-shadow-sm font-bold hover:bg-green-600 active:translate-y-1 transition">
                                                SETUJU!
                                            </button>
                                        </form>
                                        <button onclick="document.getElementById('modal-tolak-{{ $p->id }}').classList.remove('hidden')"
                                            class="px-4 py-1 bg-red-500 text-white comic-border comic-shadow-sm font-bold hover:bg-red-600 active:translate-y-1 transition">
                                            TOLAK
                                        </button>
                                    </div>

                                    {{-- Modal Tolak Gaya Komik --}}
                                    <div id="modal-tolak-{{ $p->id }}" class="hidden fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
                                        <div class="bg-white comic-border comic-shadow p-8 w-full max-w-md mx-4 -rotate-1">
                                            <h4 class="comic-title text-2xl mb-4 text-red-600">KENAPA DITOLAK?</h4>
                                            <form action="{{ route('peminjaman.tolak', $p) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <textarea name="catatan_admin" rows="3" placeholder="Alasannya..."
                                                    class="w-full comic-border p-3 focus:ring-0 focus:border-black text-lg mb-4"></textarea>
                                                <div class="flex gap-4 font-bold">
                                                    <button type="submit" class="flex-1 py-2 bg-red-500 text-white comic-border comic-shadow-sm hover:bg-red-600">YAKIN!</button>
                                                    <button type="button" onclick="document.getElementById('modal-tolak-{{ $p->id }}').classList.add('hidden')"
                                                        class="flex-1 py-2 bg-gray-200 comic-border comic-shadow-sm">BATAL</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @endif

            {{-- Semua Peminjaman --}}
            <div class="bg-white comic-border comic-shadow overflow-hidden">
                <div class="p-6 flex justify-between items-center border-b-4 border-black bg-blue-100">
                    <h3 class="comic-title text-2xl text-black uppercase">Log Aktivitas Peminjaman</h3>
                    <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center px-6 py-2 bg-indigo-500 text-white comic-border comic-shadow-sm font-bold hover:bg-indigo-600 hover:-rotate-3 transition">
                        + PINJAM BUKU BARU!
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-black text-white">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs uppercase tracking-widest">Anggota</th>
                                <th class="px-4 py-4 text-left text-xs uppercase tracking-widest">Buku</th>
                                <th class="px-4 py-4 text-left text-xs uppercase tracking-widest">Tgl Pinjam</th>
                                <th class="px-4 py-4 text-left text-xs uppercase tracking-widest">Status</th>
                                <th class="px-4 py-4 text-left text-xs uppercase tracking-widest">Token</th>
                                <th class="px-4 py-4 text-center text-xs uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-black bg-white font-semibold">
                            @forelse($peminjamans as $p)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-4 border-r-2 border-black">{{ $p->anggota->nama }}</td>
                                <td class="px-4 py-4 border-r-2 border-black italic">{{ $p->buku->judul }}</td>
                                <td class="px-4 py-4 border-r-2 border-black">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td class="px-4 py-4 border-r-2 border-black">
                                    <span class="px-3 py-1 comic-border comic-shadow-sm inline-block -rotate-2 {{ 
                                        $p->status === 'disetujui' ? 'bg-green-400' : 
                                        ($p->status === 'menunggu' ? 'bg-yellow-300' : 'bg-gray-300') 
                                    }} text-black uppercase text-xs font-bold">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 border-r-2 border-black">
                                    @if($p->token)
                                        <span class="font-mono text-sm bg-yellow-200 comic-border px-2 py-1">{{ $p->token }}</span>
                                    @else
                                        <span class="text-gray-400">NONE</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('peminjaman.show', $p) }}" class="px-2 py-1 bg-white comic-border comic-shadow-sm hover:bg-gray-100 text-xs">DETAIL</a>

                                        @if(auth()->user()->isAdmin())
                                            @if($p->status === 'disetujui')
                                                <form action="{{ route('peminjaman.ambil', $p) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button class="px-2 py-1 bg-blue-400 comic-border comic-shadow-sm text-xs" onclick="return confirm('Sudah diambil?')">AMBIL</button>
                                                </form>
                                            @endif
                                            @if($p->status === 'dipinjam')
                                                <form action="{{ route('peminjaman.kembalikan', $p) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button class="px-2 py-1 bg-green-400 comic-border comic-shadow-sm text-xs" onclick="return confirm('Kembalikan?')">BALIK!</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('peminjaman.destroy', $p) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="px-2 py-1 bg-red-400 text-white comic-border comic-shadow-sm text-xs" onclick="return confirm('MUSNAHKAN DATA?')">HAPUS</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <p class="comic-title text-4xl text-gray-300 uppercase rotate-2">Zonk! Tidak ada data.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-white border-t-4 border-black">
                    {{ $peminjamans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>