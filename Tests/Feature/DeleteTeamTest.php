<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\DeleteTeamForm;
use Livewire\Livewire;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;

class DeleteTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function teamsCanBeDeleted(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->ownedTeams()->save($team = Team::factory()->make([
            'personal_team' => false,
        ]));

        $team->users()->attach(
            $otherUser = User::factory()->create(),
            ['role' => 'test-role']
        );

        $component = Livewire::test(DeleteTeamForm::class, ['team' => $team->fresh()])
            ->call('deleteTeam');

        $this->assertNull($team->fresh());
        $this->assertCount(0, $otherUser->fresh()->teams);
    }

    /**
     * @test
     */
    public function personalTeamsCantBeDeleted(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $component = Livewire::test(DeleteTeamForm::class, ['team' => $user->currentTeam])
            ->call('deleteTeam')
            ->assertHasErrors(['team']);

        $this->assertNotNull($user->currentTeam->fresh());
    }
}
