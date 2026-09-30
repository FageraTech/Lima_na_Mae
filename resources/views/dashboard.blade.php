@extends('layouts.app', ['title' => 'Dashboard | Lima na Mae'])

@section('content')
    <div class="dashboard-intro">
        <div>
            <p class="eyebrow">Farmer dashboard</p>
            <h1>Good morning, {{ Str::before(auth()->user()->name, ' ') }}.</h1>
            <p class="dashboard-lede">Here is your water picture at a glance. We will keep building this into your daily field companion.</p>
        </div>
        <a class="primary-button compact-button" href="{{ route('water-projects.index') }}">Explore projects <span aria-hidden="true">-&gt;</span></a>
    </div>

    <div class="stat-grid">
        <article class="stat-card stat-card-accent">
            <span class="stat-label">Water projects</span>
            <strong>{{ $projectCount }}</strong>
            <span class="stat-note">Across the network</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Active now</span>
            <strong>{{ $activeProjectCount }}</strong>
            <span class="stat-note">Projects in progress</span>
        </article>
        <a href="{{ route('water-points.index') }}" class="stat-card stat-card-link">
            <span class="stat-label">Water sources</span>
            <strong>{{ $waterSourceCount }}</strong>
            <span class="stat-note">Being tracked · View water points</span>
        </a>
    </div>

    <div class="dashboard-grid">
        <section class="dashboard-section project-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Keep an eye on the flow</p>
                    <h2>Latest water projects</h2>
                </div>
                <a href="{{ route('water-projects.index') }}" class="text-link">View all</a>
            </div>

            @forelse ($recentProjects as $project)
                <a href="{{ route('water-projects.show', $project) }}" class="project-row">
                    <span class="project-icon">WP</span>
                    <span class="project-details">
                        <strong>{{ $project->name }}</strong>
                        <span>{{ $project->location }}, {{ $project->county }}</span>
                    </span>
                    <span class="status-pill status-{{ $project->status }}">{{ ucfirst($project->status) }}</span>
                </a>
            @empty
                <div class="empty-state">Your latest water projects will appear here.</div>
            @endforelse
        </section>

        <section class="dashboard-section next-section">
            <p class="eyebrow">Next up</p>
            <h2>Your field toolkit</h2>
            <p>We are preparing a simpler way to connect your farms, irrigation systems, and seasonal plans.</p>
            <div class="toolkit-list">
                <span><b>01</b> Add your farm details</span>
                <span><b>02</b> Track an irrigation system</span>
                <span><b>03</b> Plan your next season</span>
            </div>
        </section>
    </div>

    <section class="mt-4 grid gap-4 md:grid-cols-3">
        <a href="{{ route('water-points.index') }}" class="dashboard-section transition hover:-translate-y-1">
            <p class="eyebrow">Community reporting</p>
            <h2 class="mt-3 text-2xl font-bold">Share a water-point signal</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Confirm a point is still working or report an incident from its history page.</p>
            <span class="mt-5 block text-sm font-bold text-emerald-700">Open water points -&gt;</span>
        </a>
        <a href="{{ route('operations.index') }}" class="dashboard-section transition hover:-translate-y-1">
            <p class="eyebrow">Operations</p>
            <h2 class="mt-3 text-2xl font-bold">Keep field work moving</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Review reports, overdue inspections, and technician tasks in one place.</p>
            <span class="mt-5 block text-sm font-bold text-emerald-700">Open operations -&gt;</span>
        </a>
        <a href="{{ route('impact-reports.index') }}" class="dashboard-section transition hover:-translate-y-1">
            <p class="eyebrow">Impact reporting</p>
            <h2 class="mt-3 text-2xl font-bold">See the bigger picture</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Compare counties, spot recurring issues, and export your report.</p>
            <span class="mt-5 block text-sm font-bold text-emerald-700">View impact reports -&gt;</span>
        </a>
    </section>
@endsection