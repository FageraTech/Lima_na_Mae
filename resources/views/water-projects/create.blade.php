@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Create Water Project</h1>
        <p class="mt-2 text-slate-600">
            Add a new water access or agricultural support project.
        </p>
    </div>

    <div class="rounded bg-white p-6 shadow">
        <form action="{{ route('water-projects.store') }}" method="POST">
            @csrf

            @include('water-projects.form', [
                'buttonText' => 'Create Project',
            ])
        </form>
    </div>
@endsection