<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectsPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Project $project)
    {
       return $user->is($project->owner);
    }
}
