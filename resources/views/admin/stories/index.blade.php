<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Review Cerita - SILERA</title>
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
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.communities.index') }}">Komunitas</a>
                <a class="active" href="{{ route('admin.stories.index') }}">Cerita</a>
                <a href="{{ route('admin.users.index') }}">User</a>
                <a href="#">Pengaturan</a>
            </nav>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div>
                    <span class="dashboard-kicker">Review Cerita</span>
                    <h1>Cerita Komunitas</h1>
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
                @if (session('status'))
                    <div class="form-alert success">{{ session('status') }}</div>
                @endif

                <section class="stat-grid" aria-label="Ringkasan cerita">
                    <article class="stat-card">
                        <span class="stat-icon sky">M</span>
                        <p>Menunggu Review</p>
                        <strong>{{ $summary['submitted'] }}</strong>
                        <small>Cerita baru</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon teal">T</span>
                        <p>Tayang</p>
                        <strong>{{ $summary['published'] }}</strong>
                        <small>Muncul di halaman utama</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon gold">D</span>
                        <p>Ditolak</p>
                        <strong>{{ $summary['rejected'] }}</strong>
                        <small>Tidak ditayangkan</small>
                    </article>
                </section>

                <section class="dashboard-card admin-users-card">
                    <div class="card-heading">
                        <div>
                            <h2>Daftar Cerita Masuk</h2>
                            <p>Setujui cerita agar tampil di bagian Info Terkini Literasi Riau.</p>
                        </div>
                    </div>

                    <div class="story-review-list">
                        @forelse ($stories as $story)
                            <article class="story-review-item">
                                <img src="{{ $story->photo_path ? asset('storage/'.$story->photo_path) : asset('images/logobalai.png') }}" alt="Foto cerita {{ $story->title }}">
                                <div>
                                    <div class="story-review-heading">
                                        <span class="status-pill status-{{ $story->status }}">{{ $story->status }}</span>
                                        <small>{{ \Illuminate\Support\Carbon::parse($story->created_at)->format('d M Y') }}</small>
                                    </div>
                                    <h3>{{ $story->title }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($story->story, 220) }}</p>
                                    <div class="story-review-meta">
                                        <span>{{ $story->author_name }}</span>
                                        <span>{{ $story->community_name ?? 'Komunitas belum tercatat' }}</span>
                                        <span>{{ $story->author_email }}</span>
                                    </div>
                                    <div class="story-review-actions">
                                        @if ($story->status !== 'published')
                                            <form action="{{ route('admin.stories.approve', $story->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-primary" type="submit">Setujui</button>
                                            </form>
                                        @endif
                                        @if ($story->status !== 'rejected')
                                            <form action="{{ route('admin.stories.reject', $story->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-secondary" type="submit">Tolak</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="empty-table">Belum ada cerita komunitas yang masuk.</p>
                        @endforelse
                    </div>

                    <div class="pagination-row">
                        {{ $stories->links() }}
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
