<x-app-layout>
    <x-slot name="header">
        <h2 class="comic-title text-3xl text-gray-900 leading-tight flex items-center gap-2">
            <span class="bg-green-400 comic-border px-4 py-1 rotate-1 shadow-[6px_6px_0_0_rgba(0,0,0,1)]">
                👥 DOSSIER ANGGOTA
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
                            <h3 class="comic-title text-2xl text-black uppercase tracking-tight">Database Aliansi Pembaca</h3>
                            <p class="text-sm italic font-bold text-gray-500 underline decoration-green-400">Daftar warga yang terdaftar dalam sistem perpustakaan...</p>
                        </div>
                        
                        <a href="{{ route('anggota.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-500 border-2 border-black text-white text-sm font-black uppercase hover:bg-indigo-600 transition comic-shadow-sm active:translate-y-1 active:shadow-none">
                            <svg class="w-5 h-5 mr-2 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Rekrut Anggota Baru!
                        </a>
                    </div>

                    {{-- Alert Success --}}
                    @if(session('success'))
                        <div class="bg-green-400 comic-border comic-shadow-sm p-4 mb-6 -rotate-1">
                            <p class="text-black font-black uppercase italic">✔ UPDATE BERHASIL: {{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-black">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest border-r border-gray-800">Identitas Nama</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest border-r border-gray-800">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest border-r border-gray-800">Telepon</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest border-r border-gray-800">Domisili</th>
                                    <th class="px-6 py-4 text-center text-xs font-black text-white uppercase tracking-widest">Opsi Kendali</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-black bg-white font-bold">
                                @forelse($anggotas as $anggota)
                                <tr class="hover:bg-green-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-black uppercase">{{ $anggota->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-indigo-600 italic">{{ $anggota->email }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="bg-gray-100 px-2 py-1 comic-border text-xs">{{ $anggota->telepon }}</code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-600 text-sm max-w-xs truncate">{{ $anggota->alamat }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('anggota.show', $anggota) }}" 
                                               class="px-3 py-1 bg-white comic-border comic-shadow-sm text-xs font-black hover:bg-gray-100">
                                               LIHAT
                                            </a>
                                            <a href="{{ route('anggota.edit', $anggota) }}" 
                                               class="px-3 py-1 bg-amber-400 comic-border comic-shadow-sm text-xs font-black hover:bg-amber-500">
                                               UBAH
                                            </a>
                                            <form action="{{ route('anggota.destroy', $anggota) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-red-500 text-white comic-border comic-shadow-sm text-xs font-black hover:bg-red-600" 
                                                        onclick="return confirm('Hapus anggota ini dari aliansi?')">
                                                    PECATT!
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="inline-block p-6 comic-border bg-gray-50 -rotate-2">
                                            <p class="comic-title text-2xl text-gray-400 uppercase tracking-tighter">Tidak ada anggota yang terdeteksi...</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8 pt-6 border-t-4 border-black">
                        {{ $anggotas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script/Style Tambahan --}}
    <style>
        .comic-border { border: 3px solid black !important; }
        .comic-shadow { box-shadow: 10px 10px 0px 0px rgba(0,0,0,1); }
        .comic-shadow-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .comic-title { font-family: 'Bangers', cursive; letter-spacing: 1px; }
        .comic-body { font-family: 'Patrick Hand', cursive; }
    </style>
</x-app-layout>