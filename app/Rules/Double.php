<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Double implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        if (preg_match(config('constants.DOUBLE_REGEX'), $value)) {
            return true;
        }
        return false;
    }

    public function message(): string
    {
        return 'The selected :attribute is invalid';
    }
}
