@extends('layouts.app')

@section('content')
    <header class="flex items-center py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-600 text-sm">
                <a href="/projects">My Projects</a> / {{ $project->title }}
            </p>

            <a href="/projects/create" class="button">
                New Project
            </a>
        </div>
    </header>
    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-6">
                    <h2 class="text-gray-600 text-lg mb-3">Tasks</h2>
                    {{-- tasks --}}
                    @foreach($project->tasks as $task)
                        <form action="{{ $task->path() }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="card mb-4">
                                <div class="flex">
                                    <input name="body" value="{{ $task->body }}"
                                           class="w-full {{ $task->completed ? 'text-gray-400 line-through' : '' }}">
                                    <input name="completed" type="checkbox" onChange="this.form.submit()"
                                        {{ $task->completed ? 'checked' : '' }}>
                                </div>

                            </div>
                        </form>
                    @endforeach

                    <div class="card mb-4">
                        <form action="{{ $project->path() . '/tasks' }}" method="POST">
                            @csrf
                            <input class="w-full" placeholder="Add a new task..." name="body">
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-gray-600 text-lg mb-3">General Notes</h2>
                    {{-- general notes --}}
                    <form method="POST" action="{{ $project->path() }}">
                        @csrf
                        @method('PATCH')
                        <textarea class="card w-full mb-4"
                                  style="min-height: 200px;"
                                  placeholder="Make a note..."
                                  name="notes"
                        >{{ $project->notes }}</textarea>

                        <button type="submit" class="button">Save</button>
                    </form>
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>

@endsection
