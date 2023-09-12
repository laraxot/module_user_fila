<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function emailVerificationScreenCanBeRendered(): void
    {
        if (! Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped('Email verification not enabled.');

            return;
        }

        $user = User::factory()->withPersonalTeam()->unverified()->create();

        $testResponse = $this->actingAs($user)->get('/email/verify');

        $testResponse->assertStatus(200);
    }

    #[Test]
    public function emailCanBeVerified(): void
    {
        if (! Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped('Email verification not enabled.');

            return;
        }

        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1((string) $user->email)]
        );

        $testResponse = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $testResponse->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    #[Test]
    public function emailCanNotVerifiedWithInvalidHash(): void
    {
        if (! Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped('Email verification not enabled.');

            return;
        }

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
