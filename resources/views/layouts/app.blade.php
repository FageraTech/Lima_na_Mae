<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Lima na Mae' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-page">
    <header class="app-header">
        <nav class="app-nav">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark">LM</span>
                <span>Lima na Mae</span>
            </a>

            <div class="nav-actions">
                <span class="user-chip">{{ Str::substr(auth()->user()->name, 0, 1) }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Sign out</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="app-shell">
        <aside class="app-sidebar" aria-label="Main navigation">
            <p class="sidebar-label">Workspace</p>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">Dashboard</a>
                <a href="{{ route('water-projects.index') }}" class="sidebar-link {{ request()->routeIs('water-projects.*') ? 'sidebar-link-active' : '' }}">Water projects</a>
                <a href="{{ route('water-points.index') }}" class="sidebar-link {{ request()->routeIs('water-points.*') ? 'sidebar-link-active' : '' }}">Water points</a>
                <a href="{{ route('irrigation-planner.create') }}" class="sidebar-link {{ request()->routeIs('irrigation-planner.*') ? 'sidebar-link-active' : '' }}">Irrigation planner</a>
                <a href="{{ route('crop-water-recommendations.create') }}" class="sidebar-link {{ request()->routeIs('crop-water-recommendations.*') ? 'sidebar-link-active' : '' }}">Crop recommendations</a>
                <a href="{{ route('operations.index') }}" class="sidebar-link {{ request()->routeIs('operations.*') ? 'sidebar-link-active' : '' }}">Operations</a>
                <a href="{{ route('impact-reports.index') }}" class="sidebar-link {{ request()->routeIs('impact-reports.*') ? 'sidebar-link-active' : '' }}">Impact reports</a>
            </nav>
        </aside>

        <main class="app-main">
        @if (session('success'))
            <div class="mb-6 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            @yield('content')
        </main>
    </div>
</body>
</html>