<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water-point impact report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print { .print-hidden { display: none; } body { background: white; } }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-5xl p-8">
        <div class="print-hidden mb-8 flex justify-end"><button onclick="window.print()" class="rounded bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Print report</button></div>
        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Lima na Mae</p>
        <h1 class="mt-2 text-4xl font-bold">Water-point impact report</h1>
        <p class="mt-2 text-slate-500">Generated {{ now()->format('d M Y H:i') }} · {{ $selectedCounty ?: 'All counties' }}</p>
        <div class="mt-8 grid grid-cols-3 gap-4"><div class="rounded bg-white p-5 shadow"><p class="text-sm text-slate-500">Water points</p><strong class="text-3xl">{{ $totalWaterPoints }}</strong></div><div class="rounded bg-white p-5 shadow"><p class="text-sm text-slate-500">Functional</p><strong class="text-3xl">{{ $functionalPercentage }}%</strong></div><div class="rounded bg-white p-5 shadow"><p class="text-sm text-slate-500">Avg. resolution</p><strong class="text-3xl">{{ $averageResolutionDays }} days</strong></div></div>
        <section class="mt-8 rounded bg-white p-6 shadow"><h2 class="text-xl font-bold">County health</h2><table class="mt-4 min-w-full text-left text-sm"><thead class="border-b"><tr><th class="py-2">County</th><th class="py-2">Points</th><th class="py-2">Functional</th><th class="py-2">Estimated users</th></tr></thead><tbody>@foreach ($countyAnalytics as $county)<tr class="border-b"><td class="py-2">{{ $county['county'] }}</td><td class="py-2">{{ $county['total'] }}</td><td class="py-2">{{ $county['functional_percentage'] }}%</td><td class="py-2">{{ number_format($county['estimated_users']) }}</td></tr>@endforeach</tbody></table></section>
        <section class="mt-8 rounded bg-white p-6 shadow"><h2 class="text-xl font-bold">Priority signals</h2><ul class="mt-4 list-disc space-y-2 pl-5 text-sm"><li>{{ $recurringIssues->count() }} recurring issue(s)</li><li>{{ $coverageGaps->count() }} coverage gap signal(s)</li><li>{{ $overdueInspections->count() }} overdue inspection(s)</li><li>{{ $criticalReports->count() }} open critical incident(s)</li><li>{{ $overdueAssignments->count() }} overdue technician task(s)</li></ul></section>
    </main>
</body>
</html>
