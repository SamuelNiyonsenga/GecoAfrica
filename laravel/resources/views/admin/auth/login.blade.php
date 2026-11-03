@extends('layouts.admin-guest')
@section('title', 'Sign in')
@section('content')
<main class="login-wrap">
    <section class="login-card" aria-labelledby="login-title">
        <img class="login-logo" src="{{ asset('images/geco-logo.png') }}" alt="GECO Africa">
        <p class="eyebrow">GECO Africa · Content management</p>
        <h1 id="login-title">Sign in</h1>
        <p class="muted">Use the account created for you by a CMS administrator.</p>
        <form method="post" action="{{ route('admin.login.store') }}" class="form-stack">
            @csrf
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" autocomplete="username" value="{{ old('email') }}" required autofocus>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
            <button class="button button-primary button-wide" type="submit">Sign in</button>
        </form>
    </section>
</main>
@endsection