@extends('layouts.site')

@section('content')
<main id="main-content">
    <section class="home-showcase" data-home-carousel data-interval="7500" aria-label="GECO Africa projects and engineering services" aria-roledescription="carousel">
        <div class="home-showcase-track" data-home-track>
            @foreach ($heroSlides as $slide)
                <article class="home-showcase-slide @if ($loop->first) is-active @endif" data-home-slide aria-roledescription="slide" aria-label="Slide {{ $loop->iteration }} of {{ count($heroSlides) }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}" @if (!$loop->first) inert @endif>
                    <img class="home-showcase-image" src="{{ asset($slide['image']) }}" width="1920" height="1080" alt="{{ $slide['alt'] }}" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
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

    <section class="home-facts-strip" id="home-at-a-glance" aria-label="GECO Africa at a glance">
        <div class="site-container home-facts-grid">
            <div><strong>2007</strong><span>Engineering practice<br>founded in Rwanda</span></div>
            <div><strong>2009</strong><span>Registered as<br>GECO Africa Ltd</span></div>
            <div><strong>100<span>%</span></strong><span>Rwandan-owned<br>engineering company</span></div>
            <div><strong>04</strong><span>Connected service<br>areas under one team</span></div>
        </div>
    </section>

    <section class="section services-section" id="services">
        <div class="site-container">
            <div class="section-heading services-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> What we do</p>
                    <h2>One team.<br><em>Every critical layer.</em></h2>
                </div>
                <p class="heading-aside">From the ground below to the infrastructure above, we bring connected expertise to each stage of a project.</p>
            </div>

            <div class="services-grid">
                @foreach ($services as $service)
                        <article class="service-card" data-reveal>
                        <div class="service-card-top"><span>{{ $service['number'] }}</span><span class="service-arrow" aria-hidden="true">↗</span></div>
                        <h3>{{ $service['title'] }}</h3>
                        <p>{{ $service['description'] }}</p>
                        <div class="service-tags">
                            @foreach ($service['tags'] as $tag)
                                <span>{{ $tag }}</span>
                            @endforeach
                        </div>
                        <a class="service-card-action" href="{{ route('services') }}#{{ $service['slug'] }}">Explore service <span aria-hidden="true">→</span></a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section home-projects-section" id="home-projects">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> Selected project references</p>
                    <h2>Engineering work with<br><em>lasting public value.</em></h2>
                </div>
                <div class="home-projects-heading-aside">
                    <p class="heading-aside">Explore a small selection from GECO’s published project references, spanning roads, buildings and geotechnical investigations across Rwanda.</p>
                    <a class="text-link" href="{{ route('projects') }}">Browse all project references <span aria-hidden="true">→</span></a>
                </div>
            </div>

            <div class="home-project-grid">
                @foreach ($featuredProjects as $project)
                    <article class="home-project-card" data-reveal>
                        <a class="home-project-image" href="{{ route('projects') }}#project-references" aria-label="View GECO project references: {{ $project['title'] }}">
                            <img src="{{ asset($project['image']) }}" width="1200" height="675" alt="{{ $project['imageAlt'] }}" @if (!$loop->first) loading="lazy" @endif>
                            <span>{{ $project['number'] }}</span>
                        </a>
                        <div class="home-project-copy">
                            <p>{{ $project['category'] }} <i aria-hidden="true"></i> {{ $project['location'] }}</p>
                            <h3>{{ $project['title'] }}</h3>
                            <div class="home-project-description"><span>{{ $project['summary'] }}</span><a href="{{ route('projects') }}#project-references" aria-label="Explore project references" title="Explore project references">↗</a></div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="home-project-footnote"><span>{{ $projectReferenceCount }}</span><p>Named assignments in GECO’s published reference schedule, covering geotechnical work, roads and transport, and buildings and civil works.</p><a href="{{ route('projects') }}#project-references">View the full schedule <span aria-hidden="true">↗</span></a><a href="{{ route('activities') }}">Browse dated activities <span aria-hidden="true">↗</span></a></div>
        </div>
    </section>

    <section class="about-section" id="about">
        <div class="about-image-wrap">
            <img src="{{ asset('images/home/residential-project.webp') }}" width="1500" height="850" loading="lazy" alt="Residential building in Rwanda, part of the built environment GECO Africa supports">
            <span class="about-image-label"><span></span> Local expertise · Since 2007</span>
        </div>
        <div class="about-copy">
            <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Who we are</p>
            <h2>Local knowledge.<br><em>Engineering rigour.</em></h2>
            <p class="about-lede">GECO Africa is a Rwandan-owned engineering and construction consultancy with a specialist foundation in geotechnical engineering.</p>
            <p class="about-body">Since 2007, we have helped clients make informed decisions about the ground, the design and the delivery of their projects. Our multidisciplinary team brings technical depth, practical experience and a clear understanding of the local context.</p>
            <a class="button button-outline" href="#experience">See our experience <span aria-hidden="true">↗</span></a>
            <div class="about-signature"><span class="signature-line"></span><span>Grounded in Rwanda.<br><strong>Ready for what’s next.</strong></span></div>
        </div>
    </section>

    <section class="section home-principles-section">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> What guides the work</p>
                    <h2>Technical purpose.<br><em>Human value.</em></h2>
                </div>
                <p class="heading-aside">GECO’s original vision, objective and motto remain part of the company’s story and the way it describes its work.</p>
            </div>
            <div class="home-principles-grid">
                @foreach ($companyPrinciples as $principle)
                    <article class="home-principle-card" data-reveal>
                        <div><span>{{ $principle['number'] }}</span><small>{{ $principle['label'] }}</small></div>
                        <h3>{{ $principle['title'] }}</h3>
                        <p>{{ $principle['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="home-laboratory-section">
        <div class="home-laboratory-visual">
            <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" loading="lazy" alt="GECO Africa drilling team carrying out ground investigation work in Rwanda">
            <div><small>Ground investigation · Soil mechanics · Testing</small><strong>Evidence that helps<br>projects move forward.</strong></div>
        </div>
        <div class="home-laboratory-copy">
            <p class="eyebrow"><span class="eyebrow-line"></span> Soil mechanics &amp; geotechnical laboratory</p>
            <h2>From the field<br>to <em>the lab.</em></h2>
            <p>GECO combines core drilling and ground investigation with soil sampling and materials testing through its Soil Mechanics and Geotechnical Engineering Laboratory.</p>
            <div class="home-test-list" aria-label="Published laboratory test capabilities">
                @foreach ($laboratoryCapabilities as $capability)
                    <span>{{ $capability }}</span>
                @endforeach
            </div>
            <div class="home-resource-actions">
                <a class="button button-dark" href="{{ asset('assets/laboratory-price-list.pdf') }}" target="_blank" rel="noopener">Laboratory test price list <span aria-hidden="true">↗</span></a>
                <a class="home-resource-link" href="{{ asset('assets/geco-equipment-list.pdf') }}" target="_blank" rel="noopener">View GECO equipment list <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>

    <section class="section experience-section home-experience-section" id="experience">
        <div class="site-container experience-grid">
            <div class="experience-intro">
                <p class="eyebrow"><span class="eyebrow-line"></span> Experience that matters</p>
                <h2>Better decisions<br>start with <em>better insight.</em></h2>
                <p>Sound engineering begins with the right questions. We combine field investigation, design and hands-on oversight to help projects move forward with confidence.</p>
                <a class="text-link" href="{{ route('projects') }}">Explore project references <span aria-hidden="true">→</span></a>
            </div>
            <div class="experience-list">
                @foreach ($experience as $item)
                    <div class="experience-item" data-reveal>
                        <span class="experience-check" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="m4 10 4 4 8-8"/></svg></span>
                        <p>{{ $item }}</p>
                        <span class="experience-item-arrow" aria-hidden="true">↗</span>
                    </div>
                @endforeach
                <div class="experience-image-card">
                    <img src="{{ asset('images/home/road-survey.webp') }}" width="1000" height="620" loading="lazy" alt="Surveyors carrying out a topographic survey for a road project">
                    <div><span>Fieldwork, designed around the real world</span><small>Survey · Investigation · Supervision</small></div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <div class="site-container contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Let's build with confidence</p>
                <h2>What are you<br><em>planning?</em></h2>
            </div>
            <div class="contact-action">
                <p>Bring us your project question. We’ll help you find the right place to start.</p>
                <a class="button button-bright" href="{{ route('contact') }}">Talk to our team <span aria-hidden="true">↗</span></a>
                <a class="contact-phone" href="tel:+250788842990">Or call <strong>+250 788 842 990</strong></a>
            </div>
            <svg class="contact-contour" viewBox="0 0 300 260" fill="none" aria-hidden="true"><path d="M296 9C224 6 151 15 93 47 36 78 10 119 8 157c-2 37 30 66 71 82 43 17 106 17 161 10M296 42c-61-1-119 8-165 33-46 26-66 58-66 86 0 28 23 48 56 61 34 12 82 13 124 8M295 77c-49 1-92 8-126 27-34 19-48 42-47 62 1 18 17 32 42 41 25 9 60 9 91 5M294 112c-35 2-64 8-87 20-22 13-31 27-29 39 2 12 13 21 31 27 17 5 39 6 58 3"/></svg>
        </div>
    </section>
</main>
@endsection
