<<<<<<< HEAD
<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire;

use ArtMin96\FilamentJet\FilamentJet;
use Illuminate\Support\Str;
use Livewire\Component;
use Webmozart\Assert\Assert;

use function Safe\file_get_contents;

class TermsOfService extends Component
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        Assert::string($termsFile = FilamentJet::localizedMarkdownPath('terms.md'), 'wip');

        $view = view('filament-jet::livewire.terms-of-service', [
            'terms' => Str::markdown(file_get_contents($termsFile)),
        ]);

        $view->layout('filament::components.layouts.base', [
            'title' => __('filament-jet::registration.terms_of_service'),
        ]);

        return $view;
    }
}
=======
=======
>>>>>>> c3ef5a0 (up)
<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function Safe\file_get_contents;
use ArtMin96\FilamentJet\FilamentJet;

class TermsOfService extends Component
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        Assert::string($termsFile = FilamentJet::localizedMarkdownPath('terms.md'),'wip');

        $view = view('filament-jet::livewire.terms-of-service', [
            'terms' => Str::markdown(file_get_contents($termsFile)),
        ]);

        $view->layout('filament::components.layouts.base', [
            'title' => __('filament-jet::registration.terms_of_service'),
        ]);

        return $view;
    }
}
<<<<<<< HEAD
>>>>>>> d1783f5 (up)
=======
>>>>>>> c3ef5a0 (up)
