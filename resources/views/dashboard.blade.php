<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dashboard-body">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar" aria-label="Navigasi dashboard">
            <a href="{{ url('/') }}" class="dashboard-brand">
                <img class="dashboard-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
            </a>

            <nav class="dashboard-menu">
                <a class="active" href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.communities.index') }}">Komunitas</a>
                <a href="{{ route('admin.stories.index') }}">Cerita</a>
                <a href="{{ route('admin.users.index') }}">User</a>
                <a href="#">Pengaturan</a>
            </nav>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div>
                    <span class="dashboard-kicker">Dashboard</span>
                    <h1>Selamat datang di SILERA</h1>
                </div>
                <div class="dashboard-user">
                    <span>{{ auth()->user()->name }}</span>
                    <strong>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</strong>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Keluar</button>
                    </form>
                </div>
            </header>

            <main class="dashboard-content">
                <section class="welcome-panel">
                    <div>
                        <span class="eyebrow">Dashboard SILERA</span>
                        <h2>Ringkasan data terbaru</h2>
                        <p>Dashboard ini membaca data akun komunitas dan cerita yang masuk melalui sistem SILERA.</p>
                    </div>
                    <a class="btn btn-primary" href="{{ url('/') }}">Lihat Website</a>
                </section>

                <section class="stat-grid" aria-label="Ringkasan data">
                    <article class="stat-card">
                        <span class="stat-icon">K</span>
                        <p>Total Komunitas</p>
                        <strong>{{ $stats['communities'] }}</strong>
                        <small>Data komunitas tersimpan</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon sky">B</span>
                        <p>Cerita Tayang</p>
                        <strong>{{ $stats['published_stories'] }}</strong>
                        <small>Articles yang disetujui</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon gold">P</span>
                        <p>Akun Pending</p>
                        <strong>{{ $stats['pending_communities'] }}</strong>
                        <small>Menunggu verifikasi admin</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon teal">A</span>
                        <p>Cerita Review</p>
                        <strong>{{ $stats['submitted_stories'] }}</strong>
                        <small>Menunggu persetujuan</small>
                    </article>
                </section>

                <section class="dashboard-grid">
                    <article class="dashboard-card">
                        <div class="card-heading">
                            <div>
                                <h2>Cerita Terbaru</h2>
                                <p>Pembaruan terakhir dari cerita komunitas yang masuk.</p>
                            </div>
                            <a href="{{ route('admin.stories.index') }}">Lihat semua</a>
                        </div>
                        <div class="activity-list">
                            @forelse ($activities as $activity)
                                <div>
                                    <span></span>
                                    <p><strong>{{ $activity->account?->community_name ?? 'Komunitas Literasi' }}</strong> mengirim cerita {{ $activity->title }}.</p>
                                    <small>{{ $activity->created_at->translatedFormat('d M Y') }} - {{ $activity->status }}</small>
                                </div>
                            @empty
                                <div>
                                    <span></span>
                                    <p><strong>Belum ada cerita.</strong> Cerita komunitas akan tampil di sini.</p>
                                    <small>Menunggu data</small>
                                </div>
                            @endforelse
                        </div>
                    </article>

                    <article class="dashboard-card">
                        <div class="card-heading">
                            <div>
                                <h2>Status Sistem</h2>
                                <p>Perbandingan data komunitas dan cerita terbaru.</p>
                            </div>
                        </div>
                        <div class="progress-list">
                            <div>
                                <span>Komunitas Terdaftar</span>
                                <strong>{{ $stats['communities'] }}</strong>
                                <div><i style="width: {{ min($stats['communities'] * 10, 100) }}%"></i></div>
                            </div>
                            <div>
                                <span>Cerita Tayang</span>
                                <strong>{{ $stats['published_stories'] }}</strong>
                                <div><i style="width: {{ min($stats['published_stories'] * 10, 100) }}%"></i></div>
                            </div>
                            <div>
                                <span>Menunggu Review</span>
                                <strong>{{ $stats['submitted_stories'] }}</strong>
                                <div><i style="width: {{ min($stats['submitted_stories'] * 10, 100) }}%"></i></div>
                            </div>
                        </div>
                    </article>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
