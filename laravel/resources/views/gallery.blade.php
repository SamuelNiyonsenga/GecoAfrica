@extends('layouts.site')

@section('content')
<main id="main-content" class="gallery-page">
    <section class="gallery-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Gallery</span>
            </nav>

            <div class="gallery-hero-grid">
                <div class="gallery-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> Design · Fieldwork · Delivery</p>
                    <h1>A closer look at <em>the work.</em></h1>
                    <p>See the projects, field investigations and laboratory work behind GECO Africa’s engineering practice in Rwanda.</p>
                    <a class="button button-dark" href="#photo-collection">Explore the gallery <span aria-hidden="true">↓</span></a>
                    <div class="gallery-hero-proof"><span>GECO AFRICA · RWANDA</span><i aria-hidden="true"></i><span>PHOTOGRAPHIC ARCHIVE</span></div>
                </div>
                <figure class="gallery-featured-photo">
                    <img src="{{ asset('images/gallery/bg_13.jpg') }}" width="1200" height="800" alt="Drilling rig and investigation crew working at a project site in Rwanda" fetchpriority="high">
                    <figcaption><span>Field investigations</span><small>On site · GECO Africa project archive</small></figcaption>
                    <span class="gallery-featured-index" aria-hidden="true">01 / 42</span>
                </figure>
            </div>
        </div>
    </section>

    <section class="gallery-stats-strip" aria-label="Gallery overview">
        <div class="site-container gallery-stats-inner">
            <div><strong>{{ count($photos) }}</strong><span>Original photographs<br>and project images</span></div>
            <div><strong>{{ count($galleryFilters) - 1 }}</strong><span>Areas of work<br>to explore</span></div>
            <p>One archive. From early design studies to work on the ground.</p>
        </div>
    </section>

    <section class="gallery-collection-section" id="photo-collection">
        <div class="site-container">
            <div class="section-heading gallery-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> Project photography</p>
                    <h2>Real work. <em>Documented with purpose.</em></h2>
                </div>
                <p class="gallery-heading-aside">Browse GECO Africa’s original gallery images by discipline. Open any photograph to view it at full size and move through the collection.</p>
            </div>

            <div class="gallery-toolbar">
                <div class="gallery-filters" role="group" aria-label="Filter photographs by work area">
                    @foreach ($galleryFilters as $filter)
                        <button class="gallery-filter" type="button" data-gallery-filter="{{ $filter['id'] }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                            <span>{{ $filter['label'] }}</span><small>{{ $filter['id'] === 'all' ? count($photos) : $filter['count'] }}</small>
                        </button>
                    @endforeach
                </div>
                <p class="gallery-result-count" aria-live="polite"><strong data-gallery-count>{{ count($photos) }}</strong> photographs</p>
            </div>

            <div class="gallery-grid" data-gallery-grid>
                @foreach ($photos as $photo)
                    <article class="gallery-card" data-gallery-card data-category="{{ $photo['category'] }}" data-shape="{{ $photo['file'] === 'map.jpg' ? 'portrait' : 'landscape' }}">
                        <button class="gallery-card-image" type="button" data-gallery-open
                            data-gallery-src="{{ asset($photo['image']) }}"
                            data-gallery-alt="{{ $photo['alt'] }}"
                            data-gallery-title="{{ $photo['title'] }}"
                            data-gallery-caption="{{ $photo['caption'] }}"
                            data-gallery-category="{{ $photo['categoryLabel'] }}"
                            aria-label="Open photograph: {{ $photo['title'] }}">
                            <img src="{{ asset($photo['image']) }}" width="1000" height="700" alt="{{ $photo['alt'] }}" loading="lazy" decoding="async">
                            <span class="gallery-photo-number">{{ $photo['number'] }}</span>
                            <span class="gallery-zoom-label"><span aria-hidden="true">↗</span> View photograph</span>
                        </button>
                        <div class="gallery-card-info">
                            <p class="gallery-card-category">{{ $photo['categoryLabel'] }}</p>
                            <h3>{{ $photo['title'] }}</h3>
                            <details class="gallery-source-caption">
                                <summary>Original gallery caption</summary>
                                <p>{{ $photo['legacyCategory'] }}@if($photo['legacyTitle'] && $photo['legacyTitle'] !== $photo['legacyCategory']) · {{ $photo['legacyTitle'] }}@endif</p>
                            </details>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="gallery-empty-state" data-gallery-empty hidden>No photographs are listed in this category yet.</p>
            <p class="gallery-archive-note"><span aria-hidden="true">i</span><span>This collection carries forward all 41 photographs shown on GECO Africa’s former gallery page, plus the full-size photograph linked from its first gallery image.</span></p>
        </div>
    </section>

    <dialog class="gallery-lightbox" data-gallery-lightbox aria-label="Photograph viewer">
        <div class="gallery-lightbox-frame">
            <button class="gallery-lightbox-close" type="button" data-gallery-close aria-label="Close photograph viewer">×</button>
            <button class="gallery-lightbox-arrow gallery-lightbox-prev" type="button" data-gallery-prev aria-label="Previous photograph">‹</button>
            <figure class="gallery-lightbox-figure">
                <div class="gallery-lightbox-image-wrap"><img data-gallery-lightbox-image src="" alt=""></div>
                <figcaption>
                    <div><p data-gallery-lightbox-category></p><h2 data-gallery-lightbox-title></h2><span data-gallery-lightbox-caption></span></div>
                    <small data-gallery-lightbox-count></small>
                </figcaption>
            </figure>
            <button class="gallery-lightbox-arrow gallery-lightbox-next" type="button" data-gallery-next aria-label="Next photograph">›</button>
        </div>
    </dialog>

    <section class="gallery-contact-strip">
        <div class="site-container gallery-contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Building what comes next</p>
                <h2>Let’s bring good engineering <em>to your project.</em></h2>
            </div>
            <div>
                <p>Connect with GECO Africa about geotechnical investigation, engineering design and project supervision.</p>
                <a class="button button-bright" href="mailto:info@gecoafrica.com?subject=Project%20enquiry">Start a project enquiry <span aria-hidden="true">↗</span></a>
                <a class="gallery-phone-link" href="tel:+250788842990">+250 788 842 990</a>
            </div>
        </div>
    </section>
</main>
<script src="{{ asset('js/gallery.js') }}" defer></script>
@endsection
