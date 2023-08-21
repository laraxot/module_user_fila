<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace Modules\User\Events;

use ArtMin96\FilamentJet\Contracts\UserContract;
use Illuminate\Foundation\Events\Dispatchable;

abstract class TwoFactorAuthenticationEvent
{
    use Dispatchable;

    /**
     * The team member being added.
     */
    public UserContract $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }
}
=======
<?php

namespace Modules\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use ArtMin96\FilamentJet\Contracts\TeamContract;
use ArtMin96\FilamentJet\Contracts\UserContract;

abstract class TwoFactorAuthenticationEvent
{
    use Dispatchable;

    /**
     * The team member being added.
     *
     */
    public UserContract $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }
}
>>>>>>> d1783f5 (up)
