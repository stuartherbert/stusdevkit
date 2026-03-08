<?php

declare(strict_types=1);

namespace StusDevKit\DateTimeKit\Tests\Unit\Fixtures;

use StusDevKit\DateTimeKit\Formatters\WhenSingleFormatterInterface;
use StusDevKit\DateTimeKit\When;

class StubSingleFormatter implements WhenSingleFormatterInterface
{
    public function formatWhen(When $when): string
    {
        return $when->format('Y-m-d H:i:s');
    }
}
