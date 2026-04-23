<x-app-layout>
    <x-slot name="header">
        <h2 class="comic-title text-3xl text-gray-900 leading-tight flex items-center gap-2">
            <span class="bg-indigo-400 comic-border px-4 py-1 rotate-1 shadow-[6px_6px_0_0_rgba(0,0,0,1)]">
                📂 KATEGORI BUKU
            </span>
        </h2>
    </x-slot>

    <div class="py-8 comic-body">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card Utama --}}
            <div class="bg-white comic-border comic-shadow overflow-hidden">
                <div class="p-6">
                    {{-- Header Tabel --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 border-b-4 border-black pb-6">
                        <div>
                            <h3 class="comic-title text-2xl text-black uppercase tracking-tight">Daftar Arsip Kategori</h3>
                            <p class="text-sm italic font-bold text-gray-500">Mengelola genre dan klasifikasi buku...</p>
                        </div>
                        
                        <a href="{{ route('kategori.create') }}" class="inline-flex items-center px-6 py-3 bg-green-400 border-2 border-black text-black text-sm font-black uppercase hover:bg-green-500 transition comic-shadow-sm active:translate-y-1 active:shadow-none">
                            <svg class="w-5 h-5 mr-2 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Kategori Baru!
                        </a>
                    </div>

                    {{-- Alert Success --}}
                    @if(session('success'))
                        <div class="bg-yellow-300 comic-border comic-shadow-sm p-4 mb-6 -rotate-1">
                            <p class="text-black font-black uppercase italic">⚡ NICE! {{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-black">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest border-r border-gray-700">Nama Kategori</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest border-r border-gray-700">Deskripsi/Catatan</th>
                                    <th class="px-6 py-4 text-center text-xs font-black text-white uppercase tracking-widest">Aksi Strategis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-black bg-white">
                                @forelse($kategoris as $kategori)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-black text-lg text-black underline decoration-indigo-400 decoration-4 uppercase">
                                            {{ $kategori->nama }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-700 font-medium italic">
                                            "{{ $kategori->deskripsi ?? 'Tidak ada deskripsi rahasia...' }}"
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('kategori.show', $kategori) }}" 
                                               class="px-3 py-1 bg-white comic-border comic-shadow-sm text-xs font-bold hover:bg-gray-100">
                                               DETAIL
                                            </a>
                                            <a href="{{ route('kategori.edit', $kategori) }}" 
                                               class="px-3 py-1 bg-amber-400 comic-border comic-shadow-sm text-xs font-bold hover:bg-amber-500">
                                               EDIT
                                            </a>
                                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-red-500 text-white comic-border comic-shadow-sm text-xs font-bold hover:bg-red-600" 
                                                        onclick="return confirm('Hapus kategori ini selamanya?')">
                                                    HAPUS
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="inline-block p-6 comic-border bg-gray-100 -rotate-2">
                                            <p class="comic-title text-2xl text-gray-400 uppercase">Kategori Masih Kosong, Kapten!</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8 pt-6 border-t-4 border-black">
                        {{ $kategoris->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script/Style Tambahan (jika belum ada di layout utama) --}}
    <style>
        .comic-border { border: 3px solid black !important; }
        .comic-shadow { box-shadow: 10px 10px 0px 0px rgba(0,0,0,1); }
        .comic-shadow-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .comic-title { font-family: 'Bangers', cursive; letter-spacing: 1px; }
        .comic-body { font-family: 'Patrick Hand', cursive; }
    </style>
</x-app-layout>