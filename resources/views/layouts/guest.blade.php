<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Welcome | Lima na Mae' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-story">
            <a href="{{ url('/') }}" class="brand brand-light">
                <span class="brand-mark">LM</span>
                <span>Lima na Mae</span>
            </a>

            <div class="story-copy">
                <p class="eyebrow">Water, land, and better harvests</p>
                <h1>Grow with the rhythm of your land.</h1>
                <p>One calm place for farmers to keep an eye on water projects, plan the season, and make every drop count.</p>
            </div>

            <div class="story-footer">
                <span class="story-line"></span>
                <span>Built for the people who keep the fields moving.</span>
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-panel-inner">
                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>