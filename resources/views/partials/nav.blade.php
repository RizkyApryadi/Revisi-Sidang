<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </form>

    <ul class="navbar-nav navbar-right">
        <!-- User Dropdown (custom, no Bootstrap JS) -->
        <li class="nav-item custom-dropdown">
            <a href="#" id="userDropdown" class="nav-link nav-link-user custom-dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
            </a>

            <div class="custom-dropdown-menu" role="menu" aria-labelledby="userDropdown">
                <div class="dropdown-title">Halo, {{ Auth::user()->name }}</div>

                <!-- Profile link -->
                <a href="#" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Pengaturan Profil
                </a>

                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <a href="{{ route('logout') }}"
                   class="dropdown-item has-icon text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
