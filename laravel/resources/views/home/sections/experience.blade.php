<section class="section experience-section home-experience-section" data-cms-section="experience" @if (!($homeSections['experience']['visible'] ?? true)) hidden @endif id="experience">
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
                    <img src="{{ asset('images/home/road-survey.webp') }}" width="1000" height="620" loading="lazy" decoding="async" alt="Surveyors carrying out a topographic survey for a road project">
                    <div><span>Fieldwork, designed around the real world</span><small>Survey · Investigation · Supervision</small></div>
                </div>
            </div>
        </div>
    </section>
