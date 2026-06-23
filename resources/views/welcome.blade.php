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
                <img class="silera-logo"src="{{ asset('images/logosilera.jpeg') }}"alt="Logo SILERA">
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
                        <a class="btn btn-primary btn-lg" href="{{ route('community-account.create') }}">Buat Akun <span aria-hidden="true">&mdash;</span></a>
                        <a class="btn btn-secondary btn-lg" href="{{ route('community-stories.create') }}">Tambahkan Cerita <span aria-hidden="true">➕</span></a>
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
                            <a href="{{ route('communities.show', $community) }}" class="community-link" aria-label="Profil {{ $community->community_name }}">
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
                            </a>
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
                            <span class="feature-icon">📚</span>
                            <strong>Pusat Data</strong>
                            <p>Basis data terpadu komunitas literasi se-Provinsi Riau.</p>
                        </div>
                        <div>
                            <span class="feature-icon">🤝</span>
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
                            $coverPath = $isPublishedStory ? $item->cover_photo_path : $item->photo_path;
                        @endphp

                        <article class="news-card">
                            @if ($isPublishedStory)
                                <a href="{{ route('stories.show', $item) }}" aria-label="Baca cerita {{ $item->title }}">
                            @endif
                            @if ($coverPath)
                                <img class="news-image" src="{{ asset('storage/'.$coverPath) }}" alt="Cover cerita {{ $item->title }}">
                            @else
                                <div class="news-thumb">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            @endif
                            <div class="news-meta">{{ \Illuminate\Support\Carbon::parse($item->created_at)->translatedFormat('d M Y') }} <span>&bull;</span> 5 min baca</div>
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

    <style>
        .site-footer .social-links { display:flex; gap:.5rem; margin-top:.75rem; }
        .site-footer .social-btn { display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:999px; background:linear-gradient(135deg,#eef2ff,#e0f2fe); color:#0f172a; text-decoration:none; box-shadow:0 4px 12px rgba(2,6,23,0.08); transition:transform .14s ease, box-shadow .14s ease; }
        .site-footer .social-btn:hover { transform:translateY(-3px) scale(1.03); box-shadow:0 8px 24px rgba(2,6,23,0.12); }
        .site-footer .social-btn svg { width:18px; height:18px; fill:currentColor; }
        .site-footer .social-label { position:absolute; left:-9999px; }
    </style>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-about">
                <div class="footer-brand">
                    <img src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                    <strong>SILERA</strong>
                </div>
                <p>SILERA adalah sistem informasi digital yang dikelola oleh Balai Bahasa Provinsi Riau untuk mendukung kemajuan literasi di Provinsi Riau.</p>
                <div class="social-links" aria-label="Media sosial">
                    <a class="social-btn" href="#" aria-label="Facebook" title="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.2 8.2V6.9c0-.6.4-.9 1-.9h1.6V3.2c-.8-.1-1.7-.2-2.5-.2-2.6 0-4.4 1.6-4.4 4.5v.7H7.2v3.1h2.7V21h3.4v-9.7h2.7l.4-3.1h-3.1Z"/></svg>
                    </a>
                    <a class="social-btn" href="#" aria-label="TikTok" title="TikTok">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.8 3h3.1c.2 1.4 1 2.7 2.1 3.5.7.5 1.4.8 2.2.9v3.2c-1.5-.1-2.9-.6-4.1-1.4v5.9c0 3.4-2.7 5.9-6.1 5.9-3.1 0-5.6-2.2-5.9-5.1-.3-3.5 2.4-6.4 5.9-6.4.4 0 .8 0 1.1.1v3.4c-.4-.1-.8-.2-1.2-.2-1.6 0-2.8 1.3-2.6 2.9.1 1.2 1.1 2.1 2.3 2.2 1.5.1 2.8-1.1 2.8-2.6V3Z"/></svg>
                    </a>
                    <a class="social-btn" href="#" aria-label="Instagram" title="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 3h8.4C18.8 3 21 5.2 21 7.8v8.4c0 2.6-2.2 4.8-4.8 4.8H7.8C5.2 21 3 18.8 3 16.2V7.8C3 5.2 5.2 3 7.8 3Zm0 3C6.8 6 6 6.8 6 7.8v8.4c0 1 .8 1.8 1.8 1.8h8.4c1 0 1.8-.8 1.8-1.8V7.8c0-1-.8-1.8-1.8-1.8H7.8Zm8.9.8a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2ZM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm0 2.7a1.3 1.3 0 1 0 0 2.6 1.3 1.3 0 0 0 0-2.6Z"/></svg>
                    </a>
                    <a class="social-btn" href="#" aria-label="YouTube" title="YouTube">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2c-.2-1-1-1.8-2-2C17.8 4.8 12 4.8 12 4.8s-5.8 0-7.6.4c-1 .2-1.8 1-2 2C2 9 2 12 2 12s0 3 .4 4.8c.2 1 1 1.8 2 2 1.8.4 7.6.4 7.6.4s5.8 0 7.6-.4c1-.2 1.8-1 2-2C22 15 22 12 22 12s0-3-.4-4.8ZM10 14.8V9.2l5.2 2.8L10 14.8Z"/></svg>
                    </a>
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
            <p>&copy; 2024 SILERA - Sistem Informasi Komunitas Literasi Riau, Balai Bahasa Provinsi Riau.</p>
        </div>
    </footer>
</body>
</html>
