<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<div class="main-sidebar">
        <aside id="sidebar-wrapper" style="height:100vh; display:flex; flex-direction:column;">

                <!-- Brand -->
                <div class="sidebar-brand mt-3">
                        <p style="font-family: 'Times New Roman', Times, Serif; font-weight:bold; font-size:20px;">
                                E-Church
                        </p>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                        <a href="#">{{ strtoupper(substr(config(''), 0, 2)) }}SIG</a>
                </div>

                <ul class="sidebar-menu"
                        style="overflow-y:auto; flex:1; padding-bottom:1rem; -webkit-overflow-scrolling: touch;">

                        {{-- ===================== ADMIN ===================== --}}
                        @if (Auth::check() && Auth::user()->role == 'admin')

                        <!-- MAIN MENU -->
                        <li class="menu-header sidebar-section-toggle" data-section="main-menu">
                                <span>MENU</span>
                                <span class="sidebar-toggle-icon"><i class="fas fa-chevron-up"></i></span>
                        </li>
                        <li class="section-wrapper">
                                <ul class="section-items">
                                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                                        <i class="fas fa-columns"></i> <span>Dashboard</span>
                                                </a>
                                        </li>

                                        <!-- PELAYANAN (Dropdown) -->
                                        {{-- <li class="{{ request()->routeIs('admin.pelayanan.*') ? 'active' : '' }}">
                                                <a href="#" class="nav-link has-dropdown"><i
                                                                class="fas fa-hands-helping"></i>
                                                        <span>Pelayanan Gereja</span></a>
                                                <ul class="dropdown-menu">
                                                        <li
                                                                class="{{ request()->routeIs('admin.pelayanan.baptisan') ? 'active' : '' }}">
                                                                <a class="nav-link"
                                                                        href="{{ route('admin.pelayanan.baptisan') }}"><i
                                                                                class="fas fa-tint"></i> Baptisan</a>
                                                        </li>
                                                        <li
                                                                class="{{ request()->routeIs('admin.pelayanan.katekisasi') ? 'active' : '' }}">
                                                                <a class="nav-link"
                                                                        href="{{ route('admin.pelayanan.katekisasi') }}"><i
                                                                                class="fas fa-book"></i> Katekisasi</a>
                                                        </li>
                                                        <li
                                                                class="{{ request()->routeIs('admin.pelayanan.pernikahan') ? 'active' : '' }}">
                                                                <a class="nav-link"
                                                                        href="{{ route('admin.pelayanan.pernikahan') }}"><i
                                                                                class="fas fa-heart"></i> Pernikahan</a>
                                                        </li>
                                                        <li
                                                                class="{{ request()->routeIs('admin.pelayanan.kedukaan') ? 'active' : '' }}">
                                                                <a class="nav-link"
                                                                        href="{{ route('admin.pelayanan.kedukaan') }}"><i
                                                                                class="fas fa-cross"></i> Kedukaan</a>
                                                        </li>
                                                        <li
                                                                class="{{ request()->routeIs('admin.pelayanan.pindah') ? 'active' : '' }}">
                                                                <a class="nav-link"
                                                                        href="{{ route('admin.pelayanan.pindah') }}"><i
                                                                                class="fas fa-sign-out-alt"></i>
                                                                        Pindah</a>
                                                        </li>
                                                </ul>
                                        </li> --}}
                                </ul>
                        </li>

                        <!-- MASTER DATA -->
                        <li class="menu-header sidebar-section-toggle" data-section="master-data">
                                <span>Master Data</span>
                                <span class="sidebar-toggle-icon"><i class="fas fa-chevron-up"></i></span>
                        </li>
                        <li class="section-wrapper">
                                <ul class="section-items">
                                        <li class="{{ request()->routeIs('admin.wijk') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.wijk') }}">
                                                        <i class="fas fa-map-marker-alt"></i> <span>WIJK</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.jemaat') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.jemaat') }}">
                                                        <i class="fas fa-users"></i> <span>Jemaat</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.penatua') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.penatua') }}">
                                                        <i class="fas fa-user-friends"></i> <span>Penatua</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.pendeta') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.pendeta') }}">
                                                        <i class="fas fa-user-tie"></i> <span>Pendeta</span>
                                                </a>
                                        </li>


                                        <li class="{{ request()->routeIs('admin.ibadah') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.ibadah') }}">
                                                        <i class="fas fa-church"></i> <span>Ibadah</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.berita') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.berita') }}">
                                                        <i class="fas fa-newspaper"></i> <span>Berita</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.pelayan') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.pelayan') }}">
                                                        <i class="fas fa-file"></i> <span>Pelayanan Ibadah</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.galeri') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.galeri') }}">
                                                        <i class="fas fa-image"></i> <span>Galeri</span>
                                                </a>
                                        </li>

                                        <li class="{{ request()->routeIs('admin.user') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('admin.user') }}">
                                                        <i class="fas fa-cog"></i> <span>User</span>
                                                </a>
                                        </li>
                                </ul>
                        </li>

                        <!-- LAPORAN -->
                        <li class="menu-header sidebar-section-toggle" data-section="laporan">
                                <span>LAPORAN</span>
                                <span class="sidebar-toggle-icon"><i class="fas fa-chevron-down"></i></span>
                        </li>

                        @endif

                        {{-- ===================== PENDETA ===================== --}}
                        @if (Auth::check() && Auth::user()->role == 'pendeta')
                        <li class="menu-header sidebar-section-toggle" data-section="pendeta-menu">
                                <span>MENU PENDETA</span>
                                <span class="sidebar-toggle-icon"><i class="fas fa-chevron-up"></i></span>
                        </li>
                        <li class="section-wrapper">
                                <ul class="section-items">
                                        <li class="{{ request()->routeIs('pendeta.dashboard') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('pendeta.dashboard') }}">
                                                        <i class="fas fa-columns"></i> <span>Dashboard</span>
                                                </a>
                                        </li>
                                        {{-- Tambah menu khusus pendeta di sini --}}
                                </ul>
                        </li>
                        @endif

                        {{-- ===================== PENATUA ===================== --}}
                        @if (Auth::check() && Auth::user()->role == 'penatua')
                        <li class="menu-header sidebar-section-toggle" data-section="penatua-menu">
                                <span>MENU PENATUA</span>
                                <span class="sidebar-toggle-icon"><i class="fas fa-chevron-up"></i></span>
                        </li>
                        <li class="section-wrapper">
                                <ul class="section-items">
                                        <li class="{{ request()->routeIs('penatua.dashboard') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.dashboard') }}">
                                                        <i class="fas fa-columns"></i> <span>Dashboard</span>
                                                </a>
                                        </li>
                                        <li class="{{ request()->routeIs('penatua.jemaat') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.jemaat') }}">
                                                        <i class="fas fa-users"></i> <span>Jemaat</span>
                                                </a>
                                        </li>

                                        <!-- Pelayanan links for Penatua -->
                                        <li
                                                class="{{ request()->routeIs('penatua.pelayanan.baptisan') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.pelayanan.baptisan') }}">
                                                        <i class="fas fa-tint"></i> <span>Baptisan</span>
                                                </a>
                                        </li>
                                        <li
                                                class="{{ request()->routeIs('penatua.pelayanan.katekisasi') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.pelayanan.katekisasi') }}">
                                                        <i class="fas fa-book"></i> <span>Katekisasi</span>
                                                </a>
                                        </li>
                                        <li
                                                class="{{ request()->routeIs('penatua.pelayanan.kedukaan') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.pelayanan.kedukaan') }}">
                                                        <i class="fas fa-cross"></i> <span>Kedukaan</span>
                                                </a>
                                        </li>
                                        <li
                                                class="{{ request()->routeIs('penatua.pelayanan.pernikahan') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.pelayanan.pernikahan') }}">
                                                        <i class="fas fa-heart"></i> <span>Pernikahan</span>
                                                </a>
                                        </li>
                                        <li
                                                class="{{ request()->routeIs('penatua.pelayanan.pindah') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('penatua.pelayanan.pindah') }}">
                                                        <i class="fas fa-sign-out-alt"></i> <span>Pindah</span>
                                                </a>
                                        </li>
                                        {{-- Tambah menu khusus penatua di sini --}}
                                </ul>
                        </li>
                        @endif

                </ul>
        </aside>
