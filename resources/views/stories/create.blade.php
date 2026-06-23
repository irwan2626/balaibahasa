<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambahkan Cerita - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="story-page">
    <header class="site-header">
        <div class="container nav-shell">
            <a href="{{ url('/') }}" class="brand-group" aria-label="Beranda SILERA">
                <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Kemendikdasmen Balai Bahasa Provinsi Riau">
                <span class="brand-divider"></span>
                <img class="silera-logo"src="{{ asset('images/logosilera.jpeg') }}"alt="Logo SILERA">
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/#komunitas') }}">Komunitas</a>
                <a href="{{ url('/#informasi') }}">Informasi</a>
                <a href="{{ url('/#tentang') }}">Tentang Kami</a>
            </nav>

            @if (session('account_created'))
             @php
                    $accountLogo = session('account_logo') ?: \App\Models\CommunityAccountRequest::query()
                        ->where('email', session('account_email'))
                        ->value('logo_path');
                @endphp
                <a class="account-chip" href="{{ route('community-profile.show') }}" aria-label="Buka akun {{ session('account_name') }}">

    @if($accountLogo)
        <img src="{{ asset('storage/'.$accountLogo) }}"
             alt="Logo akun {{ session('account_name') }}">
    @else
        <span>{{ strtoupper(substr(session('account_name', 'A'), 0, 1)) }}</span>
    @endif

</a>
            @endif
        </div>
    </header>

    <main class="story-shell">
        <section class="story-hero simple-hero" style="padding:1rem 0; text-align:center;">
            <div>
                <h1 style="margin:0 0 .5rem">Bagikan Kisah Literasi Anda</h1>
                <p style="margin:0;color:#6b7280">Form sederhana: unggah foto, berikan judul singkat, dan ceritakan pengalaman kegiatan.</p>
            </div>
        </section>

        <section class="story-form-card simple-form" style="max-width:720px;margin:1.25rem auto;padding:1rem;">
             @if (session('status'))
                 <div class="form-alert success">
                     {{ session('status') }}
                 </div>
             @endif

             @if ($errors->any())
                 <div class="form-alert error">
                     Mohon periksa kembali data cerita Anda.
                 </div>
             @endif

            <form class="story-form-simple" action="{{ route('community-stories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('stories._form')
             </form>
         </section>
     </main>

    <script>
        document.getElementById('photoInput')?.addEventListener('change', function(e){
            const files = Array.from(e.target.files || []);
            const preview = document.getElementById('photoPreview');
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
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.alt = file.name;
                    figure.appendChild(img);
                    preview.appendChild(figure);
                };
                reader.readAsDataURL(file);
            });

            preview.hidden = false;
        });
    </script>
</body>
</html>
