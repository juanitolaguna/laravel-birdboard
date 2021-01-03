<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_projects()
    {
        //$this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $this->post('/projects', $project->toArray())->assertRedirect('login');

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');


        $this->get($project->path())->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        //$this->signIn();
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $project = Project::factory()->make([
            'owner_id' => auth()->id()
        ]);

        $response = $this->post('/projects', $project->toArray());
        $this->assertDatabaseHas('projects', $project->toArray());

        $project = Project::where($project->toArray())->first();
        $response->assertRedirect($project->path());
        $this->get($project->path())
            ->assertSee($project['title'])
            ->assertSee(str_limit($project['description'], 100))
            ->assertSee($project['notes']);
    }


    /** @test */
    public function a_user_can_update_a_project() {
        $this->withoutExceptionHandling();
        //$this->signIn();
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $project = Project::factory()->create([
            'owner_id' => auth()->id(),
        ]);

        $this->patch($project->path(), [
            'notes' => 'Changed'
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', ['notes' => 'Changed']);



    }

    /** @test */
    public function a_user_can_view_a_project()
    {

        $this->signIn();

        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        //$this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();

        //$this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $this->patch($project->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
