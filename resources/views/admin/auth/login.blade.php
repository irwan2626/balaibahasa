<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Dashboard - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-shell">
        <section class="admin-login-card">
            <a class="admin-login-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
            </a>

            <div class="admin-login-heading">
                <span>Dashboard Admin</span>
                <h1>Masuk ke SILERA</h1>
                <p>Gunakan akun admin untuk mengelola dashboard, data komunitas, dan user admin.</p>
            </div>

            @if ($errors->any())
                <div class="form-alert error">
                    Email atau kata sandi belum sesuai.
                </div>
            @endif

            <form class="admin-login-form" action="{{ route('login.store') }}" method="POST">
                @csrf

                <label class="modern-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@silera.test" required autofocus>
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label class="modern-field">
                    <span>Kata Sandi</span>
                    <input type="password" name="password" placeholder="password" required>
                    @error('password')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label class="remember-field">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat sesi masuk</span>
                </label>

                <button class="modern-submit" type="submit">Masuk Dashboard <span aria-hidden="true">-&gt;</span></button>
            </form>

            <p class="admin-login-note">Akun awal: <strong>admin@silera.test</strong> / <strong>password</strong></p>
        </section>
    </main>
</body>
</html>
