@extends('layouts.site')

@section('content')
<main id="main-content" class="services-page">
    <section class="services-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Services</span>
            </nav>

            <div class="services-hero-grid">
                <div class="services-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> Engineering · Rwanda</p>
                    <h1>Engineering services,<br><em>built around the work.</em></h1>
                    <p>From understanding the ground to designing roads and buildings, GECO brings technical services together to help projects move forward with clarity.</p>
                    <div class="hero-actions">
                        <a class="button button-dark" href="#service-list">Explore our services <span aria-hidden="true">↓</span></a>
                        <a class="text-link" href="mailto:{{ $sitePublicEmail }}?subject=Project%20enquiry">Talk to our team <span aria-hidden="true">→</span></a>
                    </div>
                </div>

                <div class="services-hero-visual">
                    <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" alt="GECO Africa field team conducting a geotechnical investigation in Rwanda" fetchpriority="high" decoding="async">
                    <div class="services-hero-overlay" aria-hidden="true"></div>
                    <div class="services-hero-caption"><span>Insight from the ground up</span><small>GECO Africa · Since 2007</small></div>
                    <div class="services-hero-count"><strong>04</strong><span>Connected service areas</span></div>
                </div>
            </div>
        </div>
    </section>

    <nav class="service-anchor-strip" id="service-list" aria-label="Jump to a service">
        <div class="site-container service-anchor-inner">
            @foreach ($services as $service)
                <a href="#{{ $service['slug'] }}"><span>{{ $service['number'] }}</span>{{ $service['title'] }}</a>
            @endforeach
        </div>
    </nav>

    <div class="service-details">
        @foreach ($services as $service)
            <section class="service-detail-section" id="{{ $service['slug'] }}">
                <div class="site-container service-detail-grid @if ($loop->even) service-detail-reverse @endif">
                    <figure class="service-detail-visual">
                        <img src="{{ asset($service['image']) }}" width="1600" height="900" alt="{{ $service['imageAlt'] }}" loading="lazy" decoding="async">
                        <figcaption><span>{{ $service['imageCaption'] }}</span><span class="service-figure-number">{{ $service['number'] }}</span></figcaption>
                    </figure>

                    <div class="service-detail-copy">
                        <p class="eyebrow"><span class="eyebrow-line"></span> Service {{ $service['number'] }}</p>
                        <h2>{{ $service['title'] }}</h2>
                        <p class="service-detail-intro">{{ $service['intro'] }}</p>
                        <ul class="service-scope-list">
                            @foreach ($service['items'] as $item)
                                <li><span aria-hidden="true">✓</span>{{ $item }}</li>
                            @endforeach
                        </ul>

                        @if (!empty($service['priceList']))
                            <a class="service-resource-link" href="{{ asset($service['priceList']) }}" target="_blank" rel="noopener">
                                <span><small>Laboratory resource</small><strong>View laboratory test price list</strong></span>
                                <span class="resource-arrow" aria-hidden="true">↗</span>
                            </a>
                        @endif

                            <a class="service-enquiry-link" href="mailto:{{ $sitePublicEmail }}?subject=Enquiry%20about%20{{ rawurlencode($service['title']) }}">Discuss this service <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </section>
        @endforeach
    </div>

    <section class="service-approach-section">
        <div class="site-container service-approach-grid">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> A connected approach</p>
                <h2>Better decisions,<br><em>at every stage.</em></h2>
                <p>GECO’s service areas bring together investigation, design and oversight, so clients can find the right technical support for their project.</p>
            </div>
            <div class="service-approach-steps">
                <a href="#geotechnical-engineering"><span>01</span><strong>Understand the site</strong><small>Investigations, survey and feasibility studies</small><i aria-hidden="true">↗</i></a>
                <a href="#road-design-and-supervision"><span>02</span><strong>Plan and design</strong><small>Technical studies, drawings and tender documents</small><i aria-hidden="true">↗</i></a>
                <a href="#civil-engineering-construction"><span>03</span><strong>Supervise delivery</strong><small>Construction oversight, quality checks and reporting</small><i aria-hidden="true">↗</i></a>
            </div>
        </div>
    </section>

    <section class="services-contact-section">
        <div class="site-container services-contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Start a conversation</p>
                <h2>Tell us what you’re<br><em>planning.</em></h2>
            </div>
            <div class="services-contact-action">
                <p>Share a little about your project and the technical support you need. Our team can help you identify a useful next step.</p>
                <a class="button button-bright" href="mailto:{{ $sitePublicEmail }}?subject=Project%20enquiry">Enquire about our services <span aria-hidden="true">↗</span></a>
                <a class="contact-phone" href="tel:{{ preg_replace('/[^0-9+]/', '', $sitePrimaryPhone) }}">Or call <strong>{{ $sitePrimaryPhone }}</strong></a>
                <a class="services-about-link" href="{{ route('about') }}">Learn more about GECO Africa <span aria-hidden="true">→</span></a>
            </div>
            <svg class="contact-contour" viewBox="0 0 300 260" fill="none" aria-hidden="true"><path d="M296 9C224 6 151 15 93 47 36 78 10 119 8 157c-2 37 30 66 71 82 43 17 106 17 161 10M296 42c-61-1-119 8-165 33-46 26-66 58-66 86 0 28 23 48 56 61 34 12 82 13 124 8M295 77c-49 1-92 8-126 27-34 19-48 42-47 62 1 18 17 32 42 41 25 9 60 9 91 5M294 112c-35 2-64 8-87 20-22 13-31 27-29 39 2 12 13 21 31 27 17 5 39 6 58 3"/></svg>
        </div>
    </section>
</main>
@endsection
