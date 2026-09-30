@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Water Projects</h1>
            <p class="mt-2 text-slate-600">
                Manage water access and agricultural support projects.
            </p>
        </div>

        <a
            href="{{ route('water-projects.create') }}"
            class="rounded bg-emerald-700 px-5 py-2 font-semibold text-white hover:bg-emerald-800"
        >
            Add Project
        </a>
    </div>

    @if ($waterProjects->isEmpty())
        <div class="rounded bg-white p-8 text-center shadow">
            <p class="text-slate-600">No water projects have been added yet.</p>
        </div>
    @else
        <div class="overflow-hidden rounded bg-white shadow">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Location</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Water Sources</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @foreach ($waterProjects as $waterProject)
                        <tr>
                            <td class="px-6 py-4 font-medium">
                                {{ $waterProject->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $waterProject->location }}, {{ $waterProject->county }}
                            </td>
                            <td class="px-6 py-4">
                                {{ ucfirst($waterProject->status) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $waterProject->water_sources_count }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a
                                        href="{{ route('water-projects.show', $waterProject) }}"
                                        class="text-emerald-700 hover:underline"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('water-projects.edit', $waterProject) }}"
                                        class="text-blue-700 hover:underline"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('water-projects.destroy', $waterProject) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-700 hover:underline"
                                            onclick="return confirm('Delete this project?')"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection