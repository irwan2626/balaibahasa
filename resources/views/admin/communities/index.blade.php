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
</head>
<body class="dashboard-body">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar" aria-label="Navigasi dashboard">
            <a href="{{ url('/') }}" class="dashboard-brand">
                <img class="dashboard-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
            </a>

            <nav class="dashboard-menu">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a class="active" href="{{ route('admin.communities.index') }}">Komunitas</a>
                <a href="{{ route('admin.stories.index') }}">Cerita</a>
                <a href="{{ route('admin.users.index') }}">User</a>
                <a href="#">Pengaturan</a>
            </nav>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div>
                    <span class="dashboard-kicker">Komunitas</span>
                    <h1>Daftar Akun Komunitas</h1>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-table">Belum ada akun komunitas yang terdaftar.</td>
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
</body>
</html>
