<section class="about-section" data-cms-section="about" @if (!($homeSections['about']['visible'] ?? true)) hidden @endif id="about">
        <div class="about-image-wrap">
            <img src="{{ asset('images/home/residential-project.webp') }}" width="1500" height="850" loading="lazy" decoding="async" alt="Residential building in Rwanda, part of the built environment GECO Africa supports">
            <span class="about-image-label"><span></span> Local expertise · Since 2007</span>
        </div>
        <div class="about-copy">
            <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> {{ $aboutEyebrow }}</p>
            <h2>{{ $aboutHeading }}<br><em>{{ $aboutAccentHeading }}</em></h2>
            <p class="about-lede">{{ $aboutLede }}</p>
            <p class="about-body">{{ $aboutBody }}</p>
            <a class="button button-outline" href="{{ url($aboutLinkUrl) }}">{{ $aboutLinkLabel }} <span aria-hidden="true">↗</span></a>
            <div class="about-signature"><span class="signature-line"></span><span>{{ $aboutSignature }}<br><strong>{{ $aboutSignatureAccent }}</strong></span></div>
        </div>
    </section>
