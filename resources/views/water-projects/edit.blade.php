@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Edit Water Project</h1>
        <p class="mt-2 text-slate-600">
            Update the project information below.
        </p>
    </div>

    <div class="rounded bg-white p-6 shadow">
        <form
            action="{{ route('water-projects.update', $waterProject) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @include('water-projects.form', [
                'buttonText' => 'Update Project',
            ])
        </form>
    </div>
@endsection