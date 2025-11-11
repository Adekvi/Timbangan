<header class="mb-3 shadow-sm p-3 rounded d-flex justify-content-between align-items-center">
    <!-- Kiri: tombol burger + form pencarian -->
    <div class="d-flex align-items-center">

        <a href="#" class="burger-btn d-block d-xl-none me-3">
            <i class="fa-solid fa-bars fs-3" style="color: #435ebe"></i>
        </a>

        <!-- Form Pencarian -->
        <form action="" class="d-none d-lg-flex align-items-center mb-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search ..." aria-label="Search" />
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Kanan: hanya foto profil dengan dropdown -->
    <div class="dropdown">
        <a href="#" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false"
            class="text-decoration-none">
            <img src="{{ asset('assets/images/profile.png') }}" alt="Profile" class="rounded-circle" width="42"
                height="42" style="cursor:pointer;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow animated fadeIn" aria-labelledby="dropdownProfile">
            <li class="px-3 py-2 text-center">
                <div class="avatar-lg mx-auto mb-2">
                    <img src="{{ asset('assets/images/profile.png') }}" alt="Profile" class="avatar-img rounded-circle"
                        width="70" height="70">
                </div>
                <h6 class="fw-bold mb-0">{{ Auth::user()->username ?? '-' }}</h6>
                <p class="text-muted small mb-2">hello@example.com</p>
                <a href="#" class="btn btn-sm btn-secondary">View Profile</a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="fa-solid fa-user me-2"></i> My Profile
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>
