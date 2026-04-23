<nav x-data="{ open: false }" class="bg-white border-b-4 border-black font-comic">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group relative inline-block px-3 py-1 bg-yellow-400 border-2 border-black shadow-[4px_4px_0_0_rgba(0,0,0,1)] transform -rotate-2 hover:rotate-0 transition-transform">
                        <span class="text-xl font-black text-black uppercase tracking-tighter">
                            📚 BOOK-HQ
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="comic-nav-item">
                        Dashboard
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('kategori.index')" :active="request()->routeIs('kategori.*')" class="comic-nav-item">
                            Kategori
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')" class="comic-nav-item">
                        Buku
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('anggota.index')" :active="request()->routeIs('anggota.*')" class="comic-nav-item">
                            Anggota
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.*')" class="comic-nav-item">
                        Peminjaman
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-1 border-2 border-black text-sm font-bold rounded-none bg-white shadow-[3px_3px_0_0_rgba(0,0,0,1)] hover:bg-gray-50 focus:outline-none transition">
                            <div class="uppercase">{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b-2 border-black bg-gray-50">
                            <p class="text-[10px] font-black uppercase text-gray-500">ID Card:</p>
                            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-black border-2 border-black uppercase {{ auth()->user()->isAdmin() ? 'bg-red-400 text-black' : 'bg-green-400 text-black' }}">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                        
                        <div class="border-black">
                            <x-dropdown-link :href="route('profile.edit')" class="font-bold hover:bg-yellow-100 uppercase text-xs">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="font-bold hover:bg-red-100 text-red-600 uppercase text-xs"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 border-2 border-black rounded-none text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none transition shadow-[2px_2px_0_0_rgba(0,0,0,1)]">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 20h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t-4 border-black bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="comic-mobile-link">
                Dashboard
            </x-responsive-nav-link>
            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('kategori.index')" :active="request()->routeIs('kategori.*')" class="comic-mobile-link">
                    Kategori
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')" class="comic-mobile-link">
                Buku
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.*')" class="comic-mobile-link">
                Peminjaman
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t-4 border-black bg-gray-100">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-black text-base text-black uppercase tracking-tight">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-600">{{ Auth::user()->email }}</div>
                </div>
                <span class="px-2 py-1 border-2 border-black text-[10px] font-black uppercase {{ auth()->user()->isAdmin() ? 'bg-red-400' : 'bg-green-400' }}">
                    {{ auth()->user()->role }}
                </span>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold uppercase italic">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="font-black text-red-600 uppercase italic"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Tambahan Utility Classes untuk Navigasi Comic */
    .font-comic {
        font-family: 'Patrick Hand', cursive; /* Pastikan font ini di-import di layout utama */
    }

    .comic-nav-item {
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: -0.025em !important;
        border: none !important;
        transition: all 0.1s !important;
        position: relative;
        display: inline-flex;
        align-items: center;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .comic-nav-item:hover {
        color: #4f46e5 !important; 
        transform: scale(1.1) rotate(-1deg);
    }

    /* Efek Stabilo untuk Menu Aktif */
    /* Laravel Breeze biasanya mengirim class 'border-indigo-400' untuk link aktif */
    .inline-flex.items-center.px-1.pt-1.border-b-2.border-indigo-400 {
        border-bottom: 4px solid black !important;
        background-color: #fef08a; /* Yellow-200 */
        transform: rotate(1deg);
        margin-top: 10px;
        margin-bottom: 10px;
        height: fit-content;
    }

    /* Mobile Link */
    .comic-mobile-link {
        font-weight: 900 !important;
        text-transform: uppercase !important;
        border-left: 8px solid black !important;
        transition: background 0.2s;
    }

    .comic-mobile-link:hover {
        background-color: #e0e7ff !important; /* Indigo-100 */
    }
</style>