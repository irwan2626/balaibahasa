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
                        <span class="eyebrow">Laravel Dashboard</span>
                        <h2>You're logged in!</h2>
                        <p>Ini adalah halaman dashboard bawaan bergaya Laravel yang sudah disesuaikan untuk kebutuhan awal sistem SILERA.</p>
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
                        <p>Berita Terbit</p>
                        <strong>{{ $stats['posts'] }}</strong>
                        <small>Informasi literasi</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon gold">P</span>
                        <p>Program Aktif</p>
                        <strong>{{ $stats['active_programs'] }}</strong>
                        <small>Direncanakan atau berjalan</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon teal">A</span>
                        <p>Kolaborator</p>
                        <strong>{{ $stats['members'] }}</strong>
                        <small>Anggota komunitas</small>
                    </article>
                </section>

                <section class="dashboard-grid">
                    <article class="dashboard-card">
                        <div class="card-heading">
                            <div>
                                <h2>Aktivitas Terbaru</h2>
                                <p>Pembaruan terakhir pada data komunitas literasi.</p>
                            </div>
                            <a href="#">Lihat semua</a>
                        </div>
                        <div class="activity-list">
                            @forelse ($activities as $activity)
                                <div>
                                    <span></span>
                                    <p><strong>{{ $activity->community_name }}</strong> mengirim laporan {{ $activity->title }}.</p>
                                    <small>{{ \Illuminate\Support\Carbon::parse($activity->activity_date)->translatedFormat('d M Y') }} - {{ $activity->status }}</small>
                                </div>
                            @empty
                                <div>
                                    <span></span>
                                    <p><strong>Belum ada aktivitas.</strong> Laporan komunitas akan tampil di sini.</p>
                                    <small>Menunggu data</small>
                                </div>
                            @endforelse
                        </div>
                    </article>

                    <article class="dashboard-card">
                        <div class="card-heading">
                            <div>
                                <h2>Progres Verifikasi</h2>
                                <p>Status kelengkapan data komunitas.</p>
                            </div>
                        </div>
                        <div class="progress-list">
                            <div>
                                <span>Profil Komunitas</span>
                                <strong>82%</strong>
                                <div><i style="width: 82%"></i></div>
                            </div>
                            <div>
                                <span>Dokumen Pendukung</span>
                                <strong>64%</strong>
                                <div><i style="width: 64%"></i></div>
                            </div>
                            <div>
                                <span>Publikasi Berita</span>
                                <strong>48%</strong>
                                <div><i style="width: 48%"></i></div>
                            </div>
                        </div>
                    </article>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
