<?php
session_start();
include 'config/koneksi.php';

// Pesan error dari proses_login.php ditampilkan dengan aman.
$pesan_error = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Pelanggaran Siswa</title>

    <!-- Bootstrap 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">

    <!-- Font dan ikon -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --navy: #082e70;
            --navy-dark: #06265e;
            --blue: #1765bd;
            --yellow: #ffc928;
            --text: #172b4d;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background: #edf3fb;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            overflow: hidden;
            position: relative;
            background:
                radial-gradient(circle at 12% 12%, rgba(76, 151, 237, .45), transparent 32%),
                linear-gradient(130deg, #0b4b9a 0%, #0c66bd 48%, #eaf2fc 48%, #f5f8fd 100%);
        }

        /* Foto sekolah opsional:
           simpan foto gedung sekolah di assets/img/sekolah.jpg */
        .login-page::before {
            content: "";
            position: absolute;
            inset: 0 0 0 0;
            background:
                linear-gradient(90deg, rgba(4, 35, 91, .90), rgba(7, 69, 145, .78) 48%, rgba(7, 69, 145, .08) 78%),
                url("assets/img/sekolah.jpg") center/cover no-repeat;
            opacity: .92;
        }

        .login-page::after {
            content: "";
            position: absolute;
            width: 360px;
            height: 360px;
            right: -190px;
            top: -190px;
            border: 42px solid rgba(255, 201, 40, .95);
            transform: rotate(45deg);
            pointer-events: none;
        }

        .page-content {
            width: 100%;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            padding: 55px 7%;
        }

        .identity {
            width: 56%;
            color: #fff;
            padding: 20px 5% 20px 0;
        }

        .school-mark {
            width: 116px;
            height: 116px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255,255,255,.75);
            border-radius: 50%;
            margin: 0 0 28px 4px;
            color: #fff;
            background: rgba(255,255,255,.10);
            box-shadow: 0 12px 35px rgba(0,0,0,.12);
        }

        .school-mark i { font-size: 57px; }

        .identity h1 {
            margin: 0;
            font-size: clamp(30px, 4vw, 54px);
            line-height: 1.16;
            font-weight: 800;
            letter-spacing: -.8px;
            text-transform: uppercase;
        }

        .identity h1 span {
            display: block;
            color: var(--yellow);
        }

        .yellow-line {
            width: min(440px, 90%);
            height: 5px;
            margin: 22px 0 17px;
            border-radius: 5px;
            background: var(--yellow);
        }

        .motto {
            font-size: clamp(14px, 1.5vw, 19px);
            font-weight: 500;
            letter-spacing: .2px;
            color: rgba(255,255,255,.95);
        }

        .school-quote {
            margin-top: 75px;
            max-width: 470px;
            padding-left: 17px;
            border-left: 4px solid var(--yellow);
            color: rgba(255,255,255,.92);
            font-size: 15px;
            font-style: italic;
            line-height: 1.7;
        }

        .login-side {
            width: 44%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .login-card {
            width: 100%;
            max-width: 475px;
            padding: 42px 42px 38px;
            border: 1px solid rgba(255,255,255,.85);
            border-radius: 22px;
            background: rgba(255,255,255,.96);
            box-shadow: 0 24px 65px rgba(3, 28, 73, .22);
            backdrop-filter: blur(12px);
        }

        .login-icon {
            width: 76px;
            height: 76px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 13px;
            color: var(--navy);
            background: #eaf2ff;
            border-radius: 20px;
            font-size: 37px;
        }

        .login-card h2 {
            margin: 0;
            color: var(--navy);
            text-align: center;
            font-size: 34px;
            font-weight: 800;
        }

        .login-subtitle {
            margin: 5px 0 25px;
            color: #596b88;
            text-align: center;
            font-size: 14px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 13px;
            margin: 0 0 25px;
            color: #1b4e91;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #d6e0ef;
        }

        .form-group { margin-bottom: 19px; }

        .form-group label {
            margin-bottom: 8px;
            color: #314665;
            font-size: 13px;
            font-weight: 600;
        }

        .input-wrap { position: relative; }

        .input-wrap > i {
            position: absolute;
            top: 50%;
            left: 17px;
            z-index: 2;
            transform: translateY(-50%);
            color: #174783;
            font-size: 16px;
        }

        .form-control {
            height: 54px;
            padding: 10px 48px 10px 48px;
            border: 1px solid #cbd7e8;
            border-radius: 11px;
            color: #203653;
            background: #fbfdff;
            font-size: 14px;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #397ac5;
            background: #fff;
        }

        .toggle-password {
            position: absolute;
            top: 0;
            right: 0;
            width: 48px;
            height: 54px;
            border: 0;
            color: #637794;
            background: transparent;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            height: 54px;
            margin-top: 7px;
            border: 0;
            border-radius: 11px;
            color: #fff;
            background: linear-gradient(110deg, var(--navy-dark), #104b9c);
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .2px;
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .btn-login:hover {
            color: #fff;
            filter: brightness(1.08);
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(8,46,112,.22);
        }

        .alert {
            border: 0;
            border-radius: 10px;
            font-size: 13px;
        }

        .login-footer {
            margin-top: 23px;
            color: #8492a8;
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
        }

        @media (max-width: 991.98px) {
            .page-content { padding: 40px 5%; }
            .identity { width: 50%; }
            .login-side { width: 50%; }
            .login-card { padding: 35px 28px; }
            .school-quote { margin-top: 45px; }
        }

        @media (max-width: 767.98px) {
            .login-page {
                background: linear-gradient(145deg, #0b4b9a, #0c66bd 48%, #edf3fb 48%);
            }
            .login-page::before {
                background:
                    linear-gradient(180deg, rgba(4,35,91,.91), rgba(7,69,145,.78) 43%, rgba(7,69,145,.08) 100%),
                    url("assets/img/sekolah.jpg") center/cover no-repeat;
            }
            .page-content {
                min-height: 100vh;
                flex-direction: column;
                justify-content: center;
                padding: 35px 18px;
            }
            .identity {
                width: 100%;
                padding: 0;
                text-align: center;
            }
            .school-mark {
                width: 76px;
                height: 76px;
                margin: 0 auto 15px;
            }
            .school-mark i { font-size: 37px; }
            .identity h1 { font-size: clamp(24px, 7vw, 34px); }
            .yellow-line { margin: 13px auto; width: 220px; }
            .motto { font-size: 12px; }
            .school-quote { display: none; }
            .login-side {
                width: 100%;
                justify-content: center;
                margin-top: 28px;
            }
            .login-card {
                max-width: 440px;
                padding: 29px 23px 25px;
                border-radius: 18px;
            }
            .login-icon { width: 61px; height: 61px; font-size: 29px; }
            .login-card h2 { font-size: 29px; }
        }
    </style>
</head>

<body>
<div class="login-page">
    <main class="page-content">
        <section class="identity" aria-label="Identitas sekolah">
            <div class="school-mark">
                <i class="fas fa-school" aria-hidden="true"></i>
            </div>

            <h1>
                SMK Muhammadiyah
                <span>Kota Tasikmalaya</span>
            </h1>

            <div class="yellow-line"></div>

            <p class="motto">Religius <span>•</span> Berkarakter <span>•</span> Berprestasi</p>

            <div class="school-quote">
                “Membangun Generasi Unggul untuk Masa Depan Bangsa”
            </div>
        </section>

        <section class="login-side" aria-label="Form login">
            <div class="login-card">
                <div class="login-icon">
                    <i class="fas fa-book-reader" aria-hidden="true"></i>
                </div>

                <h2>Login</h2>
                <p class="login-subtitle">Sistem Pelanggaran Siswa</p>

                <div class="divider">
                    <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                </div>

                <?php if (!empty($pesan_error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($pesan_error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <form action="proses_login.php" method="POST" autocomplete="on">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Masukkan email"
                                autocomplete="username"
                                required
                                autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required>
                            <button class="toggle-password" type="button"
                                    id="togglePassword" aria-label="Tampilkan password">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" name="login" value="login" class="btn btn-login">
                        <i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>
                        Login
                    </button>
                </form>

                <div class="login-footer">
                    &copy; <?= date('Y'); ?> SMK Muhammadiyah Kota Tasikmalaya<br>
                    Sistem Informasi Pelanggaran Siswa
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    togglePassword.addEventListener('click', function () {
        const sedangTersembunyi = passwordInput.type === 'password';
        passwordInput.type = sedangTersembunyi ? 'text' : 'password';
        togglePasswordIcon.classList.toggle('fa-eye', !sedangTersembunyi);
        togglePasswordIcon.classList.toggle('fa-eye-slash', sedangTersembunyi);
        togglePassword.setAttribute(
            'aria-label',
            sedangTersembunyi ? 'Sembunyikan password' : 'Tampilkan password'
        );
    });
</script>
</body>
</html>
