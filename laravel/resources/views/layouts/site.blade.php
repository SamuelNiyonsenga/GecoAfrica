<!doctype html>
<html lang="en-RW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#00376d">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <title>{{ $pageTitle ?? 'GECO Africa | Engineering the foundations of progress' }}</title>
    <meta name="description" content="{{ $pageDescription ?? 'Civil engineering, geotechnical and infrastructure consultancy in Rwanda.' }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? 'https://gecoafrica.com/' }}">
    <link rel="icon" href="{{ asset('images/geco-icon.jpg') }}" type="image/jpeg">
    @if (!empty($preloadImage))
        <link rel="preload" as="image" href="{{ $preloadImage }}" fetchpriority="high">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_RW">
    <meta property="og:site_name" content="GECO Africa">
    <meta property="og:title" content="{{ $pageTitle ?? 'GECO Africa | Engineering the foundations of progress' }}">
    <meta property="og:description" content="{{ $pageDescription ?? 'Civil engineering, geotechnical and infrastructure consultancy in Rwanda.' }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? 'https://gecoafrica.com/' }}">
    <meta property="og:image" content="{{ $ogImage ?? 'https://gecoafrica.com/images/home/fieldwork-drilling.webp' }}">
    <meta property="og:image:alt" content="{{ $ogImageAlt ?? 'GECO Africa engineering and geotechnical work in Rwanda' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?? 'GECO Africa | Engineering the foundations of progress' }}">
    <meta name="twitter:description" content="{{ $pageDescription ?? 'Civil engineering, geotechnical and infrastructure consultancy in Rwanda.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? 'https://gecoafrica.com/images/home/fieldwork-drilling.webp' }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script type="application/ld+json">@json($structuredData)</script>
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>

    <div class="utility-bar">
        <div class="site-container utility-inner">
            <p><span class="utility-dot" aria-hidden="true"></span> Engineering the foundations of progress</p>
            <div class="utility-links">
                <a href="mailto:info@gecoafrica.com">info@gecoafrica.com</a>
                <span aria-hidden="true">/</span>
                <a href="tel:+250788842990">+250 788 842 990</a>
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
                <a href="{{ route('about') }}" @class(['nav-current' => request()->routeIs('about')]) @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
                <a href="{{ route('services') }}" @class(['nav-current' => request()->routeIs('services')]) @if(request()->routeIs('services')) aria-current="page" @endif>Services</a>
                <a href="{{ route('projects') }}" @class(['nav-current' => request()->routeIs('projects')]) @if(request()->routeIs('projects')) aria-current="page" @endif>Projects</a>
                <a href="{{ route('gallery') }}" @class(['nav-current' => request()->routeIs('gallery')]) @if(request()->routeIs('gallery')) aria-current="page" @endif>Gallery</a>
                <a href="{{ route('activities') }}" @class(['nav-current' => request()->routeIs('activities')]) @if(request()->routeIs('activities')) aria-current="page" @endif>Activities</a>
                <a href="{{ route('blog') }}" @class(['nav-current' => request()->routeIs('blog', 'blog.show')]) @if(request()->routeIs('blog', 'blog.show')) aria-current="page" @endif>Insights</a>
                <a href="{{ route('team') }}" @class(['nav-current' => request()->routeIs('team')]) @if(request()->routeIs('team')) aria-current="page" @endif>Team</a>
                <a href="{{ route('partners') }}" @class(['nav-current' => request()->routeIs('partners')]) @if(request()->routeIs('partners')) aria-current="page" @endif>Partners</a>
                <a href="{{ route('home') }}#experience">Experience</a>
                <a href="{{ route('contact') }}" @class(['nav-current' => request()->routeIs('contact')]) @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
                <a class="nav-cta" href="mailto:info@gecoafrica.com?subject=Project%20enquiry">Start a conversation <span aria-hidden="true">↗</span></a>
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
            </div>
            <div class="footer-column">
                <span class="footer-label">Explore</span>
                <a href="{{ route('about') }}">About GECO</a>
                <a href="{{ route('services') }}">Our services</a>
                <a href="{{ route('projects') }}">Project references</a>
                <a href="{{ route('gallery') }}">Photo gallery</a>
                <a href="{{ route('activities') }}">Activities archive</a>
                <a href="{{ route('blog') }}">Insights &amp; articles</a>
                <a href="{{ route('team') }}">Our team</a>
                <a href="{{ route('partners') }}">Our partners</a>
                <a href="{{ route('home') }}#experience">Our experience</a>
            </div>
            <div class="footer-column footer-contact">
                <span class="footer-label">Get in touch</span>
                <a href="{{ route('contact') }}">Contact page</a>
                <a href="mailto:info@gecoafrica.com">info@gecoafrica.com</a>
                <a href="tel:+250788842990">+250 788 842 990</a>
                <a href="tel:+250788572346">+250 788 572 346</a>
                <span>Avenue des Poids Lourds<br>Source Oil Petrol Station building<br>P.O. Box 3711, Kigali, Rwanda</span>
            </div>
        </div>
        <div class="site-container footer-bottom">
            <span>© {{ date('Y') }} GECO Africa Ltd. All rights reserved.</span>
            <span>Engineering &amp; construction consultancy · Rwanda</span>
        </div>
    </footer>

    <script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
