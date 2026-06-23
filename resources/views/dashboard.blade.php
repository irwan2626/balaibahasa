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
    <style>
        /* Mobile-friendly sidebar: hidden by default, slide-in on open */
        .dashboard-layout { display:flex; min-height:100vh; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; }
        .dashboard-main { flex:1 1 auto; min-width:0; }

        /* overlay for mobile sidebar */
        .dashboard-sidebar-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:100002 }
        .dashboard-sidebar-overlay.visible { opacity:1; pointer-events:auto; }

        /* make topbar positioned so we can absolutely place toggle */
        .dashboard-topbar { position: relative; }

        @media (max-width:1000px) {
            .dashboard-sidebar {
                position:fixed; left:0; top:0; height:100vh; transform:translateX(-120%); z-index:100010; background:#fff; box-shadow:4px 0 22px rgba(0,0,0,0.08); width:260px;
            }
            .dashboard-layout.sidebar-open .dashboard-sidebar { transform:translateX(0); }

            /* topbar: keep hamburger and center the title */
            .dashboard-topbar { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 1rem; }
            .dashboard-topbar .topbar-left { display:flex; align-items:center; gap:0.75rem; width:100%; }
            .dashboard-topbar .topbar-title { flex:1 1 auto; text-align:center; }

            /* place hamburger closer to left edge */
            .sidebar-toggle { position:absolute; left:12px; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; gap:0.5rem; padding:.35rem .5rem; border-radius:8px; background:transparent; border:1px solid rgba(0,0,0,0.04); z-index:100020; }

            /* hide desktop user block on mobile */
            .dashboard-user { display:none; }
        }

        /* hide toggle on desktop */
        @media (min-width:1001px) { .sidebar-toggle { display:none; } }

        /* Sidebar account block styling */
        .sidebar-account { display:block; }
        @media (min-width:1001px) {
            /* hide account block in desktop sidebar since topbar already shows user */
            .sidebar-account { display:none; }
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
                <a class="active" href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.communities.index') }}">Komunitas</a>
                <a href="{{ route('admin.stories.index') }}">Cerita</a>
                <a href="{{ route('admin.users.index') }}">User</a>
                {{-- Pengaturan menu dihapus sesuai permintaan --}}
            </nav>
            {{-- Sidebar account / profile (visible in mobile sidebar) --}}
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
                        <span class="dashboard-kicker">Dashboard</span>
                        <h1>Selamat datang di SILERA</h1>
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
                toggle.setAttribute('aria-expanded', 'true');
                document.documentElement.style.overflow = 'hidden';
                // append overlay to body to avoid stacking context issues, keep sidebar in layout
                try { if (overlay.parentNode !== document.body) document.body.appendChild(overlay); } catch(e){}
                const first = findFirstFocusable(sidebar);
                if (first) first.focus();
            }

            function closeSidebar() {
                layout.classList.remove('sidebar-open');
                overlay.classList.remove('visible');
                toggle.setAttribute('aria-expanded','false');
                document.documentElement.style.overflow = '';
                toggle.focus();
            }

            toggle.addEventListener('click', function(){
                if (layout.classList.contains('sidebar-open')) closeSidebar(); else openSidebar();
            });

            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>
</body>
</html>
