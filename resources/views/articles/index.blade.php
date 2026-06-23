<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Articles - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container nav-shell">
            <a href="{{ route('home') }}" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <strong>SILERA</strong>
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('communities.index') }}">Komunitas</a>
                <a class="active" href="{{ route('articles.index') }}">Informasi</a>
                <a href="{{ route('home') }}#tentang">Tentang Kami</a>
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
                </div>
            @else
                <div class="nav-actions">
                    <a class="btn btn-ghost" href="{{ route('community-login.create') }}">Masuk</a>
                </div>
            @endif
        </div>
    </header>

    <main class="article-directory-page">
        <section class="section article-directory-hero">
            <div class="container">
                <div class="section-heading">
                    <h1>Articles Literasi Riau</h1>
                    <p>Kumpulan cerita komunitas literasi yang sudah disetujui dan diterbitkan oleh admin SILERA.</p>
                </div>

                <div class="news-grid">
                    @forelse ($articles as $article)
                        <article class="news-card">
                            <a href="{{ route('stories.show', $article) }}" aria-label="Baca artikel {{ $article->title }}">
                                @if ($article->cover_photo_path)
                                    <img class="news-image" src="{{ asset('storage/'.$article->cover_photo_path) }}" alt="Cover artikel {{ $article->title }}">
                                @else
                                    <div class="news-thumb">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                @endif
                                <div class="news-meta">{{ $article->created_at->translatedFormat('d M Y') }} <span>&bull;</span> 5 min baca</div>
                                <h3>{{ $article->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($article->story, 140) }}</p>
                            </a>
                        </article>
                    @empty
                        <article class="community-empty-state">
                            <h3>Belum ada articles yang tayang.</h3>
                            <p>Articles akan tampil di sini setelah cerita komunitas disetujui oleh admin.</p>
                        </article>
                    @endforelse
                </div>

                <div class="pagination-row">
                    {{ $articles->links() }}
                </div>
            </div>
        </section>
    </main>
</body>
</html>
