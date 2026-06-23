<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Admin - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Responsive admin layout (shared pattern) */
        .dashboard-layout { display:flex; min-height:100vh; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; }
        .dashboard-main { flex:1 1 auto; min-width:0; margin-left:240px; box-sizing:border-box; /* ensure main can scroll if content is long */ max-height:100vh; overflow:auto; -webkit-overflow-scrolling:touch; }
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
            .dashboard-main { margin-left:0; padding:0 1rem; overflow:auto; -webkit-overflow-scrolling:touch; }
        }
        @media (min-width:1001px) { .sidebar-toggle { display:none; } }

        /* Desktop: use two-column grid so content sits directly to the right of the sidebar */
        @media (min-width:1001px) {
            .dashboard-layout { display: grid; grid-template-columns: 240px 1fr; align-items: start; gap: 0; }
            .dashboard-main { margin-left: 0; overflow:auto; -webkit-overflow-scrolling:touch; }
            .dashboard-sidebar { position: relative; transform: none; }
            .dashboard-main .dashboard-content { padding: 1.25rem 1.5rem; max-width: none; }
        }

        /* Make the primary action button more prominent in card headings */
        .card-heading { display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        /* Keep existing .btn-primary background from global styles; only force white text/icon for contrast */
        .card-heading .btn-primary { display:inline-flex; align-items:center; gap:0.6rem; padding:.6rem .9rem; border-radius:10px; font-weight:700; box-shadow:0 6px 18px rgba(13, 60, 100, 0.08); color:#ffffff; }
        .card-heading .btn-primary:hover { filter:brightness(.95); }
        .card-heading .btn-primary .btn-icon { display:inline-flex; width:20px; height:20px; align-items:center; justify-content:center; background:rgba(255,255,255,0.12); border-radius:6px; color:#ffffff; }
        @media (max-width:700px) { .card-heading { flex-direction:column; align-items:flex-start; } .card-heading .btn-primary { width:100%; justify-content:center; } }

        /* Table responsiveness: horizontal scroll on small screens */
        .admin-table-wrap { overflow:auto; -webkit-overflow-scrolling:touch; }
        .admin-table { min-width:640px; width:100%; }

        /* Hide sidebar-account on desktop (topbar shows user) */
        .sidebar-account { display:block; }
        @media (min-width:1001px) { .sidebar-account { display:none; } }
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
                <a href="{{ route('admin.stories.index') }}">Cerita</a>
                <a class="active" href="{{ route('admin.users.index') }}">User</a>
            </nav>
            {{-- Sidebar account for mobile (in-sidebar profile + logout) --}}
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
                        <span class="dashboard-kicker">User Admin</span>
                        <h1>Daftar User Dashboard</h1>
                    </div>
                </div>
                <div class="dashboard-user">
                    <span>{{ auth()->user()->name }}</span>
                    <strong>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</strong>
                </div>
            </header>

            <main class="dashboard-content">
                @if (session('status'))
                    <div class="form-alert success">{{ session('status') }}</div>
                @endif

                <section class="dashboard-card admin-users-card">
                    <div class="card-heading">
                        <div>
                            <h2>Admin Dashboard</h2>
                            <p>Akun berikut memiliki akses masuk ke dashboard SILERA.</p>
                        </div>
                        <a class="btn btn-primary" href="{{ route('admin.users.create') }}" aria-label="Tambah Admin Baru">
                            <span class="btn-icon">+</span>
                            <span>Tambah Admin</span>
                        </a>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Dibuat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <span class="table-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            {{ $user->name }}
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td><span class="status-pill">Admin</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-row">
                        {{ $users->links() }}
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

            function findFirstFocusable(container){ if (!container) return null; return container.querySelector('a, button, input, textarea, [tabindex]:not([tabindex="-1"])'); }

            function openSidebar(){
                layout.classList.add('sidebar-open'); overlay.classList.add('visible'); toggle.setAttribute('aria-expanded','true'); document.documentElement.style.overflow='hidden';
                try { if (overlay.parentNode !== document.body) document.body.appendChild(overlay); } catch(e){}
                const first = findFirstFocusable(sidebar); if (first) first.focus();
            }

            function closeSidebar(){ layout.classList.remove('sidebar-open'); overlay.classList.remove('visible'); toggle.setAttribute('aria-expanded','false'); document.documentElement.style.overflow=''; toggle.focus(); }

            toggle.addEventListener('click', function(){ if (layout.classList.contains('sidebar-open')) closeSidebar(); else openSidebar(); });
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>
</body>
</html>
