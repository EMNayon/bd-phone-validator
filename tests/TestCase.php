<?php

namespace Sanvalabs\BdPhone\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sanvalabs\BdPhone\BdPhoneServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BdPhoneServiceProvider::class];
    }
}