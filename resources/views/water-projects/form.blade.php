<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block font-medium">Project name</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $waterProject->name ?? '') }}"
            required
            class="w-full rounded border-slate-300"
        >
    </div>

    <div>
        <label for="location" class="mb-2 block font-medium">Location</label>
        <input
            id="location"
            name="location"
            type="text"
            value="{{ old('location', $waterProject->location ?? '') }}"
            required
            class="w-full rounded border-slate-300"
        >
    </div>

    <div>
        <label for="county" class="mb-2 block font-medium">County</label>
        <input
            id="county"
            name="county"
            type="text"
            value="{{ old('county', $waterProject->county ?? 'Kitui') }}"
            required
            class="w-full rounded border-slate-300"
        >
    </div>

    <div>
        <label for="status" class="mb-2 block font-medium">Status</label>
        <select
            id="status"
            name="status"
            required
            class="w-full rounded border-slate-300"
        >
            @foreach (['planned', 'active', 'completed'] as $status)
                <option
                    value="{{ $status }}"
                    @selected(old('status', $waterProject->status ?? 'planned') === $status)
                >
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="start_date" class="mb-2 block font-medium">Start date</label>
        <input
            id="start_date"
            name="start_date"
            type="date"
            value="{{ old('start_date', isset($waterProject?->start_date) ? $waterProject->start_date->format('Y-m-d') : '') }}"
            class="w-full rounded border-slate-300"
        >
    </div>

    <div>
        <label for="completion_date" class="mb-2 block font-medium">Completion date</label>
        <input
            id="completion_date"
            name="completion_date"
            type="date"
            value="{{ old('completion_date', isset($waterProject?->completion_date) ? $waterProject->completion_date->format('Y-m-d') : '') }}"
            class="w-full rounded border-slate-300"
        >
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-2 block font-medium">Description</label>
        <textarea
            id="description"
            name="description"
            rows="5"
            class="w-full rounded border-slate-300"
        >{{ old('description', $waterProject->description ?? '') }}</textarea>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button
        type="submit"
        class="rounded bg-emerald-700 px-5 py-2 font-semibold text-white hover:bg-emerald-800"
    >
        {{ $buttonText }}
    </button>

    <a
        href="{{ route('water-projects.index') }}"
        class="rounded border border-slate-300 bg-white px-5 py-2 font-semibold"
    >
        Cancel
    </a>
</div>