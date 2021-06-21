<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InArray implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($array)
    {
        $this->array = explode(",", $array);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param                      string $attribute
     * @SuppressWarnings("unused")
     * @param                      mixed  $value
     * @return                     bool
     */
    public function passes($attribute, $value)
    {
        $is_subset = array_diff($value, $this->array);
        if (!$is_subset) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid';
    }
}
