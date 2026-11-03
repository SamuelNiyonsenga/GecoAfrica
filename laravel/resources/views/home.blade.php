@extends('layouts.site')

@section('content')
<main id="main-content">
        @include('home.sections.hero')
    @include('home.sections.facts')
    @include('home.sections.services')
    @include('home.sections.projects')
    @include('home.sections.about')
    @include('home.sections.principles')
    @include('home.sections.laboratory')
    @include('home.sections.experience')
    @include('home.sections.contact')

<script>
(() => {
    const settings = @json($homeSections ?? []);
    const main = document.getElementById('main-content');
    if (!main) return;
    Object.entries(settings).sort((a, b) => (a[1].order ?? 999) - (b[1].order ?? 999)).forEach(([key, setting]) => {
        const section = main.querySelector(`[data-cms-section="${key}"]`);
        if (section) { section.hidden = setting.visible === false; main.appendChild(section); }
    });
})();
</script></main>
@endsection
