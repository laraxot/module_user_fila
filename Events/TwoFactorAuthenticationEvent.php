<?php

namespace Modules\User\Events;

use Illuminate\Foundation\Events\Dispatchable;

abstract class TwoFactorAuthenticationEvent
{
    use Dispatchable;

    /**
     * The user instance.
     *
     * @var \Modules\User\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Modules\User\Models\User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
