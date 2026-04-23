<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            📋 Form Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 p-6">

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">

                        {{-- Pilih Anggota: admin bisa pilih, anggota otomatis diri sendiri --}}
                        @if(auth()->user()->isAdmin())
                            <div>
                                <label for="anggota_id" class="block text-sm font-medium text-gray-700">Pilih Anggota</label>
                                <select name="anggota_id" id="anggota_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Pilih Anggota --</option>
                                    @foreach($anggotas as $anggota)
                                        <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                            {{ $anggota->nama }} ({{ $anggota->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('anggota_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        @else
                            {{-- Anggota: tampilkan info diri sendiri, hidden input --}}
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <span class="ml-auto text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">Peminjam</span>
                            </div>
                            <input type="hidden" name="anggota_id" value="{{ $anggotaLogin->id ?? '' }}">
                        @endif

                        <div>
                            <label for="buku_id" class="block text-sm font-medium text-gray-700">Pilih Buku</label>
                            <select name="buku_id" id="buku_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach($bukus as $buku)
                                    <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                        {{ $buku->judul }} ({{ $buku->penulis }}) — Stok: {{ $buku->stok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('buku_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('tanggal_pinjam')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            Ajukan Peminjaman
                        </button>
                        <a href="{{ route('peminjaman.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
