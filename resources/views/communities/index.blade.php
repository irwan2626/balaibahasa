<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Komunitas Literasi - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('layouts.navbar')

    <main class="community-directory-page">
        <section class="section community-directory-hero">
            <div class="container">
                <div class="section-heading split-heading">
                    <div>
                        <h1>Daftar Komunitas Literasi</h1>
                        <p>Telusuri komunitas literasi yang sudah terdaftar di SILERA.</p>
                    </div>
                    <form class="search-field" action="{{ route('communities.index') }}" method="GET">
                        <span aria-hidden="true">⌕</span>
                        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama komunitas...">
                    </form>
                </div>

                @if ($search !== '')
                    <p class="search-result-note">
                        Hasil pencarian untuk <strong>{{ $search }}</strong>: {{ $communities->total() }} komunitas.
                    </p>
                @endif

                <div class="community-grid">
                    @forelse ($communities as $community)
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
                            <h3>Komunitas tidak ditemukan.</h3>
                            <p>Coba gunakan kata kunci lain atau hapus pencarian.</p>
                        </article>
                    @endforelse
                </div>

                <div class="pagination-row">
                    {{ $communities->links() }}
                </div>
            </div>
        </section>
    </main>
</body>
</html>
