<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambahkan Cerita - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="story-page">
    <header class="site-header">
        <div class="container nav-shell">
            <a href="{{ url('/') }}" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <strong>SILERA</strong>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/#komunitas') }}">Komunitas</a>
                <a href="{{ url('/#informasi') }}">Informasi</a>
                <a href="{{ url('/#tentang') }}">Tentang Kami</a>
            </nav>

            @if (session('account_created'))
                <div class="nav-actions">
                    <a class="account-chip" href="{{ route('dashboard') }}" aria-label="Buka akun {{ session('account_name') }}">
                        <span>{{ strtoupper(substr(session('account_name', 'A'), 0, 1)) }}</span>
                    </a>
                </div>
            @endif
        </div>
    </header>

    <main class="story-shell">
        <section class="story-hero">
            <div>
                <span class="register-badge">
                    <span aria-hidden="true">✎</span>
                    Cerita Komunitas
                </span>
                <h1>Bagikan kisah literasi dari komunitas Anda</h1>
                <p>Unggah foto kegiatan, tulis judul yang jelas, lalu ceritakan dampak kegiatan literasi yang ingin dibagikan ke jejaring SILERA.</p>
            </div>
            <aside>
                <small>Akun aktif</small>
                <strong>{{ session('account_name') }}</strong>
                <span>{{ session('account_email') }}</span>
            </aside>
        </section>

        <section class="story-form-card">
            @if (session('status'))
                <div class="form-alert success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="form-alert error">
                    Mohon periksa kembali data cerita Anda.
                </div>
            @endif

            <form class="story-form" action="{{ route('community-stories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="story-upload">
                    <input type="file" name="photo" accept="image/png,image/jpeg,image/webp" required>
                    <span class="story-upload-visual">
                        <strong>Unggah Foto Cerita</strong>
                        <small>JPG, PNG, atau WEBP maksimal 2 MB</small>
                    </span>
                </label>
                @error('photo')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <div class="story-fields">
                    <label class="modern-field">
                        <span>Judul Cerita</span>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Lapak Baca di Tepian Sungai Siak" required>
                        @error('title')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>

                    <label class="modern-field">
                        <span>Isi Cerita</span>
                        <textarea name="story" rows="10" placeholder="Tulis cerita kegiatan, siapa yang terlibat, lokasi, dan dampaknya bagi warga..." required>{{ old('story') }}</textarea>
                        @error('story')
                            <small>{{ $message }}</small>
                        @enderror
                    </label>

                    <button class="modern-submit" type="submit">
                        Kirim Cerita
                        <span aria-hidden="true">-&gt;</span>
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
