<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;

final class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function resetPasswordLinkScreenCanBeRendered(): void
    {
        if (! Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');

            return;
        }

        $testResponse = $this->get('/forgot-password');

        $testResponse->assertStatus(200);
    }

    #[Test]
    public function resetPasswordLinkCanBeRequested(): void
    {
        if (! Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');

            return;
        }

        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    #[Test]
    public function resetPasswordScreenCanBeRendered(): void
    {
        if (! Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');

            return;
        }

        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (object $notification): bool {
            $testResponse = $this->get('/reset-password/'.$notification->token);

            $testResponse->assertStatus(200);

            return true;
        });
    }

    #[Test]
    public function passwordCanBeResetWithValidToken(): void
    {
        if (! Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');

            return;
        }

        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user): bool {
            $testResponse = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $testResponse->assertSessionHasNoErrors();

            return true;
        });
    }
}
