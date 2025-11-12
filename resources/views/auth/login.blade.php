<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('auth/css/style.css') }}" />
</head>

<body>
    <!-- Background Elements -->
    <div class="background">
        <div class="noise"></div>
        <div class="grid"></div>
        <div class="gradient-sphere sphere-1"></div>
        <div class="gradient-sphere sphere-2"></div>
    </div>

    <!-- Main Content -->
    <div class="login-page">
        <!-- Left Panel with intro content -->
        <div class="left-panel">
            <!-- Brand Logo -->
            <div class="brand fade-in fade-in-1">
                <div class="logo">KMJ</div>
                <div class="logo-text">#duniakanindomakmurjaya</div>
            </div>

            <!-- Intro Text -->
            <div class="intro-text fade-in fade-in-2">
                <h1>PT. Kanindo Makmur Jaya</h1>
                <p>
                    Jl. Raya Jepara - Kudus, Pendosawalan, Kec. Kalinyamatan, Kabupaten Jepara, Jawa Tengah 59462,
                    Jepara 59462
                </p>
            </div>

            <!-- Features List -->
            <div class="features fade-in fade-in-3">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-users-gear"></i>

                        {{-- <i class="fa-solid fa-shield-halved"></i> --}}
                    </div>
                    <div class="feature-text">Perusahaan Tas Merk Ternama</div>
                </div>
                {{-- <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div class="feature-text">Real-time collaboration</div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="feature-text">Advanced analytics</div>
                </div> --}}
            </div>

            <!-- Footer -->
            <div class="footer fade-in fade-in-4">
                <span>© <?= date('Y') ?> . All rights reserved.</span>
                {{-- <nav>
                    <ul>
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                </nav> --}}
            </div>
        </div>

        <!-- Right Panel with login form -->
        <div class="right-panel">
            <div class="login-container">
                <div class="login-header">
                    <h2>Selamat Datang!</h2>
                    <p>Silahkan masukkan username dan password anda!</p>
                </div>

                <!-- Login tabs -->
                {{-- <div class="tabs">
                    <button class="tab active">Email</button>
                    <button class="tab">Phone</button>
                    <div class="tab-bg"></div>
                </div> --}}

                <!-- Login form -->
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-with-icon">
                            <input type="text" name="username" id="username" class="input-field"
                                value="{{ old('username') ?: Cookie::get('username') }}" placeholder="Username"
                                required />
                            <i class="fa-regular fa-user form-icon"></i>
                            @error('username')
                                <span class="text-danger"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <input type="password" name="password" id="password" class="input-field"
                                placeholder="Enter your password" required />
                            <i class="fa-solid fa-lock form-icon"></i>
                            <button type="button" class="password-toggle">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-extras">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember" checked />
                            <label for="remember">Remember me</label>
                        </div>

                        <div class="forgot-password">
                            <a href="#">Forgot password?</a>
                        </div>
                    </div>

                    <button type="submit" class="login-button">Sign In</button>

                    <div class="signup-link">
                        Don't have an account? <a href="#">Create account</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('auth/js/script.js') }}"></script>
</body>

</html>
