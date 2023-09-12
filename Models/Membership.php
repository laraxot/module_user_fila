<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use ArtMin96\FilamentJet\Models\Membership as FilamentJetMembership;

/**
 * Modules\User\Models\Membership.
 *
 * @property int                             $id
 * @property int                             $team_id
 * @property int                             $user_id
 * @property string|null                     $role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Membership newModelQuery()
 * @method static Builder|Membership newQuery()
 * @method static Builder|Membership query()
 * @method static Builder|Membership whereCreatedAt($value)
 * @method static Builder|Membership whereId($value)
 * @method static Builder|Membership whereRole($value)
 * @method static Builder|Membership whereTeamId($value)
 * @method static Builder|Membership whereUpdatedAt($value)
 * @method static Builder|Membership whereUserId($value)
 *
 * @mixin IdeHelperMembership
 *
 * @property string|null $customer_id
 *
 * @method static Builder|Membership whereCustomerId($value)
 *
 * @mixin \Eloquent
 */
class Membership extends FilamentJetMembership
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var string
     */
    protected $connection = 'user';
}
