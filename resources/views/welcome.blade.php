<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SILERA - Sistem Informasi Komunitas Literasi Riau</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container nav-shell">
            <a href="#" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <strong>SILERA</strong>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="active" href="#">Beranda</a>
                <a href="#komunitas">Komunitas</a>
                <a href="#informasi">Informasi</a>
                <a href="#tentang">Tentang Kami</a>
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

    <main>
        <section class="hero-section">
            <div class="container hero-grid">
                <div class="hero-copy">
                    <span class="eyebrow">Balai Bahasa Provinsi Riau</span>
                    <h1>SILERA: Sistem Informasi Komunitas Literasi Riau</h1>
                    <p>Membangun ekosistem literasi yang terintegrasi di Bumi Lancang Kuning melalui pendataan, kolaborasi, dan pemberdayaan komunitas literasi di seluruh pelosok Riau.</p>
                    <div class="hero-actions">
                        <a class="btn btn-primary btn-lg" href="{{ route('community-account.create') }}">Buat Akun <span aria-hidden="true">↗</span></a>
                        <a class="btn btn-secondary btn-lg" href="{{ route('community-stories.create') }}">Tambahkan Cerita <span aria-hidden="true">✎</span></a>
                    </div>
                </div>

                <div class="hero-preview" aria-label="Pratinjau laman SILERA">
                    <x-browser-preview />
                </div>
            </div>
        </section>

        <section id="komunitas" class="section community-section">
            <div class="container">
                <div class="section-heading split-heading">
                    <div>
                        <h2>Daftar Komunitas Literasi</h2>
                        <p>Temukan dan bergabung dengan berbagai komunitas literasi, taman bacaan, dan forum diskusi yang tersebar di wilayah Riau.</p>
                    </div>
                    <label class="search-field">
                        <span aria-hidden="true">⌕</span>
                        <input type="search" placeholder="Cari komunitas atau lokasi...">
                    </label>
                </div>

                <div class="community-grid">
                    @foreach ([
                        ['name' => 'TBM Hamfara...', 'type' => 'Taman Bacaan', 'area' => 'Pekanbaru'],
                        ['name' => 'Rumpus Bintang', 'type' => 'Komunitas', 'area' => 'Dumai'],
                        ['name' => 'Forum Lingkar Pena', 'type' => 'Forum', 'area' => 'Kampar'],
                        ['name' => 'TBM Kandas Library', 'type' => 'Perpustakaan', 'area' => 'Siak'],
                    ] as $community)
                        <article class="community-card">
                            <div class="map-preview">
                                <span class="map-pin pin-a"></span>
                                <span class="map-pin pin-b"></span>
                                <span class="map-pin pin-c"></span>
                                <span class="map-river"></span>
                                <strong>Riau</strong>
                            </div>
                            <div class="card-body">
                                <span class="chip">{{ $community['type'] }}</span>
                                <h3>{{ $community['name'] }}</h3>
                                <p>{{ $community['area'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="center-action">
                    <a class="btn btn-secondary btn-wide" href="#">Lihat Semua Komunitas</a>
                </div>
            </div>
        </section>

        <section id="tentang" class="about-section">
            <div class="container about-grid">
                <div class="about-visual">
                    <x-browser-preview compact="true" />
                </div>
                <div class="about-copy">
                    <h2>Tentang Laman SILERA</h2>
                    <p>SILERA (Sistem Informasi Komunitas Literasi Riau) merupakan platform digital yang diinisiasi oleh Balai Bahasa Provinsi Riau untuk menjadi pusat data dan kolaborasi bagi para pegiat literasi di wilayah Riau.</p>
                    <div class="feature-list">
                        <div>
                            <span class="feature-icon">▣</span>
                            <strong>Pusat Data</strong>
                            <p>Basis data terpadu komunitas literasi se-Provinsi Riau.</p>
                        </div>
                        <div>
                            <span class="feature-icon">⌘</span>
                            <strong>Kolaborasi</strong>
                            <p>Memfasilitasi jejaring antar pegiat literasi daerah.</p>
                        </div>
                    </div>
                    <div class="ministry-signature">
                        <img src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                    </div>
                </div>
            </div>
        </section>

        <section id="informasi" class="section news-section">
            <div class="container">
                <div class="section-heading split-heading">
                    <h2>Info Terkini Literasi Riau</h2>
                    <a href="#">Lihat Semua Berita <span aria-hidden="true">›</span></a>
                </div>

                <div class="news-grid">
                    @php
                        $fallbackNews = collect([
                            (object) ['created_at' => '2023-10-12', 'title' => 'Lapak Baca Pinggir Sungai Siak: Menghidupkan Budaya Baca', 'story' => 'Gerakan literasi masyarakat di tepian sungai Siak semakin bergairah dengan hadirnya pojok-pojok baca baru.', 'photo_path' => null],
                            (object) ['created_at' => '2023-10-08', 'title' => 'Gerakan Satu Dusun Satu Pojok Baca Capai Target', 'story' => 'Upaya pemerintah daerah dan Balai Bahasa dalam pemerataan literasi hingga ke tingkat dusun menunjukkan hasil positif.', 'photo_path' => null],
                            (object) ['created_at' => '2023-10-05', 'title' => 'Menyongsong Festival Literasi Riau 2024', 'story' => 'Persiapan matang mulai dilakukan untuk menyambut gelaran akbar tahunan bagi para pegiat literasi Riau.', 'photo_path' => null],
                        ]);
                        $newsItems = $publishedStories->isNotEmpty() ? $publishedStories : $fallbackNews;
                    @endphp

                    @foreach ($newsItems as $item)
                        <article class="news-card">
                            @if ($item->photo_path)
                                <img class="news-image" src="{{ asset('storage/'.$item->photo_path) }}" alt="Foto cerita {{ $item->title }}">
                            @else
                                <div class="news-thumb">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            @endif
                            <div class="news-meta">{{ \Illuminate\Support\Carbon::parse($item->created_at)->translatedFormat('d M Y') }} <span>•</span> 5 min baca</div>
                            <h3>{{ $item->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($item->story, 130) }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-about">
                <div class="footer-brand">
                    <img src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                    <strong>SILERA</strong>
                </div>
                <p>SILERA adalah sistem informasi digital yang dikelola oleh Balai Bahasa Provinsi Riau untuk mendukung kemajuan literasi di Provinsi Riau.</p>
                <div class="social-links" aria-label="Media sosial">
                    <a href="#" aria-label="Instagram">◌</a>
                    <a href="#" aria-label="Bagikan">↗</a>
                    <a href="#" aria-label="Email">✉</a>
                </div>
            </div>
            <div>
                <h2>Tautan Cepat</h2>
                <a href="#">Beranda</a>
                <a href="#komunitas">Komunitas</a>
                <a href="#informasi">Informasi</a>
                <a href="#tentang">Tentang Kami</a>
            </div>
            <div>
                <h2>Bantuan</h2>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Hubungi Kami</a>
                <a href="#">Peta Situs</a>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>© 2024 SILERA - Sistem Informasi Komunitas Literasi Riau, Balai Bahasa Provinsi Riau.</p>
        </div>
    </footer>
</body>
</html>