</div>

<style>
        .menu-header.sidebar-section-toggle {
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-right: 0.75rem;
        }

        .sidebar-toggle-icon {
                transition: transform .12s linear;
                margin-left: .5rem;
        }

        .sidebar-toggle-icon i {
                width: 1em;
                display: inline-block;
        }

        /* Animated section items */
        .section-wrapper {
                list-style: none;
        }

        .section-items {
                overflow: hidden;
                max-height: 0;
                transition: max-height .28s ease, opacity .2s linear;
                opacity: 0;
                padding-left: 0;
                margin: 0;
        }

        .section-items li {
                padding-left: 0;
        }

        .section-items.open {
                opacity: 1;
        }

        /* Dropdown inside section (Pelayanan) */
        /* keep dropdown rendered (display:block) and animate with max-height */
        .dropdown-menu {
                display: block;
                overflow: hidden;
                max-height: 0;
                transition: max-height .22s ease, opacity .15s linear;
                opacity: 0;
                margin: 0;
                padding-left: 0;
        }

        .dropdown-menu.open {
                opacity: 1;
        }

        /* Bootstrap's .dropdown-menu is position:absolute by default; override inside our sidebar
           so submenu items are part of document flow and push content down. */
        .section-items .dropdown-menu {
                position: static !important;
                left: auto !important;
                top: auto !important;
                width: auto !important;
                box-shadow: none !important;
                background: transparent !important;
                padding-left: 0.6rem;
        }
