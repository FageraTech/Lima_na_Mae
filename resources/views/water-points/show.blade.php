@extends('layouts.app', ['title' => $waterSource->name . ' | Lima na Mae'])

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Water point history</p>
            <h1 class="mt-2 text-3xl font-bold">{{ $waterSource->name }}</h1>
            <p class="mt-2 text-slate-600">
                {{ ucfirst($waterSource->type) }} · {{ $waterSource->location }} ·
                {{ $waterSource->waterProject->name }}
            </p>
        </div>
        <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800">
            {{ str_replace('_', ' ', ucfirst($waterSource->lifecycle_status)) }}
        </span>
    </div>

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

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded bg-white p-6 shadow lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">Full timeline</h2>
                    <p class="mt-1 text-sm text-slate-600">Inspections, maintenance, and status changes.</p>
                </div>
                <span class="text-sm text-slate-500">{{ $waterSource->events->count() }} events</span>
            </div>

            @forelse ($waterSource->events as $event)
                <article class="relative border-l-2 border-emerald-200 pb-6 pl-6 last:pb-0">
                    <span class="absolute -left-2 top-0 h-3 w-3 rounded-full bg-emerald-600"></span>
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <h3 class="font-semibold">{{ $event->title }}</h3>
                            <p class="mt-1 text-xs uppercase tracking-wide text-emerald-700">{{ str_replace('_', ' ', $event->type) }}</p>
                        </div>
                        <time class="text-sm text-slate-500">{{ $event->occurred_at->format('d M Y') }}</time>
                    </div>
                    @if ($event->description)
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $event->description }}</p>
                    @endif
                    @if ($event->user)
                        <p class="mt-2 text-xs text-slate-400">Recorded by {{ $event->user->name }}</p>
                    @endif
                </article>
            @empty
                <p class="text-sm text-slate-600">No history has been recorded for this water point yet.</p>
            @endforelse
        </section>

        <div class="space-y-6">
            <section class="rounded bg-white p-6 shadow">
                <h2 class="text-xl font-bold">Record inspection</h2>
                <form method="POST" action="{{ route('water-points.inspections.store', $waterSource) }}" class="mt-4 space-y-3">
                    @csrf
                    <label class="block text-sm font-semibold" for="checked_at">Checked on</label>
                    <input class="w-full rounded border border-slate-300 px-3 py-2" id="checked_at" name="checked_at" type="date" value="{{ now()->format('Y-m-d') }}" required>
                    <label class="block text-sm font-semibold" for="outcome">Outcome</label>
                    <select class="w-full rounded border border-slate-300 px-3 py-2" id="outcome" name="outcome" required>
                        <option value="operational">Operational</option>
                        <option value="needs_attention">Needs attention</option>
                        <option value="non_operational">Not operational</option>
                    </select>
                    <label class="block text-sm font-semibold" for="inspection_notes">Notes</label>
                    <textarea class="w-full rounded border border-slate-300 px-3 py-2" id="inspection_notes" name="notes" rows="3"></textarea>
                    <button class="w-full rounded bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800" type="submit">Add inspection</button>
                </form>
            </section>

            <section class="rounded bg-white p-6 shadow">
                <h2 class="text-xl font-bold">Log maintenance</h2>
                <form method="POST" action="{{ route('water-points.maintenance.store', $waterSource) }}" class="mt-4 space-y-3">
                    @csrf
                    <label class="block text-sm font-semibold" for="performed_at">Performed on</label>
                    <input class="w-full rounded border border-slate-300 px-3 py-2" id="performed_at" name="performed_at" type="date" value="{{ now()->format('Y-m-d') }}" required>
                    <label class="block text-sm font-semibold" for="work_done">Work completed</label>
                    <textarea class="w-full rounded border border-slate-300 px-3 py-2" id="work_done" name="work_done" rows="3" required></textarea>
                    <label class="block text-sm font-semibold" for="technician">Technician</label>
                    <input class="w-full rounded border border-slate-300 px-3 py-2" id="technician" name="technician" type="text">
                    <button class="w-full rounded bg-slate-800 px-4 py-2 font-semibold text-white hover:bg-slate-900" type="submit">Log maintenance</button>
                </form>
            </section>

            <section class="rounded bg-white p-6 shadow">
                <h2 class="text-xl font-bold">Community signals</h2>
                <div class="mt-4 space-y-3">
                    <form method="POST" action="{{ route('water-points.confirm', $waterSource) }}">
                        @csrf
                        <button class="w-full rounded bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-200" type="submit">Still working</button>
                    </form>
                    <form method="POST" action="{{ route('water-points.subscribe', $waterSource) }}">
                        @csrf
                        <button class="w-full rounded border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" type="submit">Subscribe to alerts</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('water-points.incidents.store', $waterSource) }}" class="mt-5 space-y-3 border-t border-slate-100 pt-5">
                    @csrf
                    <p class="text-sm font-bold">Report an incident</p>
                    <select class="w-full rounded border border-slate-300 px-3 py-2 text-sm" name="severity" required>
                        <option value="low">Low severity</option>
                        <option value="medium">Medium severity</option>
                        <option value="critical">Critical</option>
                    </select>
                    <textarea class="w-full rounded border border-slate-300 px-3 py-2 text-sm" name="description" rows="3" placeholder="What is happening?" required></textarea>
                    <button class="w-full rounded bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800" type="submit">Report incident</button>
                </form>
            </section>

            <section class="rounded bg-white p-6 shadow">
                <h2 class="text-xl font-bold">Assign technician</h2>
                <form method="POST" action="{{ route('water-points.assignments.store', $waterSource) }}" class="mt-4 space-y-3">
                    @csrf
                    <input class="w-full rounded border border-slate-300 px-3 py-2 text-sm" name="technician_name" placeholder="Technician name" required>
                    <input class="w-full rounded border border-slate-300 px-3 py-2 text-sm" name="technician_phone" placeholder="Phone number">
                    <input class="w-full rounded border border-slate-300 px-3 py-2 text-sm" name="task" placeholder="Task" required>
                    <label class="block text-sm font-semibold" for="due_at">Due date</label>
                    <input class="w-full rounded border border-slate-300 px-3 py-2 text-sm" id="due_at" name="due_at" type="datetime-local" required>
                    <label class="flex items-center gap-2 text-sm"><input name="critical" type="checkbox" value="1"> Critical task</label>
                    <button class="w-full rounded bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900" type="submit">Assign task</button>
                </form>
                @foreach ($waterSource->assignments as $assignment)
                    <div class="mt-4 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex justify-between gap-3"><strong>{{ $assignment->task }}</strong><span class="text-slate-500">{{ ucfirst($assignment->status) }}</span></div>
                        <p class="mt-1 text-slate-500">{{ $assignment->technician_name }} · Due {{ $assignment->due_at?->format('d M Y') ?? 'Not set' }}</p>
                        @if ($assignment->status !== 'completed')
                            <form method="POST" action="{{ route('assignments.complete', $assignment) }}" class="mt-2">@csrf<button class="text-xs font-semibold text-emerald-700 hover:underline" type="submit">Mark complete</button></form>
                        @endif
                    </div>
                @endforeach
            </section>

            @if ($waterSource->lifecycle_status !== 'decommissioned')
                <form method="POST" action="{{ route('water-points.decommission', $waterSource) }}">
                    @csrf
                    <button class="w-full rounded border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50" type="submit" onclick="return confirm('Decommission this water point? Its history will be preserved.')">Decommission water point</button>
                </form>
            @endif
        </div>
    </div>

    <a href="{{ route('water-projects.show', $waterSource->waterProject) }}" class="mt-6 inline-block text-emerald-700 hover:underline">Back to project</a>
@endsection