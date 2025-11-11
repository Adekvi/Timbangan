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

        <!-- Right Panel with login form -->
        <div class="right-panel">
            <div class="login-container">
                <div class="login-header">
                    <h2>Lupa Password? 🔒</h2>
                    <p>Masukkan No. Hp anda untuk melakukan Reset Password!</p>
                </div>

                <!-- Login form -->
                <form id="formAuthentication" class="mb-3" action="#" method="POST">
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. Hp</label>
                        <input type="number" class="form-control" id="no_hp" name="no_hp"
                            placeholder="08........" autofocus />
                    </div>
                    <button class="btn btn-primary d-grid w-100">Kirim</button>
                </form>
                <div class="text-center">
                    <a href="{{ url('login') }}" class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                        Kembali Login?
                    </a>

                </div>
            </div>
        </div>

        <script src="{{ asset('auth/js/script.js') }}"></script>
</body>

</html>
