<?php

declare(strict_types=1);

namespace Modules\User\Events;

use ArtMin96\FilamentJet\Contracts\UserContract;
use Illuminate\Foundation\Events\Dispatchable;

abstract class TwoFactorAuthenticationEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        /**
         * The team member being added.
         */
        public UserContract $userContract
    ) {
    }
}
