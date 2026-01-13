<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <style>
        /* Sidebar responsive behavior (non-invasive):
           - .main-sidebar is hidden by default (shifted left)
           - body.sidebar-open shows the sidebar
           - on wide screens opening the sidebar also shifts nav/main/footer by sidebar width
        */
        .main-sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 260px; transform: translateX(-260px); transition: transform .25s ease; z-index: 1030; background: inherit; }
        body.sidebar-open .main-sidebar { transform: translateX(0); }
        @media (min-width: 768px) {
            body.sidebar-open nav.navbar,
            body.sidebar-open #app main,
            body.sidebar-open .main-footer { margin-left: 260px; transition: margin-left .25s ease; }
        }
        @media (max-width: 767px) {
            /* On small screens sidebar overlays content, do not shift layout */
            body.sidebar-open nav.navbar,
            body.sidebar-open #app main,
            body.sidebar-open .main-footer { margin-left: 0; }
        }
    </style>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (toggles sidebar and responsive menu) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" onclick="toggleSidebar()"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<script>
    (function(){
        const key = 'sidebar-open';
        function isMobile(){ return window.innerWidth < 768; }

        window.toggleSidebar = function(){
            // toggle class
            const opened = document.body.classList.toggle('sidebar-open');
            try{ localStorage.setItem(key, opened ? '1' : '0'); }catch(e){}
        };

        // initialize from storage, but keep mobile default closed
        document.addEventListener('DOMContentLoaded', function(){
            try{
                const val = localStorage.getItem(key);
                if(val === '1' && !isMobile()) document.body.classList.add('sidebar-open');
            }catch(e){}
        });

        // on resize: if mobile, always remove sidebar-open so it overlays/hidden; if desktop, restore stored state
        let resizeTimer;
        window.addEventListener('resize', function(){
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function(){
                try{
                    if(isMobile()){
                        document.body.classList.remove('sidebar-open');
                    } else {
                        const val = localStorage.getItem(key);
                        if(val === '1') document.body.classList.add('sidebar-open');
                    }
                }catch(e){}
            }, 120);
        });
    })();
</script>