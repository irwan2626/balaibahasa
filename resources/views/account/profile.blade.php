<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Komunitas - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="community-profile-page">
@include('layouts.navbar')

    <main class="community-profile-shell">
        <section class="community-profile-hero">
            <div class="community-profile-logo">
                @if ($account->logo_path)
                    <img src="{{ asset('storage/'.$account->logo_path) }}" alt="Logo {{ $account->community_name }}">
                @else
                    <span>{{ strtoupper(substr($account->community_name, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <span class="dashboard-kicker">Dashboard Komunitas</span>
                <h1>{{ $account->community_name }}</h1>
                <p>{{ $account->name }} · {{ $account->position }} · {{ $account->email }}</p>
            </div>
            <a class="btn btn-primary" href="{{ route('community-stories.create') }}">Tambahkan Cerita</a>
        </section>

        @if (session('status'))
            <div class="form-alert success">{{ session('status') }}</div>
        @endif

        <section class="stat-grid" aria-label="Ringkasan cerita komunitas">
            <article class="stat-card">
                <span class="stat-icon sky">M</span>
                <p>Menunggu Review</p>
                <strong>{{ $storyStats['submitted'] }}</strong>
                <small>Cerita sedang dikurasi</small>
            </article>
            <article class="stat-card">
                <span class="stat-icon teal">T</span>
                <p>Tayang</p>
                <strong>{{ $storyStats['published'] }}</strong>
                <small>Tampil di halaman utama</small>
            </article>
            <article class="stat-card">
                <span class="stat-icon gold">D</span>
                <p>Ditolak</p>
                <strong>{{ $storyStats['rejected'] }}</strong>
                <small>Perlu perbaikan</small>
            </article>
        </section>

        <section class="community-profile-grid">
            <article class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <h2>Informasi Akun</h2>
                        <p>Data profil komunitas yang tersimpan di SILERA.</p>
                    </div>
                </div>
                <dl class="profile-info-list">
                    <div><dt>Nama Pengelola</dt><dd>{{ $account->name }}</dd></div>
                    <div><dt>Nama Komunitas</dt><dd>{{ $account->community_name }}</dd></div>
                    <div><dt>Jabatan</dt><dd>{{ $account->position }}</dd></div>
                    <div><dt>Visi</dt><dd>{{ $account->vision ?: ($account->vision_mission ?: '-') }}</dd></div>
                    <div><dt>Misi</dt><dd>{{ $account->mission ?: '-' }}</dd></div>
                    <div><dt>Latar Belakang</dt><dd>{{ $account->background ?: '-' }}</dd></div>
                    <div><dt>Telepon</dt><dd>{{ $account->phone }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $account->email }}</dd></div>
                    <div><dt>Status</dt><dd><span class="status-pill status-{{ $account->status }}">{{ $account->status }}</span></dd></div>
                </dl>
            </article>

            <article class="dashboard-card">
                <div class="card-heading">
                    <div>
                        <h2>Pengaturan Profil</h2>
                        <p>Perbarui informasi dasar komunitas Anda.</p>
                    </div>
                </div>
                <form class="profile-settings-form" action="{{ route('community-profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <label class="modern-field">
                        <span>Nama Pengelola</span>
                        <input type="text" name="name" value="{{ old('name', $account->name) }}" required>
                    </label>
                    <label class="modern-field">
                        <span>Nama Komunitas</span>
                        <input type="text" name="community_name" value="{{ old('community_name', $account->community_name) }}" required>
                    </label>
                    <label class="modern-field">
                        <span>Jabatan</span>
                        <input type="text" name="position" value="{{ old('position', $account->position) }}" required>
                    </label>
                    <label class="modern-field">
                        <span>Visi Komunitas</span>
                        <textarea name="vision" rows="4" required>{{ old('vision', $account->vision ?: $account->vision_mission) }}</textarea>
                    </label>
                    <label class="modern-field">
                        <span>Misi Komunitas</span>
                        <textarea name="mission" rows="5" required>{{ old('mission', $account->mission) }}</textarea>
                    </label>
                    <label class="modern-field">
                        <span>Latar Belakang Komunitas</span>
                        <textarea name="background" rows="5" required>{{ old('background', $account->background) }}</textarea>
                    </label>
                    <label class="modern-field">
                        <span>Nomor Telepon</span>
                        <input type="tel" name="phone" value="{{ old('phone', $account->phone) }}" required>
                    </label>
                    <label class="modern-field">
                        <span>Ganti Logo Komunitas</span>
                        <input class="file-input" type="file" name="logo" accept="image/png,image/jpeg,image/webp">
                    </label>
                     
                     <button class="modern-submit" type="submit">Simpan Pengaturan <span aria-hidden="true">-&gt;</span></button>
                 </form>
             </article>
         </section>

        <section class="dashboard-card">
            <div class="card-heading">
                <div>
                    <h2>Riwayat Cerita</h2>
                    <p>Lima cerita terbaru dari komunitas Anda.</p>
                </div>
            </div>
            <div class="profile-story-list">
                @forelse ($stories as $story)
                    <article>
                        <div class="profile-story-row">
                            <strong>{{ $story->title }}</strong>
                            <span class="status-pill status-{{ $story->status }}">{{ $story->status }}</span>
                            <small>{{ $story->created_at->format('d M Y') }}</small>
                        </div>
                        @if ($story->review_comment)
                            <div class="community-review-comment">
                                <strong>Komentar Admin</strong>
                                <p>{{ $story->review_comment }}</p>
                                @if ($story->reviewed_at)
                                    <small>Direview pada {{ $story->reviewed_at->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="empty-table">Belum ada cerita yang dikirim.</p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
