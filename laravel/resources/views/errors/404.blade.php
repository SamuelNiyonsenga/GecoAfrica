@extends('layouts.site')

@section('content')
@section('robots', 'noindex')
<main class="error-page site-container" id="main-content">
    <p class="eyebrow"><span class="eyebrow-line"></span> Page not found</p>
    <h1>We couldn’t find that page.</h1>
    <p>The address may have changed, or the page may no longer be available.</p>
    <a class="button button-dark" href="{{ route('home') }}">Go to Home <span aria-hidden="true">↗</span></a>
    <nav aria-label="Explore GECO Africa"><a href="{{ route('services') }}">Services</a><a href="{{ route('projects') }}">Projects</a><a href="{{ route('contact') }}">Contact</a></nav>
</main>
@endsection
