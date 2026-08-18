<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders(): void
    {
        Project::factory()->count(2)->create();
        Experience::factory()->create();

        $this->get(route('home'))->assertOk();
    }

    public function test_timeline_page_renders(): void
    {
        Experience::factory()->create();

        $this->get(route('timeline'))->assertOk();
    }

    public function test_contact_page_renders(): void
    {
        $this->get(route('contact'))->assertOk();
    }

    public function test_project_detail_page_renders(): void
    {
        $project = Project::factory()->create();

        $this->get(route('project.show', $project->slug))
            ->assertOk()
            ->assertSee($project->title);
    }

    public function test_archived_project_detail_returns_404(): void
    {
        $project = Project::factory()->archived()->create();

        $this->get(route('project.show', $project->slug))->assertNotFound();
    }

    public function test_project_api_hides_archived_projects(): void
    {
        $visible = Project::factory()->create();
        $archived = Project::factory()->archived()->create();

        $this->getJson(route('api.project', $visible->id))->assertOk();
        $this->getJson(route('api.project', $archived->id))->assertNotFound();
    }

    public function test_home_page_serialises_project_images_for_the_modal(): void
    {
        // La galerie de la modale lit project.images depuis le @json() de la
        // vue : sans eager loading la relation n'est pas sérialisée.
        $project = Project::factory()->create();
        $project->images()->create(['image_path' => 'projects/demo.png', 'is_main' => true, 'order' => 0]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('projects/demo.png', escape: false);
    }
}
