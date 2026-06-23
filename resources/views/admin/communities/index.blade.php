<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Komunitas - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Mobile sidebar behavior reused from dashboard */
        .dashboard-layout { display:flex; min-height:100vh; gap:0; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; }
        /* ensure main area is shifted on desktop so sidebar does not overlap content */
        .dashboard-main { flex:1 1 auto; min-width:0; margin-left:240px; box-sizing:border-box; /* enable vertical scrolling inside main when content is long */ max-height:100vh; overflow:auto; -webkit-overflow-scrolling:touch; }
        .dashboard-sidebar-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:100002 }
        .dashboard-sidebar-overlay.visible { opacity:1; pointer-events:auto; }
        .dashboard-topbar { position:relative; }

        @media (max-width:1000px) {
            .dashboard-sidebar { position:fixed; left:0; top:0; height:100vh; transform:translateX(-120%); z-index:100010; background:#fff; box-shadow:4px 0 22px rgba(0,0,0,0.08); width:260px; }
            .dashboard-layout.sidebar-open .dashboard-sidebar { transform:translateX(0); }
            .dashboard-topbar { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 1rem; }
            .dashboard-topbar .topbar-left { display:flex; align-items:center; gap:0.75rem; width:100%; }
            .dashboard-topbar .topbar-title { flex:1 1 auto; text-align:center; }
            .dashboard-user { display:none; }
            .sidebar-toggle { position:absolute; left:12px; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; gap:0.5rem; padding:.35rem .5rem; border-radius:8px; background:transparent; border:1px solid rgba(0,0,0,0.04); z-index:100020; }
            /* On mobile make main area full width (no left margin) */
            .dashboard-main { margin-left: 0; padding: 0 1rem; overflow:auto; -webkit-overflow-scrolling:touch; }
        }
        @media (min-width:1001px) { .sidebar-toggle { display:none; } }

        /* Sidebar account block shown only on mobile */
        .sidebar-account { display:block; }
        @media (min-width:1001px) { .sidebar-account { display:none; } }

        /* Table responsiveness: allow horizontal scroll on small screens to avoid breaking layout */
        .admin-table-wrap { overflow:auto; -webkit-overflow-scrolling:touch; }
        .community-account-table { min-width:900px; width:100%; border-collapse:collapse; }
        .community-account-table td, .community-account-table th { white-space:nowrap; }

        /* Ensure sidebar sits above overlay when opened */
        .dashboard-sidebar { z-index:100020; }

        /* Desktop: use two-column grid so content sits directly to the right of the sidebar */
        @media (min-width:1001px) {
            .dashboard-layout { display: grid; grid-template-columns: 240px 1fr; align-items: start; gap: 0; }
            /* main should not use additional left margin when grid is used */
            .dashboard-main { margin-left: 0; overflow:auto; -webkit-overflow-scrolling:touch; }
            .dashboard-sidebar { position: relative; transform: none; }
            .dashboard-main .dashboard-content { padding: 1.25rem 1.5rem; max-width: none; }
            .dashboard-topbar .topbar-title { text-align: left; }
            .card-heading { max-width: none; }
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
                <a class="active" href="{{ route('admin.communities.index') }}">Komunitas</a>
                <a href="{{ route('admin.stories.index') }}">Cerita</a>
                <a href="{{ route('admin.users.index') }}">User</a>
            </nav>
            {{-- Sidebar account for mobile --}}
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
                        <span class="dashboard-kicker">Komunitas</span>
                        <h1>Daftar Akun Komunitas</h1>
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

                <section class="stat-grid" aria-label="Ringkasan akun komunitas">
                    <article class="stat-card">
                        <span class="stat-icon">K</span>
                        <p>Total Akun</p>
                        <strong>{{ $communities->total() }}</strong>
                        <small>Permohonan komunitas</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon sky">P</span>
                        <p>Menunggu</p>
                        <strong>{{ $summary['pending'] }}</strong>
                        <small>Perlu verifikasi admin</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon teal">A</span>
                        <p>Disetujui</p>
                        <strong>{{ $summary['approved'] }}</strong>
                        <small>Akun aktif</small>
                    </article>
                    <article class="stat-card">
                        <span class="stat-icon gold">R</span>
                        <p>Ditolak</p>
                        <strong>{{ $summary['rejected'] }}</strong>
                        <small>Pengajuan tidak valid</small>
                    </article>
                </section>

                <section class="dashboard-card admin-users-card">
                    <div class="card-heading">
                        <div>
                            <h2>Akun Komunitas Terdaftar</h2>
                            <p>Data berikut berasal dari form pembuatan akun komunitas pada halaman publik.</p>
                        </div>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table community-account-table">
                            <thead>
                                <tr>
                                    <th>Komunitas</th>
                                    <th>Pengelola</th>
                                    <th>Kontak</th>
                                    <th>Jabatan</th>
                                    <th>Status</th>
                                    <th>Daftar</th>
                                    <th>Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($communities as $community)
                                    <tr>
                                        <td>
                                            @if ($community->logo_path)
                                                <img class="table-logo" src="{{ asset('storage/'.$community->logo_path) }}" alt="Logo {{ $community->community_name }}">
                                            @else
                                                <span class="table-avatar">{{ strtoupper(substr($community->community_name, 0, 1)) }}</span>
                                            @endif
                                            <div>
                                                <strong>{{ $community->community_name }}</strong>
                                                <small>ID #{{ $community->id }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $community->name }}</td>
                                        <td>
                                            <div class="community-contact">
                                                <span>{{ $community->email }}</span>
                                                <small>{{ $community->phone }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $community->position }}</td>
                                        <td>
                                            <span class="status-pill status-{{ $community->status }}">{{ $community->status }}</span>
                                        </td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($community->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="table-action-group">
                                                @if ($community->status !== 'approved')
                                                    <form action="{{ route('admin.communities.approve', $community) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="table-action approve" type="submit">Setujui</button>
                                                    </form>
                                                @endif
                                                @if ($community->status !== 'rejected')
                                                    <form action="{{ route('admin.communities.reject', $community) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="table-action reject" type="submit">Tolak</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.communities.destroy', $community) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun komunitas ini beserta seluruh artikelnya?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="table-action delete" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty-table">Belum ada akun komunitas yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-row">
                        {{ $communities->links() }}
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
