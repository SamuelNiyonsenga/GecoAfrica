@extends('layouts.site')

@section('content')
<main id="main-content" class="partners-page">
    <section class="partners-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Partners</span>
            </nav>

            <div class="partners-hero-grid">
                <div class="partners-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> Collaboration · Standards · Progress</p>
                    <h1>Good engineering is a <em>shared effort.</em></h1>
                    <p class="partners-hero-intro">Explore organizations connected with GECO Africa’s work, with information and links to their official websites.</p>
                    <div class="hero-actions">
                        <a class="button button-dark" href="#partner-directory">Explore the directory <span aria-hidden="true">↓</span></a>
                        <a class="text-link" href="{{ route('about') }}">About GECO <span aria-hidden="true">→</span></a>
                    </div>
                </div>

                <aside class="partners-hero-note" aria-label="Partner directory summary">
                    <span class="partners-note-mark" aria-hidden="true">GECO<br><b>AFRICA</b></span>
                    <span class="partners-note-rule" aria-hidden="true"></span>
                    <div>
                        <p class="partners-note-label">A record of collaboration</p>
                        <strong>{{ count($partners) }}<small> organizations</small></strong>
                        <p>Engineering, education, standards and specialist field services.</p>
                    </div>
                    <span class="partners-note-index" aria-hidden="true">01—05</span>
                </aside>
            </div>

            <div class="partners-discipline-strip" aria-label="Organizations represented">
                <span>Water &amp; field services</span><i aria-hidden="true"></i>
                <span>Standards &amp; quality</span><i aria-hidden="true"></i>
                <span>Technical education</span><i aria-hidden="true"></i>
                <span>Infrastructure</span><i aria-hidden="true"></i>
                <span>Professional engineering</span>
            </div>
        </div>
    </section>

    <section class="section partners-directory-section" id="partner-directory">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> Organizations</p>
                    <h2>Shared capability.<br><em>Different disciplines.</em></h2>
                </div>
                <p class="partners-directory-intro">Explore engineering, education, standards and field service organizations, with concise introductions and links to their official websites.</p>
            </div>

            <div class="partners-grid">
                @foreach ($partners as $partner)
                    <article class="partner-card" id="partner-{{ $loop->iteration }}">
                        <div class="partner-card-topline">
                            <span class="partner-card-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="partner-category">{{ $partner['category'] }}</span>
                        </div>
                        <div class="partner-logo-panel">
                            <img src="{{ asset($partner['image']) }}" width="300" height="190" alt="{{ $partner['alt'] }}" loading="lazy" decoding="async">
                        </div>
                        <div class="partner-card-content">
                            @if ($partner['legacyName'])
                                <p class="partner-legacy-name">{{ $partner['legacyName'] }}</p>
                            @endif
                            <h3>{{ $partner['name'] }}</h3>
                            <p class="partner-summary">{{ $partner['summary'] }}</p>
                            <a class="partner-visit-link" href="{{ $partner['website'] }}" target="_blank" rel="noopener noreferrer" aria-label="Visit {{ $partner['name'] }} official website (opens in a new tab)">
                                <span>Visit official website</span><small>{{ $partner['websiteLabel'] }}</small><b aria-hidden="true">↗</b>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="partners-history-note"><span aria-hidden="true">i</span><span><strong>About this directory</strong> This directory lists organizations associated with GECO Africa. The available information does not specify the scope or current status of each relationship.</span></p>
        </div>
    </section>

    <section class="partners-contact-strip">
        <div class="site-container partners-contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Start a conversation</p>
                <h2>Have a project that calls for <em>shared expertise?</em></h2>
            </div>
            <div>
                <p>Talk with GECO Africa about geotechnical investigation, engineering design and project delivery in Rwanda.</p>
                <a class="button button-bright" href="mailto:{{ $sitePublicEmail }}?subject=Project%20enquiry">Contact GECO Africa <span aria-hidden="true">↗</span></a>
                <a class="partners-phone-link" href="tel:{{ preg_replace('/[^0-9+]/', '', $sitePrimaryPhone) }}">{{ $sitePrimaryPhone }}</a>
            </div>
        </div>
    </section>
</main>
@endsection
