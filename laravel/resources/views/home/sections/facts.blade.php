<section class="home-facts-strip" data-cms-section="facts" @if (!($homeSections['facts']['visible'] ?? true)) hidden @endif id="home-at-a-glance" aria-label="GECO Africa at a glance">
        <div class="site-container home-facts-grid">
            @foreach ($factsItems as $item)
                <div><strong>{{ $item['value'] }}@if($item['suffix'] !== '')<span>{{ $item['suffix'] }}</span>@endif</strong><span>@foreach(explode("\n", $item['label']) as $line){{ $line }}@if(!$loop->last)<br>@endif @endforeach</span></div>
            @endforeach
        </div>
    </section>
