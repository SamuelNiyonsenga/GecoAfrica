@extends('layouts.site')

@section('content')
<main id="main-content" class="insight-article-page detail-page service-show-page">
    <section class="insight-article-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><a href="{{ route('services') }}">Services</a><span aria-hidden="true">/</span><span aria-current="page">{{ $service['title'] }}</span>
            </nav>
            <div class="insight-article-heading">
                <a class="insight-back-link" href="{{ route('services') }}"><span aria-hidden="true">←</span> All services</a>
                <p class="eyebrow"><span class="eyebrow-line"></span> Service {{ $service['number'] ?? '' }}</p>
                <h1>{{ $service['title'] }}</h1>
                @if (($service['intro'] ?? '') !== '' || ($service['summary'] ?? '') !== '')
                    <p class="insight-article-summary">{{ ($service['intro'] ?? '') ?: $service['summary'] }}</p>
                @endif
                @if (!empty($service['tags']))
                    <ul class="detail-tags" aria-label="Service areas">@foreach ((array) $service['tags'] as $tag)<li>{{ $tag }}</li>@endforeach</ul>
                @endif
            </div>
            @if (!empty($service['hasImage'] ?? true))
                <figure class="insight-article-cover">
                    <img src="{{ asset($service['image']) }}" width="1600" height="900" alt="{{ $service['imageAlt'] ?? '' }}" fetchpriority="high" decoding="async">
                    @if (($service['imageCaption'] ?? '') !== '')<figcaption><span>{{ $service['imageCaption'] }}</span><span>GECO Africa · Rwanda</span></figcaption>@endif
                </figure>
            @endif
        </div>
    </section>

    <section class="insight-reading-section">
        <div class="site-container insight-reading-grid">
            <aside class="insight-reading-aside">
                @if ($toc)
                    <span class="insight-aside-label">On this page</span>
                    <nav aria-label="Page contents">
                        @foreach ($toc as $heading)
                            <a href="#{{ $heading['id'] }}"><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $heading['heading'] }}</a>
                        @endforeach
                    </nav>
                @endif
                <div class="insight-aside-related">
                    <span class="insight-aside-label">Talk to our team</span>
                    <a href="{{ route('contact', ['service' => $service['slug']]) }}#contact-form">Send an enquiry about this service <span aria-hidden="true">↗</span></a>
                </div>
            </aside>

            <article class="insight-article-body">
                @if (!empty($service['items']))
                    <h2>What this service includes</h2>
                    <ul class="service-scope-list">
                        @foreach ((array) $service['items'] as $item)
                            <li><span aria-hidden="true">✓</span>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($body !== '')
                    <div class="rich-text">{!! $body !!}</div>
                @endif

                @if (!empty($service['gallery']))
                    <div class="detail-gallery" aria-label="Photos">
                        @foreach ((array) $service['gallery'] as $photo)
                            <a href="{{ asset($photo) }}" target="_blank" rel="noopener"><img src="{{ asset($photo) }}" width="600" height="400" loading="lazy" decoding="async" alt="{{ $service['title'] }} – photo {{ $loop->iteration }}"></a>
                        @endforeach
                    </div>
                @endif

                @if (!empty($service['priceList']))
                    <a class="service-resource-link" href="{{ asset($service['priceList']) }}" target="_blank" rel="noopener">
                        <span><small>Download</small><strong>{{ str_contains(strtolower($service['priceList']), 'price') ? 'View the price list' : 'View the service document' }}</strong></span>
                        <span class="resource-arrow" aria-hidden="true">↗</span>
                    </a>
                @endif

                <div class="insight-article-end">
                    <span class="insight-aside-label">Next steps</span>
                    <a href="{{ route('contact', ['service' => $service['slug']]) }}#contact-form">Discuss this service with our team <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('projects') }}">See related project references <span aria-hidden="true">↗</span></a>
                    @foreach ($relatedArticles as $related)
                        <a href="{{ route('blog.show', $related['slug']) }}">{{ $related['title'] }} <span aria-hidden="true">↗</span></a>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    @if ($otherServices)
        <section class="insights-related-section">
            <div class="site-container">
                <div class="section-heading">
                    <div><p class="eyebrow"><span class="eyebrow-line"></span> More from GECO Africa</p><h2>Other <em>services.</em></h2></div>
                    <a class="text-link" href="{{ route('services') }}">All services <span aria-hidden="true">→</span></a>
                </div>
                <div class="insights-related-grid">
                    @foreach (array_slice($otherServices, 0, 3) as $other)
                        <article class="insight-related-card">
                            <a href="{{ route('services.show', $other['slug']) }}" class="insight-related-image"><img src="{{ asset($other['image']) }}" width="900" height="506" loading="lazy" decoding="async" alt="{{ $other['imageAlt'] ?? '' }}"><span aria-hidden="true">↗</span></a>
                            <div><h3><a href="{{ route('services.show', $other['slug']) }}">{{ $other['title'] }}</a></h3><a class="insight-read-link" href="{{ route('services.show', $other['slug']) }}">Explore service <span aria-hidden="true">→</span></a></div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</main>
@endsection
