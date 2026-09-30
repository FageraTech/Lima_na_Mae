@extends('layouts.app', ['title' => 'Water points | Lima na Mae'])

@section('content')
    <div class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Field operations</p>
            <h1 class="mt-2 text-3xl font-bold">Water points</h1>
            <p class="mt-2 text-slate-600">Track the condition, history, and care of every community water point.</p>
        </div>
        <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800">
            {{ $waterPoints->count() }} tracked
        </span>
    </div>

    @if ($waterPoints->isEmpty())
        <section class="rounded bg-white p-10 text-center shadow">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-700">WP</div>
            <h2 class="mt-5 text-xl font-bold">No water points yet</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                Water points will appear here once they are added to a water project. Their inspection and maintenance history will live in one place.
            </p>
            <a href="{{ route('water-projects.index') }}" class="mt-6 inline-block rounded bg-emerald-700 px-5 py-2 font-semibold text-white hover:bg-emerald-800">View water projects</a>
        </section>
    @else
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($waterPoints as $waterPoint)
                <a href="{{ route('water-points.show', $waterPoint) }}" class="rounded bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-slate-900">{{ $waterPoint->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ ucfirst($waterPoint->type) }} · {{ $waterPoint->location }}</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                            {{ str_replace('_', ' ', ucfirst($waterPoint->lifecycle_status)) }}
                        </span>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                        <span>{{ $waterPoint->waterProject->name }}</span>
                        <span>{{ $waterPoint->events_count }} events</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection