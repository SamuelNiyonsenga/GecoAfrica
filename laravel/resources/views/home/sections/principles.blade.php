<section class="section home-principles-section" data-cms-section="principles" @if (!($homeSections['principles']['visible'] ?? true)) hidden @endif>
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow"><span class="eyebrow-line"></span> {{ $principlesEyebrow }}</p>
                    <h2>{{ $principlesHeading }}<br><em>{{ $principlesAccentHeading }}</em></h2>
                </div>
                <p class="heading-aside">{{ $principlesIntro }}</p>
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
