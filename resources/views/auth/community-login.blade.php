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
    <style>
        body.community-login-page { background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); font-family: 'Plus Jakarta Sans', Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .community-login-shell { min-height: calc(100vh - 80px); display:flex; align-items:center; justify-content:center; padding:2rem 1rem; }
        .login-card { width:100%; max-width:520px; background:#fff; border-radius:12px; box-shadow:0 8px 30px rgba(16,24,40,0.08); padding:2rem; }
        .login-header { text-align:center; margin-bottom:1.25rem; }
        .login-header .badge { display:inline-block; background:#eef2ff; color:#3730a3; padding:.25rem .6rem; border-radius:999px; font-weight:600; font-size:.85rem; margin-bottom:.5rem; }
        .login-header h1 { margin:0; font-size:1.25rem; color:#111827; }
        .login-header p { margin:.5rem 0 0; color:#6b7280; font-size:.95rem; }
        .modern-field { display:block; margin-bottom:.9rem; }
        .modern-field span { display:block; font-size:.85rem; color:#374151; margin-bottom:.35rem; }
        .modern-field input { width:100%; padding:.65rem .75rem; border:1px solid #e6e9ef; border-radius:8px; font-size:.975rem; color:#111827; background:#fbfdff; }
        .modern-field small { color:#ef4444; display:block; margin-top:.35rem; }
        .modern-submit { width:100%; display:inline-flex; align-items:center; justify-content:center; gap:.6rem; padding:.7rem .9rem; border-radius:10px; border:none; background:linear-gradient(90deg,#4f46e5,#6366f1); color:#fff; font-weight:600; cursor:pointer; margin-top:.25rem; }
        .modern-submit span { opacity:.9; }
        .login-footer { text-align:center; margin-top:1rem; color:#6b7280; font-size:.95rem; }
        @media (max-width:640px) { .login-card{padding:1.25rem;} }
    </style>
</head>
<body class="community-login-page">
    @include('layouts.navbar')

    <main class="community-login-shell">
        <section class="login-card" aria-labelledby="login-title">
            <div class="login-header">
                <div class="badge">Akun Komunitas</div>
                <h1 id="login-title">Masuk dan lanjutkan cerita literasi Anda</h1>
                <p>Masuk menggunakan email dan kata sandi yang dibuat saat mendaftar.</p>
            </div>

            <form class="community-login-form" action="{{ route('community-login.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="form-alert error" role="alert">
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
                    <span aria-hidden="true">&rarr;</span>
                </button>

                <div class="login-footer">
                    Belum punya akun? <a href="{{ route('community-account.create') }}">Buat akun komunitas</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
