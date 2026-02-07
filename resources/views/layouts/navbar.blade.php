<nav class="navbar navbar-expand-lg navbar-light sticky-top bg-white shadow-sm">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand fw-bold fs-4 text-primary-custom" href="{{ url('/') }}">
            LOKERIND
        </a>

        <!-- Toggler Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="navbar-collapse collapse" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center mx-auto">
                @guest
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->is('lowongan*') ? 'active' : '' }}"
                            href="{{ route('jobs.index') }}">Lowongan</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link {{ request()->is('perusahaan*') ? 'active' : '' }}"
                            href="{{ route('companies.index') }}">Perusahaan</a>
                    </li>
                @endguest

                {{-- Menu Kandidat --}}
                @auth
                    @if (Auth::user()->isUser())
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->is('lowongan*') ? 'active' : '' }}"
                                href="{{ route('jobs.index') }}">Lowongan</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->is('perusahaan*') ? 'active' : '' }}"
                                href="{{ route('companies.index') }}">Perusahaan</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->is('riwayat*') ? 'active' : '' }}"
                                href="{{ route('history.index') }}">Riwayat</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->is('activity*') ? 'active' : '' }}"
                                href="{{ route('candidate.activity') }}">Aktivitas</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}"
                                href="{{ route('profile.index') }}">Profil</a>
                        </li>
                    @endif

                    {{-- Menu Company --}}
                    @if (Auth::user()->isCompany())
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}"
                                href="{{ route('company.dashboard') }}">Dashboard</a>
                        </li>
                        {{-- <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('company.candidates.match') ? 'active' : '' }}"
                                href="{{ route('company.candidates.match') }}">
                                Kandidat Cocok
                            </a>
                        </li> --}}
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('company.riwayat.index') ? 'active' : '' }}"
                                href="{{ route('company.riwayat.index') }}">Riwayat</a>
                        </li>
                        {{-- <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('company.jobs.index') ? 'active' : '' }}"
                                href="{{ route('company.jobs.index') }}">Kelola Jobs</a>
                        </li> --}}
                        <li class="nav-item mx-2">
                            <a class="nav-link {{ request()->routeIs('company.profile') ? 'active' : '' }}"
                                href="{{ route('company.profile') }}">Profile</a>
                        </li>
                    @endif
                @endauth

                @auth
                    <!-- User Profile Dropdown Mobile -->
                    <li class="nav-item dropdown d-lg-none">
                        <a class="btn btn-outline-primary-custom dropdown-toggle d-flex align-items-center justify-content-center px-3 mt-2 w-100"
                            href="#" role="button" id="userDropdownMobile" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="user-name">{{ Str::limit(Auth::user()->name, 15) }}</span>
                        </a>
                        <ul class="dropdown-menu w-100" aria-labelledby="userDropdownMobile">
                            <li>
                                <button type="button" class="dropdown-item text-danger logout-btn">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Sign Up Button Mobile -->
                    <li class="nav-item d-lg-none mt-2">
                        <a class="btn btn-outline-primary-custom px-4 w-100" href="/register">Sign Up</a>
                    </li>
                    <!-- Login Button Mobile -->
                    <li class="nav-item d-lg-none mt-2">
                        <a class="btn btn-primary-custom px-4 text-white w-100" href="/login">Login</a>
                    </li>
                @endauth
            </ul>

            <!-- Desktop Buttons Only -->
            <ul class="navbar-nav align-items-lg-center d-none d-lg-flex">
                @auth
                    <!-- User Profile Dropdown Desktop -->
                    <li class="nav-item dropdown">
                        <a class="btn btn-outline-primary-custom dropdown-toggle d-flex align-items-center px-3"
                            href="#" role="button" id="userDropdownDesktop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="user-name">{{ Str::limit(Auth::user()->name, 15) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownDesktop">
                            <li>
                                <button type="button" class="dropdown-item text-danger logout-btn">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Sign Up Button Desktop -->
                    <li class="nav-item mx-2">
                        <a class="btn btn-outline-primary-custom px-4" href="/register">Sign Up</a>
                    </li>

                    <!-- Login Button Desktop -->
                    <li class="nav-item">
                        <a class="btn btn-primary-custom px-4 text-white" href="/login">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutButtons = document.querySelectorAll('.logout-btn');

        logoutButtons.forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();

                const result = await Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: "Apakah Anda yakin ingin keluar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch('{{ route('logout') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Logout',
                                text: 'Terima kasih, sampai jumpa lagi!',
                                confirmButtonColor: 'var(--primary-blue)',
                                timer: 1200,
                                showConfirmButton: false
                            });

                            window.location.href = data.redirect ||
                                '{{ route('index.home') }}';
                        } else {
                            throw new Error('Logout failed');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat logout. Silakan coba lagi.',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                }
            });
        });
    });
</script>
