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
                    <span class="dashboard-kicker">Edit Cerita</span>
                    <h1>Perbarui Teks Cerita</h1>
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
