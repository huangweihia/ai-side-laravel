<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_project()
    {
        $project = Project::create([
            'name' => 'Test Project',
            'full_name' => 'test/test-project',
            'url' => 'https://github.com/test/test-project',
            'description' => 'A test project',
            'stars' => 1000,
            'forks' => 100,
            'score' => 8.5,
            'language' => 'PHP',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'stars' => 1000,
        ]);

        $this->assertEquals('test-project', $project->name);
        $this->assertEquals(1000, $project->stars);
    }

    /** @test */
    public function it_calculates_score_correctly()
    {
        $score = Project::calculateScore(50000, 5, 'high');
        
        $this->assertGreaterThan(5, $score);
        $this->assertLessThanOrEqual(10, $score);
    }

    /** @test */
    public function it_has_featured_scope()
    {
        Project::create([
            'name' => 'Featured Project',
            'url' => 'https://github.com/test/featured',
            'stars' => 50000,
            'is_featured' => true,
        ]);

        Project::create([
            'name' => 'Normal Project',
            'url' => 'https://github.com/test/normal',
            'stars' => 1000,
            'is_featured' => false,
        ]);

        $featured = Project::featured()->get();

        $this->assertCount(1, $featured);
        $this->assertEquals('Featured Project', $featured->first()->name);
    }

    /** @test */
    public function it_has_popular_scope()
    {
        Project::create(['name' => 'Project A', 'url' => 'https://github.com/test/a', 'stars' => 1000]);
        Project::create(['name' => 'Project B', 'url' => 'https://github.com/test/b', 'stars' => 5000]);
        Project::create(['name' => 'Project C', 'url' => 'https://github.com/test/c', 'stars' => 3000]);

        $popular = Project::popular(2)->get();

        $this->assertCount(2, $popular);
        $this->assertEquals('Project B', $popular->first()->name);
    }

    /** @test */
    public function it_returns_correct_difficulty_label()
    {
        $project = new Project(['difficulty' => 'easy']);
        $this->assertEquals('缁犫偓閸?, $project->getDifficultyLabel());

        $project->difficulty = 'medium';
        $this->assertEquals('娑擃厾鐡?, $project->getDifficultyLabel());

        $project->difficulty = 'hard';
        $this->assertEquals('閸ヤ即姣?, $project->getDifficultyLabel());
    }

    /** @test */
    public function it_updates_existing_project_on_duplicate_url()
    {
        $project = Project::create([
            'name' => 'Original Name',
            'url' => 'https://github.com/test/project',
            'stars' => 1000,
        ]);

        $updated = Project::updateOrCreate(
            ['url' => 'https://github.com/test/project'],
            ['stars' => 2000]
        );

        $this->assertEquals($project->id, $updated->id);
        $this->assertEquals(2000, $updated->fresh()->stars);
        $this->assertEquals('Original Name', $updated->fresh()->name);
    }
}
