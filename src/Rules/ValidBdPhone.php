<?php

namespace Sanvalabs\BdPhone\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sanvalabs\BdPhone\PhoneNumber;

class ValidBdPhone implements ValidationRule
{
    /**
     * @param  string[]  $operators  allow only these operators, e.g. ['Grameenphone', 'Robi']. Empty = all.
     */
    public function __construct(protected array $operators = []) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = app(PhoneNumber::class);

        if (! is_string($value) || ! $phone->isValid($value)) {
            $fail('bd-phone::validation.invalid')->translate();

            return;
        }

        if ($this->operators && ! in_array($phone->operator($value), $this->operators, true)) {
            $fail('bd-phone::validation.operator')->translate([
                'operators' => implode(', ', $this->operators),
            ]);
        }
    }
}