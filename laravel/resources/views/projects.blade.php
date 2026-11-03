@extends('layouts.site')

@section('content')
<main id="main-content" class="projects-page">
    <section class="projects-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Projects</span>
            </nav>

            <div class="projects-hero-grid">
                <div class="projects-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> Our work · Rwanda and the region</p>
                    <h1>Experience built into <em>every layer.</em></h1>
                    <p class="projects-hero-intro">From what lies beneath the ground to the roads, buildings and public facilities people rely on, GECO’s project references span a wide range of engineering challenges.</p>
                    <div class="hero-actions">
                        <a class="button button-dark" href="#featured-projects">Explore selected work <span aria-hidden="true">↓</span></a>
                        <a class="text-link" href="{{ asset('assets/our-references.pdf') }}" target="_blank" rel="noopener">Download project reference list (PDF) <span aria-hidden="true">↗</span></a>
                    </div>
                </div>

                <div class="projects-hero-visual">
                    <img src="{{ asset('images/projects/nyanza-asphalt-road.jpg') }}" width="540" height="304" fetchpriority="high" decoding="async" alt="Asphalt road construction in Nyanza District, Rwanda">
                    <div class="projects-photo-caption"><span>Infrastructure for connected communities</span><small>Road construction supervision · Rwanda</small></div>
                </div>
            </div>

            <div class="projects-metrics" aria-label="GECO Africa project experience">
                <div><strong>{{ $referenceCount }}</strong><span>Project references</span></div>
                <div><strong>100<span>+</span></strong><span>Successful projects</span></div>
                <div><strong>500<span>+</span></strong><span>Customers served</span></div>
                <div><strong>2007</strong><span>Engineering practice<br>founded in Rwanda</span></div>
            </div>
        </div>
    </section>

    <section class="section projects-featured-section" id="featured-projects">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> In the field</p>
                    <h2>Selected project <em>stories.</em></h2>
                </div>
                <p class="heading-aside">Explore selected GECO Africa projects and the engineering work behind them.</p>
            </div>

            <div class="featured-project-grid">
                @foreach ($featuredProjects as $project)
                    <article class="featured-project-card">
                        <div class="featured-project-image">
                            <img src="{{ asset($project['image']) }}" width="1200" height="900" loading="lazy" decoding="async" alt="{{ $project['imageAlt'] }}">
                            <span class="featured-project-number">{{ $project['number'] }}</span>
                        </div>
                        <div class="featured-project-body">
                            <p class="featured-project-category">{{ $project['category'] }}</p>
                            <p class="featured-project-location">{{ $project['location'] }}</p>
                            <h3>{{ $project['title'] }}</h3>
                            <p>{{ $project['summary'] }}</p>
                            @isset($project['areaNote'])
                                <details class="project-source-note">
                                    <summary>About the published area figures</summary>
                                    <p>{{ $project['areaNote'] }}</p>
                                </details>
                            @endisset
                            <a class="featured-project-action" href="#project-references">Explore project references <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="project-directory-section" id="project-references">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Full project directory</p>
                    <h2>49 references. <em>Three disciplines.</em></h2>
                </div>
                <div class="project-directory-aside">
                    <p>Browse GECO Africa’s project references, or download the project reference list.</p>
                    <a class="button button-bright" href="{{ asset('assets/our-references.pdf') }}" target="_blank" rel="noopener">Download project reference list (PDF) <span aria-hidden="true">↗</span></a>
                </div>
            </div>

            <div class="project-search" role="search">
                <label for="project-search-input">Search project references</label>
                <div><input id="project-search-input" type="search" data-project-search placeholder="Search title, location or client" autocomplete="off"><button type="button" data-project-clear hidden>Clear</button></div>
                <p data-project-results aria-live="polite">{{ $referenceCount }} project references</p>
            </div>

            <nav class="project-category-nav" aria-label="Project reference categories">
                @foreach ($projectGroups as $group)
                    <a href="#{{ $group['id'] }}"><span>{{ $group['number'] }}</span>{{ $group['title'] }} <b aria-hidden="true">↘</b></a>
                @endforeach
            </nav>

            @foreach ($projectGroups as $group)
                <section class="project-reference-group" id="{{ $group['id'] }}" aria-labelledby="{{ $group['id'] }}-heading">
                    <div class="project-reference-heading">
                        <span class="project-reference-number">{{ $group['number'] }}</span>
                        <div>
                            <h3 id="{{ $group['id'] }}-heading">{{ $group['title'] }}</h3>
                            <p>{{ $group['intro'] }}</p>
                        </div>
                        <span class="project-reference-count">{{ count($group['projects']) }} references</span>
                    </div>
                    <ol class="project-reference-list">
                        @foreach ($group['projects'] as $project)
                            <li data-project-item data-project-search-text="{{ mb_strtolower($project['title'].' '.$project['location'].' '.$project['client']) }}">
                                <article class="project-reference-card">
                                    <span class="reference-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <h4>{{ $project['title'] }}</h4>
                                        <p><span>Location</span>{{ $project['location'] }}</p>
                                        <p><span>Client</span>{{ $project['client'] }}</p>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endforeach
            <p class="project-search-empty" data-project-empty hidden>No projects match your search.</p>
        </div>
    </section>

    <section class="section selected-experience-section">
        <div class="site-container selected-experience-grid">
            <div>
                <p class="eyebrow"><span class="eyebrow-line"></span> Broader professional experience</p>
                <h2>Projects shaping <em>Rwanda’s built environment.</em></h2>
                <p class="selected-experience-intro">GECO’s company profile also lists the following major projects as part of founder Jean Kizito’s geotechnical study experience with international companies.</p>
                <a class="text-link" href="{{ route('team') }}#core-team">Meet the team behind the work <span aria-hidden="true">→</span></a>
            </div>
            <ul class="major-project-list">
                @foreach ($selectedExperience as $project)
                    <li><span aria-hidden="true">↗</span>{{ $project }}</li>
                @endforeach
            </ul>
            <aside class="projects-investment-note">
                <span>Wider company activity</span>
                <p>GECO Africa’s company profile describes investment in apartments and quarrying, areas connected with public importance, skills and shared human values.</p>
            </aside>
        </div>
    </section>

    <section class="projects-contact-strip">
        <div class="site-container projects-contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Have a project in mind?</p>
                <h2>Let’s find the <em>right place to start.</em></h2>
            </div>
            <div>
                <p>Talk with our team about a site, road, building or infrastructure challenge.</p>
                <a class="button button-bright" href="mailto:info@gecoafrica.com?subject=Project%20enquiry">Discuss your project <span aria-hidden="true">↗</span></a>
                <a class="projects-contact-phone" href="tel:+250788842990">+250 788 842 990</a>
            </div>
        </div>
    </section>
</main>
@endsection
