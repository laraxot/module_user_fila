<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function currentProfileInformationIsAvailable(): void
    {
        $this->actingAs($user = User::factory()->create());

        $testableLivewire = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->name, $testableLivewire->state['name']);
        $this->assertEquals($user->email, $testableLivewire->state['email']);
    }

    #[Test]
    public function profileInformationCanBeUpdated(): void
    {
        $this->actingAs($user = User::factory()->create());

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
            ->call('updateProfileInformation');

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}
