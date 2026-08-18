<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectImageUploadTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Projet illustré',
            'description' => 'Une description de projet.',
            'technologies' => 'PHP, Laravel',
            'status' => 'termine',
        ], $overrides);
    }

    public function test_a_png_screenshot_is_accepted(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.projects.store'), $this->payload([
                'images' => [UploadedFile::fake()->image('capture.png', 1920, 1080)],
            ]))
            ->assertRedirect(route('admin.projects.index'))
            ->assertSessionHasNoErrors();

        $this->assertCount(1, Storage::disk('public')->allFiles('projects'));
        $this->assertTrue(Project::first()->images()->first()->is_main);
    }

    public function test_an_oversized_image_is_rejected_with_a_readable_message(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.projects.store'), $this->payload([
                'images' => [UploadedFile::fake()->create('enorme.png', 6000, 'image/png')],
            ]))
            ->assertSessionHasErrors('images.0');

        $this->assertStringContainsString(
            'trop lourde',
            session('errors')->first('images.0'),
            'Le message doit expliquer la cause du refus.'
        );
        $this->assertSame(0, Project::count());
    }

    public function test_more_than_ten_images_are_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $images = [];
        for ($i = 0; $i < 11; $i++) {
            $images[] = UploadedFile::fake()->image("capture-{$i}.png");
        }

        $this->actingAs($admin)
            ->post(route('admin.projects.store'), $this->payload(['images' => $images]))
            ->assertSessionHasErrors('images');

        $this->assertSame(0, Project::count());
    }

    public function test_a_pdf_disguised_as_an_image_is_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.projects.store'), $this->payload([
                'images' => [UploadedFile::fake()->create('document.pdf', 100, 'application/pdf')],
            ]))
            ->assertSessionHasErrors('images.0');

        $this->assertSame(0, Project::count());
    }
}
