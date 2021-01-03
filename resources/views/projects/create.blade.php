@extends('layouts.app')

@section('content')
    <h1>Create a Project</h1>
    <form method="POST" action="/projects">
        @csrf
        <label for="title">Title</label>
        <div>
            <input type="text" name="title" id="title" placeholder="Title">
        </div>
        <br>
        <label for="description">Description</label>
        <div>
            <textarea type="text" name="description" id="description" placeholder="Description"></textarea>
        </div>
        <br><br>
        <button type="submit">Create Project</button>
        <a href="/projects">Cancel</a>
    </form>
@endsection
