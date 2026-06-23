<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi - SILERA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="center-shell">
        <section class="card" style="max-width:520px;margin:3rem auto;padding:1.5rem;border-radius:10px;box-shadow:0 8px 30px rgba(16,24,40,0.06)">
            <h1>Lupa Kata Sandi</h1>
            @if (session('status'))
                <div class="form-alert success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('community-login.send-reset') }}" method="POST">
                @csrf
                <label class="modern-field">
                    <span>Email</span>
                    <input type="email" name="email" required placeholder="nama@email.com">
                </label>
                <button class="modern-submit" type="submit">Kirim tautan reset</button>
            </form>
        </section>
    </main>
</body>
</html>
