@extends('layouts.app', ['title' => 'Impact reports | Lima na Mae'])

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Evidence for action</p>
            <h1 class="mt-2 text-3xl font-bold">Impact reports</h1>
            <p class="mt-2 text-slate-600">See water-point health, response speed, and the places that need attention.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('impact-reports.print', request()->query()) }}" target="_blank" class="rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Print / PDF</a>
            <a href="{{ route('impact-reports.export', request()->query()) }}" class="rounded bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Download CSV</a>
        </div>
    </div>

    <form method="GET" action="{{ route('impact-reports.index') }}" class="mb-6 flex max-w-md items-end gap-3 rounded bg-white p-4 shadow">
        <div class="flex-1">
            <label class="block text-sm font-semibold" for="county">County</label>
            <select class="mt-2 w-full rounded border border-slate-300 px-3 py-2" id="county" name="county">
                <option value="">All counties</option>
                @foreach ($counties as $county)
                    <option value="{{ $county }}" @selected($selectedCounty === $county)>{{ $county }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900" type="submit">Apply</button>
    </form>

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <article class="rounded bg-white p-5 shadow"><p class="text-sm text-slate-500">Water points</p><strong class="mt-2 block text-3xl">{{ $totalWaterPoints }}</strong></article>
        <article class="rounded bg-white p-5 shadow"><p class="text-sm text-slate-500">Functional</p><strong class="mt-2 block text-3xl">{{ $functionalPercentage }}%</strong></article>
        <article class="rounded bg-white p-5 shadow"><p class="text-sm text-slate-500">Average resolution</p><strong class="mt-2 block text-3xl">{{ $averageResolutionDays }} <span class="text-base font-normal">days</span></strong></article>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded bg-white p-6 shadow">
            <h2 class="text-xl font-bold">County health</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500"><tr><th class="py-3 pr-4">County</th><th class="py-3 pr-4">Points</th><th class="py-3 pr-4">Functional</th><th class="py-3">Users</th></tr></thead>
                    <tbody>
                        @forelse ($countyAnalytics as $county)
                            <tr class="border-b border-slate-100"><td class="py-3 pr-4 font-semibold">{{ $county['county'] }}</td><td class="py-3 pr-4">{{ $county['total'] }}</td><td class="py-3 pr-4">{{ $county['functional_percentage'] }}%</td><td class="py-3">{{ number_format($county['estimated_users']) }}</td></tr>
                        @empty
                            <tr><td class="py-4 text-slate-500" colspan="4">No water-point data for this filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded bg-white p-6 shadow">
            <h2 class="text-xl font-bold">Recurring issues</h2>
            <p class="mt-1 text-sm text-slate-500">Three or more incidents in the last 90 days.</p>
            <div class="mt-4 space-y-3">
                @forelse ($recurringIssues as $issue)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3"><span><strong class="block">{{ $issue['water_point'] }}</strong><span class="text-xs text-slate-500">{{ $issue['county'] }}</span></span><span class="rounded bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">{{ $issue['reports'] }} reports</span></div>
                @empty
                    <p class="text-sm text-slate-500">No recurring issues detected.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded bg-white p-6 shadow">
            <h2 class="text-xl font-bold">Coverage gap signals</h2>
            <p class="mt-1 text-sm text-slate-500">High-demand points currently marked non-functional.</p>
            <div class="mt-4 space-y-3">
                @forelse ($coverageGaps as $waterPoint)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3"><span><strong class="block">{{ $waterPoint->name }}</strong><span class="text-xs text-slate-500">{{ $waterPoint->waterProject->county }}</span></span><span class="text-sm font-semibold text-red-700">{{ number_format($waterPoint->estimated_users) }} users</span></div>
                @empty
                    <p class="text-sm text-slate-500">No coverage gap signals detected.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded bg-white p-6 shadow">
            <h2 class="text-xl font-bold">Attention queue</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between border-b border-slate-100 pb-3"><span>Overdue inspections</span><strong>{{ $overdueInspections->count() }}</strong></div>
                <div class="flex justify-between border-b border-slate-100 pb-3"><span>Critical incidents</span><strong class="text-red-700">{{ $criticalReports->count() }}</strong></div>
                <div class="flex justify-between"><span>Overdue technician tasks</span><strong>{{ $overdueAssignments->count() }}</strong></div>
            </div>
        </section>
    </div>
@endsection
