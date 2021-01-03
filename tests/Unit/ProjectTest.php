<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->owner);


    }

    /** @test */
    public function it_can_add_a_task()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    public function it_has_tasks() {
        $project = Project::factory()->create();
        $this->assertInstanceOf(Collection::class, $project->tasks);
    }




}
