<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn () => null);

            restore_exception_handler();

            if (null === $previousHandler) {
                break;
            }

            restore_exception_handler();
        }
    }
}
