@extends('layouts.site')

@section('content')
<main id="main-content" class="insight-article-page detail-page project-show-page">
    <section class="insight-article-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><a href="{{ route('projects') }}">Projects</a><span aria-hidden="true">/</span><span aria-current="page">{{ $project['title'] }}</span>
            </nav>
            <div class="insight-article-heading">
                <a class="insight-back-link" href="{{ route('projects') }}"><span aria-hidden="true">←</span> All projects</a>
                @if (($project['category'] ?? '') !== '')<p class="eyebrow"><span class="eyebrow-line"></span> {{ $project['category'] }}</p>@endif
                <h1>{{ $project['title'] }}</h1>
                @if (($project['summary'] ?? '') !== '')<p class="insight-article-summary">{{ $project['summary'] }}</p>@endif
                <dl class="detail-facts">
                    @foreach (['location' => 'Location', 'client' => 'Client', 'year' => 'When'] as $factKey => $factLabel)
                        @if (trim((string) ($project[$factKey] ?? '')) !== '')<div><dt>{{ $factLabel }}</dt><dd>{{ $project[$factKey] }}</dd></div>@endif
                    @endforeach
                </dl>
            </div>
            @if (!empty($project['hasImage'] ?? true))
                <figure class="insight-article-cover">
                    <img src="{{ asset($project['image']) }}" width="1600" height="900" alt="{{ $project['imageAlt'] ?? '' }}" fetchpriority="high" decoding="async">
                    <figcaption><span>{{ $project['imageAlt'] ?? $project['title'] }}</span><span>{{ $project['location'] ?? 'GECO Africa' }}</span></figcaption>
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
                    <span class="insight-aside-label">Planning something similar?</span>
                    <a href="{{ route('contact') }}#contact-form">Talk to our team <span aria-hidden="true">↗</span></a>
                </div>
            </aside>

            <article class="insight-article-body">
                @if ($body !== '')
                    <div class="rich-text">{!! $body !!}</div>
                @elseif (($project['summary'] ?? '') === '')
                    <p>More details about this project are coming soon.</p>
                @endif

                @if (!empty($project['areaNote']))
                    <details class="project-source-note"><summary>About the published area figures</summary><p>{{ $project['areaNote'] }}</p></details>
                @endif

                @if (!empty($project['gallery']))
                    <div class="detail-gallery" aria-label="Project photos">
                        @foreach ((array) $project['gallery'] as $photo)
                            <a href="{{ asset($photo) }}" target="_blank" rel="noopener"><img src="{{ asset($photo) }}" width="600" height="400" loading="lazy" decoding="async" alt="{{ $project['title'] }} – photo {{ $loop->iteration }}"></a>
                        @endforeach
                    </div>
                @endif

                <div class="insight-article-end">
                    <span class="insight-aside-label">Continue exploring</span>
                    <a href="{{ route('projects') }}#project-references">Browse all project references <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('services') }}">See GECO Africa's services <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('contact') }}#contact-form">Discuss a project with our team <span aria-hidden="true">↗</span></a>
                </div>
            </article>
        </div>
    </section>

    @if ($otherProjects)
        <section class="insights-related-section">
            <div class="site-container">
                <div class="section-heading">
                    <div><p class="eyebrow"><span class="eyebrow-line"></span> In the field</p><h2>More project <em>stories.</em></h2></div>
                    <a class="text-link" href="{{ route('projects') }}">All projects <span aria-hidden="true">→</span></a>
                </div>
                <div class="insights-related-grid">
                    @foreach ($otherProjects as $other)
                        <article class="insight-related-card">
                            <a href="{{ route('projects.show', $other['slug']) }}" class="insight-related-image"><img src="{{ asset($other['image']) }}" width="900" height="506" loading="lazy" decoding="async" alt="{{ $other['imageAlt'] ?? '' }}"><span aria-hidden="true">↗</span></a>
                            <div><p class="insight-card-meta"><span>{{ $other['location'] ?? '' }}</span></p><h3><a href="{{ route('projects.show', $other['slug']) }}">{{ $other['title'] }}</a></h3><a class="insight-read-link" href="{{ route('projects.show', $other['slug']) }}">Read the project story <span aria-hidden="true">→</span></a></div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</main>
@endsection
