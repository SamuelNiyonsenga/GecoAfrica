@extends('layouts.admin')
@section('title', 'Overview')
@section('content')
<header class="page-heading">
    <div><p class="eyebrow">GECO Africa CMS</p><h1>Welcome, {{ Str::before(auth()->user()->name, ' ') }}</h1><p class="muted">Manage the content and messages on your website.</p></div>
    <a class="button button-outline" href="{{ route('home') }}" target="_blank" rel="noopener">View website</a>
</header>
<section class="stat-grid" aria-label="Content overview">
    <article class="stat-card"><span>Published content</span><strong>{{ $publishedCount }}</strong></article>
    <article class="stat-card"><span>Draft content</span><strong>{{ $draftCount }}</strong></article>
    <article class="stat-card"><span>New enquiries</span><strong>{{ $newEnquiries }}</strong></article>
    <article class="stat-card"><span>Total content records</span><strong>{{ $contentCount }}</strong></article>
</section>
<section class="panel">
    <div class="panel-heading"><div><p class="eyebrow">Getting started</p><h2>Your website content will appear here</h2></div></div>
    <p class="muted">The CMS is being connected to GECO Africa’s existing Laravel pages. Your current website remains available while content management is built.</p>
</section>
@endsection