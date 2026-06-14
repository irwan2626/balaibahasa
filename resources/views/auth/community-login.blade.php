<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Akun Komunitas - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="community-login-page">
    <header class="site-header">
        <div class="container nav-shell">
            <a href="{{ route('home') }}" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <strong>SILERA</strong>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ url('/#komunitas') }}">Komunitas</a>
                <a href="{{ url('/#informasi') }}">Informasi</a>
                <a href="{{ url('/#tentang') }}">Tentang Kami</a>
            </nav>
        </div>
    </header>

    <main class="community-login-shell">
        <section class="community-login-card">
            <div class="community-login-copy">
                <span class="register-badge">
                    <span aria-hidden="true">↗</span>
                    Akun Komunitas
                </span>
                <h1>Masuk dan lanjutkan cerita literasi Anda</h1>
                <p>Gunakan email dan kata sandi yang dibuat saat mendaftarkan komunitas di SILERA.</p>
            </div>

            <form class="community-login-form" action="{{ route('community-login.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="form-alert error">
                        Email atau kata sandi belum sesuai.
                    </div>
                @endif

                <label class="modern-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label class="modern-field">
                    <span>Kata Sandi</span>
                    <input type="password" name="password" placeholder="Masukkan kata sandi" required>
                    @error('password')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <button class="modern-submit" type="submit">
                    Masuk Akun
                    <span aria-hidden="true">-&gt;</span>
                </button>

                <p class="community-login-register">
                    Belum punya akun? <a href="{{ route('community-account.create') }}">Buat akun komunitas</a>
                </p>
            </form>
        </section>
    </main>
</body>
</html>
