<?php
/**
 * @see https://github.com/ryangjchandler/filament-user-resource/blob/main/src/Resources/UserResource.php
 * @see https://github.com/3x1io/filament-user/blob/main/src/Resources/UserResource.php
 */

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\User\Filament\Resources\UserResource\Pages\EditUser;
use Modules\User\Filament\Resources\UserResource\Pages\ListUsers;
use Modules\User\Filament\Resources\UserResource\Pages\CreateUser;
use Modules\User\Filament\Resources\UserResource\Widgets\UserOverview;
use Modules\User\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use Modules\User\Filament\Resources\UserResource\RelationManagers\TeamsRelationManager;
use Modules\User\Filament\Resources\UserResource\RelationManagers\ProfileRelationManager;

class UserResource extends XotBaseResource
{
    protected static string $resourceFile = __FILE__;

    // protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    private static bool|\Closure $enablePasswordUpdates = true;

    private static ?\Closure $extendFormCallback = null;

    /*
    protected static function getNavigationLabel(): string
    {
        return trans('filament-user::user.resource.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('filament-user::user.resource.label');
    }

    public static function getLabel(): string
    {
        return trans('filament-user::user.resource.single');
    }

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-user.group');
    }

    protected function getTitle(): string
    {
        return trans('filament-user::user.resource.title.resource');
    }
    */

    protected static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getWidgets(): array
    {
        return [
            UserOverview::class,
        ];
    }

    public static function extendForm(\Closure $callback): void
    {
        static::$extendFormCallback = $callback;
    }

    public static function formOld(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(),
                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name'),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static function () {
                $schema = [
                    'left' => Card::make([
                        'name' => TextInput::make('name')
                            ->required(),
                        'email' => TextInput::make('email')
                            ->required()
                            ->unique(ignoreRecord: true),
                        'password' => TextInput::make('password')
                            ->required()
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            /*
                            ->dehydrateStateUsing(static function ($state) use ($form){
                                if(!empty($state)){
                                    return Hash::make($state);
                                }

                                $user = User::find($form->getColumns());
                                if($user){
                                    return $user->password;
                                }
                            }),
                            */
                            ->visible(fn ($livewire): bool => $livewire instanceof CreateUser)
                            ->rule(Password::default()),
                        'new_password_group' => Group::make([
                            'new_password' => TextInput::make('new_password')
                                ->password()
                                ->label('New Password')
                                ->nullable()
                                ->rule(Password::default())
                                ->dehydrated(false),
                            'new_password_confirmation' => TextInput::make('new_password_confirmation')
                                ->password()
                                ->label('Confirm New Password')
                                ->rule('required', fn ($get): bool => (bool) $get('new_password'))
                                ->same('new_password')
                                ->dehydrated(false),
                        ])->visible(static::$enablePasswordUpdates),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name'),
                        /*
                        Select::make('teams')
                            ->multiple()
                            ->options(fn()=>Team::get()->pluck('name','id')),
                        */
                    ])->columnSpan(8),
                    'right' => Card::make([
                        'created_at' => Placeholder::make('created_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;')),
                    ])->columnSpan(4),
                ];
                if (static::$extendFormCallback instanceof \Closure) {
                    return value(static::$extendFormCallback, $schema);
                }

                return $schema;
            })
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->toggleable(),
                TextColumn::make('email')->sortable()->searchable(),
                // TextColumn::make('profile.first_name')->label('first name')->sortable()->searchable()->toggleable(),
                // TextColumn::make('profile.last_name')->label('last name')->sortable()->searchable()->toggleable(),
                TextColumn::make('teams.name')->sortable()->searchable()->toggleable(),
                // Tables\Columns\TextColumn::make('email'),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //    ->dateTime(),
                TextColumn::make('role.name')->toggleable(),
                TextColumn::make('roles.name')->toggleable(),
                // Tables\Columns\TextColumn::make('created_at')->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //    ->dateTime(),
                // Tables\Columns\TextColumn::make('role_id'),
                // Tables\Columns\TextColumn::make('display_name'),
                // Tables\Columns\TextColumn::make('phone_number'),
                // Tables\Columns\TextColumn::make('phone_verified_at')
                //    ->dateTime(),
                // Tables\Columns\TextColumn::make('photo'),
                BooleanColumn::make('email_verified_at')->sortable()->searchable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        Role::ROLE_USER => 'User',
                        Role::ROLE_OWNER => 'Owner',
                        Role::ROLE_ADMINISTRATOR => 'Administrator',
                    ])
                    ->attribute('role_id'),
                Filter::make('verified')
                    ->label(trans('filament-user::user.resource.verified'))
                    ->query(fn (Builder $builder): Builder => $builder->whereNotNull('email_verified_at')),
                Filter::make('unverified')
                    ->label(trans('filament-user::user.resource.unverified'))
                    ->query(fn (Builder $builder): Builder => $builder->whereNull('email_verified_at')),
            ])
            ->actions([
                EditAction::make(),
                Action::make('changePassword')
                    ->action(static function (User $user, array $data): void {
                        $user->update([
                            'password' => Hash::make($data['new_password']),
                        ]);
                        Filament::notify('success', 'Password changed successfully.');
                    })
                    ->form([
                        TextInput::make('new_password')
                            ->password()
                            ->label('New Password')
                            ->required()
                            ->rule(Password::default()),
                        TextInput::make('new_password_confirmation')
                            ->password()
                            ->label('Confirm New Password')
                            ->rule('required', fn ($get): bool => (bool) $get('new_password'))
                            ->same('new_password'),
                    ])
                    ->icon('heroicon-o-key')
                // ->visible(fn (User $record): bool => $record->role_id === Role::ROLE_ADMINISTRATOR)
                ,
                Action::make('deactivate')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(fn (User $record) => $record->delete())
                // ->visible(fn (User $record): bool => $record->role_id === Role::ROLE_ADMINISTRATOR)
                ,
            ])

            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function enablePasswordUpdates(bool|\Closure $condition = true): void
    {
        static::$enablePasswordUpdates = $condition;
    }

    /*
    public static function getModel(): string
    {
        return config('filament-user-resource.model');
    }
    */

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
            ProfileRelationManager::class,
            RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
