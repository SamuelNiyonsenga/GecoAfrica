@extends('layouts.site')

@section('content')
<main id="main-content" class="team-page">
    <section class="team-hero">
        <div class="site-container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Our team</span>
            </nav>

            <div class="team-hero-grid">
                <div class="team-hero-copy">
                    <p class="eyebrow"><span class="eyebrow-line"></span> People · Knowledge · Purpose</p>
                    <h1>Engineering is built by <em>people.</em></h1>
                    <p class="team-hero-intro">Meet the engineers, laboratory professionals and specialists behind GECO Africa’s work. Our team brings together technical depth, practical experience and a shared commitment to quality and human values.</p>
                    <div class="hero-actions">
                        <a class="button button-dark" href="#core-team">Meet the team <span aria-hidden="true">↓</span></a>
                        <a class="text-link" href="{{ route('about') }}">Our story <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="team-hero-visual">
                    <img src="{{ asset('images/team/jean-kizito.png') }}" width="640" height="640" alt="GECO Africa founder and Managing Director Jean Kizito Munyarugerero" fetchpriority="high">
                    <div class="team-hero-caption"><span>Technical leadership</span><small>MUNYARUGERERO Jean Kizito · Founder and Managing Director</small></div>
                </div>
            </div>

            <div class="team-facts" aria-label="GECO Africa team overview">
                <div><strong>{{ $teamCount }}</strong><span>Professionals and specialists<br>listed in the team profile</span></div>
                <div><strong>{{ count($coreTeam) }}</strong><span>Core professional profiles<br>with CVs available</span></div>
                <div><strong>{{ count($supportingTeam) }}</strong><span>Supporting specialists<br>named on the former site</span></div>
                <div><strong>2007</strong><span>GECO’s engineering practice<br>founded in Rwanda</span></div>
            </div>
        </div>
    </section>

    <section class="section team-core-section" id="core-team">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> Professional team</p>
                    <h2>Technical skill. <em>Shared responsibility.</em></h2>
                </div>
                <p class="heading-aside">The four professional profiles and CVs published by GECO Africa are available here, together with the specialist areas recorded on the original site.</p>
            </div>

            <div class="team-core-grid">
                @foreach ($coreTeam as $person)
                    <article class="team-person-card" id="{{ $person['slug'] }}">
                        <div class="team-person-photo">
                            <img src="{{ asset($person['image']) }}" width="640" height="640" alt="{{ $person['alt'] }}" loading="lazy">
                            <span class="team-person-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="team-person-content">
                            <p class="team-person-role">{{ $person['role'] }}</p>
                            <h3>{{ $person['name'] }}</h3>
                            <p class="team-person-bio">{{ $person['bio'] }}</p>
                            <div class="team-expertise">
                                <span>Focus</span>
                                <p>{{ $person['focus'] }}</p>
                            </div>
                            <ul class="team-expertise-tags" aria-label="Areas of experience">
                                @foreach ($person['expertise'] as $area)
                                    <li>{{ $area }}</li>
                                @endforeach
                            </ul>
                            <a class="team-cv-link" href="{{ asset($person['cv']) }}" target="_blank" rel="noopener">View published CV <span>PDF</span><b aria-hidden="true">↗</b></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="team-support-section" id="supporting-specialists">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Supporting specialists</p>
                    <h2>Additional expertise. <em>One shared purpose.</em></h2>
                </div>
                <p class="team-support-aside">GECO’s former team page also named these supporting professionals. The experience figures are shown as published and may need confirmation before being treated as current.</p>
            </div>

            <div class="team-support-grid">
                @foreach ($supportingTeam as $person)
                    <article class="team-support-card">
                        <span class="team-support-initials" aria-hidden="true">{{ $person['initials'] }}</span>
                        <p class="team-person-role">{{ $person['role'] }}</p>
                        <h3>{{ $person['name'] }}</h3>
                        <p class="team-support-qualification">{{ $person['qualification'] }}</p>
                        @if ($person['experience'])
                            <span class="team-support-experience">{{ $person['experience'] }}</span>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="team-values-section">
        <div class="site-container team-values-inner">
            <p class="eyebrow"><span class="eyebrow-line"></span> What brings us together</p>
            <blockquote>“Improve our society with the quality of our work, and share our technical expertise and human values with the world.”</blockquote>
            <span>GECO Africa · Company objective</span>
        </div>
    </section>

    <section class="team-contact-strip">
        <div class="site-container team-contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Work with GECO Africa</p>
                <h2>Let’s make the next project <em>a shared effort.</em></h2>
            </div>
            <div>
                <p>Speak with our team about geotechnical investigation, engineering design or project delivery.</p>
                <a class="button button-bright" href="mailto:info@gecoafrica.com?subject=Project%20enquiry">Contact our team <span aria-hidden="true">↗</span></a>
                <a class="team-phone-link" href="tel:+250788842990">+250 788 842 990</a>
            </div>
        </div>
    </section>
</main>
@endsection
