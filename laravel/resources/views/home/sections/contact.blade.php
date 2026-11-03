<section class="contact-section" data-cms-section="contact" @if (!($homeSections['contact']['visible'] ?? true)) hidden @endif id="contact">
        <div class="site-container contact-inner">
            <div>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> {{ $contactEyebrow }}</p>
                <h2>{{ $contactHeading }}<br><em>{{ $contactAccentHeading }}</em></h2>
            </div>
            <div class="contact-action">
                <p>{{ $contactIntro }}</p>
                <a class="button button-bright" href="{{ url($contactLinkUrl) }}">{{ $contactLinkLabel }} <span aria-hidden="true">↗</span></a>
                <a class="contact-phone" href="tel:{{ preg_replace('/[^0-9+]/', '', $sitePrimaryPhone) }}">Or call <strong>{{ $sitePrimaryPhone }}</strong></a>
            </div>
            <svg class="contact-contour" viewBox="0 0 300 260" fill="none" aria-hidden="true"><path d="M296 9C224 6 151 15 93 47 36 78 10 119 8 157c-2 37 30 66 71 82 43 17 106 17 161 10M296 42c-61-1-119 8-165 33-46 26-66 58-66 86 0 28 23 48 56 61 34 12 82 13 124 8M295 77c-49 1-92 8-126 27-34 19-48 42-47 62 1 18 17 32 42 41 25 9 60 9 91 5M294 112c-35 2-64 8-87 20-22 13-31 27-29 39 2 12 13 21 31 27 17 5 39 6 58 3"/></svg>
        </div>
    </section>
