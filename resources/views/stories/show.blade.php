<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $story->title }} - SILERA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Swiper CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <style>
        /* Reduce slider area and center it; make images cropped and responsive */
        .public-story-cover.story-swiper-container {
            max-width: 900px;
            margin: 1.25rem auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        /* constrain image height and crop using object-fit */
        .story-swiper .swiper-slide img,
        .public-story-cover img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            display: block;
        }

        /* smaller on mobile to avoid large visual area */
        @media (max-width: 768px) {
            .story-swiper .swiper-slide img,
            .public-story-cover img {
                height: 220px;
            }
        }

        /* pagination positioning */
        .story-swiper .swiper-pagination {
            bottom: 8px;
        }

        /* keep article content width aligned with the slider */
        .public-story-article .public-story-content {
            max-width: 900px;
            margin: 1rem auto;
        }
    </style>
</head>
<body class="public-story-page">
@include('layouts.navbar')

    <main>
        <article class="public-story-article">
            <header class="public-story-hero">
                <a class="back-link" href="{{ route('home') }}#komunitas">← Kembali ke cerita komunitas</a>
                <div class="public-story-meta">
                    <span class="chip">{{ $story->account?->community_name ?? 'Komunitas Literasi' }}</span>
                    <span>{{ $story->created_at->format('d M Y') }}</span>
                    <span>5 min baca</span>
                </div>
                <h1>{{ $story->title }}</h1>
                <p>Ditulis oleh {{ $story->author_name }} dari {{ $story->account?->community_name ?? 'komunitas literasi Riau' }}.</p>
            </header>

            {{-- If there are multiple photos, show a responsive Swiper slider. Otherwise fall back to single cover image. --}}
            @if ($story->photos?->isNotEmpty())
                <div class="public-story-cover story-swiper-container">
                    <div class="swiper story-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($story->photos as $photo)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Foto {{ $loop->iteration }} dari {{ $story->title }}">
                                </div>
                            @endforeach
                        </div>

                        <!-- Navigation buttons removed for public story slider per request -->

                         <!-- Pagination dots -->
                         <div class="swiper-pagination" aria-hidden="false"></div>
                     </div>
                 </div>
             @else
                <div class="public-story-cover">
                    <img src="{{ $story->cover_photo_path ? asset('storage/'.$story->cover_photo_path) : asset('images/logobalai.png') }}"
                        alt="Cover cerita {{ $story->title }}">
                </div>
            @endif

            <div class="public-story-content">
                @foreach (preg_split('/\R{2,}/', $story->story) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </article>

        @if ($relatedStories->isNotEmpty())
            <section class="public-related-section">
                <div class="container">
                    <div class="section-heading">
                        <h2>Cerita Lainnya</h2>
                    </div>
                    <div class="news-grid">
                        @foreach ($relatedStories as $related)
                            <article class="news-card">
                                <a href="{{ route('stories.show', $related) }}">
                                    @if ($related->cover_photo_path)
                                        <img class="news-image" src="{{ asset('storage/'.$related->cover_photo_path) }}" alt="Cover cerita {{ $related->title }}">
                                    @else
                                        <div class="news-thumb"><span></span><span></span><span></span></div>
                                    @endif
                                    <div class="news-meta">{{ $related->created_at->format('d M Y') }} <span>•</span> 5 min baca</div>
                                    <h3>{{ $related->title }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($related->story, 120) }}</p>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    <!-- Swiper JS (CDN) and initialization -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperEl = document.querySelector('.swiper.story-swiper');
            if (!swiperEl) return;

            const paginationEl = swiperEl.querySelector('.swiper-pagination');

            // Slower autoplay to match homepage behavior
            const swiper = new Swiper(swiperEl, {
                loop: true,
                speed: 500, // transition duration in ms
                autoplay: {
                    delay: 3000, // time between slides
                    disableOnInteraction: false,
                },
                pagination: {
                    el: paginationEl,
                    clickable: true,
                },
                keyboard: {
                    enabled: true,
                },
                slidesPerView: 1,
                spaceBetween: 10,
                breakpoints: {
                    768: {
                        slidesPerView: 1,
                    }
                }
            });

            // Pause autoplay on hover, resume on leave
            swiperEl.addEventListener('mouseenter', function () {
                if (swiper.autoplay) swiper.autoplay.stop();
            });
            swiperEl.addEventListener('mouseleave', function () {
                if (swiper.autoplay) swiper.autoplay.start();
            });
        });
    </script>
</body>
</html>
