@extends('layouts.site')

@section('content')
<main id="main-content" class="about-page">
    <section class="about-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">About GECO</span>
            </nav>

            <div class="about-hero-grid">
                <div class="about-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> Rwandan-owned · Africa-focused</p>
                    <h1>Engineering rooted in Rwanda. <em>Built for progress.</em></h1>
                    <p class="about-hero-intro">GECO Africa Ltd is a civil engineering consultancy and construction company established in 2007 and registered as GECO Africa Ltd in 2009. We bring local knowledge and technical expertise to the infrastructure that connects communities and supports growth.</p>
                    <div class="hero-actions">
                        <a class="button button-dark" href="#our-story">Discover our story <span aria-hidden="true">↓</span></a>
                        <a class="text-link" href="mailto:{{ $sitePublicEmail }}?subject=Project%20enquiry">Discuss a project <span aria-hidden="true">→</span></a>
                    </div>
                </div>

                <div class="about-hero-visual">
                    <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" alt="GECO Africa field team conducting a geotechnical investigation in Rwanda" fetchpriority="high" decoding="async">
                    <div class="about-hero-photo-caption"><span>Local knowledge. Lasting infrastructure.</span><small>Geotechnical investigation · Rwanda</small></div>
                    <div class="about-hero-stat"><strong>2007</strong><span>Founded in Rwanda</span></div>
                </div>
            </div>

            <div class="about-facts" aria-label="GECO Africa at a glance">
                <div><strong>2007</strong><span>Company formed</span></div>
                <div><strong>2009</strong><span>Registered as GECO Africa Ltd</span></div>
                <div><strong>100%</strong><span>Rwandan-owned</span></div>
                <div><strong>4</strong><span>Core engineering disciplines</span></div>
            </div>
        </div>
    </section>

    <section class="section about-story-section" id="our-story">
        <div class="site-container about-story-grid">
            <div class="about-story-heading">
                <p class="eyebrow"><span class="eyebrow-line"></span> Our story</p>
                <h2>Built on local knowledge. <em>Growing with purpose.</em></h2>
                <p>GECO began as an independent engineering practice and grew into a Rwandan-owned civil engineering consultancy serving projects across the country.</p>
                <div class="about-location-card">
                    <span class="location-pin" aria-hidden="true">⌖</span>
                    <span><small>Our office</small><strong>{{ $siteAddress }}<br>{{ $siteCity }}, {{ $siteCountry }}</strong><em>{{ $sitePoBox ? 'P.O. Box '.$sitePoBox : '' }}</em></span>
                </div>
            </div>

            <div class="about-story-content">
                <p>GECO Africa Ltd was first formed in 2007 in the name of geotechnical engineer MUNYARUGERERO Jean Kizito. In 2009, the company was registered as GECO Africa Ltd in response to Rwanda’s call for Rwandans to build consulting firms capable of meeting the country’s challenges and competing at the highest level.</p>
                <p>GECO became Rwanda’s first privately equipped geotechnical engineering company. Today it is a 100% Rwandan-owned civil engineering consultancy and construction company, with two main shareholders: Jean Kizito Munyarugerero and Aline Aimée Mukaneza Rwabutogo, both engineers. Aline is the co-founder and wife of the Managing Director.</p>

                <div class="about-timeline" aria-label="Company milestones">
                    <div class="about-timeline-item"><span>2007</span><p>GECO’s engineering practice is founded by geotechnical engineer Jean Kizito Munyarugerero.</p></div>
                    <div class="about-timeline-item"><span>2009</span><p>The company is registered as GECO Africa Ltd and begins its next chapter as an independent Rwandan firm.</p></div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-projects-section">
        <div class="site-container about-projects-grid">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Selected project experience</p>
                <h2>Grounded in work that <em>moves Rwanda forward.</em></h2>
                <p>Jean Kizito has worked with international companies on geotechnical studies for major Rwandan infrastructure and building projects, including:</p>
                <a class="about-projects-link" href="{{ route('projects') }}">Explore the project references <span aria-hidden="true">↗</span></a>
            </div>
            <ul class="project-list">
                @foreach ($projects as $project)
                    <li><span aria-hidden="true">↗</span>{{ $project }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section about-purpose-section">
        <div class="site-container">
            <div class="section-heading about-purpose-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> What guides us</p>
                    <h2>Technical rigour.<br><em>Human value.</em></h2>
                </div>
                <p class="heading-aside">We want the quality of our work and the knowledge we share to make a lasting contribution to society.</p>
            </div>

            <div class="purpose-grid">
                <article class="purpose-card purpose-card-featured">
                    <span class="purpose-index">01 / Vision</span>
                    <h3>A trusted engineering voice</h3>
                    <p>To become a premier Rwandan, African and international think tank on geotechnical engineering, infrastructure projects and service provision.</p>
                </article>
                <article class="purpose-card">
                    <span class="purpose-index">02 / Main objective</span>
                    <h3>Improve society through quality</h3>
                    <p>To improve society through the quality of our work and share our technical expertise and human values with the world.</p>
                </article>
                <article class="purpose-card purpose-motto">
                    <span class="purpose-index">03 / Motto</span>
                    <p>“Designing and building for ever.”</p>
                    <span class="motto-rule" aria-hidden="true"></span>
                    <small>GECO Africa Ltd</small>
                </article>
            </div>
        </div>
    </section>

    <section class="section capabilities-section" id="capabilities">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> What we do</p>
                    <h2>Engineering services from <em>ground to delivery.</em></h2>
                </div>
                <div class="about-services-aside">
                    <p class="heading-aside">Four complementary disciplines connect early investigation with design, construction supervision and delivery.</p>
                    <a class="text-link" href="{{ route('services') }}">View service details <span aria-hidden="true">→</span></a>
                </div>
            </div>

            <div class="capabilities-grid">
                @foreach ($serviceScopes as $scope)
                    <article class="capability-card">
                        <div class="capability-card-top"><span>{{ $scope['number'] }}</span><span aria-hidden="true">↗</span></div>
                        <h3>{{ $scope['title'] }}</h3>
                        <p>{{ $scope['intro'] }}</p>
                        <ul>
                            @foreach ($scope['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section people-section" id="our-people">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> The people behind the work</p>
                    <h2>Experienced people.<br><em>One shared purpose.</em></h2>
                </div>
                <p class="heading-aside">Meet GECO’s shareholders and professional team. Staff CV documents are available below.</p>
            </div>

            <div class="people-grid">
                @foreach ($team as $person)
                    <article class="person-card">
                        <div class="person-photo"><img src="{{ asset($person['image']) }}" width="288" height="360" alt="{{ $person['alt'] }}" loading="lazy" decoding="async"></div>
                        <div class="person-card-content">
                            <p class="person-role">{{ $person['role'] }}</p>
                            <h3>{{ $person['name'] }}</h3>
                            <p class="person-bio">{{ $person['bio'] }}</p>
                            @if(!empty($person['profile']))
                                <p class="person-profile"><span>Profile</span>{{ $person['profile'] }}</p>
                            @endif
                            <a class="person-cv-link" href="{{ asset($person['cv']) }}" target="_blank" rel="noopener">View CV (PDF) <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <p class="team-page-cta"><a class="text-link" href="{{ route('team') }}">Meet the full GECO team <span aria-hidden="true">→</span></a></p>
        </div>
    </section>

    <section class="about-resources-section">
        <div class="site-container about-resources-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Company resource</p>
                <h2>Review GECO’s <em>equipment list.</em></h2>
                <p>GECO’s equipment list is available to view as a PDF.</p>
            </div>
            <a class="button button-bright" href="{{ asset('assets/geco-equipment-list.pdf') }}" target="_blank" rel="noopener">View equipment list <span aria-hidden="true">↗</span></a>
        </div>
    </section>

    <section class="about-contact-strip">
        <div class="site-container about-contact-inner">
            <p>Planning a project in Rwanda?</p>
            <a href="mailto:{{ $sitePublicEmail }}?subject=Project%20enquiry">Start a conversation <span aria-hidden="true">↗</span></a>
            <span>{{ $sitePrimaryPhone }}</span>
        </div>
    </section>
</main>
@endsection