</style>
<script>
        (function () {
                function storageKey(name) { return 'sidebar.section.' + name; }

                function setIcon(header, isOpen) {
                        var icon = header.querySelector('.sidebar-toggle-icon i');
                        if (!icon) return;
                        icon.classList.remove('fa-chevron-up', 'fa-chevron-down');
                        icon.classList.add(isOpen ? 'fa-chevron-up' : 'fa-chevron-down');
                }

                function expand(itemsEl) {
                        itemsEl.classList.add('open');
                        // set max-height to scrollHeight to animate
                        itemsEl.style.maxHeight = itemsEl.scrollHeight + 'px';
                        itemsEl.style.opacity = '1';
                }

                function collapse(itemsEl) {
                        // set max-height to current scrollHeight then to 0 for smooth transition
                        itemsEl.style.maxHeight = itemsEl.scrollHeight + 'px';
                        // force repaint
                        itemsEl.offsetHeight;
                        itemsEl.style.maxHeight = '0';
                        itemsEl.style.opacity = '0';
                        itemsEl.classList.remove('open');
                }

                function applyState(header, isOpen) {
                        var wrapper = header.nextElementSibling;
                        if (!wrapper) return;
                        var items = wrapper.querySelector('.section-items');
                        if (!items) return;
                        if (isOpen) expand(items); else collapse(items);
                        header.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                        setIcon(header, isOpen);
                }

                function toggleSection(header) {
                        var wrapper = header.nextElementSibling;
                        if (!wrapper) return;
                        var items = wrapper.querySelector('.section-items');
                        if (!items) return;
                        var currentlyOpen = items.classList.contains('open');
                        var newOpen = !currentlyOpen;
                        applyState(header, newOpen);
                        var key = storageKey(header.dataset.section || header.getAttribute('data-section') || 'unknown');
                        try { localStorage.setItem(key, newOpen ? '1' : '0'); } catch (e) { }
                }

                document.querySelectorAll('.sidebar-section-toggle').forEach(function (hdr) {
                        hdr.setAttribute('role', 'button'); hdr.setAttribute('tabindex', '0');
                        hdr.addEventListener('click', function () { toggleSection(hdr); });
                        hdr.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleSection(hdr); } });

                        var key = storageKey(hdr.dataset.section || hdr.getAttribute('data-section') || 'unknown');
                        var stored = null;
                        try { stored = localStorage.getItem(key); } catch (e) { stored = null; }
                        if (stored === '0') applyState(hdr, false);
                        else if (stored === '1') applyState(hdr, true);
                        else {
                                // default: open
                                applyState(hdr, true);
                        }
                });

                // When content inside a section changes height (e.g., active item), keep it expanded size
                window.addEventListener('load', function () {
                        document.querySelectorAll('.section-items.open').forEach(function (el) {
                                el.style.maxHeight = el.scrollHeight + 'px';
                        });
                        // initialize any dropdowns inside sections (Pelayanan)
                        document.querySelectorAll('.has-dropdown').forEach(function (a) {
                                var menu = a.parentElement.querySelector('.dropdown-menu');
                                if (!menu) return;

                                // normalize display so measurements work (override any bootstrap display:none)
                                menu.style.display = 'block';

                                // if any child is active, open this dropdown
                                var anyActive = menu.querySelector('li.active') !== null;
                                if (anyActive) {
                                        menu.classList.add('open');
                                        menu.style.maxHeight = menu.scrollHeight + 'px';
                                        menu.style.opacity = '1';
                                } else {
                                        // ensure collapsed initial state
                                        menu.classList.remove('open');
                                        menu.style.maxHeight = '0';
                                        menu.style.opacity = '0';
                                        // keep hidden visually but present for measurements
                                        menu.style.display = 'none';
                                }

                                a.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        var isOpen = menu.classList.contains('open');
                                        var sectionItems = a.closest('.section-items');

                                        if (isOpen) {
                                                // collapse dropdown: set explicit maxHeight then animate to 0
                                                menu.style.display = 'block';
                                                menu.style.maxHeight = menu.scrollHeight + 'px';
                                                // force repaint then collapse
                                                void menu.offsetHeight;
                                                menu.style.maxHeight = '0';
                                                menu.style.opacity = '0';
                                                menu.classList.remove('open');

                                                // after transition, hide from layout
                                                setTimeout(function () { menu.style.display = 'none'; }, 260);

                                                // if we previously removed max constraint, keep the section open
                                                if (sectionItems && sectionItems.getAttribute('data-no-max')) {
                                                        sectionItems.style.maxHeight = sectionItems.scrollHeight + 'px';
                                                        sectionItems.removeAttribute('data-no-max');
                                                }

                                        } else {
                                                // ensure parent is open so dropdown can be visible
                                                if (sectionItems && !sectionItems.classList.contains('open')) {
                                                        expand(sectionItems);
                                                        // also update header state
                                                        var wrapper = sectionItems.closest('.section-wrapper');
                                                        if (wrapper) {
                                                                var header = wrapper.previousElementSibling;
                                                                if (header && header.classList.contains('sidebar-section-toggle')) {
                                                                        try { localStorage.setItem(storageKey(header.dataset.section || header.getAttribute('data-section') || 'unknown'), '1'); } catch (e) { }
                                                                        header.setAttribute('aria-expanded', 'true');
                                                                        setIcon(header, true);
                                                                }
                                                        }
                                                }

                                                // make sure menu is rendered, then measure
                                                menu.style.display = 'block';
                                                menu.classList.add('open');
                                                menu.style.maxHeight = menu.scrollHeight + 'px';
                                                menu.style.opacity = '1';

                                                // force repaint so heights are accurate
                                                void menu.offsetHeight;

                                                // ensure parent isn't clipped: remove max-height restriction so submenu can show
                                                if (sectionItems) {
                                                        sectionItems.style.maxHeight = 'none';
                                                        sectionItems.style.opacity = '1';
                                                        // add marker to revert later when section is collapsed
                                                        sectionItems.setAttribute('data-no-max', '1');
                                                }
                                        }
                                });
                        });
                        });

                // mark that custom sidebar dropdown handlers were initialized
                try { window.__sidebar_custom_initialized = true; } catch(e) {}
        })();
</script>