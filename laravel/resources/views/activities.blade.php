@extends('layouts.site')

@section('content')
<main id="main-content" class="activities-page">
    <section class="activities-hero">
        <div class="site-container activities-hero-grid">
            <div class="activities-hero-copy">
                <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Activities</span></nav>
                <p class="eyebrow"><span class="eyebrow-line"></span> Fieldwork · Studies · Project delivery</p>
                <h1>Engineering in action.<br><em>Across Rwanda.</em></h1>
                <p>Explore GECO Africa’s engineering assignments across geotechnical investigation, core drilling, surveying, water supply and building assessments.</p>
                <div class="activities-hero-facts">
                    <div><strong>{{ count($activities) }}</strong><span>published assignments</span></div>
                    <div><strong>2021–22</strong><span>Activity dates</span></div>
                    <div><strong>{{ str_pad((string) count($categories), 2, '0', STR_PAD_LEFT) }}</strong><span>engineering categories</span></div>
                </div>
                <a class="button button-dark" href="#activity-archive">Explore activities <span aria-hidden="true">↓</span></a>
            </div>
            <figure class="activities-hero-visual">
                <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" fetchpriority="high" decoding="async" alt="GECO Africa field team carrying out a geotechnical site investigation in Rwanda">
                <figcaption><span>GECO Africa · Rwanda</span><strong>Ground evidence.<br>Practical engineering.</strong></figcaption>
            </figure>
        </div>
    </section>

    <section class="activities-archive-section section" id="activity-archive">
        <div class="site-container">
            <div class="section-heading activities-section-heading">
                <div><p class="eyebrow"><span class="eyebrow-line"></span> Engineering activities</p><h2>Engineering work,<br><em>clearly recorded.</em></h2></div>
                <p class="heading-aside">Each assignment includes its title, client, date and duration. Use the filters to find work by engineering discipline.</p>
            </div>

            <div class="activity-filter-panel" aria-label="Filter published assignments">
                <div class="activity-filters" role="group" aria-label="Filter activities by discipline">
                    <button type="button" data-activity-filter="all" aria-pressed="true">All <span>{{ count($activities) }}</span></button>
                    @foreach ($categories as $category)
                        <button type="button" data-activity-filter="{{ $category }}" aria-pressed="false">{{ $category }} <span>{{ collect($activities)->where('category', $category)->count() }}</span></button>
                    @endforeach
                </div>
                <label class="activity-search"><span class="sr-only">Search activities</span><input type="search" data-activity-search placeholder="Search assignment or client" autocomplete="off"><span aria-hidden="true">⌕</span></label>
            </div>

            <p class="activity-results" data-activity-results role="status" aria-live="polite">Showing {{ count($activities) }} assignments</p>
            <div class="activity-grid">
                @foreach ($activities as $activity)
                    <article class="activity-card" id="activity-{{ $activity['id'] }}" data-activity-card data-activity-category="{{ $activity['category'] }}" data-activity-search-text="{{ strtolower($activity['title'].' '.$activity['client'].' '.$activity['category'].' '.$activity['date']) }}" data-reveal>
                        <div class="activity-card-top"><span class="activity-card-category"><i aria-hidden="true"></i>{{ $activity['category'] }}</span><span class="activity-card-date">{{ $activity['date'] }}</span></div>
                        <h3>{{ $activity['title'] }}</h3>
                        <div class="activity-card-meta"><div><span>Client</span><strong>{{ $activity['client'] }}</strong></div><div><span>Duration</span><strong>{{ $activity['duration'] }}</strong></div>@if (!empty($activity['location']))<div><span>Location</span><strong>{{ $activity['location'] }}</strong></div>@endif</div>
                        <a href="{{ route('contact') }}?subject={{ rawurlencode('Enquiry about GECO Africa activity '.$activity['id']) }}" class="activity-card-link">Discuss a similar assignment <span aria-hidden="true">↗</span></a>
                    </article>
                @endforeach
            </div>
            <p class="activity-empty" data-activity-empty hidden>No matching assignments. Try another search or discipline.</p>
        </div>
    </section>

    <section class="activities-contact-strip">
        <div class="site-container activities-contact-inner">
            <div><p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Have a project in mind?</p><h2>Let’s find the right<br><em>engineering start.</em></h2></div>
            <div><p>Talk with GECO about your site, study or infrastructure assignment. Our team can help identify the right investigation, design or supervision scope.</p><a class="button button-bright" href="{{ route('contact') }}">Start a project enquiry <span aria-hidden="true">↗</span></a></div>
        </div>
    </section>
</main>
@endsection
