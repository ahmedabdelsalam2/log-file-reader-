<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function tearDown(): void
    {
        parent::tearDown();

        echo PHP_EOL . "************************************" . PHP_EOL;
        echo "************************************" . PHP_EOL;
        echo "************************************" . PHP_EOL . PHP_EOL;
        echo "****  memory usage : " . (memory_get_usage() / 8e+6) . PHP_EOL . PHP_EOL;
        echo "************************************" . PHP_EOL;
        echo "************************************" . PHP_EOL;
        echo "************************************" . PHP_EOL . PHP_EOL;
        ;
    }
}
