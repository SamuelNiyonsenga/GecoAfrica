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
                    <div class="gallery-hero-proof"><span>GECO AFRICA · RWANDA</span><i aria-hidden="true"></i><span>PROJECT PHOTOGRAPHY</span></div>
                </div>
                <figure class="gallery-featured-photo">
                    <img src="{{ asset('images/gallery/bg_13.jpg') }}" width="1500" height="844" alt="Drilling rig and investigation crew working at a project site in Rwanda" fetchpriority="high" decoding="async">
                    <figcaption><span>Field investigations</span><small>On site · GECO Africa projects</small></figcaption>
                    <span class="gallery-featured-index" aria-hidden="true">01 / 42</span>
                </figure>
            </div>
        </div>
    </section>

    <section class="gallery-stats-strip" aria-label="Gallery overview">
        <div class="site-container gallery-stats-inner">
                <div><strong>{{ count($photos) }}</strong><span>GECO Africa<br>project photographs</span></div>
            <div><strong>{{ count($galleryFilters) - 1 }}</strong><span>Areas of work<br>to explore</span></div>
            <p>GECO Africa’s photography spans early design studies and work on the ground.</p>
        </div>
    </section>

    <section class="gallery-collection-section" id="photo-collection">
        <div class="site-container">
            <div class="section-heading gallery-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> Project photography</p>
                    <h2>Real work. <em>Documented with purpose.</em></h2>
                </div>
                <p class="gallery-heading-aside">Browse GECO Africa’s project photographs by discipline. Open any photograph to view it at full size and move through the collection.</p>
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
                    <article class="gallery-card" data-gallery-card data-category="{{ $photo['category'] }}">
                        <button class="gallery-card-image" type="button" data-gallery-open
                            data-gallery-src="{{ asset($photo['image']) }}"
                            data-gallery-alt="{{ $photo['alt'] }}"
                            data-gallery-title="{{ $photo['title'] }}"
                            data-gallery-caption="{{ $photo['caption'] }}"
                            data-gallery-category="{{ $photo['categoryLabel'] }}"
                            aria-label="Open photograph: {{ $photo['title'] }}">
                            <img src="{{ asset($photo['image']) }}" width="1500" height="844" alt="{{ $photo['alt'] }}" loading="lazy" decoding="async">
                            <span class="gallery-zoom-label"><span aria-hidden="true">↗</span> View photograph</span>
                        </button>
                        <div class="gallery-card-info">
                            <p class="gallery-card-category">{{ $photo['categoryLabel'] }}</p>
                            <h3>{{ $photo['title'] }}</h3>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="gallery-empty-state" data-gallery-empty hidden>No photographs are listed in this category yet.</p>
            <p class="gallery-archive-note"><span aria-hidden="true">i</span><span>Explore GECO Africa’s project photography, field investigations and laboratory work across Rwanda.</span></p>
        </div>
    </section>

    <dialog class="gallery-lightbox" data-gallery-lightbox aria-label="Photograph viewer">
        <div class="gallery-lightbox-frame">
            <button class="gallery-lightbox-close" type="button" data-gallery-close aria-label="Close photograph viewer">×</button>
            <button class="gallery-lightbox-arrow gallery-lightbox-prev" type="button" data-gallery-prev aria-label="Previous photograph">‹</button>
            <figure class="gallery-lightbox-figure">
                <div class="gallery-lightbox-image-wrap"><img data-gallery-lightbox-image width="1500" height="844" alt="" decoding="async"></div>
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
                <a class="button button-bright" href="mailto:{{ $sitePublicEmail }}?subject=Project%20enquiry">Start a project enquiry <span aria-hidden="true">↗</span></a>
                <a class="gallery-phone-link" href="tel:{{ preg_replace('/[^0-9+]/', '', $sitePrimaryPhone) }}">{{ $sitePrimaryPhone }}</a>
            </div>
        </div>
    </section>
</main>
<script src="{{ asset('js/gallery.js') }}" defer></script>
@endsection
