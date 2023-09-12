<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use ArtMin96\FilamentJet\FilamentJet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Modules\User\Models\Role.
 *
 * @property int                                                                                 $id
 * @property string                                                                              $name
 * @property string                                                                              $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection<int, Permission> $permissions
 * @property int|null                                                                            $permissions_count
 * @property Collection<int, User> $users
 * @property int|null                                                                            $users_count
 *
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role permission($permissions)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 *
 * @mixin IdeHelperRole
 *
 * @property int $team_id
 *
 * @method static Builder|Role whereTeamId($value)
 *
 * @mixin \Eloquent
 */
class Role extends SpatieRole
{
    use HasFactory;

    public const ROLE_ADMINISTRATOR = 1;
    
    public const ROLE_OWNER = 2;
    
    public const ROLE_USER = 3;

    /**
     * @var string
     */
    protected $connection = 'user';

    /**
     * Get all of the teams the user belongs to.
     */
    public function team()
    {
        $teamClass = FilamentJet::teamModel();

        return $this->belongsTo($teamClass);
        /*
        $pivotClass = FilamentJet::membershipModel();
        $pivot = app($pivotClass);
        $pivotTable = $pivot->getTable();
        $pivotDbName = $pivot->getConnection()->getDatabaseName();
        $pivotTableFull = $pivotDbName.'.'.$pivotTable;

        // $this->setConnection('mysql');
        return $this->belongsToMany(FilamentJet::teamModel(), $pivotTableFull, null, 'team_id')
            ->using($pivotClass)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
        */
    }
}
