<<<<<<< HEAD
<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace Modules\User\Events;

use ArtMin96\FilamentJet\Contracts\TeamContract;
use ArtMin96\FilamentJet\Contracts\UserContract;
use Illuminate\Foundation\Events\Dispatchable;

class TeamMemberRemoved
{
    use Dispatchable;

    /**
     * The team instance.
     */
    public TeamContract $team;

    /**
     * The team member being added.
     */
    public UserContract $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TeamContract $team, UserContract $user)
    {
        $this->team = $team;
        $this->user = $user;
    }
}
=======
=======
>>>>>>> c3ef5a0 (up)
<?php

namespace Modules\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use ArtMin96\FilamentJet\Contracts\TeamContract;
use ArtMin96\FilamentJet\Contracts\UserContract;

class TeamMemberRemoved
{
    use Dispatchable;

     /**
     * The team instance.
     *
     */
    public TeamContract $team;

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
     public function __construct(TeamContract $team,UserContract $user)
    {
        $this->team = $team;
        $this->user = $user;
    }
}
<<<<<<< HEAD
>>>>>>> d1783f5 (up)
=======
>>>>>>> c3ef5a0 (up)
