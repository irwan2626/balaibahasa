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
    @include('layouts.navbar')

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
