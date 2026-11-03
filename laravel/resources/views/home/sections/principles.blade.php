<section class="section home-principles-section" data-cms-section="principles" @if (!($homeSections['principles']['visible'] ?? true)) hidden @endif>
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