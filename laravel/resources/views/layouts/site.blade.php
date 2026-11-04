<!doctype html>
<html lang="en-RW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (config('app.noindex'))<meta name="robots" content="noindex, nofollow">@endif
    <meta name="theme-color" content="#00376d">
    @hasSection('robots')<meta name="robots" content="@yield('robots')">@else<meta name="robots" content="index, follow, max-image-preview:large">@endif
    <title>{{ $pageTitle ?? 'GECO Africa | Engineering the foundations of progress' }}</title>
    <meta name="description" content="{{ $pageDescription ?? 'Civil engineering, geotechnical and infrastructure consultancy in Rwanda.' }}">
    <link rel="canonical" href="{{ \App\Support\Canonical::current() }}">
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/inter-latin-wght-normal.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/source-serif-4-latin-wght-normal.woff2') }}" crossorigin>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon-32.png') }}" type="image/png" sizes="32x32">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">
    @if (!empty($preloadImage))
        {{-- Preload this site's own copy, even when the stored value is a gecoafrica.com address. --}}
        <link rel="preload" as="image" href="{{ asset(ltrim(preg_replace('#^https?://(www\.)?gecoafrica\.com/#i', '', $preloadImage), '/')) }}" fetchpriority="high">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_RW">
    <meta property="og:site_name" content="GECO Africa">
    <meta property="og:title" content="{{ $pageTitle ?? 'GECO Africa | Engineering the foundations of progress' }}">
    <meta property="og:description" content="{{ $pageDescription ?? 'Civil engineering, geotechnical and infrastructure consultancy in Rwanda.' }}">
    <meta property="og:url" content="{{ \App\Support\Canonical::current() }}">
    <meta property="og:image" content="{{ $ogImage ?? 'https://gecoafrica.com/images/home/fieldwork-drilling.webp' }}">
    <meta property="og:image:alt" content="{{ $ogImageAlt ?? 'GECO Africa engineering and geotechnical work in Rwanda' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?? 'GECO Africa | Engineering the foundations of progress' }}">
    <meta name="twitter:description" content="{{ $pageDescription ?? 'Civil engineering, geotechnical and infrastructure consultancy in Rwanda.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? 'https://gecoafrica.com/images/home/fieldwork-drilling.webp' }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    @if (!empty($structuredData))<script type="application/ld+json">@json($structuredData)</script>@endif
    @stack('head')
</head>
<body id="top">
    @php
        $siteSettings = $cmsSiteSettings ?? [];
        $sitePhones = is_array($siteSettings['phone_numbers'] ?? null) ? array_values($siteSettings['phone_numbers']) : [];
        $primaryPhone = $sitePrimaryPhone ?? ($sitePhones[0] ?? '+250 788 842 990');
        $secondaryPhone = $siteSecondaryPhone ?? ($sitePhones[1] ?? '+250 788 572 346');
        $publicEmail = $sitePublicEmail ?? ($siteSettings['public_email'] ?? 'info@gecoafrica.com');
    @endphp
    <a class="skip-link" href="#main-content">Skip to content</a>
    @if (!empty($cmsPreview) && $cmsPreview->active())
        <aside class="cms-preview-banner" role="status">
            <strong>Preview — this version is not published yet</strong>
            @if ($cmsPreview->pageKey())
                <a href="{{ route('admin.pages.edit', $cmsPreview->pageKey()) }}">Back to editor</a>
                <form method="post" action="{{ route('admin.pages.publish', $cmsPreview->pageKey()) }}">@csrf<button type="submit">Publish now</button></form>
            @elseif ($cmsPreview->entryId() && $cmsPreview->section())
                <a href="{{ route('admin.content.edit', [$cmsPreview->section(), $cmsPreview->entryId()]) }}">Back to editor</a>
                <form method="post" action="{{ route('admin.content.publish', [$cmsPreview->section(), $cmsPreview->entryId()]) }}">@csrf @method('PUT')<button type="submit">Publish now</button></form>
            @endif
        </aside>
    @endif
    <!-- TODO(client): provide a crisp SVG GECO logo in full-colour and white versions to replace the current 203×167 PNG, whose embedded tagline is not legible at small sizes. -->

    <div class="utility-bar">
        <div class="site-container utility-inner">
            <p><span class="utility-dot" aria-hidden="true"></span> {{ $siteSettings['tagline'] ?? 'Engineering the foundations of progress' }}</p>
            <div class="utility-links">
                <a href="mailto:{{ $publicEmail }}">{{ $publicEmail }}</a>
                <span aria-hidden="true">/</span>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryPhone) }}">{{ $primaryPhone }}</a>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="site-container header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="GECO Africa home">
                <img class="brand-logo" src="{{ asset('images/geco-logo.png') }}" width="84" height="84" alt="GECO Africa — Geotechnical Engineering Construction Company">
            </a>

            <button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="primary-navigation">
                <span></span><span></span>
            </button>

            <nav class="primary-navigation" id="primary-navigation" aria-label="Main navigation">
                <a href="{{ route('home') }}" @class(['nav-current' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
                <div class="nav-group {{ request()->routeIs('about', 'team', 'partners') ? 'nav-group-current' : '' }}">
                    <a href="{{ route('about') }}" @class(['nav-current' => request()->routeIs('about', 'team', 'partners')]) @if(request()->routeIs('about', 'team', 'partners')) aria-current="page" @endif>About</a>
                    <button class="nav-group-toggle" type="button" aria-label="Show About pages" aria-expanded="false" aria-controls="about-menu"><span aria-hidden="true">▾</span></button>
                    <div class="nav-submenu" id="about-menu" hidden>
                        <a href="{{ route('about') }}">About GECO</a><a href="{{ route('team') }}" @if(request()->routeIs('team')) aria-current="page" @endif>Team</a><a href="{{ route('partners') }}" @if(request()->routeIs('partners')) aria-current="page" @endif>Partners</a><a href="{{ route('home') }}#experience">Experience</a>
                    </div>
                </div>
                <a href="{{ route('services') }}" @class(['nav-current' => request()->routeIs('services')]) @if(request()->routeIs('services')) aria-current="page" @endif>Services</a>
                <div class="nav-group {{ request()->routeIs('projects', 'activities') ? 'nav-group-current' : '' }}">
                    <a href="{{ route('projects') }}" @class(['nav-current' => request()->routeIs('projects', 'activities')]) @if(request()->routeIs('projects', 'activities')) aria-current="page" @endif>Projects</a>
                    <button class="nav-group-toggle" type="button" aria-label="Show project pages" aria-expanded="false" aria-controls="projects-menu"><span aria-hidden="true">▾</span></button>
                    <div class="nav-submenu" id="projects-menu" hidden>
                        <a href="{{ route('projects') }}">Project references</a><a href="{{ route('activities') }}" @if(request()->routeIs('activities')) aria-current="page" @endif>Activities</a>
                    </div>
                </div>
                <a href="{{ route('gallery') }}" @class(['nav-current' => request()->routeIs('gallery')]) @if(request()->routeIs('gallery')) aria-current="page" @endif>Gallery</a>
                <a href="{{ route('blog') }}" @class(['nav-current' => request()->routeIs('blog', 'blog.show')]) @if(request()->routeIs('blog', 'blog.show')) aria-current="page" @endif>Insights</a>
                <a href="{{ route('contact') }}" @class(['nav-current' => request()->routeIs('contact')]) @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
                <a class="nav-cta" href="mailto:{{ $publicEmail }}?subject=Project%20enquiry">Start a conversation <span aria-hidden="true">↗</span></a>
                <div class="mobile-nav-contact"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryPhone) }}">{{ $primaryPhone }}</a><a href="mailto:{{ $publicEmail }}">{{ $publicEmail }}</a></div>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="site-footer">
        <div class="site-container footer-main">
            <div class="footer-brand-block">
                <a class="brand brand-light" href="{{ route('home') }}" aria-label="GECO Africa home">
                    <img class="brand-logo" src="{{ asset('images/geco-logo.png') }}" width="84" height="84" alt="GECO Africa — Geotechnical Engineering Construction Company">
                </a>
                <p>Grounded in local knowledge.<br>Built for lasting impact.</p>
                @php($socialNames = ['linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'x' => 'X'])
                @php($socialLinks = collect(is_array($siteSettings['social_links'] ?? null) ? $siteSettings['social_links'] : [])->filter(fn ($url) => is_string($url) && filter_var($url, FILTER_VALIDATE_URL) && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)))
                {{-- Links appear once they are filled in under Admin → Site settings. TODO(client): provide GECO's social media addresses. --}}
                @if ($socialLinks->isNotEmpty())
                    <nav class="footer-social" aria-label="GECO Africa on social media">
                        @foreach ($socialNames as $socialKey => $socialLabel)
                            @if ($socialLinks->has($socialKey))
                                <a href="{{ $socialLinks[$socialKey] }}" target="_blank" rel="noopener noreferrer">{{ $socialLabel }} <span aria-hidden="true">↗</span><span class="sr-only"> (opens in a new tab)</span></a>
                            @endif
                        @endforeach
                    </nav>
                @endif
            </div>
            <div class="footer-column">
                <span class="footer-label">Explore</span>
                <a href="{{ route('about') }}">About GECO</a>
                <a href="{{ route('services') }}">Our services</a>
                <a href="{{ route('projects') }}">Project references</a>
                <a href="{{ route('gallery') }}">Photo gallery</a>
                <a href="{{ route('activities') }}">Activities</a>
                <a href="{{ route('blog') }}">Insights &amp; articles</a>
                <a href="{{ route('team') }}">Our team</a>
                <a href="{{ route('partners') }}">Our partners</a>
                <a href="{{ route('home') }}#experience">Our experience</a>
            </div>
            @if (!empty($footerServices))
            <div class="footer-column footer-services">
                <span class="footer-label">Our services</span>
                @foreach ($footerServices as $footerService)
                    <a href="{{ route('services') }}{{ $footerService['slug'] !== '' ? '#'.$footerService['slug'] : '' }}">{{ $footerService['title'] }}</a>
                @endforeach
            </div>
            @endif
            <div class="footer-column footer-contact">
                <span class="footer-label">Get in touch</span>
                <a href="{{ route('contact') }}">Contact page</a>
                <a href="mailto:{{ $publicEmail }}">{{ $publicEmail }}</a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryPhone) }}">{{ $primaryPhone }}</a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $secondaryPhone) }}">{{ $secondaryPhone }}</a>
                <span>{{ $siteAddress ?? ($siteSettings['address'] ?? 'Avenue des Poids Lourds, Source Oil Petrol Station building') }}<br>P.O. Box {{ $sitePoBox ?? ($siteSettings['po_box'] ?? '3711') }}, {{ $siteCity ?? ($siteSettings['city'] ?? 'Kigali') }}, {{ $siteCountry ?? ($siteSettings['country'] ?? 'Rwanda') }}</span>
                @php($openingHours = array_values(array_filter(is_array($siteSettings['opening_hours'] ?? null) ? $siteSettings['opening_hours'] : [])))
                @if ($openingHours)
                    <span class="footer-hours">@foreach ($openingHours as $line){{ $line }}@if(!$loop->last)<br>@endif @endforeach</span>
                @endif
            </div>
        </div>
        <div class="site-container footer-bottom">
            <span>© {{ date('Y') }} {{ $siteSettings['business_name'] ?? 'GECO Africa Ltd' }}. All rights reserved.</span>
            <span>Engineering &amp; construction consultancy · Rwanda</span>
            <a class="back-to-top" href="#top">Back to top ↑</a>
        </div>
    </footer>

    <script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>