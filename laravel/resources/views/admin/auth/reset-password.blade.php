@extends('layouts.admin-guest')
@section('title', 'Choose a new password')
@section('content')
<main class="login-wrap">
    <section class="login-card" aria-labelledby="reset-title">
        <img class="login-logo" src="{{ asset('images/geco-logo.png') }}" alt="GECO Africa">
        <p class="eyebrow">GECO Africa · Content management</p>
        <h1 id="reset-title">Choose a new password</h1>
        <p class="muted">Use at least 12 characters. A short sentence you can remember works well.</p>
        <form method="post" action="{{ route('admin.password.update') }}" class="form-stack">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" autocomplete="username" value="{{ old('email', $email) }}" required>
            <label for="password">New password</label>
            <input id="password" name="password" type="password" minlength="12" autocomplete="new-password" required autofocus>
            <label for="password_confirmation">Repeat the new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" minlength="12" autocomplete="new-password" required>
            @if ($errors->any())<p class="field-error">{{ $errors->first() }}</p>@endif
            <button class="button button-primary button-wide" type="submit">Save new password</button>
        </form>
        <p class="muted login-alt-link"><a href="{{ route('admin.password.request') }}">Ask for a new link</a></p>
    </section>
</main>
@endsection
