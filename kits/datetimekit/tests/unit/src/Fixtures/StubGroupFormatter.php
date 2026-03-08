<?php

declare(strict_types=1);

namespace StusDevKit\DateTimeKit\Tests\Unit\Fixtures;

use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\When;

class StubGroupFormatter implements WhenGroupFormatterInterface
{
    public function __construct(
        private readonly When $when,
    ) {
    }

    public function date(): string
    {
        return $this->when->format('Y-m-d');
    }

    public function time(): string
    {
        return $this->when->format('H:i:s');
    }
}
