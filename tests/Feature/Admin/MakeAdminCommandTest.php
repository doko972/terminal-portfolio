<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MakeAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_administrator(): void
    {
        $this->artisan('portfolio:make-admin', [
            'email' => 'patron@example.com',
            '--name' => 'Le Patron',
            '--password' => 'un-mot-de-passe',
        ])->assertSuccessful();

        $user = User::where('email', 'patron@example.com')->firstOrFail();

        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('un-mot-de-passe', $user->password));
    }

    public function test_the_created_account_can_actually_reach_the_back_office(): void
    {
        // Le piège que ce test verrouille : is_admin n'étant pas mass-assignable,
        // une création par User::create() produirait un compte à 403.
        $this->artisan('portfolio:make-admin', [
            'email' => 'patron@example.com',
            '--name' => 'Le Patron',
            '--password' => 'un-mot-de-passe',
        ])->assertSuccessful();

        $this->actingAs(User::where('email', 'patron@example.com')->firstOrFail())
            ->get(route('admin.projects.index'))
            ->assertOk();
    }

    public function test_it_promotes_an_existing_account(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->artisan('portfolio:make-admin', ['email' => $user->email])
            ->assertSuccessful();

        $this->assertTrue($user->fresh()->is_admin);
    }

    public function test_it_rejects_a_weak_password(): void
    {
        $this->artisan('portfolio:make-admin', [
            'email' => 'patron@example.com',
            '--name' => 'Le Patron',
            '--password' => 'court',
        ])->assertFailed();

        $this->assertDatabaseMissing('users', ['email' => 'patron@example.com']);
    }
}
