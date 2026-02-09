<!-- HEADER TOP -->
<header class="w-full bg-white border-b">
    <div class="max-w-[1400px] mx-auto px-8 py-4 flex items-center justify-center gap-6">
        <!-- Logo -->
        <div class="text-3xl font-bold text-sky-600 tracking-wide text-center">
            HKBP Soposurung
        </div>

        <!-- Login -->
        @guest
        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition">
            Masuk
        </a>
        @endguest
    </div>
</header>

<!-- MAIN NAV -->
<nav id="navbar" class="w-full bg-white border-b">
    <div class="max-w-[1400px] mx-auto px-8 flex justify-center">
        <ul
            class="flex items-center justify-center gap-10 text-sm font-semibold uppercase tracking-wide text-slate-800 py-3">

            <li>
                <a href="{{ route('pages.guest.dashboard') }}" class="hover:text-sky-600 transition">
                    Home
                </a>
            </li>

            <!-- Tentang (Dropdown) -->
            <li class="relative">
                <button data-dropdown-toggle aria-controls="aboutMenu" aria-expanded="false"
                    class="flex items-center gap-1 hover:text-sky-600 transition">
                    TENTANG
                    <svg class="w-4 h-4 mt-[1px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul id="aboutMenu" class="absolute left-0 mt-3 w-48 bg-white border shadow-lg text-sm text-slate-700
                           transform origin-top scale-y-0 opacity-0 pointer-events-none
                           transition-all duration-200 ease-out z-50">

                    <li>
                        <a href="{{ route('guest.jadwal') }}" class="block px-4 py-2 hover:bg-slate-100">
                            Jadwal Ibadah
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('guest.kegiatan') }}" class="block px-4 py-2 hover:bg-slate-100">
                            Kegiatan Gereja
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('guest.layanan') }}" class="hover:text-sky-600 transition">
                    Layanan
                </a>
            </li>

            <li>
                <a href="{{ route('guest.galeri') }}" class="hover:text-sky-600 transition">
                    Galeri
                </a>
            </li>

            <li>
                <a href="{{ route('guest.renungan') }}" class="hover:text-sky-600 transition">
                    Renungan
                </a>
            </li>

        </ul>
    </div>
</nav>