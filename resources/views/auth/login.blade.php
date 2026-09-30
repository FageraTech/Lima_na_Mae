@extends('layouts.guest', ['title' => 'Log in | Lima na Mae'])

@section('content')
    <div class="auth-heading">
        <p class="eyebrow">Farmer portal</p>
        <h2>Welcome back.</h2>
        <p>Sign in to see what is happening across your water projects.</p>
    </div>

    @if ($errors->any())
        <div class="form-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <label class="field-label" for="email">Email address</label>
        <input class="field-input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>

        <div class="field-row">
            <label class="field-label" for="password">Password</label>
            <span class="field-hint">8 characters minimum</span>
        </div>
        <input class="field-input" id="password" name="password" type="password" autocomplete="current-password" required>

        <label class="check-label" for="remember">
            <input id="remember" name="remember" type="checkbox" value="1">
            <span>Keep me signed in</span>
        </label>

        <button class="primary-button" type="submit">Sign in <span aria-hidden="true">-&gt;</span></button>
    </form>

    <p class="auth-switch">New to Lima na Mae? <a href="{{ route('register') }}">Create a farmer account</a></p>
@endsection