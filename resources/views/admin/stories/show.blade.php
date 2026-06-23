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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <style>
        /* Shared admin responsive layout */
        .dashboard-layout { display:flex; min-height:100vh; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; z-index:100020; }
        .dashboard-main { flex:1 1 auto; min-width:0; margin-left:240px; box-sizing:border-box; }
        .dashboard-sidebar-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:100010 }
        .dashboard-sidebar-overlay.visible { opacity:1; pointer-events:auto; }

        /* Constrain admin review image/slider size and center it */
        .admin-story-swiper-container {
            max-width: 760px;
            margin: 1rem auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        /* Fallback single image in article card */
        .story-article-card > img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        /* Slides image sizing */
        .admin-story-swiper .swiper-slide img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 768px) {
            .admin-story-swiper .swiper-slide img,
            .story-article-card > img {
                height: 200px;
            }

            .admin-story-swiper-container {
                margin: 0.75rem auto;
            }
        }

        /* Align the article body width with the slider for better balance */
        .story-article-body {
            max-width: 760px;
            margin-top: 0.75rem;
        }

        /* Story detail grid responsive: two-column on desktop, stacked on mobile */
        .story-detail-grid { display:grid; grid-template-columns: 1fr 360px; gap:1.25rem; align-items:start; }
        @media (max-width:1000px) {
            .dashboard-main { margin-left:0; padding:0 1rem; }
            .story-detail-grid { grid-template-columns: 1fr; }
            .dashboard-topbar { position:relative; display:flex; align-items:center; gap:0.75rem; padding:0.6rem 1rem; }
            .dashboard-topbar .topbar-left { display:flex; align-items:center; gap:0.75rem; width:100%; }
            .dashboard-topbar .topbar-title { flex:1 1 auto; text-align:center; }
            .dashboard-user { display:none; }
            .sidebar-toggle { position:absolute; left:12px; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; gap:0.5rem; padding:.35rem .5rem; border-radius:8px; background:transparent; border:1px solid rgba(0,0,0,0.04); z-index:100030; }
            .dashboard-sidebar { position:fixed; left:0; top:0; height:100vh; transform:translateX(-120%); background:#fff; box-shadow:4px 0 22px rgba(0,0,0,0.08); width:260px; }
            .dashboard-layout.sidebar-open .dashboard-sidebar { transform:translateX(0); }
        }
    </style>
</head>
<body class="dashboard-body">
    <div class="dashboard-layout" id="dashboardLayout">
        <aside id="dashboardSidebar" class="dashboard-sidebar" aria-label="Navigasi dashboard">
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
            {{-- Sidebar account (mobile) --}}
            <div class="sidebar-account" style="padding:1rem;border-top:1px solid #f1f2f4;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:44px;height:44px;border-radius:999px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-weight:600;color:#111827;">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div>
                        <div style="font-size:0.9rem;color:#6b7280">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin-top:0.75rem;">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="width:100%;padding:.55rem;border-radius:8px;border:1px solid #e6e9ef;background:#fff;color:#111827;">Keluar</button>
                </form>
            </div>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle" aria-controls="dashboardSidebar" aria-expanded="false" aria-label="Buka menu">☰</button>
                    <div class="topbar-title">
                        <span class="dashboard-kicker">Artikel Cerita</span>
                        <h1>Review Cerita Komunitas</h1>
                    </div>
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

                        {{-- Show uploaded photos as a slider for admin review; fallback to single cover image --}}
                        @if ($story->photos?->isNotEmpty())
                            <div class="admin-story-swiper-container story-swiper-container">
                                <div class="swiper admin-story-swiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($story->photos as $photo)
                                            <div class="swiper-slide">
                                                <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Foto {{ $loop->iteration }} - {{ $story->title }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="swiper-button-prev" aria-label="Sebelumnya"></div>
                                    <div class="swiper-button-next" aria-label="Selanjutnya"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        @else
                            <img src="{{ $story->cover_photo_path ? asset('storage/'.$story->cover_photo_path) : asset('images/logobalai.png') }}" alt="Foto cerita {{ $story->title }}">
                        @endif

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
                            {{-- Additional small photos removed in admin view; only cover image is shown on the story page --}}
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
    <div id="dashboardSidebarOverlay" class="dashboard-sidebar-overlay" aria-hidden="true"></div>

    <!-- Swiper JS (CDN) and initialization for admin story photos -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select the actual .swiper element used for slides
            const swiperEl = document.querySelector('.swiper.admin-story-swiper');
            if (!swiperEl) return;

            const nextBtn = swiperEl.querySelector('.swiper-button-next');
            const prevBtn = swiperEl.querySelector('.swiper-button-prev');
            const paginationEl = swiperEl.querySelector('.swiper-pagination');

            new Swiper(swiperEl, {
                loop: false,
                speed: 400,
                autoplay: false,
                pagination: {
                    el: paginationEl,
                    clickable: true,
                },
                navigation: {
                    nextEl: nextBtn,
                    prevEl: prevBtn,
                },
                keyboard: {
                    enabled: true,
                },
                slidesPerView: 1,
                spaceBetween: 10,
                breakpoints: {
                    768: {
                        slidesPerView: 1,
                    }
                }
            });
        });
    </script>
    <script>
        (function(){
            const layout = document.getElementById('dashboardLayout');
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('dashboardSidebarOverlay');

            if (!layout || !toggle || !sidebar || !overlay) return;

            function findFirstFocusable(container){ if (!container) return null; return container.querySelector('a, button, input, textarea, [tabindex]:not([tabindex="-1"])'); }

            function openSidebar(){ layout.classList.add('sidebar-open'); overlay.classList.add('visible'); toggle.setAttribute('aria-expanded','true'); document.documentElement.style.overflow='hidden'; try { if (overlay.parentNode !== document.body) document.body.appendChild(overlay); } catch(e){} const first = findFirstFocusable(sidebar); if (first) first.focus(); }
            function closeSidebar(){ layout.classList.remove('sidebar-open'); overlay.classList.remove('visible'); toggle.setAttribute('aria-expanded','false'); document.documentElement.style.overflow=''; toggle.focus(); }

            toggle.addEventListener('click', function(){ if (layout.classList.contains('sidebar-open')) closeSidebar(); else openSidebar(); });
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>
</body>
</html>
