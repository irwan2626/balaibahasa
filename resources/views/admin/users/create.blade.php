<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Admin - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dashboard-body">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar" aria-label="Navigasi dashboard">
            <a href="{{ url('/') }}" class="dashboard-brand">
                <img class="dashboard-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
            </a>

            <nav class="dashboard-menu">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.communities.index') }}">Komunitas</a>
                <a href="{{ route('admin.stories.index') }}">Cerita</a>
                <a class="active" href="{{ route('admin.users.index') }}">User</a>
                <a href="#">Pengaturan</a>
            </nav>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div>
                    <span class="dashboard-kicker">User Admin</span>
                    <h1>Tambah Akun Admin</h1>
                </div>
                <div class="dashboard-user">
                    <span>{{ auth()->user()->name }}</span>
                    <strong>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</strong>
                </div>
            </header>

            <main class="dashboard-content">
                <section class="dashboard-card admin-form-card">
                    <div class="card-heading">
                        <div>
                            <h2>Data Admin Baru</h2>
                            <p>Akun ini dapat masuk dan mengelola dashboard SILERA.</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}">Kembali</a>
                    </div>

                    <form class="admin-user-form" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <label class="modern-field">
                            <span>Nama Admin</span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Admin Balai Bahasa" required>
                            @error('name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@silera.test" required>
                            @error('email')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <div class="admin-user-form-grid">
                            <label class="modern-field">
                                <span>Kata Sandi</span>
                                <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="modern-field">
                                <span>Konfirmasi Kata Sandi</span>
                                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                            </label>
                        </div>

                        <button class="modern-submit" type="submit">Simpan Admin <span aria-hidden="true">-&gt;</span></button>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
