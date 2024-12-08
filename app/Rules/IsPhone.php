<?php

namespace App\Rules;

use App\Models\Phone;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class IsPhone implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        return (new Phone($value))->isValid();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('The :attribute must be valid phone number.');
    }
}
