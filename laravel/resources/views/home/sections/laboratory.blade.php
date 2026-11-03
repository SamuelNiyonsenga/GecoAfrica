<section class="home-laboratory-section" data-cms-section="laboratory" @if (!($homeSections['laboratory']['visible'] ?? true)) hidden @endif>
        <div class="home-laboratory-visual">
            <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" loading="lazy" decoding="async" alt="GECO Africa drilling team carrying out ground investigation work in Rwanda">
            <div><small>{{ $labVisualLabel }}</small><strong>{!! nl2br(e($labVisualHeading)) !!}</strong></div>
        </div>
        <div class="home-laboratory-copy">
            <p class="eyebrow"><span class="eyebrow-line"></span> {{ $labEyebrow }}</p>
            <h2>{{ $labHeading }}<br><em>{{ $labAccentHeading }}</em></h2>
            <p>{{ $labText }}</p>
            <div class="home-test-list" aria-label="Published laboratory test capabilities">
                @foreach ($labTests as $capability)
                    <span>{{ $capability }}</span>
                @endforeach
            </div>
            <div class="home-resource-actions">
                <a class="button button-dark" href="{{ asset($labPriceListUrl) }}" target="_blank" rel="noopener">{{ $labPriceListLabel }} <span aria-hidden="true">↗</span></a>
                <a class="home-resource-link" href="{{ asset($labEquipmentUrl) }}" target="_blank" rel="noopener">{{ $labEquipmentLabel }} <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>
