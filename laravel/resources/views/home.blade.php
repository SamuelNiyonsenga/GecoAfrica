@extends('layouts.site')

@section('content')
<main id="main-content">
    @php
        $homePartialKeys = ['hero', 'facts', 'services', 'projects', 'about', 'principles', 'laboratory', 'experience', 'contact'];
        $orderedHomePartials = collect($homePartialKeys)->sortBy(fn ($key) => (int) ($homeSections[$key]['order'] ?? (array_search($key, $homePartialKeys, true) + 1)));
    @endphp
    @foreach ($orderedHomePartials as $sectionKey)
        @if (($homeSections[$sectionKey]['visible'] ?? true) !== false)
            @include('home.sections.'.$sectionKey)
        @endif
    @endforeach
</main>
@endsection
