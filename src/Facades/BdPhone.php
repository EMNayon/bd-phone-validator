<?php

namespace Sanvalabs\BdPhone\Facades;

use Illuminate\Support\Facades\Facade;
use Sanvalabs\BdPhone\PhoneNumber;

/**
 * @method static string|null normalize(string $number)
 * @method static bool isValid(string $number)
 * @method static string|null operator(string $number)
 * @method static string|null international(string $number)
 *
 * @see PhoneNumber
 */
class BdPhone extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PhoneNumber::class;
    }
}