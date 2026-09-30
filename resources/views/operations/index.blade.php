@extends('layouts.app', ['title' => 'Operations | Lima na Mae'])

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Field operations</p>
            <h1 class="mt-2 text-3xl font-bold">Operations</h1>
            <p class="mt-2 text-slate-600">Move from community signal to a clear field response.</p>
        </div>
        <a href="{{ route('water-points.index') }}" class="rounded bg-emerald-700 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Open water points</a>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <a href="#incidents" class="rounded bg-red-50 p-5 shadow"><p class="text-sm font-semibold text-red-700">Open incidents</p><strong class="mt-2 block text-3xl text-red-900">{{ $openIncidents->count() }}</strong><span class="mt-1 block text-xs text-red-700">Community reports needing review</span></a>
        <a href="#tasks" class="rounded bg-white p-5 shadow"><p class="text-sm font-semibold text-slate-600">Technician tasks</p><strong class="mt-2 block text-3xl">{{ $activeAssignments->count() }}</strong><span class="mt-1 block text-xs text-slate-500">Assigned and in progress</span></a>
        <a href="#inspections" class="rounded bg-amber-50 p-5 shadow"><p class="text-sm font-semibold text-amber-700">Overdue inspections</p><strong class="mt-2 block text-3xl text-amber-900">{{ $overdueInspections->count() }}</strong><span class="mt-1 block text-xs text-amber-700">90+ days without a check</span></a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section id="incidents" class="rounded bg-white p-6 shadow">
            <div class="flex items-center justify-between"><div><h2 class="text-xl font-bold">Community reports</h2><p class="mt-1 text-sm text-slate-500">Signals from people using the water points.</p></div><a href="{{ route('water-points.index') }}" class="text-sm font-semibold text-emerald-700">Report issue</a></div>
            <div class="mt-5 space-y-4">
                @forelse ($openIncidents as $incident)
                    <a href="{{ route('water-points.show', $incident->waterSource) }}" class="block border-b border-slate-100 pb-4 hover:text-emerald-700"><div class="flex items-start justify-between gap-3"><strong>{{ $incident->waterSource->name }}</strong><span class="rounded px-2 py-1 text-xs font-semibold {{ $incident->severity === 'critical' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($incident->severity) }}</span></div><p class="mt-1 text-sm text-slate-600">{{ $incident->description }}</p><span class="mt-2 block text-xs text-slate-400">Reported {{ $incident->reported_at->diffForHumans() }}</span></a>
                @empty
                    <p class="text-sm text-slate-500">No open community reports.</p>
                @endforelse
            </div>
        </section>

        <section id="tasks" class="rounded bg-white p-6 shadow">
            <div class="flex items-center justify-between"><div><h2 class="text-xl font-bold">Technician tasks</h2><p class="mt-1 text-sm text-slate-500">Work assigned to keep points running.</p></div><a href="{{ route('impact-reports.index') }}" class="text-sm font-semibold text-emerald-700">View impact</a></div>
            <div class="mt-5 space-y-4">
                @forelse ($activeAssignments as $assignment)
                    <div class="border-b border-slate-100 pb-4"><div class="flex items-start justify-between gap-3"><strong>{{ $assignment->task }}</strong>@if ($assignment->due_at?->isPast())<span class="text-xs font-semibold text-red-700">Overdue</span>@else<span class="text-xs text-slate-500">Due {{ $assignment->due_at?->format('d M Y') }}</span>@endif</div><p class="mt-1 text-sm text-slate-600">{{ $assignment->technician_name }} · {{ $assignment->waterSource->name }}</p><form method="POST" action="{{ route('assignments.complete', $assignment) }}" class="mt-2">@csrf<button class="text-xs font-semibold text-emerald-700 hover:underline" type="submit">Mark complete</button></form></div>
                @empty
                    <p class="text-sm text-slate-500">No active technician tasks.</p>
                @endforelse
            </div>
        </section>

        <section id="inspections" class="rounded bg-white p-6 shadow lg:col-span-2">
            <div class="flex items-center justify-between"><div><h2 class="text-xl font-bold">Inspection gaps</h2><p class="mt-1 text-sm text-slate-500">Water points that have not been checked in the last 90 days.</p></div><a href="{{ route('water-points.index') }}" class="text-sm font-semibold text-emerald-700">Open water points</a></div>
            <div class="mt-5 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($overdueInspections as $waterPoint)
                    <a href="{{ route('water-points.show', $waterPoint) }}" class="rounded border border-amber-100 bg-amber-50 p-4 hover:border-amber-300"><strong class="block">{{ $waterPoint->name }}</strong><span class="mt-1 block text-sm text-slate-600">{{ $waterPoint->waterProject->county }} · {{ $waterPoint->location }}</span><span class="mt-3 block text-xs font-semibold text-amber-800">{{ $waterPoint->last_checked_at ? 'Last checked ' . $waterPoint->last_checked_at->diffForHumans() : 'Never checked' }}</span></a>
                @empty
                    <p class="text-sm text-slate-500">All active water points are up to date.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
