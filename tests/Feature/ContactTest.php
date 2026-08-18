<?php

namespace Tests\Feature;

use App\Mail\ContactMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.recaptcha.secret_key' => 'test-secret']);
        config(['services.contact.to' => 'destinataire@example.com']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'nom' => 'Jean Test',
            'email' => 'jean@example.com',
            'sujet' => 'Bonjour',
            'message' => 'Un message suffisamment long pour passer la validation.',
            'g-recaptcha-response' => 'jeton-valide',
        ], $overrides);
    }

    public function test_a_valid_message_is_sent(): void
    {
        Mail::fake();
        Http::fake(['*siteverify' => Http::response(['success' => true])]);

        $this->post(route('contact.send'), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('success');

        Mail::assertSent(ContactMail::class);
    }

    public function test_a_failed_recaptcha_blocks_the_message(): void
    {
        Mail::fake();
        Http::fake(['*siteverify' => Http::response(['success' => false])]);

        $this->post(route('contact.send'), $this->payload())
            ->assertSessionHasErrors('g-recaptcha-response');

        Mail::assertNothingSent();
    }

    public function test_an_unreachable_recaptcha_service_blocks_the_message(): void
    {
        // Une panne réseau ne doit pas revenir à laisser passer le captcha.
        Mail::fake();
        Http::fake(fn () => throw new \RuntimeException('réseau indisponible'));

        $this->post(route('contact.send'), $this->payload())
            ->assertSessionHasErrors('g-recaptcha-response');

        Mail::assertNothingSent();
    }

    public function test_a_missing_recaptcha_token_is_rejected(): void
    {
        Mail::fake();

        $this->post(route('contact.send'), $this->payload(['g-recaptcha-response' => '']))
            ->assertSessionHasErrors('g-recaptcha-response');

        Mail::assertNothingSent();
    }

    public function test_smtp_failures_are_not_leaked_to_the_visitor(): void
    {
        Http::fake(['*siteverify' => Http::response(['success' => true])]);
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('SMTP mot-de-passe-secret'));

        $response = $this->post(route('contact.send'), $this->payload());

        $response->assertSessionHas('error');
        $this->assertStringNotContainsString(
            'mot-de-passe-secret',
            session('error'),
            "Le détail de l'exception ne doit jamais atteindre le visiteur."
        );
    }
}
