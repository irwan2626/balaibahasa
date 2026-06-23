<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $story->title }} - Review Cerita SILERA</title>
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
                    <span class="dashboard-kicker">Artikel Cerita</span>
                    <h1>Review Cerita Komunitas</h1>
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

                @if ($errors->any())
                    <div class="form-alert error">Mohon periksa komentar review.</div>
                @endif

                <div class="story-detail-grid">
                    <article class="story-article-card">
                        <a class="back-link" href="{{ route('admin.stories.index') }}">← Kembali ke daftar cerita</a>
                        <img src="{{ $story->cover_photo_path ? asset('storage/'.$story->cover_photo_path) : asset('images/logobalai.png') }}" alt="Foto cerita {{ $story->title }}">
                        <div class="story-article-body">
                            <div class="story-review-heading">
                                <span class="status-pill status-{{ $story->status }}">{{ $story->status }}</span>
                                <small>{{ $story->created_at->format('d M Y') }}</small>
                            </div>
                            <h2>{{ $story->title }}</h2>
                            <div class="story-review-meta">
                                <span>{{ $story->author_name }}</span>
                                <span>{{ $story->account?->community_name ?? 'Komunitas belum tercatat' }}</span>
                                <span>{{ $story->author_email }}</span>
                            </div>
                            <div class="story-article-content">
                                @foreach (preg_split('/\R{2,}/', $story->story) as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                            @if ($story->photos->isNotEmpty())
                                <section class="story-photo-gallery admin-story-photo-gallery" aria-label="Galeri foto cerita">
                                    @foreach ($story->photos as $photo)
                                        <figure>
                                            <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Foto cerita {{ $story->title }}">
                                        </figure>
                                    @endforeach
                                </section>
                            @endif
                        </div>
                    </article>

                    <aside class="story-review-panel">
                        <div class="card-heading">
                            <div>
                                <h2>Catatan Admin</h2>
                                <p>Tulis komentar perbaikan untuk pemilik cerita.</p>
                            </div>
                        </div>

                        <div class="story-review-actions stacked compact">
                            <a class="btn btn-secondary" href="{{ route('admin.stories.edit', $story) }}">Edit Teks Cerita</a>
                            <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" onsubmit="return confirm('Hapus cerita ini dari daftar cerita masuk?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus Cerita</button>
                            </form>
                        </div>

                        @if ($story->review_comment)
                            <div class="review-comment-box">
                                <strong>Komentar terakhir</strong>
                                <p>{{ $story->review_comment }}</p>
                                @if ($story->reviewed_at)
                                    <small>Direview {{ $story->reviewed_at->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                        @endif

                        <form class="review-comment-form" action="{{ route('admin.stories.comment', $story) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label class="modern-field">
                                <span>Komentar Review</span>
                                <textarea name="review_comment" rows="6" placeholder="Contoh: Mohon tambahkan lokasi kegiatan dan jumlah peserta.">{{ old('review_comment', $story->review_comment) }}</textarea>
                                @error('review_comment')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>
                            <button class="btn btn-secondary" type="submit">Simpan Komentar</button>
                        </form>

                        <div class="story-review-actions stacked">
                            @if ($story->status !== 'published')
                                <form action="{{ route('admin.stories.approve', $story) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="review_comment" value="{{ old('review_comment', $story->review_comment) }}">
                                    <button class="btn btn-primary" type="submit">Setujui Cerita</button>
                                </form>
                            @endif
                            @if ($story->status !== 'rejected')
                                <form action="{{ route('admin.stories.reject', $story) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label class="modern-field">
                                        <span>Komentar Perbaikan Saat Menolak</span>
                                        <textarea name="review_comment" rows="4" placeholder="Wajib diisi jika cerita ditolak.">{{ old('review_comment', $story->review_comment) }}</textarea>
                                    </label>
                                    <button class="btn btn-secondary" type="submit">Tolak dan Kirim Komentar</button>
                                </form>
                            @endif
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
