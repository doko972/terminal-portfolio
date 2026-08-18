<?php

namespace Tests\Feature\Admin;

use App\Models\Experience;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les vues d'administration passent par <x-app-layout> : ces tests garantissent
 * que chaque écran du back-office se rend réellement.
 */
class AdminViewsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_project_screens_render(): void
    {
        $admin = $this->admin();
        $project = Project::factory()->create();

        $this->actingAs($admin)->get(route('admin.projects.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.projects.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.projects.edit', $project))->assertOk();
    }

    public function test_experience_screens_render(): void
    {
        $admin = $this->admin();
        $experience = Experience::factory()->create();

        $this->actingAs($admin)->get(route('admin.experiences.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.experiences.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.experiences.edit', $experience))->assertOk();
    }

    public function test_dashboard_and_profile_render(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('profile.edit'))->assertOk();
    }

    public function test_featured_flag_can_be_switched_off(): void
    {
        // Une case décochée n'est pas envoyée par le navigateur : sans
        // résolution explicite côté contrôleur, l'ancienne valeur survivait.
        $project = Project::factory()->featured()->create();

        $this->actingAs($this->admin())->put(route('admin.projects.update', $project), [
            'title' => $project->title,
            'description' => $project->description,
            'technologies' => 'PHP, Laravel',
            'status' => 'termine',
        ])->assertRedirect(route('admin.projects.index'));

        $this->assertFalse($project->fresh()->is_featured);
    }

    public function test_two_projects_with_the_same_title_get_distinct_slugs(): void
    {
        $admin = $this->admin();

        foreach ([1, 2] as $i) {
            $this->actingAs($admin)->post(route('admin.projects.store'), [
                'title' => 'Portfolio Terminal',
                'description' => 'Description du projet numéro '.$i,
                'technologies' => 'PHP, Laravel',
                'status' => 'termine',
            ])->assertRedirect(route('admin.projects.index'));
        }

        $slugs = Project::where('title', 'Portfolio Terminal')->pluck('slug');

        $this->assertCount(2, $slugs);
        $this->assertCount(2, $slugs->unique(), 'Les slugs doivent rester uniques.');
    }
}
