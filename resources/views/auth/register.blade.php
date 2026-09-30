@extends('layouts.guest', ['title' => 'Create account | Lima na Mae'])

@section('content')
    <div class="auth-heading">
        <p class="eyebrow">Start your field journal</p>
        <h2>Make room to grow.</h2>
        <p>Create your farmer account and bring your water planning into focus.</p>
    </div>

    @if ($errors->any())
        <div class="form-alert" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <label class="field-label" for="name">Full name</label>
        <input class="field-input" id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>

        <label class="field-label" for="email">Email address</label>
        <input class="field-input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>

        <label class="field-label" for="password">Password</label>
        <input class="field-input" id="password" name="password" type="password" autocomplete="new-password" required>

        <label class="field-label" for="password_confirmation">Confirm password</label>
        <input class="field-input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>

        <button class="primary-button" type="submit">Create account <span aria-hidden="true">-&gt;</span></button>
    </form>

    <p class="auth-switch">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
@endsection