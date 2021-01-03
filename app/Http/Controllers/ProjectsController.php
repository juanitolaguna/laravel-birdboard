<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function store()
    {

        // validare returns only validated attributes
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'max:255'
        ]);

        $attributes['owner_id'] = auth()->id();

        $project = auth()->user()->projects()->create($attributes);

        //Project::create($attributes);
        return redirect($project->path());
    }

    public function update(Project $project) {
//        if (auth()->user()->isNot($project->owner)) {
//            abort(403);
//        }

//        $project->update([
//            'notes' => request('notes')
//        ]);
        $this->authorize('update', $project);

        $project->update(request(['notes']));

        return redirect($project->path());
    }

    public function show(Project $project)
    {
//        if (auth()->id() !== (int)$project->owner_id) {
//            abort(403);
//        }

//        if (auth()->user()->isNot($project->owner)) {
//            abort(403);
//        }
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }
}
