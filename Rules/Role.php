<?php

declare(strict_types=1);

namespace Modules\User\Rules;

use ArtMin96\FilamentJet\FilamentJet;
use Illuminate\Contracts\Validation\Rule;

class Role implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     */
    public function passes($attribute, $value): bool
    {
        return in_array($value, array_keys(FilamentJet::$roles));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must be a valid role.');
    }
}
