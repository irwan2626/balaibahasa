<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun Komunitas - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="register-page register-page-modern">
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
                    <a class="account-chip" href="{{ route('community-stories.create') }}" aria-label="Buka akun {{ session('account_name') }}">
                        <span>{{ strtoupper(substr(session('account_name', 'A'), 0, 1)) }}</span>
                    </a>
                    <form class="account-logout-form" action="{{ route('community-login.destroy') }}" method="POST">
                        @csrf
                        <button type="submit">Keluar</button>
                    </form>
                </div>
            @else
                <div class="nav-actions">
                    <a class="btn btn-ghost" href="{{ route('community-login.create') }}">Masuk</a>
                </div>
            @endif
        </div>
    </header>

    <main class="register-modern-shell">
        <section class="register-hero-panel" aria-label="Manfaat pendaftaran komunitas">
            <div class="register-hero-content">
                <span class="register-badge">
                    <span aria-hidden="true">◇</span>
                    Digital Literasi Riau
                </span>
                <h1>Membangun Masa Depan Literasi Bersama</h1>
                <p>Daftarkan komunitas Anda di SILERA untuk mendapatkan akses ke sumber daya pendidikan, pendampingan dari Balai Bahasa Riau, dan jaringan literasi yang luas di seluruh provinsi.</p>
                <div class="register-metrics">
                    <div>
                        <strong>150+</strong>
                        <span>Komunitas Terdaftar</span>
                    </div>
                    <div>
                        <strong>12</strong>
                        <span>Kabupaten/Kota</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="register-form-area">
            <div class="register-form-card" aria-labelledby="register-title">
                <div class="register-form-heading">
                    <h2 id="register-title">Bergabung dengan Ekosistem Literasi Riau</h2>
                    <p>Lengkapi data di bawah ini untuk memulai profil komunitas Anda.</p>
                </div>

                @if (session('status'))
                    <div class="form-alert success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="form-alert error">
                        Mohon periksa kembali data yang ditandai.
                    </div>
                @endif

                <form class="modern-account-form" action="{{ route('community-account.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        <label class="modern-field">
                            <span>Nama Lengkap Pengelola</span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                            @error('name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Nama Komunitas Literasi</span>
                            <input type="text" name="community_name" value="{{ old('community_name') }}" placeholder="Contoh: Ruang Baca Lancang" required>
                            @error('community_name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Jabatan</span>
                            <select name="position" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach (['Ketua', 'Koordinator', 'Sekretaris', 'Bendahara', 'Relawan'] as $position)
                                    <option value="{{ $position }}" @selected(old('position') === $position)>{{ $position }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Nomor Telepon Seluler</span>
                            <div class="phone-field">
                                <b>+62</b>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="81234567890" required>
                            </div>
                            @error('phone')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field form-wide">
                            <span>Pos El / Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                            @error('email')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field password-field">
                            <span>Kata Sandi</span>
                            <input type="password" name="password" placeholder="........" required>
                            <i aria-hidden="true">◎</i>
                            @error('password')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Konfirmasi Kata Sandi</span>
                            <input type="password" name="password_confirmation" placeholder="........" required>
                        </label>
                    </div>

                    <label class="terms-field">
                        <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                        <span>Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> SILERA.</span>
                    </label>
                    @error('terms')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    <button class="modern-submit" type="submit">
                        Buat Akun Komunitas
                        <span aria-hidden="true">-&gt;</span>
                    </button>
                </form>

                @if (session('account_created'))
                    <div class="modern-login-link">
                        Akun Anda sudah tercatat. <a href="{{ route('community-stories.create') }}">Tambahkan cerita</a>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="register-footer">
        <div>
            <strong>SILERA</strong>
            <p>© 2024 SILERA - Sistem Informasi Komunitas Literasi Riau. Balai Bahasa Provinsi Riau.</p>
        </div>
        <nav aria-label="Tautan bantuan">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Syarat &amp; Ketentuan</a>
            <a href="#">Hubungi Kami</a>
            <a href="#">Peta Situs</a>
        </nav>
    </footer>
</body>
</html>
