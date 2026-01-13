<!-- Navigation -->
<nav id="navbar" class="fixed top-0 w-full bg-white/95 backdrop-blur-lg z-50 py-4 transition-all duration-300">
    <div class="max-w-[1400px] mx-auto px-8 flex items-center justify-between">
        <div
            class="text-2xl font-extrabold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">
            HKBP Soposurung
        </div>

        <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-800">
            <li><a href="{{ route('pages.guest.dashboard') }}" class="hover:text-primary transition">Homepage</a></li>

            <!-- About dropdown (click-to-toggle) -->
            <li class="relative">
                <button data-dropdown-toggle aria-controls="aboutMenu" aria-expanded="false"
                    class="flex items-center gap-2 hover:text-primary transition font-medium">
                    Tentang
                    <svg class="w-4 h-4 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul id="aboutMenu" data-dropdown
                    class="absolute left-0 mt-3 w-44 bg-white rounded-xl shadow-lg text-sm text-slate-800 transform origin-top scale-y-0 opacity-0 pointer-events-none transition-all duration-200 ease-out">
                    <li><a href="{{ route('guest.jadwal') }}"
                            class="flex items-center px-4 py-2 hover:bg-slate-100">Jadwal Ibadah</a></li>
                    <li><a href="{{ route('guest.kegiatan') }}"
                            class="flex items-center px-4 py-2 hover:bg-slate-100">Kegiatan Gereja</a></li>
                </ul>
            </li>

            <li><a href="{{ route('guest.layanan') }}" class="hover:text-primary transition">Layanan</a></li>

            <li><a href="{{ route('guest.galeri') }}" class="hover:text-primary transition">Galeri</a></li>
        </ul>

        <!-- Button Masuk -->
        @guest
        <a href="{{ route('login') }}"
            class="hidden md:inline px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
            Masuk
        </a>
        @endguest
    </div>

</nav>


<!-- Mobile Menu -->
<div id="mobileMenu"
    class="fixed top-0 right-[-100%] w-full h-screen bg-slate-900/95 backdrop-blur-lg flex flex-col items-center justify-center transition-right duration-400">
    <ul class="text-center space-y-6 px-6">
        <li><a href="{{ route('guest.dashboard') }}"
                class="text-3xl font-semibold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">Home</a>
        </li>

        <li>
            <details class="text-left">
                <summary class="text-3xl font-semibold cursor-pointer text-white/95">Tentang</summary>
                <ul class="mt-4 space-y-3">
                    <li><a href="#about" class="text-xl text-white/80">Tentang Kami</a></li>
                    <li><a href="#team" class="text-xl text-white/80">Team</a></li>
                    <li><a href="#history" class="text-xl text-white/80">Sejarah</a></li>
                </ul>
            </details>
        </li>

        <li><a href="#layanan"
                class="text-3xl font-semibold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">Layanan</a>
        </li>

        <li><a href="{{ route('guest.galeri') }}"
                class="text-3xl font-semibold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">Galeri</a>
        </li>
        <li><a href="{{ route('guest.layanan') }}"
                class="text-3xl font-semibold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">Layanan</a>
        </li>
        <li><a href="{{ route('guest.jadwal') }}"
                class="text-3xl font-semibold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">Jadwal</a>
        </li>
        <li><a href="{{ route('guest.kegiatan') }}"
                class="text-3xl font-semibold bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-400 bg-clip-text text-transparent">Kegiatan</a>
        </li>
        <!-- Warta link removed -->
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
	const toggles = document.querySelectorAll('[data-dropdown-toggle]');

	toggles.forEach(btn => {
		const menuId = btn.getAttribute('aria-controls');
		const menu = menuId ? document.getElementById(menuId) : null;

		// ensure menus start hidden (use scale/opacity for smooth animation)
		if (menu) menu.classList.add('scale-y-0', 'opacity-0', 'pointer-events-none');

		btn.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			const isOpen = btn.getAttribute('aria-expanded') === 'true';

			// close other dropdowns (apply scale/opacity hide)
			toggles.forEach(other => {
				if (other === btn) return;
				other.setAttribute('aria-expanded', 'false');
				const otherMenu = document.getElementById(other.getAttribute('aria-controls'));
				if (otherMenu) {
					otherMenu.classList.add('scale-y-0', 'opacity-0', 'pointer-events-none');
					otherMenu.classList.remove('scale-y-100', 'opacity-100');
				}
			});

			if (!isOpen && menu) {
				menu.classList.remove('scale-y-0', 'opacity-0', 'pointer-events-none');
				menu.classList.add('scale-y-100', 'opacity-100');
				btn.setAttribute('aria-expanded', 'true');
			} else if (menu) {
				menu.classList.add('scale-y-0', 'opacity-0', 'pointer-events-none');
				menu.classList.remove('scale-y-100', 'opacity-100');
				btn.setAttribute('aria-expanded', 'false');
			}
		});

		// prevent clicks inside menu from closing it
		if (menu) {
			menu.addEventListener('click', function (e) { e.stopPropagation(); });
		}
	});

	// close all when clicking outside (use scale/opacity hide)
	document.addEventListener('click', function () {
		toggles.forEach(btn => {
			btn.setAttribute('aria-expanded', 'false');
			const menu = document.getElementById(btn.getAttribute('aria-controls'));
			if (menu) {
				menu.classList.add('scale-y-0', 'opacity-0', 'pointer-events-none');
				menu.classList.remove('scale-y-100', 'opacity-100');
			}
		});
	});
});
</script>