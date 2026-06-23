<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Kata Sandi - SILERA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="center-shell">
        <section class="card" style="max-width:520px;margin:3rem auto;padding:1.5rem;border-radius:10px;box-shadow:0 8px 30px rgba(16,24,40,0.06)">
            <h1>Reset Kata Sandi</h1>
            @if ($errors->any())
                <div class="form-alert error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('community-login.reset.post') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label class="modern-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ $email }}" required>
                </label>

                <label class="modern-field">
                    <span>Password Baru</span>
                    <input type="password" name="password" required>
                </label>

                <label class="modern-field">
                    <span>Ulangi Password</span>
                    <input type="password" name="password_confirmation" required>
                </label>

                <button class="modern-submit" type="submit">Ubah kata sandi</button>
            </form>
        </section>
    </main>
</body>
</html>
