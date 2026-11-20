@extends('layouts.admin')
@section('title', 'SEO health')
@section('content')
<header class="page-heading"><div><p class="eyebrow">Search &amp; email</p><h1>SEO health</h1><p class="muted">How each public page will look in Google, and what to improve. Pages with the most to fix are listed first.</p></div><a class="button button-outline" href="{{ route('admin.settings.edit') }}#seo-settings">SEO settings</a></header>

@if ($siteHidden)
    <div class="notice notice-error" role="alert"><strong>The whole website is hidden from search engines.</strong> Untick "Hide the whole website from search engines" in <a href="{{ route('admin.settings.edit') }}#seo-settings">Site settings</a> when the site is ready to be found (or remove APP_NOINDEX from the server's .env).</div>
@endif

<section class="stat-grid" aria-label="SEO overview">
    <article class="stat-card"><span>Pages checked</span><strong>{{ count($rows) }}</strong></article>
    <article class="stat-card"><span>Things to improve</span><strong>{{ $issueCount }}</strong></article>
    <article class="stat-card"><span>Google Search Console</span><strong style="font-size:18px">{{ !empty($settings['google_site_verification']) ? 'Connected' : 'Not set up' }}</strong></article>
    <article class="stat-card"><span>Sitemap for Google</span><strong style="font-size:15px"><a href="{{ route('sitemap') }}" target="_blank" rel="noopener">sitemap.xml ↗</a></strong></article>
</section>

<section class="panel">
    <div class="table-scroll"><table class="content-table health-table">
        <thead><tr><th>Page</th><th>In Google</th><th>To improve</th><th></th></tr></thead>
        <tbody>
        @foreach ($rows as $row)
            <tr>
                <td><strong>{{ $row['name'] }}</strong><br><small class="muted">{{ $row['type'] }}</small>@if($row['url'])<br><a href="{{ $row['url'] }}" target="_blank" rel="noopener"><small>View ↗</small></a>@endif</td>
                <td><div class="google-preview"><span class="google-preview-title">{{ \Illuminate\Support\Str::limit($row['title'], 62) }}</span><span class="google-preview-description">{{ \Illuminate\Support\Str::limit($row['description'], 160) }}</span></div></td>
                <td>@if($row['issues'])<ul class="health-issues">@foreach($row['issues'] as $issue)<li>{{ $issue }}</li>@endforeach</ul>@else<span class="health-ok">Looks good</span>@endif</td>
                <td><a class="button button-small button-outline" href="{{ $row['edit'] }}">Edit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
</section>
@endsection
