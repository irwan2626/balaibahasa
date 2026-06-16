<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $community->community_name }} - Profil Komunitas</title>
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
                    <h2>Visi</h2>
                    <p>{{ $community->vision ?? ($community->vision_mission ? explode('\n', $community->vision_mission)[0] : '-') }}</p>

                    <h2>Misi</h2>
                    <p>{{ $community->mission ?? ($community->vision_mission ? explode('\n', $community->vision_mission)[1] ?? '-' : '-') }}</p>

                    <h2>Latar Belakang</h2>
                    <p>{{ $community->background ?? '-' }}</p>
                </section>
            </div>
        </section>
    </main>
</body>
</html>
