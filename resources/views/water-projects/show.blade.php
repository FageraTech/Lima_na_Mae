@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">{{ $waterProject->name }}</h1>
            <p class="mt-2 text-slate-600">
                {{ $waterProject->location }}, {{ $waterProject->county }}
            </p>
        </div>

        <a
            href="{{ route('water-projects.edit', $waterProject) }}"
            class="rounded bg-blue-700 px-5 py-2 font-semibold text-white hover:bg-blue-800"
        >
            Edit Project
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <section class="rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-xl font-bold">Project Details</h2>

            <dl class="space-y-3">
                <div>
                    <dt class="font-semibold">Status</dt>
                    <dd>{{ ucfirst($waterProject->status) }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Start date</dt>
                    <dd>{{ $waterProject->start_date?->format('d M Y') ?? 'Not set' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Completion date</dt>
                    <dd>{{ $waterProject->completion_date?->format('d M Y') ?? 'Not set' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Description</dt>
                    <dd>{{ $waterProject->description ?? 'No description provided.' }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-xl font-bold">Water Sources</h2>

            @forelse ($waterProject->waterSources as $waterSource)
                <div class="border-b border-slate-200 py-3 last:border-0">
                    <a href="{{ route('water-points.show', $waterSource) }}" class="font-semibold text-emerald-700 hover:underline">
                        {{ $waterSource->name }}
                    </a>
                    <p class="text-sm text-slate-600">
                        {{ ucfirst($waterSource->type) }} · {{ str_replace('_', ' ', ucfirst($waterSource->lifecycle_status)) }}
                    </p>
                </div>
            @empty
                <p class="text-slate-600">
                    No water sources have been added to this project.
                </p>
            @endforelse
        </section>
    </div>

    <a
        href="{{ route('water-projects.index') }}"
        class="mt-6 inline-block text-emerald-700 hover:underline"
    >
        Back to projects
    </a>
@endsection