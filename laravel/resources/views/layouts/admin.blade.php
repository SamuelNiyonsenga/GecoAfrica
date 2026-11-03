<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title', 'CMS') · GECO Africa</title><link rel="icon" href="{{ asset('images/geco-mark.svg') }}" type="image/svg+xml"><link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}"></head>
<body class="admin-body"><div class="admin-shell">
<header class="admin-mobile-top"><a href="{{ route('admin.dashboard') }}" aria-label="GECO Africa CMS home"><img src="{{ asset('images/geco-logo.png') }}" alt="GECO Africa"></a><button type="button" class="button button-outline" data-admin-menu-open aria-controls="admin-sidebar" aria-expanded="false">Menu</button></header>
<div class="admin-sidebar-backdrop" data-admin-menu-backdrop hidden></div>
<aside class="admin-sidebar" id="admin-sidebar" aria-label="CMS sidebar"><a class="admin-brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/geco-logo.png') }}" alt="GECO Africa"><span>Content management</span></a>
<nav class="admin-nav" aria-label="CMS navigation">
<a href="{{ route('admin.dashboard') }}" @class(['is-current' => request()->routeIs('admin.dashboard')])>Overview</a>
<a href="{{ route('admin.enquiries.index') }}" @class(['is-current' => request()->routeIs('admin.enquiries.*')])>Enquiries @if(($adminNewEnquiries ?? 0) > 0)<span class="nav-badge">{{ $adminNewEnquiries }}</span>@endif</a>
<span class="nav-label">Website</span><a href="{{ route('admin.pages.index') }}" @class(['is-current' => request()->routeIs('admin.pages.*')])>Pages</a>
<details class="admin-nav-group" data-nav-group @if(request()->routeIs('admin.content.*')) open @endif><summary>Content</summary><div>
@foreach(['services' => 'Services', 'project-groups' => 'Project groups', 'projects' => 'Project references', 'featured-projects' => 'Featured projects', 'team' => 'Team', 'partners' => 'Partners', 'gallery' => 'Gallery', 'gallery-categories' => 'Gallery categories', 'blog' => 'Blog posts', 'blog-categories' => 'Blog categories', 'activities' => 'Activities'] as $section => $label)<a href="{{ route('admin.content.index', $section) }}" @class(['is-current' => request()->route('section') === $section])>{{ $label }}</a>@endforeach
</div></details>
<a href="{{ route('admin.media.index') }}" @class(['is-current' => request()->routeIs('admin.media.*')])>Media library</a><span class="nav-label">Settings</span><a href="{{ route('admin.settings.edit') }}" @class(['is-current' => request()->routeIs('admin.settings.*')])>Site settings</a><a href="{{ route('admin.users.index') }}" @class(['is-current' => request()->routeIs('admin.users.*')])>Users</a><a href="{{ route('admin.activity.index') }}" @class(['is-current' => request()->routeIs('admin.activity.*')])>Activity log</a><a href="{{ route('admin.trash.index') }}" @class(['is-current' => request()->routeIs('admin.trash.*')])>Trash</a>
</nav>
<form method="post" action="{{ route('admin.logout') }}" class="admin-logout">@csrf<span>{{ auth()->user()->name }}</span><button type="submit" class="button button-quiet">Sign out</button></form>
</aside>
<main class="admin-main" id="main-content"><div class="admin-topbar"><span>@yield('title', 'CMS')</span><a href="{{ route('home') }}" target="_blank" rel="noopener">View website ↗</a></div>
@if (session('success'))<div class="notice notice-success" role="status" data-auto-dismiss>{{ session('success') }}</div>@endif
@if ($errors->any())<div class="notice notice-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')
</main></div><script src="{{ asset('js/admin-structured.js') }}?v={{ filemtime(public_path('js/admin-structured.js')) }}" defer></script></body></html>
