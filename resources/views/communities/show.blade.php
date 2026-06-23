<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $community->community_name }} - Profil Komunitas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .community-profile-hero { display:flex; gap:1.25rem; align-items:center; margin-bottom:1.75rem; }
        .community-logo-large img, .community-logo-large span { width:120px; height:120px; display:inline-block; border-radius:8px; object-fit:cover; background:#f3f4f6; font-size:48px; color:#111827; display:flex; align-items:center; justify-content:center; }
        .community-meta h1 { margin:0 0 .25rem; }
        .community-meta .community-admin { margin:0; color:#6b7280; }
        .community-details { margin-top:1.5rem; display:grid; gap:1rem; }
        .community-detail-card { background: #ffffff; padding:1rem 1.25rem; border-radius:8px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .community-detail-card h2 { margin:0 0 .5rem; font-size:1.125rem; }
        .community-detail-card p { margin:0; line-height:1.8; color:#374151; white-space:pre-wrap; }
        /* Khusus untuk Latar Belakang: jarak paragraf sedikit lebih rapat */
        .background-card p { line-height:1.5; }
        @media (max-width: 768px) { .community-profile-hero { flex-direction:column; align-items:flex-start; } .community-logo-large img, .community-logo-large span { width:96px; height:96px; font-size:36px; } }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container nav-shell">
            <a href="{{ route('home') }}" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <img class="silera-logo"src="{{ asset('images/logosilera.jpeg') }}"alt="Logo SILERA">
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('communities.index') }}">Komunitas</a>
                <a href="{{ route('home') }}#informasi">Informasi</a>
                <a href="{{ route('home') }}#tentang">Tentang Kami</a>
            </nav>
        </div>
    </header>

    <main class="community-profile-page">
        <section class="section">
            <div class="container">
                <article class="community-profile-hero">
                    <div class="community-logo-large">
                        @if ($community->logo_path)
                            <img src="{{ asset('storage/'.$community->logo_path) }}" alt="Logo {{ $community->community_name }}">
                        @else
                            <span>{{ strtoupper(substr($community->community_name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div class="community-meta">
                        <h1>{{ $community->community_name }}</h1>
                        <p class="community-admin">{{ $community->name }} &mdash; {{ $community->position }}</p>
                    </div>
                </article>

                <section class="community-details">
                    @php
                        $vision = $community->vision;
                        $mission = $community->mission;
                        if ((empty($vision) || empty($mission)) && $community->vision_mission) {
                            $parts = preg_split('/\r\n|\r|\n/', $community->vision_mission);
                            $vision = $vision ?: ($parts[0] ?? null);
                            $mission = $mission ?: ($parts[1] ?? null);
                        }
                    @endphp

                    <div class="community-detail-card" aria-labelledby="vision-heading">
                        <h2 id="vision-heading">Visi</h2>
                        <p>{!! nl2br(e($vision ?? '-')) !!}</p>
                    </div>

                    <div class="community-detail-card" aria-labelledby="mission-heading">
                        <h2 id="mission-heading">Misi</h2>
                        <p>{!! nl2br(e($mission ?? '-')) !!}</p>
                    </div>

                    <div class="community-detail-card background-card" aria-labelledby="background-heading">
                        <h2 id="background-heading">Latar Belakang</h2>
                        <p>{!! nl2br(e($community->background ?? '-')) !!}</p>
                    </div>
                </section>
            </div>
        </section>
    </main>
</body>
</html>
