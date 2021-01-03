<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_add_tasks_to_projects() {

        $project = Project::factory()->create();
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_the_project_may_add_tasks() {
        $this->signIn();
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function only_the_owner_of_the_project_may_update_tasks() {
        $this->signIn();
        $project = Project::factory()->create(); // project that you did not create
        $task = $project->addTask('test task');


                $this->patch($project->path() . '/tasks/' . $task->id, ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $body = 'Test task';

        $this->post($project->path() . '/tasks', ['body' => $body]);
        $this->assertDatabaseHas('tasks', ['body' => $body]);
        $this->get($project->path())
            ->assertSee($body);
    }

    /** @test */
    public function a_task_can_be_updated() {
        $this->withoutExceptionHandling();

        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

//        $project = Project::factory()->create(['owner_id' => auth()->id()]);
//        $task = $project->addTask('Test task');

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ])->assertStatus(302);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_requires_a_body() {
        $this->signIn();
        $project = Project::factory()->create([
            'owner_id' => auth()->id()
        ]);

        $task = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $task)->assertSessionHasErrors('body');
    }

}
