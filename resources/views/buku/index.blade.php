<x-app-layout>
    <x-slot name="header">
        <h2 class="comic-title text-3xl text-gray-900 leading-tight flex items-center gap-2">
            <span class="bg-yellow-400 comic-border px-4 py-1 -rotate-1 shadow-[6px_6px_0_0_rgba(0,0,0,1)]">
                📚 KOLEKSI BUKU TERBAIK!!
            </span>
        </h2>
    </x-slot>

    <div class="py-8 comic-body">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kontainer Utama --}}
            <div class="bg-white comic-border comic-shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                        <div>
                            <h3 class="comic-title text-2xl">Daftar Buku Tersedia</h3>
                            <div class="h-1.5 w-24 bg-indigo-500 comic-border mt-1"></div>
                        </div>

                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('buku.create') }}" class="inline-flex items-center px-6 py-2 bg-indigo-500 text-white comic-border comic-shadow-sm font-black uppercase tracking-widest hover:bg-indigo-600 transition-all hover:-translate-y-1 active:translate-y-0 active:shadow-none">
                                <svg class="w-5 h-5 mr-2 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Koleksi Baru!
                            </a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="bg-green-400 comic-border comic-shadow-sm p-4 mb-6 rotate-1">
                            <p class="text-black font-bold uppercase">💥 BOOM! {{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-black">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Judul Buku</th>
                                    <th class="px-4 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Mastermind (Penulis)</th>
                                    <th class="px-4 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Genre</th>
                                    <th class="px-4 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Persediaan</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-white uppercase tracking-widest">Aksi Bakubaku</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-4 divide-gray-100 bg-white">
                                @forelse($bukus as $buku)
                                <tr class="hover:bg-yellow-50 transition-colors">
                                    <td class="px-4 py-5">
                                        <div class="font-black text-lg text-black uppercase">{{ $buku->judul }}</div>
                                    </td>
                                    <td class="px-4 py-5 font-bold italic text-gray-700 underline decoration-2 decoration-yellow-400">
                                        {{ $buku->penulis }}
                                    </td>
                                    <td class="px-4 py-5">
                                        <span class="px-3 py-1 bg-blue-100 comic-border text-xs font-black uppercase">
                                            {{ $buku->kategori->nama }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="inline-block px-4 py-1 comic-border font-black {{ $buku->stok > 0 ? 'bg-green-400 shadow-[2px_2px_0_0_rgba(0,0,0,1)]' : 'bg-red-500 text-white shadow-none' }}">
                                            {{ $buku->stok }} UNIT
                                        </div>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('buku.show', $buku) }}" class="px-3 py-1 bg-white comic-border comic-shadow-sm text-xs font-bold hover:bg-gray-100">DETAIL</a>
                                            
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('buku.edit', $buku) }}" class="px-3 py-1 bg-amber-400 comic-border comic-shadow-sm text-xs font-bold hover:bg-amber-500">EDIT</a>
                                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button class="px-3 py-1 bg-red-500 text-white comic-border comic-shadow-sm text-xs font-bold hover:bg-red-600" onclick="return confirm('Hancurkan data buku ini?')">HAPUS</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center">
                                        <p class="comic-title text-3xl text-gray-400 uppercase italic">Zzz... Rak buku masih kosong!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination ala Komik --}}
                    <div class="mt-8 border-t-4 border-black pt-6">
                        {{ $bukus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .comic-border { border: 3px solid black !important; }
        .comic-shadow { box-shadow: 12px 12px 0px 0px rgba(0,0,0,1); }
        .comic-shadow-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .comic-title { font-family: 'Bangers', cursive; letter-spacing: 2px; }
        .comic-body { font-family: 'Patrick Hand', cursive; }
    </style>
</x-app-layout>