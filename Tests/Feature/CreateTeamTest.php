<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;
use Livewire\Livewire;
use Modules\User\Models\User;
use Tests\TestCase;

class CreateTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function teamsCanBeCreated(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Livewire::test(CreateTeamForm::class)
            ->set(['state' => ['name' => 'Test Team']])
            ->call('createTeam');

        $this->assertCount(2, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->fresh()->ownedTeams()->latest('id')->first()->name);
    }
}
