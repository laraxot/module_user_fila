<?php

namespace Modules\User\Filament\Pages;

use Modules\User\Contracts\CreatesTeams;
use Modules\User\Http\Livewire\Traits\Properties\HasUserProperty;
use Modules\User\Traits\RedirectsActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Redirector;

class CreateTeam extends Page
{
    use \Savannabits\FilamentModules\Concerns\ContextualPage;
    use RedirectsActions;
    use HasUserProperty;

    protected static string $view = 'filament-jet::filament.pages.create-team';

    public array $createTeamState = [];

    protected static function shouldRegisterNavigation(): bool
    {
        return config('filament-jet.should_register_navigation.create_team');
    }

    protected function getForms(): array
    {
        return array_merge(
            parent::getForms(),
            [
                'createTeamForm' => $this->makeForm()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament-jet::teams/create.fields.team_name'))
                            ->required()
                            ->maxLength(255),
                    ])
                    ->statePath('createTeamState'),
            ]
        );
    }

    /**
     * Create a new team.
     */
    public function createTeam(CreatesTeams $creator): Redirector
    {
        $creator->create($this->user, $this->createTeamState);

        Notification::make()
            ->title(__('filament-jet::teams/create.messages.created'))
            ->success()
            ->send();

        return $this->redirectPath($creator);
    }
}
