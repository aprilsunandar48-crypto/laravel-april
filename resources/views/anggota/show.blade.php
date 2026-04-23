<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <span class="mr-2">👁️</span> Detail Anggota
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $anggota->nama }}</h3>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Anggota Aktif</span>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium mt-1">{{ $anggota->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="font-medium mt-1">{{ $anggota->telepon }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="font-medium mt-1">{{ $anggota->alamat }}</p>
                        </div>
                    </div>

                    <h4 class="font-semibold text-gray-800 mb-4">Riwayat Peminjaman</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($anggota->peminjamans as $pinjam)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $pinjam->buku->judul }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $pinjam->tanggal_kembali ? $pinjam->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $pinjam->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $pinjam->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $pinjam->denda > 0 ? 'Rp '.number_format($pinjam->denda, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada riwayat peminjaman.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex gap-3">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('anggota.edit', $anggota) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                                Edit Anggota
                            </a>
                            <form action="{{ route('anggota.destroy', $anggota) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                    onclick="return confirm('Hapus anggota ini?')">Hapus</button>
                            </form>
                        @endif
                        <a href="{{ route('anggota.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            &larr; Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
