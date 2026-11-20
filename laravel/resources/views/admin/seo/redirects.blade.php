@extends('layouts.admin')
@section('title', 'Redirects')
@section('content')
<header class="page-heading"><div><p class="eyebrow">Search &amp; email</p><h1>Redirects</h1><p class="muted">Send visitors and Google from an old web address to a new one, so links and search rankings are not lost.</p></div></header>
<div class="two-column">
    <section class="panel">
        <h2>Add a redirect</h2>
        <p class="muted">When you change the web address of a published article, service or project, a redirect is added here automatically.</p>
        <form method="post" action="{{ route('admin.redirects.store') }}" class="form-stack">
            @csrf
            <label for="from-path">Old address</label>
            <input id="from-path" name="from_path" value="{{ old('from_path') }}" placeholder="/old-page" required>
            @error('from_path')<p class="field-error">{{ $message }}</p>@enderror
            <label for="to-path">New address</label>
            <input id="to-path" name="to_path" value="{{ old('to_path') }}" placeholder="/services/geotechnical-engineering" required>
            @error('to_path')<p class="field-error">{{ $message }}</p>@enderror
            <p class="help-text">Paste full links or just the part after the domain. Redirects are permanent (301).</p>
            <button class="button button-primary" type="submit">Save redirect</button>
        </form>
    </section>
    <section class="panel">
        <h2>Active redirects</h2>
        @if ($redirects->isEmpty())
            <p class="empty-state">No redirects yet.</p>
        @else
            <div class="table-scroll"><table class="content-table"><thead><tr><th>From</th><th>To</th><th>Used</th><th></th></tr></thead><tbody>
            @foreach ($redirects as $redirect)
                <tr>
                    <td><code>{{ $redirect->from_path }}</code>@if($redirect->source === 'auto')<br><small class="muted">Added automatically</small>@endif</td>
                    <td><code>{{ $redirect->to_path }}</code></td>
                    <td>{{ $redirect->hits }} {{ \Illuminate\Support\Str::plural('time', $redirect->hits) }}@if($redirect->last_hit_at)<br><small class="muted">{{ $redirect->last_hit_at->diffForHumans() }}</small>@endif</td>
                    <td><form method="post" action="{{ route('admin.redirects.destroy', $redirect) }}" onsubmit="return confirm('Remove this redirect? The old address will show “page not found”.')">@csrf @method('DELETE')<button class="button button-small button-danger">Remove</button></form></td>
                </tr>
            @endforeach
            </tbody></table></div>
            <div class="pagination-wrap">{{ $redirects->links('vendor.pagination.cms') }}</div>
        @endif
    </section>
</div>
@endsection
