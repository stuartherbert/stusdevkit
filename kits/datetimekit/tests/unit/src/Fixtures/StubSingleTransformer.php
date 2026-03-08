<?php

declare(strict_types=1);

namespace StusDevKit\DateTimeKit\Tests\Unit\Fixtures;

use StusDevKit\DateTimeKit\Formatters\WhenSingleTransformerInterface;
use StusDevKit\DateTimeKit\When;

class StubSingleTransformer implements WhenSingleTransformerInterface
{
    /**
     * @return array{year: int, month: int, day: int}
     */
    public function transformWhen(When $when): array
    {
        return [
            'year' => $when->getYear(),
            'month' => $when->getMonthOfYear(),
            'day' => $when->getDayOfMonth(),
        ];
    }
}
