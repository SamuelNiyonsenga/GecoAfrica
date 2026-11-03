<section class="section services-section" data-cms-section="services" @if (!($homeSections['services']['visible'] ?? true)) hidden @endif id="services">
        <div class="site-container">
            <div class="section-heading services-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> What we do</p>
                    <h2>One team.<br><em>Every critical layer.</em></h2>
                </div>
                <p class="heading-aside">From the ground below to the infrastructure above, we bring connected expertise to each stage of a project.</p>
            </div>

            <div class="services-grid home-scroll-row" id="home-services-row" data-home-scroll-row="services" role="region" aria-label="GECO Africa services" tabindex="0">
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
            <div class="home-row-controls" role="group" aria-label="Scroll through services"><button type="button" data-home-row-prev="services" aria-label="Previous services">←</button><button type="button" data-home-row-next="services" aria-label="Next services">→</button></div>
        </div>
    </section>
