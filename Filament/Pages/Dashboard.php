<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages;

use Filament\Pages\Page;
use Savannabits\FilamentModules\Concerns\ContextualPage;

class Dashboard extends Page
{
    use ContextualPage;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'user::filament.pages.dashboard';
}
=======
<?php

namespace Modules\User\Filament\Pages;

use Filament\Pages\Page;
use Savannabits\FilamentModules\Concerns\ContextualPage;

class Dashboard extends Page
{
    use ContextualPage;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'user::filament.pages.dashboard';
}
>>>>>>> d1783f5 (up)
