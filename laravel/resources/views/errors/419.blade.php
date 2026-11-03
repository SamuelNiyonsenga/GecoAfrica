@extends('layouts.site')

@section('content')
@section('robots', 'noindex')
<main class="error-page site-container" id="main-content">
    <p class="eyebrow"><span class="eyebrow-line"></span> Page expired</p>
    <h1>Please refresh and try again.</h1>
    <p>Your session expired before this request could be completed.</p>
    <a class="button button-dark" href="{{ route('home') }}">Go to Home <span aria-hidden="true">↗</span></a>
    <nav aria-label="Explore GECO Africa"><a href="{{ route('services') }}">Services</a><a href="{{ route('projects') }}">Projects</a><a href="{{ route('contact') }}">Contact</a></nav>
</main>
@endsection
