@extends('layouts.admin-guest')
@section('title', 'Forgot password')
@section('content')
<main class="login-wrap">
    <section class="login-card" aria-labelledby="forgot-title">
        <img class="login-logo" src="{{ asset('images/geco-logo.png') }}" alt="GECO Africa">
        <p class="eyebrow">GECO Africa · Content management</p>
        <h1 id="forgot-title">Forgot your password?</h1>
        <p class="muted">Enter the email address you sign in with. We will email you a link to choose a new password.</p>
        @if (session('status'))<div class="notice notice-success" role="status">{{ session('status') }}</div>@endif
        <form method="post" action="{{ route('admin.password.email') }}" class="form-stack">
            @csrf
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" autocomplete="username" value="{{ old('email') }}" required autofocus>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
            <button class="button button-primary button-wide" type="submit">Email me a reset link</button>
        </form>
        <p class="muted login-alt-link"><a href="{{ route('admin.login') }}">← Back to sign in</a></p>
    </section>
</main>
@endsection
