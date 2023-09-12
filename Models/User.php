<?php

declare(strict_types=1);

namespace Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Passport\Client;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\DatabaseNotification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Laravel\Passport\Token;
use Illuminate\Database\Eloquent\Builder;
use ArtMin96\FilamentJet\Contracts\UserContract as UserJetContract;
use ArtMin96\FilamentJet\Traits\CanExportPersonalData;
use ArtMin96\FilamentJet\Traits\HasProfilePhoto;
use ArtMin96\FilamentJet\Traits\HasTeams;
use ArtMin96\FilamentJet\Traits\TwoFactorAuthenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\User\Database\Factories\UserFactory;
use Modules\Xot\Datas\XotData;
use Spatie\Permission\Traits\HasRoles;
use Spatie\PersonalDataExport\ExportsPersonalData;

/**
 * Modules\User\Models\User.
 *
 * @property int                                                                                                           $id
 * @property string                                                                                                        $name
 * @property string                                                                                                        $email
 * @property string                                                                                                        $api_token
 * @property Carbon|null $email_verified_at
 * @property string                                                                                                        $password
 * @property string|null                                                                                                   $two_factor_secret
 * @property string|null                                                                                                   $two_factor_recovery_codes
 * @property string|null                                                                                                   $two_factor_confirmed_at
 * @property string|null                                                                                                   $remember_token
 * @property int|null                                                                                                      $current_team_id
 * @property string|null                                                                                                   $profile_photo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection<int, Client> $clients
 * @property int|null                                                                                                      $clients_count
 * @property string                                                                                                        $profile_photo_url
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null                                                                                                      $notifications_count
 * @property Collection<int, Permission> $permissions
 * @property int|null                                                                                                      $permissions_count
 * @property Collection<int, Role> $roles
 * @property int|null                                                                                                      $roles_count
 * @property Collection<int, Token> $tokens
 * @property int|null                                                                                                      $tokens_count
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCurrentTeamId($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereProfilePhotoPath($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTwoFactorConfirmedAt($value)
 * @method static Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder|User whereTwoFactorSecret($value)
 * @method static Builder|User whereUpdatedAt($value)
 *
 * @mixin IdeHelperUser
 *
 * @property string|null $lang
 * @property int|null    $owned_teams_count
 * @property int|null    $teams_count
 *
 * @method static Builder|User whereLang($value)
 *
 * @property Team|null $currentTeam
 * @property Collection<int, Team> $ownedTeams
 * @property \Modules\EWall\Models\Profile|null                                       $profile
 * @property Collection<int, Team> $teams
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser, \Modules\Xot\Contracts\UserContract, HasAvatar, UserJetContract, ExportsPersonalData
{ /* , HasTeamsContract */
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use CanExportPersonalData;
    use HasRoles;

    /**
     * @var string
     */
    protected $connection = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lang',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed', //Call to undefined cast [hashed] on column [password] in model [Modules\User\Models\User].
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function canAccessFilament(): bool
    {
        // return $this->role_id === Role::ROLE_ADMINISTRATOR;
        return true;
    }

    public function profile(): HasOne
    {
        $profileClass = XotData::make()->getProfileClass();

        return $this->hasOne($profileClass);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
