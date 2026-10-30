@extends('layouts.site')

@section('content')
<main id="main-content" class="insights-page">
    <section class="insights-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Insights</span>
            </nav>

            <div class="insights-hero-grid">
                <div class="insights-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> GECO Africa · Field notes</p>
                    <h1>Ideas grounded<br>in <em>engineering.</em></h1>
                    <p>Clear, practical guides to the ground, infrastructure and design decisions that shape a project. Written to help clients and project teams ask better questions at every stage.</p>
                    <div class="hero-actions">
                        <a class="button button-dark" href="#latest-insights">Explore insights <span aria-hidden="true">↓</span></a>
                        <a class="text-link" href="{{ route('services') }}">Explore our services <span aria-hidden="true">→</span></a>
                    </div>
                </div>

                <div class="insights-hero-visual">
                    <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" alt="Geotechnical field investigation by GECO Africa in Rwanda" fetchpriority="high">
                    <div class="insights-hero-image-shade" aria-hidden="true"></div>
                    <div class="insights-hero-caption"><span>From field evidence to informed decisions</span><small>Geotechnical engineering · Rwanda</small></div>
                    <span class="insights-hero-index" aria-hidden="true">GECO / 01</span>
                </div>
            </div>
            <div class="insights-hero-note"><span>01</span><p>Useful context for the work<br><strong>before, during and beyond design.</strong></p><span class="insights-note-rule" aria-hidden="true"></span></div>
        </div>
    </section>

    <section class="insights-listing-section" id="latest-insights">
        <div class="site-container">
            <div class="section-heading insights-listing-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> The GECO journal</p>
                    <h2>Knowledge for the <em>next decision.</em></h2>
                </div>
                <p class="heading-aside">Explore practical notes on investigation, design and delivery. Each guide links the ideas back to GECO’s engineering services and project experience.</p>
            </div>

            <nav class="insights-filter" aria-label="Filter insights by topic">
                @foreach ($categories as $slug => $label)
                    <a href="{{ $slug === 'all' ? route('blog') : route('blog', ['category' => $slug]) }}" @if ($activeCategory === $slug) aria-current="page" @endif>{{ $label }}<span aria-hidden="true">{{ str_pad((string) $categoryCounts[$slug], 2, '0', STR_PAD_LEFT) }}</span></a>
                @endforeach
            </nav>

            <div class="insights-grid insights-grid-count-{{ count($articles) }}">
                @foreach ($articles as $article)
                    <article class="insight-card @if ($loop->first) insight-card-featured @endif">
                        <a class="insight-card-image" href="{{ route('blog.show', $article['slug']) }}" aria-label="Read: {{ $article['title'] }}">
                            <img src="{{ asset($article['image']) }}" width="1200" height="675" alt="{{ $article['imageAlt'] }}" @if (!$loop->first) loading="lazy" @endif>
                            <span class="insight-image-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="insight-image-arrow" aria-hidden="true">↗</span>
                        </a>
                        <div class="insight-card-copy">
                            <div class="insight-card-meta"><span>{{ $article['category'] }}</span><i aria-hidden="true"></i><span>{{ $article['readTime'] }}</span></div>
                            <h3><a href="{{ route('blog.show', $article['slug']) }}">{{ $article['title'] }}</a></h3>
                            <p>{{ $article['summary'] }}</p>
                            <a class="insight-read-link" href="{{ route('blog.show', $article['slug']) }}">Read the guide <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="insights-contact-section">
        <div class="site-container insights-contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Have a technical question?</p>
                <h2>Good projects start<br>with <em>a conversation.</em></h2>
            </div>
            <div class="insights-contact-action">
                <p>Tell us what you are planning. GECO can help you find the right engineering support for your site or infrastructure project.</p>
                <a class="button button-bright" href="{{ route('contact') }}">Talk with GECO <span aria-hidden="true">↗</span></a>
                <a class="insights-contact-services" href="{{ route('services') }}">See our engineering services <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
</main>
@endsection
