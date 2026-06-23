<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda Komunitas - {{ $account->community_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.navbar')

    <main class="community-home-page">
        <section class="container">
            <div class="community-header" style="display:flex;align-items:center;gap:1rem;margin:1.5rem 0">
                <div style="width:84px;height:84px;border-radius:8px;overflow:hidden;background:#f3f4f6;display:flex;align-items:center;justify-content:center">
                    @if ($account->logo_path)
                        <img src="{{ asset('storage/'.$account->logo_path) }}" alt="Logo {{ $account->community_name }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <strong style="font-size:28px">{{ strtoupper(substr($account->community_name,0,1)) }}</strong>
                    @endif
                </div>
                <div>
                    <h1 style="margin:0">{{ $account->community_name }}</h1>
                    <p style="margin:0;color:#6b7280">{{ $account->name }} &mdash; {{ $account->position }}</p>
                </div>
            </div>

            <section>
                <h2>Cerita Terpublikasi</h2>
                <div class="stories-grid">
                    @forelse ($stories as $story)
                        <article class="story-card">
                            <h3>{{ $story->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($story->story, 140) }}</p>
                            <a href="{{ route('stories.show', $story) }}">Baca</a>
                        </article>
                    @empty
                        <p>Belum ada cerita terpublikasi.</p>
                    @endforelse
                </div>
            </section>

            <section style="margin-top:1.25rem">
                <h2>Draft & Terkirim</h2>
                @if ($drafts->isNotEmpty())
                    <ul>
                        @foreach ($drafts as $d)
                            <li>{{ $d->title }} <small>({{ $d->status }})</small></li>
                        @endforeach
                    </ul>
                @else
                    <p>Tidak ada draft atau cerita terkirim.</p>
                @endif
            </section>
        </section>
    </main>
</body>
</html>
