<section class="section home-projects-section" data-cms-section="projects" @if (!($homeSections['projects']['visible'] ?? true)) hidden @endif id="home-projects">
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

            <div class="home-project-grid home-scroll-row" id="home-projects-row" data-home-scroll-row="projects" role="region" aria-label="Selected GECO Africa project references" tabindex="0">
                @foreach ($featuredProjects as $project)
                    <article class="home-project-card" data-reveal>
                        <a class="home-project-image" href="{{ route('projects') }}#project-references" aria-label="View GECO project references: {{ $project['title'] }}">
                            <img src="{{ asset($project['image']) }}" width="1200" height="900" alt="{{ $project['imageAlt'] }}" loading="lazy" decoding="async">
                            <span>{{ $project['number'] }}</span>
                        </a>
                        <div class="home-project-copy">
                            <p class="home-project-category">{{ $project['category'] }}</p>
                            <p class="home-project-location">{{ $project['location'] }}</p>
                            <h3>{{ $project['title'] }}</h3>
                            <div class="home-project-description"><span>{{ $project['summary'] }}</span><a href="{{ route('projects') }}#project-references" aria-label="Explore project references" title="Explore project references">↗</a></div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="home-row-controls" role="group" aria-label="Scroll through selected projects"><button type="button" data-home-row-prev="projects" aria-label="Previous projects">←</button><button type="button" data-home-row-next="projects" aria-label="Next projects">→</button></div>

            <div class="home-project-footnote"><span>{{ $projectReferenceCount }}</span><p>GECO’s references span geotechnical investigations, roads and transport, and building and civil works.</p><a href="{{ route('projects') }}#project-references">Browse project references <span aria-hidden="true">↗</span></a><a href="{{ route('activities') }}">Browse dated activities <span aria-hidden="true">↗</span></a></div>
        </div>
    </section>
