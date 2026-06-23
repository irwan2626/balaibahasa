<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Admin - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Responsive admin layout (hamburger + sidebar)
           Fix: remove large gap by not using a fixed margin-left on the main content.
           Use CSS grid for desktop (240px sidebar + content) so the gap is only the sidebar width.
        */
        .dashboard-layout { display:flex; min-height:100vh; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; }
        .dashboard-main { flex:1 1 auto; min-width:0; margin-left:0; box-sizing:border-box; padding:1rem 1.25rem; }
        .dashboard-sidebar-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:100002 }
        .dashboard-sidebar-overlay.visible { opacity:1; pointer-events:auto; }
        .dashboard-topbar { position:relative; }

        /* Desktop: use grid so content sits directly to the right of the sidebar without extra gap */
        @media (min-width:1001px) {
            .dashboard-layout { display:grid; grid-template-columns:240px 1fr; }
            .dashboard-sidebar { position:static; height:auto; transform:none; box-shadow:none; }
            .dashboard-main { margin-left:0; padding:1.25rem 1.5rem; }
            .sidebar-toggle { display:none; }
        }

        @media (max-width:1000px) {
            .dashboard-sidebar { position:fixed; left:0; top:0; height:100vh; transform:translateX(-120%); z-index:100030; background:#fff; box-shadow:4px 0 22px rgba(0,0,0,0.08); width:260px; }
            .dashboard-layout.sidebar-open .dashboard-sidebar { transform:translateX(0); }
            .dashboard-topbar { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 1rem; }
            .dashboard-topbar .topbar-left { display:flex; align-items:center; gap:0.75rem; width:100%; }
            .dashboard-topbar .topbar-title { flex:1 1 auto; text-align:center; }
            .dashboard-user { display:none; }
            .sidebar-toggle { position:absolute; left:12px; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; gap:0.5rem; padding:.35rem .5rem; border-radius:8px; background:transparent; border:1px solid rgba(0,0,0,0.04); z-index:100040; }
            .dashboard-main { margin-left:0; padding:0 1rem; }
        }

        /* Form niceties on small screens */
        .admin-form-card { padding-bottom:1rem; }
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
                <a href="#">Pengaturan</a>
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
                        <span class="dashboard-kicker">User Admin</span>
                        <h1>Tambah Akun Admin</h1>
                    </div>
                </div>
                <div class="dashboard-user">
                    <span>{{ auth()->user()->name }}</span>
                    <strong>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</strong>
                </div>
            </header>

            <main class="dashboard-content">
                <section class="dashboard-card admin-form-card">
                    <div class="card-heading">
                        <div>
                            <h2>Data Admin Baru</h2>
                            <p>Akun ini dapat masuk dan mengelola dashboard SILERA.</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}">Kembali</a>
                    </div>

                    <form class="admin-user-form" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <label class="modern-field">
                            <span>Nama Admin</span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Admin Balai Bahasa" required>
                            @error('name')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@silera.test" required>
                            @error('email')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <div class="admin-user-form-grid">
                            <label class="modern-field">
                                <span>Kata Sandi</span>
                                <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="modern-field">
                                <span>Konfirmasi Kata Sandi</span>
                                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                            </label>
                        </div>

                        <button class="modern-submit" type="submit">Simpan Admin <span aria-hidden="true">-&gt;</span></button>
                    </form>
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
                try { overlay.style.zIndex='100020'; sidebar.style.zIndex='100040'; } catch(e){}
                overlay.setAttribute('aria-hidden','false'); const first = findFirstFocusable(sidebar); if (first) first.focus();
            }

            function closeSidebar(){ layout.classList.remove('sidebar-open'); overlay.classList.remove('visible'); toggle.setAttribute('aria-expanded','false'); document.documentElement.style.overflow=''; try { overlay.setAttribute('aria-hidden','true'); overlay.style.zIndex=''; sidebar.style.zIndex=''; } catch(e){} toggle.focus(); }

            toggle.addEventListener('click', function(){ if (layout.classList.contains('sidebar-open')) closeSidebar(); else openSidebar(); });
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>
</body>
</html>
