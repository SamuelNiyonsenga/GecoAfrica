@extends('layouts.admin')
@section('title', 'Pages')
@section('content')
<header class="page-heading"><div><p class="eyebrow">Website</p><h1>Pages</h1><p class="muted">Manage page titles, search details, and structured page content.</p></div></header>
<section class="panel"><div class="table-scroll"><table class="content-table"><thead><tr><th>Page</th><th>Address</th><th>Language</th><th>Status</th><th></th></tr></thead><tbody>
@foreach ($labels as $key => $label)
    @php($translation = $pages->firstWhere('page_key', $key)?->translations->firstWhere('locale_code', 'en'))
    <tr><td><strong>{{ $label }}</strong></td><td><code>/{{ $key === 'home' ? '' : $key }}</code></td><td>English</td><td><span class="status-pill {{ $translation?->published_at ? 'status-active' : 'status-draft' }}">{{ $translation?->published_at ? 'Published' : 'Draft' }}</span></td><td><a class="button button-small button-outline" href="{{ route('admin.pages.edit', $key) }}">Edit</a></td></tr>
@endforeach
</tbody></table></div></section>
@endsection