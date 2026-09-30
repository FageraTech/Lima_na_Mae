@extends('layouts.app', ['title' => 'Dashboard | Lima na Mae'])

@section('content')
    <div class="dashboard-intro">
        <div>
            <p class="eyebrow">Your water workspace</p>
            <h1>Good morning, {{ Str::before(auth()->user()->name, ' ') }}.</h1>
            <p class="dashboard-lede">A clear view of the water network, the work ahead, and the tools that help you make better decisions in the field.</p>
        </div>
        <a class="primary-button compact-button" href="{{ route('irrigation-planner.create') }}">Plan irrigation <span aria-hidden="true">-&gt;</span></a>
    </div>

    <section class="focus-panel">
        <div class="focus-heading">
            <div>
                <p class="eyebrow">Start here</p>
                <h2>Today&apos;s field focus</h2>
            </div>
            <span class="focus-date">{{ now()->format('l, d M Y') }}</span>
        </div>
        <div class="focus-grid">
            <a href="{{ route('operations.index') }}" class="focus-card focus-card-alert">
                <span class="focus-icon">!</span>
                <span><strong>{{ $openIncidentCount }} open {{ Str::plural('incident', $openIncidentCount) }}</strong><small>Review community signals</small></span>
                <span class="focus-arrow" aria-hidden="true">-&gt;</span>
            </a>
            <a href="{{ route('operations.index') }}" class="focus-card">
                <span class="focus-icon">+</span>
                <span><strong>{{ $activeAssignmentCount }} active {{ Str::plural('task', $activeAssignmentCount) }}</strong><small>Keep field work moving</small></span>
                <span class="focus-arrow" aria-hidden="true">-&gt;</span>
            </a>
            <a href="{{ route('operations.index') }}" class="focus-card focus-card-warn">
                <span class="focus-icon">~</span>
                <span><strong>{{ $overdueInspectionCount }} overdue {{ Str::plural('inspection', $overdueInspectionCount) }}</strong><small>Check points that need care</small></span>
                <span class="focus-arrow" aria-hidden="true">-&gt;</span>
            </a>
        </div>
    </section>

    <div class="dashboard-grid dashboard-grid-main">
        <section class="dashboard-section project-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Network pulse</p>
                    <h2>Water at a glance</h2>
                </div>
                <a href="{{ route('water-points.index') }}" class="text-link">Open water points</a>
            </div>
            <div class="stat-grid stat-grid-inline">
                <article class="stat-card stat-card-accent">
                    <span class="stat-label">Water projects</span>
                    <strong>{{ $projectCount }}</strong>
                    <span class="stat-note">{{ $activeProjectCount }} active now</span>
                </article>
                <a href="{{ route('water-points.index') }}" class="stat-card stat-card-link">
                    <span class="stat-label">Water sources</span>
                    <strong>{{ $waterSourceCount }}</strong>
                    <span class="stat-note">View network health</span>
                </a>
            </div>
        </section>

        <section class="dashboard-section next-section">
            <p class="eyebrow">Decision tools</p>
            <h2>Make the next move</h2>
            <p>Use local data to turn a question in the field into a practical plan.</p>
            <div class="quick-actions">
                <a href="{{ route('irrigation-planner.create') }}"><span>01</span> Build an irrigation plan <b aria-hidden="true">-&gt;</b></a>
                <a href="{{ route('crop-water-recommendations.create') }}"><span>02</span> Get crop recommendations <b aria-hidden="true">-&gt;</b></a>
                <a href="{{ route('impact-reports.index') }}"><span>03</span> Measure network impact <b aria-hidden="true">-&gt;</b></a>
            </div>
        </section>
    </div>

    <div class="dashboard-grid dashboard-grid-secondary">
        <section class="dashboard-section project-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Keep an eye on the flow</p>
                    <h2>Latest projects</h2>
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

        <section class="dashboard-section insight-section">
            <p class="eyebrow">Why it matters</p>
            <h2>From signal to action</h2>
            <p>Every report, inspection, and project update helps the network stay reliable for the communities that depend on it.</p>
            <a href="{{ route('impact-reports.index') }}" class="insight-link">See the bigger picture <span aria-hidden="true">-&gt;</span></a>
        </section>
    </div>
@endsection