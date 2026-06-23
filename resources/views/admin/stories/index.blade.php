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
    <style>
        /* Mobile sidebar behavior */
        .dashboard-layout { display:flex; min-height:100vh; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; }
        /* on desktop keep main shifted to avoid overlap */
        .dashboard-main { flex:1 1 auto; min-width:0; margin-left:240px; box-sizing:border-box; /* allow vertical scroll when content is long */ max-height:100vh; overflow:auto; -webkit-overflow-scrolling:touch; }
        .dashboard-sidebar-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:100002 }
        .dashboard-sidebar-overlay.visible { opacity:1; pointer-events:auto; }
        .dashboard-topbar { position:relative; }

        @media (max-width:1000px) {
            .dashboard-sidebar { position:fixed; left:0; top:0; height:100vh; transform:translateX(-120%); z-index:100020; background:#fff; box-shadow:4px 0 22px rgba(0,0,0,0.08); width:260px; }
            .dashboard-layout.sidebar-open .dashboard-sidebar { transform:translateX(0); }
            .dashboard-topbar { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 1rem; }
            .dashboard-topbar .topbar-left { display:flex; align-items:center; gap:0.75rem; width:100%; }
            .dashboard-topbar .topbar-title { flex:1 1 auto; text-align:center; }
            .dashboard-user { display:none; }
            .sidebar-toggle { position:absolute; left:12px; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; gap:0.5rem; padding:.35rem .5rem; border-radius:8px; background:transparent; border:1px solid rgba(0,0,0,0.04); z-index:100030; }
            .dashboard-main { margin-left:0; padding:0 1rem; /* allow scrolling on mobile */ overflow:auto; -webkit-overflow-scrolling:touch; }
        }
        @media (min-width:1001px) { .sidebar-toggle { display:none; } }

        /* Desktop: use two-column grid so content sits directly to the right of the sidebar */
        @media (min-width:1001px) {
            .dashboard-layout { display: grid; grid-template-columns: 240px 1fr; align-items: start; gap: 0; }
            .dashboard-main { margin-left: 0; overflow:auto; -webkit-overflow-scrolling:touch; }
            .dashboard-sidebar { position: relative; transform: none; }
            .dashboard-main .dashboard-content { padding: 1.25rem 1.5rem; max-width: none; }
        }

        /* Make story list adapt: allow vertical stacking and ensure images scale */
        .story-review-list { display:flex; flex-direction:column; gap:1rem; }
        .story-review-item { display:flex; gap:1rem; align-items:flex-start; }
        .story-review-item img { width:140px; height:100px; object-fit:cover; border-radius:8px; }
        @media (max-width:600px) {
            .story-review-item { flex-direction:column; }
            .story-review-item img { width:100%; height:180px; }
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
                {{-- Pengaturan menu dihapus sesuai permintaan --}}
            </nav>
            {{-- Sidebar account removed on Cerita page as requested --}}
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle" aria-controls="dashboardSidebar" aria-expanded="false" aria-label="Buka menu">☰</button>
                    <div class="topbar-title">
                        <span class="dashboard-kicker">Review Cerita</span>
                        <h1>Cerita Komunitas</h1>
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
                                <img src="{{ $story->cover_photo_path ? asset('storage/'.$story->cover_photo_path) : asset('images/logobalai.png') }}" alt="Foto cerita {{ $story->title }}">
                                <div>
                                    <div class="story-review-heading">
                                        <span class="status-pill status-{{ $story->status }}">{{ $story->status }}</span>
                                        <small>{{ \Illuminate\Support\Carbon::parse($story->created_at)->format('d M Y') }}</small>
                                    </div>
                                    <h3><a href="{{ route('admin.stories.show', $story) }}">{{ $story->title }}</a></h3>
                                    <p>{{ \Illuminate\Support\Str::limit($story->story, 220) }}</p>
                                    <div class="story-review-meta">
                                        <span>{{ $story->author_name }}</span>
                                        <span>{{ $story->account?->community_name ?? 'Komunitas belum tercatat' }}</span>
                                        <span>{{ $story->author_email }}</span>
                                    </div>
                                    <div class="story-review-actions">
                                        <a class="btn btn-secondary" href="{{ route('admin.stories.show', $story) }}">Baca Detail</a>
                                        <a class="btn btn-secondary" href="{{ route('admin.stories.edit', $story) }}">Edit Teks</a>
                                        @if ($story->status !== 'published')
                                            <form action="{{ route('admin.stories.approve', $story->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-primary" type="submit">Setujui</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" onsubmit="return confirm('Hapus cerita ini dari daftar cerita masuk?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Hapus</button>
                                        </form>
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
    <div id="dashboardSidebarOverlay" class="dashboard-sidebar-overlay" aria-hidden="true"></div>

    <script>
        (function(){
            const layout = document.getElementById('dashboardLayout');
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('dashboardSidebarOverlay');

            if (!layout || !toggle || !sidebar || !overlay) return;

            function findFirstFocusable(container){
                if (!container) return null;
                return container.querySelector('a, button, input, textarea, [tabindex]:not([tabindex="-1"])');
            }

            function openSidebar(){
                layout.classList.add('sidebar-open');
                overlay.classList.add('visible');
                toggle.setAttribute('aria-expanded','true');
                document.documentElement.style.overflow = 'hidden';
                try { if (overlay.parentNode !== document.body) document.body.appendChild(overlay); } catch(e){}
                const first = findFirstFocusable(sidebar); if (first) first.focus();
            }

            function closeSidebar(){
                layout.classList.remove('sidebar-open');
                overlay.classList.remove('visible');
                toggle.setAttribute('aria-expanded','false');
                document.documentElement.style.overflow = '';
                toggle.focus();
            }

            toggle.addEventListener('click', function(){ if (layout.classList.contains('sidebar-open')) closeSidebar(); else openSidebar(); });
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>
</body>
</html>
