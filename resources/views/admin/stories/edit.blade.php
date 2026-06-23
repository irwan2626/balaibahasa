<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit {{ $story->title }} - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Responsive admin layout (shared pattern) */
        .dashboard-layout { display:flex; min-height:100vh; }
        .dashboard-sidebar { width:240px; flex:0 0 240px; transition:transform .28s ease; z-index:100050; }
        .dashboard-main { flex:1 1 auto; min-width:0; margin-left:240px; box-sizing:border-box; }
        .dashboard-sidebar-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); opacity:0; pointer-events:none; transition:opacity .2s ease; z-index:100040 }
        .dashboard-sidebar-overlay.visible { opacity:1; pointer-events:auto; }
        .dashboard-topbar { position:relative; }

        @media (max-width:1000px) {
            .dashboard-sidebar { position:fixed; left:0; top:0; height:100vh; transform:translateX(-120%); z-index:100030; background:#fff; box-shadow:4px 0 22px rgba(0,0,0,0.08); width:260px; }
            .dashboard-layout.sidebar-open .dashboard-sidebar { transform:translateX(0); }
            .dashboard-topbar { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 1rem; }
            .dashboard-topbar .topbar-left { display:flex; align-items:center; gap:0.75rem; width:100%; }
            .dashboard-topbar .topbar-title { flex:1 1 auto; text-align:center; }
            .dashboard-user { display:none; }
            .sidebar-toggle { position:absolute; left:12px; top:50%; transform:translateY(-50%); display:inline-flex; align-items:center; gap:0.5rem; padding:.35rem .5rem; border-radius:8px; background:transparent; border:1px solid rgba(0,0,0,0.04); z-index:100060; }
            .dashboard-main { margin-left:0; padding:0 1rem; }
        }

        /* Story edit specifics: stack sections on mobile */
        .story-edit-card, .story-photo-manager-card { margin-bottom:1rem; }
        @media (max-width:700px) {
            .story-edit-form textarea { min-height: 220px; }
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
                        <span class="dashboard-kicker">Edit Cerita</span>
                        <h1>Perbarui Teks Cerita</h1>
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
                    <div class="form-alert error">Mohon periksa kembali teks cerita.</div>
                @endif

                <section class="dashboard-card story-edit-card">
                    <div class="card-heading">
                        <div>
                            <h2>Edit Cerita Masuk</h2>
                            <p>Admin dapat merapikan judul dan isi cerita sebelum cerita disetujui tampil di halaman utama.</p>
                        </div>
                    </div>

                    <form class="story-edit-form" action="{{ route('admin.stories.update', $story) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <label class="modern-field">
                            <span>Judul Cerita</span>
                            <input type="text" name="title" value="{{ old('title', $story->title) }}" required>
                            @error('title')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <label class="modern-field">
                            <span>Isi Cerita</span>
                            <textarea name="story" rows="16" required>{{ old('story', $story->story) }}</textarea>
                            @error('story')
                                <small>{{ $message }}</small>
                            @enderror
                        </label>

                        <div class="story-review-meta">
                            <span>{{ $story->author_name }}</span>
                            <span>{{ $story->account?->community_name ?? 'Komunitas belum tercatat' }}</span>
                            <span>{{ $story->author_email }}</span>
                        </div>

                        <div class="story-review-actions">
                            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                            <a class="btn btn-secondary" href="{{ route('admin.stories.show', $story) }}">Batal</a>
                        </div>
                    </form>
                </section>

                <section class="dashboard-card story-photo-manager-card">
                    <div class="card-heading">
                        <div>
                            <h2>Foto Cerita</h2>
                            <p>Tambah, lihat, atau hapus foto kegiatan yang terhubung dengan cerita ini.</p>
                        </div>
                    </div>

                    @if ($story->photos->isNotEmpty())
                        <div class="admin-photo-grid">
                            @foreach ($story->photos as $photo)
                                <figure>
                                    <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Foto cerita {{ $story->title }}">
                                    <form action="{{ route('admin.stories.photos.destroy', [$story, $photo]) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari cerita?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Hapus Foto</button>
                                    </form>
                                </figure>
                            @endforeach
                        </div>
                    @else
                        <p class="empty-table">Belum ada foto tersimpan untuk cerita ini.</p>
                    @endif

                    <form class="story-photo-upload-form" action="{{ route('admin.stories.photos.store', $story) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="modern-field">
                            <span>Tambah Foto Kegiatan</span>
                            <input id="adminPhotoInput" class="file-input" type="file" name="photos[]" accept="image/png,image/jpeg" multiple>
                            <small class="field-help">Bisa memilih lebih dari satu foto. Format JPG, JPEG, atau PNG. Maksimal 2 MB per foto.</small>
                            @error('photos')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                            @error('photos.*')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </label>

                        <div id="adminPhotoPreview" class="story-photo-preview-grid" hidden></div>

                        <button class="btn btn-primary" type="submit">Tambah Foto</button>
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
                layout.classList.add('sidebar-open');
                overlay.classList.add('visible');
                toggle.setAttribute('aria-expanded','true');
                document.documentElement.style.overflow='hidden';
                try { if (overlay.parentNode !== document.body) document.body.appendChild(overlay); } catch(e){}
                // make sure overlay is below the sidebar and does not block it
                try {
                    overlay.style.zIndex = '100040';
                    sidebar.style.zIndex = '100060';
                } catch(e) {}
                overlay.setAttribute('aria-hidden','false');
                const first = findFirstFocusable(sidebar); if (first) first.focus();
            }

            function closeSidebar(){
                layout.classList.remove('sidebar-open');
                overlay.classList.remove('visible');
                toggle.setAttribute('aria-expanded','false');
                document.documentElement.style.overflow='';
                try { overlay.setAttribute('aria-hidden','true'); overlay.style.zIndex = ''; sidebar.style.zIndex = ''; } catch(e){}
                toggle.focus();
            }

            toggle.addEventListener('click', function(){ if (layout.classList.contains('sidebar-open')) closeSidebar(); else openSidebar(); });
            overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>

    <script>
        document.getElementById('adminPhotoInput')?.addEventListener('change', function(e) {
            const files = Array.from(e.target.files || []);
            const preview = document.getElementById('adminPhotoPreview');
            preview.innerHTML = '';

            if (!files.length) {
                preview.hidden = true;
                return;
            }

            files.forEach(function(file) {
                if (!file.type.startsWith('image/')) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(ev) {
                    const figure = document.createElement('figure');
                    const image = document.createElement('img');
                    image.src = ev.target.result;
                    image.alt = file.name;
                    figure.appendChild(image);
                    preview.appendChild(figure);
                };
                reader.readAsDataURL(file);
            });

            preview.hidden = false;
        });
    </script>
</body>
</html>
