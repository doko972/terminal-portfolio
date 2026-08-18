<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Cv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CvTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(Cv::DISK);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function putCv(): void
    {
        Storage::disk(Cv::DISK)->put(Cv::PATH, '%PDF-1.4 contenu de test');
    }

    // ---------------------------------------------------------------- public

    public function test_the_download_route_returns_404_when_no_cv_is_online(): void
    {
        $this->get(route('cv.download'))->assertNotFound();
    }

    public function test_a_visitor_can_download_the_cv(): void
    {
        $this->putCv();

        $response = $this->get(route('cv.download'));

        $response->assertOk();
        $this->assertStringContainsString(
            'attachment',
            $response->headers->get('content-disposition'),
            'Le CV doit être servi en téléchargement, pas affiché en ligne.'
        );
        $this->assertStringContainsString(Cv::DOWNLOAD_NAME, $response->headers->get('content-disposition'));
    }

    public function test_the_hero_button_appears_only_when_a_cv_is_online(): void
    {
        $this->get(route('home'))->assertOk()->assertDontSee(route('cv.download'));

        $this->putCv();

        $this->get(route('home'))->assertOk()->assertSee(route('cv.download'));
    }

    // ----------------------------------------------------------------- admin

    public function test_the_admin_screen_is_protected(): void
    {
        $this->get(route('admin.cv.edit'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.cv.edit'))
            ->assertForbidden();

        $this->actingAs($this->admin())->get(route('admin.cv.edit'))->assertOk();
    }

    public function test_an_admin_can_upload_a_cv(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.cv.update'), [
                'cv' => UploadedFile::fake()->create('mon cv à jour.pdf', 200, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.cv.edit'))
            ->assertSessionHas('success');

        Storage::disk(Cv::DISK)->assertExists(Cv::PATH);
    }

    public function test_uploading_again_replaces_the_previous_file(): void
    {
        $this->putCv();
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.cv.update'), [
            'cv' => UploadedFile::fake()->create('nouveau.pdf', 300, 'application/pdf'),
        ])->assertRedirect(route('admin.cv.edit'));

        // Un seul fichier doit subsister : le chemin est fixe, pas accumulatif.
        $this->assertCount(1, Storage::disk(Cv::DISK)->allFiles('cv'));
    }

    public function test_a_non_pdf_file_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.cv.update'), [
                'cv' => UploadedFile::fake()->image('photo.png'),
            ])
            ->assertSessionHasErrors('cv');

        Storage::disk(Cv::DISK)->assertMissing(Cv::PATH);
    }

    public function test_an_oversized_pdf_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.cv.update'), [
                'cv' => UploadedFile::fake()->create('enorme.pdf', Cv::MAX_KB + 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('cv');

        Storage::disk(Cv::DISK)->assertMissing(Cv::PATH);
    }

    public function test_an_admin_can_remove_the_cv(): void
    {
        $this->putCv();

        $this->actingAs($this->admin())
            ->delete(route('admin.cv.destroy'))
            ->assertRedirect(route('admin.cv.edit'));

        Storage::disk(Cv::DISK)->assertMissing(Cv::PATH);
        $this->get(route('cv.download'))->assertNotFound();
    }

    public function test_a_non_admin_cannot_upload_or_delete(): void
    {
        $visitor = User::factory()->create(['is_admin' => false]);

        $this->actingAs($visitor)->post(route('admin.cv.update'), [
            'cv' => UploadedFile::fake()->create('intrus.pdf', 100, 'application/pdf'),
        ])->assertForbidden();

        $this->putCv();
        $this->actingAs($visitor)->delete(route('admin.cv.destroy'))->assertForbidden();
        Storage::disk(Cv::DISK)->assertExists(Cv::PATH);
    }
}
