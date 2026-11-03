<section class="home-showcase" data-cms-section="hero" @if (!($homeSections['hero']['visible'] ?? true)) hidden @endif data-home-carousel data-interval="7500" aria-label="GECO Africa projects and engineering services" aria-roledescription="carousel">
        <div class="home-showcase-track" data-home-track>
            @foreach ($heroSlides as $slide)
                <article class="home-showcase-slide @if ($loop->first) is-active @endif" data-home-slide aria-roledescription="slide" aria-label="Slide {{ $loop->iteration }} of {{ count($heroSlides) }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}" @if (!$loop->first) inert @endif>
                    <img class="home-showcase-image" src="{{ asset($slide['image']) }}" width="1920" height="1080" alt="{{ $slide['alt'] }}" decoding="async" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                    <div class="home-showcase-shade" aria-hidden="true"></div>
                    <div class="site-container home-showcase-content">
                        <p class="home-showcase-kicker"><span></span>{{ $slide['eyebrow'] }}</p>
                        @if ($loop->first)
                            <h1 class="home-showcase-title">{{ $slide['headline'] }}<br><em>{{ $slide['accentHeadline'] }}</em></h1>
                        @else
                            <h2 class="home-showcase-title">{{ $slide['headline'] }}<br><em>{{ $slide['accentHeadline'] }}</em></h2>
                        @endif
                        <p class="home-showcase-description">{{ $slide['description'] }}</p>
                        <div class="home-showcase-actions">
                            <a class="button button-brand" href="{{ $slide['primaryUrl'] }}">{{ $slide['primaryLabel'] }} <span aria-hidden="true">↗</span></a>
                            <a class="home-showcase-secondary" href="{{ $slide['secondaryUrl'] }}">{{ $slide['secondaryLabel'] }} <span aria-hidden="true">→</span></a>
                        </div>
                        <div class="home-showcase-caption"><span class="home-showcase-caption-mark" aria-hidden="true">GECO</span><span><strong>{{ $slide['caption'] }}</strong><small>{{ $slide['detail'] }}</small></span></div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="home-showcase-controls site-container">
            <div class="home-showcase-progress" aria-hidden="true"><span data-home-progress></span></div>
            <div class="home-showcase-control-row">
                <span class="home-showcase-count"><strong data-home-current>01</strong><span> / {{ str_pad((string) count($heroSlides), 2, '0', STR_PAD_LEFT) }}</span></span>
                <div class="home-showcase-dots" role="group" aria-label="Choose a featured slide">
                    @foreach ($heroSlides as $slide)
                        <button type="button" data-home-dot="{{ $loop->index }}" aria-label="Show slide {{ $loop->iteration }}: {{ $slide['caption'] }}" @if ($loop->first) aria-current="true" @endif></button>
                    @endforeach
                </div>
                <div class="home-showcase-buttons">
                    <button type="button" data-home-toggle aria-label="Pause slideshow" aria-pressed="false"><span data-home-toggle-icon aria-hidden="true">Ⅱ</span></button>
                    <button type="button" data-home-prev aria-label="Previous slide"><span aria-hidden="true">←</span></button>
                    <button type="button" data-home-next aria-label="Next slide"><span aria-hidden="true">→</span></button>
                </div>
            </div>
        </div>
        <a class="home-showcase-scroll" href="#home-at-a-glance"><span>Scroll to explore</span><i aria-hidden="true"></i></a>
    </section>
