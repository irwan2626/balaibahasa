<header class="site-header">
    <style>
        /* Mobile nav styles (small, self-contained) */
        .mobile-nav-toggle { display: none; background: transparent; border: none; font-size: 1.25rem; }
        .mobile-nav-panel { position: fixed; top: 0; left: 0; height: 100vh; width: 80%; max-width: 320px; background: #fff; box-shadow: 4px 0 18px rgba(0,0,0,0.12); transform: translateX(-110%); transition: transform .28s ease; z-index: 99999; padding: 1.25rem; overflow-y: auto; }
        .mobile-nav-panel.open { transform: translateX(0); }
        .mobile-nav-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); opacity: 0; pointer-events: none; transition: opacity .2s ease; z-index: 99998; }
        .mobile-nav-overlay.visible { opacity: 1; pointer-events: auto; }
        .mobile-nav-panel .close-btn { background: transparent; border: none; font-size: 1.25rem; float: right; }
        .mobile-nav-panel nav a { display: block; padding: .6rem 0; border-bottom: 1px solid #f1f2f4; color: #111827; text-decoration: none; }
        .mobile-account-row { display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-top:1px solid #f1f2f4;margin-top:1rem; }
        .mobile-account-row img{width:44px;height:44px;object-fit:cover;border-radius:999px}

        /* Hide desktop main nav on small screens and show hamburger */
        @media (max-width: 880px) {
            /* force hide desktop nav links on mobile */
            .main-nav { display: none !important; visibility: hidden !important; height: 0 !important; overflow: hidden !important; }
            /* show hamburger toggle */
            .mobile-nav-toggle { display: inline-flex !important; align-items:center; gap:.5rem; }
            /* ensure nav actions remain compact */
            .nav-actions { gap: .5rem; }

            /* On mobile show left profile and hide right profile */
            .profile-left { display: flex !important; align-items:center; gap:0.6rem; margin-left:0.75rem; }
            .profile-right { display: none !important; }
         }

        /* Top navbar alignment improvements */
        .nav-shell {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.6rem 0;
        }

        .brand-group {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .brand-group .brand-logo { height: 44px; width: auto; display:block; }
        .brand-group .silera-logo { height: 36px; width: auto; display:block; }

        .main-nav { display: flex; gap: 1rem; align-items: center; }
        .main-nav a { color: inherit; text-decoration: none; padding: 0.35rem 0.45rem; }

        .nav-actions { display: inline-flex; align-items: center; gap: 0.75rem; }

        .account-chip { display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none; }
        .account-chip .account-name { display:inline-block; max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Profile positioning: show left on mobile, show right on desktop */
        .profile-left { display: none; }
        .profile-right { display: flex; align-items: center; gap: 0.5rem; }

        /* Desktop-only logout button next to profile-right */
        .desktop-logout { display: none; }
        @media (min-width: 881px) {
            .desktop-logout { display: inline-block; margin-left: 0.5rem; }
        }

        /* Make hamburger more prominent */
        .mobile-nav-toggle { display: none; background: #111827; color: #fff; border: none; padding: .5rem .75rem; border-radius: 10px; font-weight:600; cursor:pointer; }
        .mobile-nav-toggle .burger-icon { display:inline-block; margin-right:.5rem; }

        /* Ensure the mobile toggle stays aligned */
        .mobile-nav-toggle { margin-left: 0.5rem; }

        /* Slight adjustments for very small screens */
        @media (max-width:420px) {
            .brand-group .brand-logo { height:36px; }
            .brand-group .silera-logo { height:28px; }
            .account-chip .account-name { display:none; }
        }

        /* On desktop show right profile and hide left profile */
        @media (min-width: 881px) {
            .profile-left { display: none !important; }
            .profile-right { display: flex !important; }
        }
    </style>

    <div class="container nav-shell">
        <a href="{{ url('/') }}" class="brand-group">
            <img class="brand-logo" src="{{ asset('images/logobalai.png') }}" alt="Balai Bahasa Riau">
            <span class="brand-divider"></span>
            <img class="silera-logo" src="{{ asset('images/logosilera.jpeg') }}" alt="SILERA" width="40px" height="auto">
        </a>

        {{-- Left-side profile (visible on mobile) --}}
        @if(session('account_created'))
            @php
                $accountLogo = session('account_logo') ?: \App\Models\CommunityAccountRequest::query()
                    ->where('email', session('account_email'))
                    ->value('logo_path');
            @endphp
            <div class="profile-left">
                <a class="account-chip" href="{{ route('community-profile.show') }}" title="Buka profil {{ session('account_name') }}">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:999px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(0,0,0,0.04);">
                        @if($accountLogo)
                            <img src="{{ asset('storage/'.$accountLogo) }}" alt="Logo {{ session('account_name') }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                            <strong style="font-size:1rem;color:#111827">{{ strtoupper(substr(session('account_name','A'),0,1)) }}</strong>
                        @endif
                    </span>
                </a>
            </div>
        @endif

         <nav class="main-nav">
             <a href="{{ url('/') }}">Beranda</a>
             <a href="{{ url('/#komunitas') }}">Komunitas</a>
             <a href="{{ url('/#informasi') }}">Informasi</a>
             <a href="{{ url('/#tentang') }}">Tentang Kami</a>
         </nav>
 
         <div class="nav-actions">
             @if(session('account_created'))
                @php
                    $accountLogo = session('account_logo') ?: \App\Models\CommunityAccountRequest::query()
                        ->where('email', session('account_email'))
                        ->value('logo_path');
                @endphp
                <div class="profile-right">
                    <a class="account-chip" href="{{ route('community-profile.show') }}" title="Buka profil {{ session('account_name') }}">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:999px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(0,0,0,0.04);">
                            @if($accountLogo)
                                <img src="{{ asset('storage/'.$accountLogo) }}" alt="Logo {{ session('account_name') }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                                <strong style="font-size:1rem;color:#111827">{{ strtoupper(substr(session('account_name','A'),0,1)) }}</strong>
                            @endif
                        </span>
                    </a>
                    <form action="{{ route('community-login.destroy') }}" method="POST" class="desktop-logout" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="padding:0.35rem 0.6rem;border-radius:8px;border:1px solid #e6e9ef;background:#fff;color:#111827;">Keluar Komunitas</button>
                    </form>
                </div>
             @else
                <a class="btn btn-ghost" href="{{ route('community-login.create') }}">Masuk Komunitas</a>
             @endif
 
             @unless(auth()->check() || session('account_created'))
                {{-- Link 'Masuk Admin' dihapus sesuai permintaan --}}
             @endunless
 
            {{-- Hamburger on rightmost area --}}
            <button class="mobile-nav-toggle" id="mobileNavToggle" aria-controls="mobileNavPanel" aria-expanded="false" aria-label="Buka menu">
                <span class="burger-icon">☰</span>
                Menu
            </button>
         </div>
     </div>

    <!-- Mobile panel & overlay -->
    <div id="mobileNavPanel" class="mobile-nav-panel" aria-hidden="true">
        <button class="close-btn" aria-label="Tutup menu" id="mobileNavClose">✕</button>
        <nav aria-label="Navigasi utama (mobile)">
            <a href="{{ url('/') }}">Beranda</a>
            <a href="{{ url('/#komunitas') }}">Komunitas</a>
            <a href="{{ url('/#informasi') }}">Informasi</a>
            <a href="{{ url('/#tentang') }}">Tentang Kami</a>
        </nav>

        <div class="mobile-actions" style="margin-top:1rem;">
            @if(session('account_created'))
                @php
                    $accountLogo = session('account_logo') ?: \App\Models\CommunityAccountRequest::query()
                        ->where('email', session('account_email'))
                        ->value('logo_path');
                @endphp
                <div class="mobile-account-row">
                    @if($accountLogo)
                        <img src="{{ asset('storage/'.$accountLogo) }}" alt="Logo {{ session('account_name') }}">
                    @else
                        <div style="width:44px;height:44px;border-radius:999px;background:#f3f4f6;display:flex;align-items:center;justify-content:center">{{ strtoupper(substr(session('account_name','A'),0,1)) }}</div>
                    @endif
                    <div>
                        <div style="font-weight:600">{{ session('account_name') }}</div>
                        <div style="font-size:0.9rem;color:#6b7280">{{ session('account_email') }}</div>
                    </div>
                </div>

                <form action="{{ route('community-login.destroy') }}" method="POST" style="margin-top:0.75rem;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width:100%;padding:.6rem;border-radius:8px;border:1px solid #e6e9ef;background:#fff;color:#111827;">Keluar Komunitas</button>
                </form>
            @else
                <a class="btn btn-ghost" href="{{ route('community-login.create') }}" style="display:block;padding:.6rem;border-radius:8px;border:1px solid #f1f2f4;text-align:center;">Masuk Komunitas</a>
            @endif

            {{-- Admin actions removed from mobile panel by request --}}
         </div>
     </div>
    <div id="mobileNavOverlay" class="mobile-nav-overlay" tabindex="-1" aria-hidden="true"></div>

    <script>
        // Move mobile panel and overlay to document.body to avoid header stacking context
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var panel = document.getElementById('mobileNavPanel');
                var overlay = document.getElementById('mobileNavOverlay');
                if (panel && overlay && panel.parentNode !== document.body) {
                    document.body.appendChild(panel);
                    document.body.appendChild(overlay);
                }
            } catch (e) {
                console.warn('Failed to relocate mobile nav panel', e);
            }
        });
    </script>
    <script>
        (function(){
            const toggle = document.getElementById('mobileNavToggle');
            const panel = document.getElementById('mobileNavPanel');
            const closeBtn = document.getElementById('mobileNavClose');
            const overlay = document.getElementById('mobileNavOverlay');

            if (!toggle || !panel) return;

            function openPanel(){
                panel.classList.add('open');
                overlay.classList.add('visible');
                panel.setAttribute('aria-hidden','false');
                toggle.setAttribute('aria-expanded','true');
                // lock body scroll
                document.documentElement.style.overflow = 'hidden';
            }

            function closePanel(){
                panel.classList.remove('open');
                overlay.classList.remove('visible');
                panel.setAttribute('aria-hidden','true');
                toggle.setAttribute('aria-expanded','false');
                document.documentElement.style.overflow = '';
                toggle.focus();
            }

            toggle.addEventListener('click', function(){
                const expanded = toggle.getAttribute('aria-expanded') === 'true';
                if (expanded) { closePanel(); } else { openPanel(); }
            });

            closeBtn?.addEventListener('click', closePanel);
            overlay?.addEventListener('click', closePanel);

            // close with ESC
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closePanel(); });
        })();
    </script>
</header>