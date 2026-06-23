<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $story->title }} - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-story-page">
    <header class="site-header">
        <div class="container nav-shell">
            <a href="{{ route('home') }}" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <img class="silera-logo"src="{{ asset('images/logosilera.jpeg') }}"alt="Logo SILERA">>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ url('/#komunitas') }}">Komunitas</a>
                <a href="{{ url('/#informasi') }}">Informasi</a>
                <a href="{{ url('/#tentang') }}">Tentang Kami</a>
            </nav>

            @if (session('account_created'))
                <div class="nav-actions">
                    <a class="account-chip" href="{{ route('community-profile.show') }}" aria-label="Buka akun {{ session('account_name') }}">
                        <span>{{ strtoupper(substr(session('account_name', 'A'), 0, 1)) }}</span>
                    </a>
                </div>
            @else
                <div class="nav-actions">
                    <a class="btn btn-ghost" href="{{ route('community-login.create') }}">Masuk</a>
                </div>
            @endif
        </div>
    </header>

    <main>
        <article class="public-story-article">
            <header class="public-story-hero">
                <a class="back-link" href="{{ route('home') }}#komunitas">← Kembali ke cerita komunitas</a>
                <div class="public-story-meta">
                    <span class="chip">{{ $story->account?->community_name ?? 'Komunitas Literasi' }}</span>
                    <span>{{ $story->created_at->format('d M Y') }}</span>
                    <span>5 min baca</span>
                </div>
                <h1>{{ $story->title }}</h1>
                <p>Ditulis oleh {{ $story->author_name }} dari {{ $story->account?->community_name ?? 'komunitas literasi Riau' }}.</p>
            </header>

           <div class="public-story-cover">
                <img src="{{ $story->cover_photo_path ? asset('storage/'.$story->cover_photo_path) : asset('images/logobalai.png') }}"
                    alt="Cover cerita {{ $story->title }}">
            </div>

            @if ($story->photos->isNotEmpty())
                 <section class="story-photo-gallery" aria-label="Galeri foto kegiatan">
                    @foreach ($story->photos as $photo)
                        <figure>
                            <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Foto kegiatan {{ $story->title }}">
                        </figure>
                    @endforeach
                </section>
            @endif

            <div class="public-story-content">
                @foreach (preg_split('/\R{2,}/', $story->story) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </article>

        @if ($relatedStories->isNotEmpty())
            <section class="public-related-section">
                <div class="container">
                    <div class="section-heading">
                        <h2>Cerita Lainnya</h2>
                    </div>
                    <div class="news-grid">
                        @foreach ($relatedStories as $related)
                            <article class="news-card">
                                <a href="{{ route('stories.show', $related) }}">
                                    @if ($related->cover_photo_path)
                                        <img class="news-image" src="{{ asset('storage/'.$related->cover_photo_path) }}" alt="Cover cerita {{ $related->title }}">
                                    @else
                                        <div class="news-thumb"><span></span><span></span><span></span></div>
                                    @endif
                                    <div class="news-meta">{{ $related->created_at->format('d M Y') }} <span>•</span> 5 min baca</div>
                                    <h3>{{ $related->title }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($related->story, 120) }}</p>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>
</body>
</html>
