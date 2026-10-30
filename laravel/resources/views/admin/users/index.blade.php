@extends('layouts.admin')
@section('title', 'Users')
@section('content')
<header class="page-heading"><div><p class="eyebrow">System</p><h1>Users</h1><p class="muted">Every active user has the same CMS access.</p></div></header>
<div class="two-column">
    <section class="panel">
        <div class="panel-heading"><div><h2>Add a user</h2><p class="muted">They can sign in after you share their details securely.</p></div></div>
        <form method="post" action="{{ route('admin.users.store') }}" class="form-stack">
            @csrf
            <label for="name">Name</label><input id="name" name="name" value="{{ old('name') }}" maxlength="120" required>
            <label for="new-email">Email</label><input id="new-email" name="email" type="email" value="{{ old('email') }}" required>
            <label for="new-password">Temporary password</label><input id="new-password" name="password" type="password" minlength="12" autocomplete="new-password" required>
            <p class="help-text">Use at least 12 characters. The user can change it after signing in.</p>
            <button class="button button-primary" type="submit">Create user</button>
        </form>
    </section>
    <section class="panel">
        <div class="panel-heading"><div><h2>CMS accounts</h2><p class="muted">{{ $users->count() }} account{{ $users->count() === 1 ? '' : 's' }}</p></div></div>
        <div class="user-list">
            @forelse ($users as $user)
                <article class="user-row">
                    <div class="user-details"><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span><span class="status-pill {{ $user->is_active ? 'status-active' : 'status-inactive' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></div>
                    <div class="user-actions">
                        <form method="post" action="{{ route('admin.users.password', $user) }}" class="inline-form">
                            @csrf @method('PUT')
                            <label class="sr-only" for="password-{{ $user->id }}">New password for {{ $user->name }}</label>
                            <input id="password-{{ $user->id }}" name="password" type="password" minlength="12" placeholder="New password" required>
                            <button class="button button-small button-outline" type="submit">Set password</button>
                        </form>
                        <form method="post" action="{{ route('admin.users.active', $user) }}">
                            @csrf @method('PATCH')<input type="hidden" name="active" value="{{ $user->is_active ? '0' : '1' }}">
                            <button class="button button-small {{ $user->is_active ? 'button-danger' : 'button-outline' }}" type="submit" @disabled($user->is(auth()->user()))>{{ $user->is_active ? 'Deactivate' : 'Reactivate' }}</button>
                        </form>
                    </div>
                </article>
            @empty <p class="muted">No CMS accounts yet.</p> @endforelse
        </div>
    </section>
</div>
@endsection