<?php
/**
 * @see https://github.com/Althinect/filament-spatie-roles-permissions/tree/2.x
 */

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Filament\Resources\PermissionResource\Pages\CreatePermission;
use Modules\User\Filament\Resources\PermissionResource\Pages\EditPermission;
use Modules\User\Filament\Resources\PermissionResource\Pages\ListPermissions;
use Modules\User\Filament\Resources\PermissionResource\Pages\ViewPermission;
use Modules\User\Filament\Resources\PermissionResource\RelationManager\RoleRelationManager;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Savannabits\FilamentModules\Concerns\ContextualResource;

class PermissionResource extends XotBaseResource
{
    use ContextualResource;
    protected static string $resourceFile = __FILE__;
    
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    // public static function shouldRegisterNavigation(): bool
    // {
    //    return config('filament-spatie-roles-permissions.should_register_on_navigation.permissions', true);
    // }

    // public static function getModel(): string
    // {
    //    return config('permission.models.permission', Permission::class);
    // }

    // public static function getLabel(): string
    // {
    //    return __('filament-spatie-roles-permissions::filament-spatie.section.permission');
    // }

    // public static function getNavigationGroup(): ?string
    // {
    //    return __(config('filament-spatie-roles-permissions.navigation_section_group', 'filament-spatie-roles-permissions::filament-spatie.section.roles_and_permissions'));
    // }

    // public static function getPluralLabel(): string
    // {
    //    return __('filament-spatie-roles-permissions::filament-spatie.section.permissions');
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(static::trans('fields.name')),
                            Select::make('guard_name')
                                ->label(static::trans('fields.guard_name'))
                                ->options(config('filament-spatie-roles-permissions.guard_names'))
                                ->default(config('filament-spatie-roles-permissions.default_guard_name')),
                            Select::make('roles')
                                ->multiple()
                                ->label(static::trans('fields.roles'))
                                ->relationship('roles', 'name')
                                ->preload(config('filament-spatie-roles-permissions.preload_roles', true)),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label(static::trans('fields.name'))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->toggleable(isToggledHiddenByDefault: config('filament-spatie-roles-permissions.toggleable_guard_names.permissions.isToggledHiddenByDefault', true))
                    ->label(static::trans('fields.guard_name'))
                    ->searchable(),
            ])
            ->filters([
                /*
                Filter::make('models')
                    ->form(function () {
                        $commands = new \Modules\User\Filament\Commands\Permission();
                        $models = $commands->getAllModels();

                        return array_map(function (\ReflectionClass $model) {
                            return Checkbox::make($model->getShortName());
                        }, $models);
                    })
                    ->query(function (Builder $query, array $data) {
                        return $query->where(function (Builder $query) use ($data) {
                            foreach ($data as $key => $value) {
                                if ($value) {
                                    $query->orWhere('name', 'like', eval(config('filament-spatie-roles-permissions.model_filter_key')));
                                }
                            }
                        });
                    }),
                */
            ])->actions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                DeleteBulkAction::make(),
                // ]),
                BulkAction::make('Attach Role')
                    ->action(static function (Collection $records, array $data) : void {
                        foreach ($records as $record) {
                            $record->roles()->sync($data['role']);
                            $record->save();
                        }
                    })
                    ->form([
                        Select::make('role')
                            ->label(static::trans('fields.role'))
                            ->options(Role::query()->pluck('name', 'id'))
                            ->required(),
                    ])->deselectRecordsAfterCompletion(),
            ]);
        // ->emptyStateActions([
        //    Tables\Actions\CreateAction::make(),
        // ])
    }

    public static function getRelations(): array
    {
        return [
            RoleRelationManager::class,
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
            'view' => ViewPermission::route('/{record}'),
        ];
    }
}
