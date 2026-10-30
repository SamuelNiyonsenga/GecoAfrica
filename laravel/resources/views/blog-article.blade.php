@extends('layouts.site')

@section('content')
<main id="main-content" class="insight-article-page">
    <section class="insight-article-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><a href="{{ route('blog') }}">Insights</a><span aria-hidden="true">/</span><span aria-current="page">{{ $article['category'] }}</span>
            </nav>
            <div class="insight-article-heading">
                <a class="insight-back-link" href="{{ route('blog') }}"><span aria-hidden="true">←</span> All insights</a>
                <p class="eyebrow"><span class="eyebrow-line"></span> {{ $article['eyebrow'] }}</p>
                <h1>{{ $article['title'] }}</h1>
                <p class="insight-article-summary">{{ $article['summary'] }}</p>
                <div class="insight-article-byline"><span>{{ $article['category'] }}</span><i aria-hidden="true"></i><span>{{ $article['readTime'] }}</span><span class="insight-byline-company">GECO Africa · Engineering guide</span></div>
            </div>
            <figure class="insight-article-cover">
                <img src="{{ asset($article['image']) }}" width="1600" height="900" alt="{{ $article['imageAlt'] }}" fetchpriority="high">
                <figcaption><span>{{ $article['imageAlt'] }}</span><span>GECO Africa · Rwanda</span></figcaption>
            </figure>
        </div>
    </section>

    <section class="insight-reading-section">
        <div class="site-container insight-reading-grid">
            <aside class="insight-reading-aside">
                <span class="insight-aside-label">In this guide</span>
                <nav aria-label="Article contents">
                    @foreach ($article['sections'] as $section)
                        <a href="#guide-section-{{ $loop->iteration }}"><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $section['heading'] }}</a>
                    @endforeach
                </nav>
                <div class="insight-aside-related">
                    <span class="insight-aside-label">Related service</span>
                    <a href="{{ route('services') }}#{{ $article['serviceAnchor'] }}">{{ $article['serviceLabel'] }} <span aria-hidden="true">↗</span></a>
                </div>
            </aside>

            <article class="insight-article-body">
                @foreach ($article['sections'] as $section)
                    <section id="guide-section-{{ $loop->iteration }}" class="insight-content-section">
                        <span class="insight-section-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h2>{{ $section['heading'] }}</h2>
                            @foreach ($section['paragraphs'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </section>
                @endforeach

                <div class="insight-article-note"><span aria-hidden="true">GECO</span><p>This guide shares general engineering context. Project requirements and design decisions should be assessed by the responsible professionals for each site and brief.</p></div>

                <div class="insight-article-end">
                    <span class="insight-aside-label">Continue exploring</span>
                    <a href="{{ route('projects') }}">Browse GECO project references <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('contact') }}">Discuss a project with our team <span aria-hidden="true">↗</span></a>
                </div>
            </article>
        </div>
    </section>

    <section class="insights-related-section">
        <div class="site-container">
            <div class="section-heading">
                <div><p class="eyebrow"><span class="eyebrow-line"></span> More from the journal</p><h2>Keep the thinking <em>moving.</em></h2></div>
                <a class="text-link" href="{{ route('blog') }}">All insights <span aria-hidden="true">→</span></a>
            </div>
            <div class="insights-related-grid">
                @foreach ($relatedArticles as $related)
                    <article class="insight-related-card">
                        <a href="{{ route('blog.show', $related['slug']) }}" class="insight-related-image"><img src="{{ asset($related['image']) }}" width="900" height="506" loading="lazy" alt="{{ $related['imageAlt'] }}"><span aria-hidden="true">↗</span></a>
                        <div><p class="insight-card-meta"><span>{{ $related['category'] }}</span><i aria-hidden="true"></i><span>{{ $related['readTime'] }}</span></p><h3><a href="{{ route('blog.show', $related['slug']) }}">{{ $related['title'] }}</a></h3><a class="insight-read-link" href="{{ route('blog.show', $related['slug']) }}">Read the guide <span aria-hidden="true">→</span></a></div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
