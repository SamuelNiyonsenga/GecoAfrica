<section class="home-laboratory-section" data-cms-section="laboratory" @if (!($homeSections['laboratory']['visible'] ?? true)) hidden @endif>
        <div class="home-laboratory-visual">
            <img src="{{ asset('images/home/fieldwork-drilling.webp') }}" width="1600" height="900" loading="lazy" decoding="async" alt="GECO Africa drilling team carrying out ground investigation work in Rwanda">
            <div><small>Ground investigation · Soil mechanics · Testing</small><strong>Evidence that helps<br>projects move forward.</strong></div>
        </div>
        <div class="home-laboratory-copy">
            <p class="eyebrow"><span class="eyebrow-line"></span> Soil mechanics &amp; geotechnical laboratory</p>
            <h2>From the field<br>to <em>the lab.</em></h2>
            <p>GECO combines core drilling and ground investigation with soil sampling and materials testing through its Soil Mechanics and Geotechnical Engineering Laboratory.</p>
            <div class="home-test-list" aria-label="Published laboratory test capabilities">
                @foreach ($laboratoryCapabilities as $capability)
                    <span>{{ $capability }}</span>
                @endforeach
            </div>
            <div class="home-resource-actions">
                <a class="button button-dark" href="{{ asset('assets/laboratory-price-list.pdf') }}" target="_blank" rel="noopener">Laboratory test price list <span aria-hidden="true">↗</span></a>
                <a class="home-resource-link" href="{{ asset('assets/geco-equipment-list.pdf') }}" target="_blank" rel="noopener">View GECO equipment list <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>
