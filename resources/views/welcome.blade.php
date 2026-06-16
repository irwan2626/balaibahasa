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
                @php
                    $accountLogo = session('account_logo') ?: \App\Models\CommunityAccountRequest::query()
                        ->where('email', session('account_email'))
                        ->value('logo_path');
                @endphp
                <div class="nav-actions">
                    <a class="account-chip" href="{{ route('community-profile.show') }}" aria-label="Buka akun {{ session('account_name') }}">
                        @if ($accountLogo)
                            <img src="{{ asset('storage/'.$accountLogo) }}" alt="Logo akun {{ session('account_name') }}">
                        @else
                            <span>{{ strtoupper(substr(session('account_name', 'A'), 0, 1)) }}</span>
                        @endif
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
                    <h1>SILERA: Sistem Informasi Komunitas Literasi Riau</h1>
                    <p>Membangun ekosistem literasi yang terintegrasi di Bumi Lancang Kuning melalui pendataan, kolaborasi, dan pemberdayaan komunitas literasi di seluruh pelosok Riau.</p>
                    <div class="hero-actions">
                        <a class="btn btn-primary btn-lg" href="{{ route('community-account.create') }}">Buat Akun <span aria-hidden="true">↗</span></a>
                        <a class="btn btn-secondary btn-lg" href="{{ route('community-stories.create') }}">Tambahkan Cerita <span aria-hidden="true">✎</span></a>
                    </div>
                </div>

                <div class="hero-preview" aria-label="Kegiatan membaca di perpustakaan">
                    <div class="hero-library-slider">
                        <img class="hero-library-slide slide-one" src="{{ asset('images/buku1.jpg') }}" alt="Kegiatan membaca di perpustakaan">
                        <img class="hero-library-slide slide-two" src="{{ asset('images/buku2.jpg') }}" alt="Suasana literasi dan buku bacaan">
                        <img class="hero-library-slide slide-three" src="{{ asset('images/buku3.jpg') }}" alt="Ruang baca komunitas literasi">
                        <div class="hero-library-dots" aria-hidden="true">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="komunitas" class="section community-section">
            <div class="container">
                <div class="section-heading split-heading">
                    <div>
                        <h2>Daftar Komunitas Literasi</h2>
                        <p>Temukan komunitas literasi yang sudah membuat akun dan terdaftar di SILERA.</p>
                    </div>
                    <form class="search-field" action="{{ route('communities.index') }}" method="GET">
                        <span aria-hidden="true">⌕</span>
                        <input type="search" name="q" placeholder="Cari nama komunitas...">
                    </form>
                </div>

                <div class="community-grid">
                    @forelse ($registeredCommunities as $community)
                        <article class="community-card community-profile-card">
                            <div class="community-logo-preview">
                                @if ($community->logo_path)
                                    <img src="{{ asset('storage/'.$community->logo_path) }}" alt="Logo {{ $community->community_name }}">
                                @else
                                    <span>{{ strtoupper(substr($community->community_name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h3>{{ $community->community_name }}</h3>
                            </div>
                        </article>
                    @empty
                        <article class="community-empty-state">
                            <h3>Belum ada komunitas terdaftar.</h3>
                            <p>Logo dan nama komunitas akan tampil di sini setelah akun komunitas disetujui admin.</p>
                        </article>
                    @endforelse
                </div>

                <div class="center-action">
                    <a class="btn btn-secondary btn-wide" href="{{ route('communities.index') }}">Lihat Semua Komunitas</a>
                </div>
            </div>
        </section>

        <section id="tentang" class="about-section">
            <div class="container about-grid">
                <div class="about-visual">
                    <div class="about-activity-slider" aria-label="Dokumentasi kegiatan literasi">
                        <img class="about-activity-slide slide-one" src="{{ asset('images/kegiatan1 (1).webp') }}" alt="Kegiatan literasi komunitas">
                        <img class="about-activity-slide slide-two" src="{{ asset('images/kegiatan1 (2).JPG') }}" alt="Dokumentasi kegiatan membaca">
                        <img class="about-activity-slide slide-three" src="{{ asset('images/kegitan.png') }}" alt="Kegiatan Balai Bahasa Provinsi Riau">
                    </div>
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
                </div>
            </div>
        </section>

        <section id="informasi" class="section news-section">
            <div class="container">
                <div class="section-heading split-heading">
                    <h2>Info Terkini Literasi Riau</h2>
                    <a href="{{ route('articles.index') }}">Lihat Semua articles <span aria-hidden="true">&rsaquo;</span></a>
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
                        @php
                            $isPublishedStory = $item instanceof \App\Models\CommunityStory;
                        @endphp

                        <article class="news-card">
                            @if ($isPublishedStory)
                                <a href="{{ route('stories.show', $item) }}" aria-label="Baca cerita {{ $item->title }}">
                            @endif
                            @if ($item->photo_path)
                                <img class="news-image" src="{{ asset('storage/'.$item->photo_path) }}" alt="Cover cerita {{ $item->title }}">
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
                            @if ($isPublishedStory)
                                </a>
                            @endif
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
