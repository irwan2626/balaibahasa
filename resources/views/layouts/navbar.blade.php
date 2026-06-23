<header class="site-header">
    <div class="container nav-shell">
        <a href="{{ url('/') }}" class="brand-group">
            <img class="brand-logo" src="{{ asset('images/logobalai.png') }}">
            <span class="brand-divider"></span>
            <img class="silera-logo"src="{{ asset('images/logosilera.jpeg') }}"alt="Logo SILERA">
        </a>

        <nav class="main-nav">
            <a href="{{ url('/') }}">Beranda</a>
            <a href="{{ url('/#komunitas') }}">Komunitas</a>
            <a href="{{ url('/#informasi') }}">Informasi</a>
            <a href="{{ url('/#tentang') }}">Tentang Kami</a>
        </nav>

        @if(session('account_created'))
            @php
                $accountLogo = session('account_logo') ?: \App\Models\CommunityAccountRequest::query()
                    ->where('email', session('account_email'))
                    ->value('logo_path');
            @endphp

            <div class="nav-actions">
                <a class="account-chip" href="{{ route('community-profile.show') }}">
                    @if($accountLogo)
                        <img src="{{ asset('storage/'.$accountLogo) }}">
                    @else
                        <span>{{ strtoupper(substr(session('account_name','A'),0,1)) }}</span>
                    @endif
                </a>
            </div>
        @endif
    </div>
</header>