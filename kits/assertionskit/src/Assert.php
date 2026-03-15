<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
// Copyright (c) 2026-present Stuart Herbert
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
//
//   * Re-distributions of source code must retain the above copyright
//     notice, this list of conditions and the following disclaimer.
//
//   * Redistributions in binary form must reproduce the above copyright
//     notice, this list of conditions and the following disclaimer in
//     the documentation and/or other materials provided with the
//     distribution.
//
//   * Neither the names of the copyright holders nor the names of his
//     contributors may be used to endorse or promote products derived
//     from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// COPYRIGHT HOLDERS AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
// INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\AssertionsKit;

use ArrayAccess;
use Countable;
use PHPUnit\Framework\Constraint\Constraint;
use StusDevKit\AssertionsKit\Contracts\Assert as AssertContract;
use StusDevKit\AssertionsKit\Exceptions\AssertionFailedException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;

/**
 * Concrete implementation of the Assert contract.
 *
 * Each assertion method contains its own checking logic
 * and throws AssertionFailedException on failure. This
 * class has no dependency on PHPUnit's assertion
 * implementation.
 *
 * Usage:
 *
 *     use StusDevKit\AssertionsKit\Assert;
 *
 *     Assert::assertTrue($value);
 *     Assert::assertEquals($expected, $actual);
 *     Assert::assertStringContainsString('needle', $haystack);
 */
class Assert implements AssertContract
{
    // ================================================================
    //
    // Array Assertions
    //
    // ----------------------------------------------------------------

    public static function assertArrayHasKey(
        mixed $key,
        array|ArrayAccess $array,
        string $message = '',
    ): void {
        if (!is_int($key) && !is_string($key)) {
            throw new InvalidArgumentException(
                detail: '$key must be int or string, got '
                    . get_debug_type($key),
            );
        }

        if (is_array($array)) {
            if (array_key_exists($key, $array)) {
                return;
            }
        } elseif ($array->offsetExists($key)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that an array has a key',
            extra: [
                'key' => self::exportValue($key),
            ],
            detail: $message,
        );
    }

    public static function assertArrayNotHasKey(
        mixed $key,
        array|ArrayAccess $array,
        string $message = '',
    ): void {
        if (!is_int($key) && !is_string($key)) {
            throw new InvalidArgumentException(
                detail: '$key must be int or string, got '
                    . get_debug_type($key),
            );
        }

        if (is_array($array)) {
            if (!array_key_exists($key, $array)) {
                return;
            }
        } elseif (!$array->offsetExists($key)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that an array does not have a key',
            extra: [
                'key' => self::exportValue($key),
            ],
            detail: $message,
        );
    }

    public static function assertIsList(
        mixed $array,
        string $message = '',
    ): void {
        if (is_array($array) && array_is_list($array)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is a list',
            extra: [
                'actual' => get_debug_type($array),
            ],
            detail: $message,
        );
    }

    public static function assertArraysAreIdentical(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        if ($expected === $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are identical',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysAreIdenticalIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        $e = $expected;
        $a = $actual;
        ksort($e);
        ksort($a);

        if ($e === $a) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are identical (ignoring order)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysHaveIdenticalValues(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        if (array_values($expected) === array_values($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays have identical values',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysHaveIdenticalValuesIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        $e = array_values($expected);
        $a = array_values($actual);
        sort($e);
        sort($a);

        if ($e === $a) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays have identical values (ignoring order)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysAreEqual(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        if ($expected == $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are equal',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysAreEqualIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        $e = $expected;
        $a = $actual;
        ksort($e);
        ksort($a);

        if ($e == $a) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are equal (ignoring order)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysHaveEqualValues(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        if (array_values($expected) == array_values($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays have equal values',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArraysHaveEqualValuesIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void {
        $e = array_values($expected);
        $a = array_values($actual);
        sort($e);
        sort($a);

        if ($e == $a) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays have equal values (ignoring order)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertArrayIsEqualToArrayOnlyConsideringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeConsidered,
        string $message = '',
    ): void {
        $keys = array_flip($keysToBeConsidered);
        $filteredExpected = array_intersect_key($expected, $keys);
        $filteredActual = array_intersect_key($actual, $keys);

        if ($filteredExpected == $filteredActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are equal (considering keys)',
            extra: [
                'expected' => self::exportValue($filteredExpected),
                'actual' => self::exportValue($filteredActual),
            ],
            detail: $message,
        );
    }

    public static function assertArrayIsEqualToArrayIgnoringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeIgnored,
        string $message = '',
    ): void {
        $keys = array_flip($keysToBeIgnored);
        $filteredExpected = array_diff_key($expected, $keys);
        $filteredActual = array_diff_key($actual, $keys);

        if ($filteredExpected == $filteredActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are equal (ignoring keys)',
            extra: [
                'expected' => self::exportValue($filteredExpected),
                'actual' => self::exportValue($filteredActual),
            ],
            detail: $message,
        );
    }

    public static function assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeConsidered,
        string $message = '',
    ): void {
        $keys = array_flip($keysToBeConsidered);
        $filteredExpected = array_intersect_key($expected, $keys);
        $filteredActual = array_intersect_key($actual, $keys);

        if ($filteredExpected === $filteredActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are identical (considering keys)',
            extra: [
                'expected' => self::exportValue($filteredExpected),
                'actual' => self::exportValue($filteredActual),
            ],
            detail: $message,
        );
    }

    public static function assertArrayIsIdenticalToArrayIgnoringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeIgnored,
        string $message = '',
    ): void {
        $keys = array_flip($keysToBeIgnored);
        $filteredExpected = array_diff_key($expected, $keys);
        $filteredActual = array_diff_key($actual, $keys);

        if ($filteredExpected === $filteredActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two arrays are identical (ignoring keys)',
            extra: [
                'expected' => self::exportValue($filteredExpected),
                'actual' => self::exportValue($filteredActual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Contains Assertions
    //
    // ----------------------------------------------------------------

    public static function assertContains(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void {
        foreach ($haystack as $item) {
            if ($item === $needle) {
                return;
            }
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains a needle',
            extra: [
                'needle' => self::exportValue($needle),
            ],
            detail: $message,
        );
    }

    public static function assertContainsEquals(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void {
        foreach ($haystack as $item) {
            if ($item == $needle) {
                return;
            }
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains a needle (equals)',
            extra: [
                'needle' => self::exportValue($needle),
            ],
            detail: $message,
        );
    }

    public static function assertNotContains(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void {
        foreach ($haystack as $item) {
            if ($item === $needle) {
                throw new AssertionFailedException(
                    title: 'Failed asserting that a haystack does not contain a needle',
                    extra: [
                        'needle' => self::exportValue($needle),
                    ],
                    detail: $message,
                );
            }
        }
    }

    public static function assertNotContainsEquals(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void {
        foreach ($haystack as $item) {
            if ($item == $needle) {
                throw new AssertionFailedException(
                    title: 'Failed asserting that a haystack does not contain a needle (equals)',
                    extra: [
                        'needle' => self::exportValue($needle),
                    ],
                    detail: $message,
                );
            }
        }
    }

    // ================================================================
    //
    // Contains Only Assertions
    //
    // ----------------------------------------------------------------

    public static function assertContainsOnlyArray(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_array(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only arrays',
            detail: $message,
        );
    }

    public static function assertContainsOnlyBool(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_bool(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only booleans',
            detail: $message,
        );
    }

    public static function assertContainsOnlyCallable(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_callable(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only callables',
            detail: $message,
        );
    }

    public static function assertContainsOnlyFloat(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_float(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only floats',
            detail: $message,
        );
    }

    public static function assertContainsOnlyInt(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_int(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only integers',
            detail: $message,
        );
    }

    public static function assertContainsOnlyIterable(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_iterable(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only iterables',
            detail: $message,
        );
    }

    public static function assertContainsOnlyNull(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_null(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only null values',
            detail: $message,
        );
    }

    public static function assertContainsOnlyNumeric(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_numeric(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only numeric values',
            detail: $message,
        );
    }

    public static function assertContainsOnlyObject(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_object(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only objects',
            detail: $message,
        );
    }

    public static function assertContainsOnlyResource(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_resource(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only resources',
            detail: $message,
        );
    }

    public static function assertContainsOnlyClosedResource(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, static fn (mixed $v): bool => get_debug_type($v) === 'resource (closed)')) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only closed resources',
            detail: $message,
        );
    }

    public static function assertContainsOnlyScalar(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_scalar(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only scalars',
            detail: $message,
        );
    }

    public static function assertContainsOnlyString(
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, is_string(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only strings',
            detail: $message,
        );
    }

    public static function assertContainsOnlyInstancesOf(
        string $className,
        iterable $haystack,
        string $message = '',
    ): void {
        if (self::allMatch($haystack, static fn (mixed $v): bool => $v instanceof $className)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack contains only instances of ' . $className,
            detail: $message,
        );
    }

    // ================================================================
    //
    // Contains Not Only Assertions
    //
    // ----------------------------------------------------------------

    public static function assertContainsNotOnlyArray(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_array(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only arrays',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyBool(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_bool(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only booleans',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyCallable(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_callable(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only callables',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyFloat(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_float(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only floats',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyInt(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_int(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only integers',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyIterable(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_iterable(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only iterables',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyNull(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_null(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only null values',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyNumeric(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_numeric(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only numeric values',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyObject(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_object(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only objects',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyResource(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_resource(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only resources',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyClosedResource(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, static fn (mixed $v): bool => get_debug_type($v) === 'resource (closed)')) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only closed resources',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyScalar(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_scalar(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only scalars',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyString(
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, is_string(...))) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only strings',
            detail: $message,
        );
    }

    public static function assertContainsNotOnlyInstancesOf(
        string $className,
        iterable $haystack,
        string $message = '',
    ): void {
        if (!self::allMatch($haystack, static fn (mixed $v): bool => $v instanceof $className)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a haystack does not contain only instances of ' . $className,
            detail: $message,
        );
    }

    // ================================================================
    //
    // Count Assertions
    //
    // ----------------------------------------------------------------

    public static function assertCount(
        int $expectedCount,
        Countable|iterable $haystack,
        string $message = '',
    ): void {
        $actualCount = self::countIterable($haystack);

        if ($actualCount === $expectedCount) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a collection has a specific count',
            extra: [
                'expected' => $expectedCount,
                'actual' => $actualCount,
            ],
            detail: $message,
        );
    }

    public static function assertNotCount(
        int $expectedCount,
        Countable|iterable $haystack,
        string $message = '',
    ): void {
        $actualCount = self::countIterable($haystack);

        if ($actualCount !== $expectedCount) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a collection does not have a specific count',
            extra: [
                'expected' => 'not ' . $expectedCount,
                'actual' => $actualCount,
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Equality Assertions
    //
    // ----------------------------------------------------------------

    public static function assertEquals(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if ($expected == $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are equal',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertEqualsCanonicalizing(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        $canonicalExpected = self::canonicalize($expected);
        $canonicalActual = self::canonicalize($actual);

        if ($canonicalExpected == $canonicalActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are equal (canonicalizing)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertEqualsIgnoringCase(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if (is_string($expected) && is_string($actual)) {
            if (mb_strtolower($expected) === mb_strtolower($actual)) {
                return;
            }
        } elseif ($expected == $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are equal (ignoring case)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertEqualsWithDelta(
        mixed $expected,
        mixed $actual,
        float $delta,
        string $message = '',
    ): void {
        /** @var float $floatExpected */
        $floatExpected = $expected;
        /** @var float $floatActual */
        $floatActual = $actual;

        if (abs($floatExpected - $floatActual) <= $delta) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are equal within delta',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
                'delta' => self::exportValue($delta),
            ],
            detail: $message,
        );
    }

    public static function assertNotEquals(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if ($expected != $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are not equal',
            extra: [
                'expected' => 'not ' . self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotEqualsCanonicalizing(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        $canonicalExpected = self::canonicalize($expected);
        $canonicalActual = self::canonicalize($actual);

        if ($canonicalExpected != $canonicalActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are not equal (canonicalizing)',
            extra: [
                'expected' => 'not ' . self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotEqualsIgnoringCase(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if (is_string($expected) && is_string($actual)) {
            if (mb_strtolower($expected) !== mb_strtolower($actual)) {
                return;
            }
        } elseif ($expected != $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are not equal (ignoring case)',
            extra: [
                'expected' => 'not ' . self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotEqualsWithDelta(
        mixed $expected,
        mixed $actual,
        float $delta,
        string $message = '',
    ): void {
        /** @var float $floatExpected */
        $floatExpected = $expected;
        /** @var float $floatActual */
        $floatActual = $actual;

        if (abs($floatExpected - $floatActual) > $delta) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are not equal within delta',
            extra: [
                'expected' => 'not ' . self::exportValue($expected),
                'actual' => self::exportValue($actual),
                'delta' => self::exportValue($delta),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Object Equality Assertions
    //
    // ----------------------------------------------------------------

    public static function assertObjectEquals(
        object $expected,
        object $actual,
        string $method = 'equals',
        string $message = '',
    ): void {
        if (!method_exists($actual, $method)) {
            throw new InvalidArgumentException(
                detail: '$method ' . $method
                    . '() does not exist on '
                    . get_debug_type($actual),
            );
        }

        if ($actual->$method($expected) === true) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two objects are equal',
            extra: [
                'expected' => get_debug_type($expected),
                'actual' => get_debug_type($actual),
                'method' => $method,
            ],
            detail: $message,
        );
    }

    public static function assertObjectNotEquals(
        object $expected,
        object $actual,
        string $method = 'equals',
        string $message = '',
    ): void {
        if (!method_exists($actual, $method)) {
            throw new InvalidArgumentException(
                detail: '$method ' . $method
                    . '() does not exist on '
                    . get_debug_type($actual),
            );
        }

        if ($actual->$method($expected) === false) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two objects are not equal',
            extra: [
                'expected' => 'not ' . get_debug_type($expected),
                'actual' => get_debug_type($actual),
                'method' => $method,
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Empty Assertions
    //
    // ----------------------------------------------------------------

    public static function assertEmpty(
        mixed $actual,
        string $message = '',
    ): void {
        if (empty($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is empty',
            extra: [
                'expected' => 'empty',
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotEmpty(
        mixed $actual,
        string $message = '',
    ): void {
        if (!empty($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not empty',
            extra: [
                'expected' => 'not empty',
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Comparison Assertions
    //
    // ----------------------------------------------------------------

    public static function assertGreaterThan(
        mixed $minimum,
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual > $minimum) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is greater than another value',
            extra: [
                'expected' => '> ' . self::exportValue($minimum),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertGreaterThanOrEqual(
        mixed $minimum,
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual >= $minimum) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is greater than or equal to another value',
            extra: [
                'expected' => '>= ' . self::exportValue($minimum),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertLessThan(
        mixed $maximum,
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual < $maximum) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is less than another value',
            extra: [
                'expected' => '< ' . self::exportValue($maximum),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertLessThanOrEqual(
        mixed $maximum,
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual <= $maximum) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is less than or equal to another value',
            extra: [
                'expected' => '<= ' . self::exportValue($maximum),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    // ================================================================
    //
    // String/File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    // ================================================================
    //
    // Filesystem Assertions
    //
    // ----------------------------------------------------------------

    // ================================================================
    //
    // Boolean Assertions
    //
    // ----------------------------------------------------------------

    public static function assertTrue(
        mixed $condition,
        string $message = '',
    ): void {
        if ($condition === true) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
            extra: [
                'expected' => 'true',
                'actual' => self::exportValue($condition),
            ],
            detail: $message,
        );
    }

    public static function assertNotTrue(
        mixed $condition,
        string $message = '',
    ): void {
        if ($condition !== true) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a condition is not true',
            extra: [
                'expected' => 'not true',
                'actual' => self::exportValue($condition),
            ],
            detail: $message,
        );
    }

    public static function assertFalse(
        mixed $condition,
        string $message = '',
    ): void {
        if ($condition === false) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a condition is false',
            extra: [
                'expected' => 'false',
                'actual' => self::exportValue($condition),
            ],
            detail: $message,
        );
    }

    public static function assertNotFalse(
        mixed $condition,
        string $message = '',
    ): void {
        if ($condition !== false) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a condition is not false',
            extra: [
                'expected' => 'not false',
                'actual' => self::exportValue($condition),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Null Assertions
    //
    // ----------------------------------------------------------------

    public static function assertNull(
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual === null) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is null',
            extra: [
                'expected' => 'null',
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotNull(
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual !== null) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not null',
            extra: [
                'expected' => 'not null',
                'actual' => 'null',
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Numeric Assertions
    //
    // ----------------------------------------------------------------

    public static function assertFinite(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_float($actual) && is_finite($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is finite',
            extra: [
                'expected' => 'finite number',
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertInfinite(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_float($actual) && is_infinite($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is infinite',
            extra: [
                'expected' => 'infinite number',
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNan(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_float($actual) && is_nan($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is NaN',
            extra: [
                'expected' => 'NaN',
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Object Property Assertions
    //
    // ----------------------------------------------------------------

    public static function assertObjectHasProperty(
        string $propertyName,
        object $object,
        string $message = '',
    ): void {
        if (property_exists($object, $propertyName)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that an object has a property',
            extra: [
                'property' => $propertyName,
                'object' => get_debug_type($object),
            ],
            detail: $message,
        );
    }

    public static function assertObjectNotHasProperty(
        string $propertyName,
        object $object,
        string $message = '',
    ): void {
        if (!property_exists($object, $propertyName)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that an object does not have a property',
            extra: [
                'property' => $propertyName,
                'object' => get_debug_type($object),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Identity Assertions
    //
    // ----------------------------------------------------------------

    public static function assertSame(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if ($expected === $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are the same',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotSame(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if ($expected !== $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two values are not the same',
            extra: [
                'expected' => 'not ' . self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Instance Assertions
    //
    // ----------------------------------------------------------------

    public static function assertInstanceOf(
        string $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if ($actual instanceof $expected) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is an instance of a given class',
            extra: [
                'expected' => $expected,
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertNotInstanceOf(
        string $expected,
        mixed $actual,
        string $message = '',
    ): void {
        if (!($actual instanceof $expected)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not an instance of a given class',
            extra: [
                'expected' => 'not ' . $expected,
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Type Assertions
    //
    // ----------------------------------------------------------------

    public static function assertIsArray(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_array($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type array',
            extra: [
                'expected' => 'array',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsBool(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_bool($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type bool',
            extra: [
                'expected' => 'bool',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsFloat(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_float($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type float',
            extra: [
                'expected' => 'float',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsInt(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_int($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type int',
            extra: [
                'expected' => 'int',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNumeric(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_numeric($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type numeric',
            extra: [
                'expected' => 'numeric',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsObject(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_object($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type object',
            extra: [
                'expected' => 'object',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsResource(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_resource($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type resource',
            extra: [
                'expected' => 'resource',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsClosedResource(
        mixed $actual,
        string $message = '',
    ): void {
        if (get_debug_type($actual) === 'resource (closed)') {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is a closed resource',
            extra: [
                'expected' => 'resource (closed)',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsString(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_string($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type string',
            extra: [
                'expected' => 'string',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsScalar(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_scalar($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type scalar',
            extra: [
                'expected' => 'scalar',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsCallable(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_callable($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type callable',
            extra: [
                'expected' => 'callable',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsIterable(
        mixed $actual,
        string $message = '',
    ): void {
        if (is_iterable($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is of type iterable',
            extra: [
                'expected' => 'iterable',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Negated Type Assertions
    //
    // ----------------------------------------------------------------

    public static function assertIsNotArray(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_array($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type array',
            extra: [
                'expected' => 'not array',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotBool(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_bool($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type bool',
            extra: [
                'expected' => 'not bool',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotFloat(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_float($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type float',
            extra: [
                'expected' => 'not float',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotInt(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_int($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type int',
            extra: [
                'expected' => 'not int',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotNumeric(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_numeric($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type numeric',
            extra: [
                'expected' => 'not numeric',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotObject(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_object($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type object',
            extra: [
                'expected' => 'not object',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotResource(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_resource($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type resource',
            extra: [
                'expected' => 'not resource',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotClosedResource(
        mixed $actual,
        string $message = '',
    ): void {
        if (get_debug_type($actual) !== 'resource (closed)') {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not a closed resource',
            extra: [
                'expected' => 'not resource (closed)',
                'actual' => 'resource (closed)',
            ],
            detail: $message,
        );
    }

    public static function assertIsNotString(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_string($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type string',
            extra: [
                'expected' => 'not string',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotScalar(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_scalar($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type scalar',
            extra: [
                'expected' => 'not scalar',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotCallable(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_callable($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type callable',
            extra: [
                'expected' => 'not callable',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotIterable(
        mixed $actual,
        string $message = '',
    ): void {
        if (!is_iterable($actual)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value is not of type iterable',
            extra: [
                'expected' => 'not iterable',
                'actual' => get_debug_type($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Regular Expression Assertions
    //
    // ----------------------------------------------------------------

    // ================================================================
    //
    // Size Assertions
    //
    // ----------------------------------------------------------------

    public static function assertSameSize(
        Countable|iterable $expected,
        Countable|iterable $actual,
        string $message = '',
    ): void {
        $expectedCount = self::countIterable($expected);
        $actualCount = self::countIterable($actual);

        if ($expectedCount === $actualCount) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two collections have the same size',
            extra: [
                'expected' => $expectedCount,
                'actual' => $actualCount,
            ],
            detail: $message,
        );
    }

    public static function assertNotSameSize(
        Countable|iterable $expected,
        Countable|iterable $actual,
        string $message = '',
    ): void {
        $expectedCount = self::countIterable($expected);
        $actualCount = self::countIterable($actual);

        if ($expectedCount !== $actualCount) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two collections do not have the same size',
            extra: [
                'expected' => 'not ' . $expectedCount,
                'actual' => $actualCount,
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    public static function assertFileEquals(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expected, 'expected');
        $actualContents = self::readFileContents($actual, 'actual');

        if ($expectedContents === $actualContents) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two files are equal',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertFileEqualsCanonicalizing(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expected, 'expected');
        $actualContents = self::readFileContents($actual, 'actual');

        $expectedLines = explode("\n", $expectedContents);
        $actualLines = explode("\n", $actualContents);
        sort($expectedLines);
        sort($actualLines);

        if ($expectedLines === $actualLines) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two files are equal (canonicalizing)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertFileEqualsIgnoringCase(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expected, 'expected');
        $actualContents = self::readFileContents($actual, 'actual');

        if (mb_strtolower($expectedContents) === mb_strtolower($actualContents)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two files are equal (ignoring case)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertFileNotEquals(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expected, 'expected');
        $actualContents = self::readFileContents($actual, 'actual');

        if ($expectedContents !== $actualContents) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two files are not equal',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertFileNotEqualsCanonicalizing(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expected, 'expected');
        $actualContents = self::readFileContents($actual, 'actual');

        $expectedLines = explode("\n", $expectedContents);
        $actualLines = explode("\n", $actualContents);
        sort($expectedLines);
        sort($actualLines);

        if ($expectedLines !== $actualLines) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two files are not equal (canonicalizing)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertFileNotEqualsIgnoringCase(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expected, 'expected');
        $actualContents = self::readFileContents($actual, 'actual');

        if (mb_strtolower($expectedContents) !== mb_strtolower($actualContents)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two files are not equal (ignoring case)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // String/File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    public static function assertStringEqualsFile(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');

        if ($expectedContents === $actualString) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string equals file contents',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualString' => self::exportValue($actualString),
            ],
            detail: $message,
        );
    }

    public static function assertStringEqualsFileCanonicalizing(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');

        $expectedLines = explode("\n", $expectedContents);
        $actualLines = explode("\n", $actualString);
        sort($expectedLines);
        sort($actualLines);

        if ($expectedLines === $actualLines) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string equals file contents (canonicalizing)',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualString' => self::exportValue($actualString),
            ],
            detail: $message,
        );
    }

    public static function assertStringEqualsFileIgnoringCase(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');

        if (mb_strtolower($expectedContents) === mb_strtolower($actualString)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string equals file contents (ignoring case)',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualString' => self::exportValue($actualString),
            ],
            detail: $message,
        );
    }

    public static function assertStringNotEqualsFile(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');

        if ($expectedContents !== $actualString) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not equal file contents',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualString' => self::exportValue($actualString),
            ],
            detail: $message,
        );
    }

    public static function assertStringNotEqualsFileCanonicalizing(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');

        $expectedLines = explode("\n", $expectedContents);
        $actualLines = explode("\n", $actualString);
        sort($expectedLines);
        sort($actualLines);

        if ($expectedLines !== $actualLines) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not equal file contents (canonicalizing)',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualString' => self::exportValue($actualString),
            ],
            detail: $message,
        );
    }

    public static function assertStringNotEqualsFileIgnoringCase(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');

        if (mb_strtolower($expectedContents) !== mb_strtolower($actualString)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not equal file contents (ignoring case)',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualString' => self::exportValue($actualString),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Constraint Assertion
    //
    // ----------------------------------------------------------------

    public static function assertThat(
        mixed $value,
        Constraint $constraint,
        string $message = '',
    ): void {
        if ($constraint->evaluate($value, '', true)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a value matches a constraint',
            extra: [
                'constraint' => $constraint->toString(),
                'actual' => self::exportValue($value),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // String Assertions
    //
    // ----------------------------------------------------------------

    public static function assertStringContainsString(
        string $needle,
        string $haystack,
        string $message = '',
    ): void {
        if (str_contains($haystack, $needle)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string contains another string',
            extra: [
                'needle' => self::exportValue($needle),
                'haystack' => self::exportValue($haystack),
            ],
            detail: $message,
        );
    }

    public static function assertStringContainsStringIgnoringCase(
        string $needle,
        string $haystack,
        string $message = '',
    ): void {
        if (mb_stripos($haystack, $needle) !== false) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string contains another string (ignoring case)',
            extra: [
                'needle' => self::exportValue($needle),
                'haystack' => self::exportValue($haystack),
            ],
            detail: $message,
        );
    }

    public static function assertStringNotContainsString(
        string $needle,
        string $haystack,
        string $message = '',
    ): void {
        if (!str_contains($haystack, $needle)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not contain another string',
            extra: [
                'needle' => self::exportValue($needle),
                'haystack' => self::exportValue($haystack),
            ],
            detail: $message,
        );
    }

    public static function assertStringNotContainsStringIgnoringCase(
        string $needle,
        string $haystack,
        string $message = '',
    ): void {
        if (mb_stripos($haystack, $needle) === false) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not contain another string (ignoring case)',
            extra: [
                'needle' => self::exportValue($needle),
                'haystack' => self::exportValue($haystack),
            ],
            detail: $message,
        );
    }

    public static function assertStringStartsWith(
        string $prefix,
        string $string,
        string $message = '',
    ): void {
        if (str_starts_with($string, $prefix)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string starts with a given prefix',
            extra: [
                'prefix' => self::exportValue($prefix),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    public static function assertStringStartsNotWith(
        string $prefix,
        string $string,
        string $message = '',
    ): void {
        if (!str_starts_with($string, $prefix)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not start with a given prefix',
            extra: [
                'prefix' => self::exportValue($prefix),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    public static function assertStringEndsWith(
        string $suffix,
        string $string,
        string $message = '',
    ): void {
        if (str_ends_with($string, $suffix)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string ends with a given suffix',
            extra: [
                'suffix' => self::exportValue($suffix),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    public static function assertStringEndsNotWith(
        string $suffix,
        string $string,
        string $message = '',
    ): void {
        if (!str_ends_with($string, $suffix)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not end with a given suffix',
            extra: [
                'suffix' => self::exportValue($suffix),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    public static function assertStringContainsStringIgnoringLineEndings(
        string $needle,
        string $haystack,
        string $message = '',
    ): void {
        $normalizedNeedle = self::normalizeLineEndings($needle);
        $normalizedHaystack = self::normalizeLineEndings($haystack);

        if (str_contains($normalizedHaystack, $normalizedNeedle)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string contains another string (ignoring line endings)',
            extra: [
                'needle' => self::exportValue($needle),
                'haystack' => self::exportValue($haystack),
            ],
            detail: $message,
        );
    }

    public static function assertStringEqualsStringIgnoringLineEndings(
        string $expected,
        string $actual,
        string $message = '',
    ): void {
        $normalizedExpected = self::normalizeLineEndings($expected);
        $normalizedActual = self::normalizeLineEndings($actual);

        if ($normalizedExpected === $normalizedActual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two strings are equal (ignoring line endings)',
            extra: [
                'expected' => self::exportValue($expected),
                'actual' => self::exportValue($actual),
            ],
            detail: $message,
        );
    }

    public static function assertStringMatchesFormat(
        string $format,
        string $string,
        string $message = '',
    ): void {
        $regex = self::formatToRegex($format);

        if (preg_match($regex, $string) === 1) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string matches a format',
            extra: [
                'format' => self::exportValue($format),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    public static function assertStringMatchesFormatFile(
        string $formatFile,
        string $string,
        string $message = '',
    ): void {
        if (!is_file($formatFile) || !is_readable($formatFile)) {
            throw new InvalidArgumentException(
                detail: '$formatFile does not exist or is not'
                    . ' readable: ' . $formatFile,
            );
        }

        $format = file_get_contents($formatFile);

        if ($format === false) {
            throw new InvalidArgumentException(
                detail: '$formatFile could not be read: '
                    . $formatFile,
            );
        }

        $regex = self::formatToRegex($format);

        if (preg_match($regex, $string) === 1) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string matches a format file',
            extra: [
                'formatFile' => self::exportValue($formatFile),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Regex Assertions
    //
    // ----------------------------------------------------------------

    public static function assertMatchesRegularExpression(
        string $pattern,
        string $string,
        string $message = '',
    ): void {
        if (preg_match($pattern, $string) === 1) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string matches a regular expression',
            extra: [
                'pattern' => self::exportValue($pattern),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    public static function assertDoesNotMatchRegularExpression(
        string $pattern,
        string $string,
        string $message = '',
    ): void {
        if (preg_match($pattern, $string) === 0) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string does not match a regular expression',
            extra: [
                'pattern' => self::exportValue($pattern),
                'string' => self::exportValue($string),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Filesystem Assertions
    //
    // ----------------------------------------------------------------

    public static function assertFileExists(
        string $filename,
        string $message = '',
    ): void {
        if (file_exists($filename) && is_file($filename)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file exists',
            extra: [
                'filename' => self::exportValue($filename),
            ],
            detail: $message,
        );
    }

    public static function assertFileDoesNotExist(
        string $filename,
        string $message = '',
    ): void {
        if (!file_exists($filename)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file does not exist',
            extra: [
                'filename' => self::exportValue($filename),
            ],
            detail: $message,
        );
    }

    public static function assertFileIsReadable(
        string $file,
        string $message = '',
    ): void {
        if (is_file($file) && is_readable($file)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file is readable',
            extra: [
                'file' => self::exportValue($file),
            ],
            detail: $message,
        );
    }

    public static function assertFileIsNotReadable(
        string $file,
        string $message = '',
    ): void {
        if (is_file($file) && !is_readable($file)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file is not readable',
            extra: [
                'file' => self::exportValue($file),
            ],
            detail: $message,
        );
    }

    public static function assertFileIsWritable(
        string $file,
        string $message = '',
    ): void {
        if (is_file($file) && is_writable($file)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file is writable',
            extra: [
                'file' => self::exportValue($file),
            ],
            detail: $message,
        );
    }

    public static function assertFileIsNotWritable(
        string $file,
        string $message = '',
    ): void {
        if (is_file($file) && !is_writable($file)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file is not writable',
            extra: [
                'file' => self::exportValue($file),
            ],
            detail: $message,
        );
    }

    public static function assertDirectoryExists(
        string $directory,
        string $message = '',
    ): void {
        if (is_dir($directory)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a directory exists',
            extra: [
                'directory' => self::exportValue($directory),
            ],
            detail: $message,
        );
    }

    public static function assertDirectoryDoesNotExist(
        string $directory,
        string $message = '',
    ): void {
        if (!file_exists($directory) || !is_dir($directory)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a directory does not exist',
            extra: [
                'directory' => self::exportValue($directory),
            ],
            detail: $message,
        );
    }

    public static function assertDirectoryIsReadable(
        string $directory,
        string $message = '',
    ): void {
        if (is_dir($directory) && is_readable($directory)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a directory is readable',
            extra: [
                'directory' => self::exportValue($directory),
            ],
            detail: $message,
        );
    }

    public static function assertDirectoryIsNotReadable(
        string $directory,
        string $message = '',
    ): void {
        if (is_dir($directory) && !is_readable($directory)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a directory is not readable',
            extra: [
                'directory' => self::exportValue($directory),
            ],
            detail: $message,
        );
    }

    public static function assertDirectoryIsWritable(
        string $directory,
        string $message = '',
    ): void {
        if (is_dir($directory) && is_writable($directory)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a directory is writable',
            extra: [
                'directory' => self::exportValue($directory),
            ],
            detail: $message,
        );
    }

    public static function assertDirectoryIsNotWritable(
        string $directory,
        string $message = '',
    ): void {
        if (is_dir($directory) && !is_writable($directory)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a directory is not writable',
            extra: [
                'directory' => self::exportValue($directory),
            ],
            detail: $message,
        );
    }

    public static function assertIsReadable(
        string $filename,
        string $message = '',
    ): void {
        if (is_readable($filename)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a path is readable',
            extra: [
                'filename' => self::exportValue($filename),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotReadable(
        string $filename,
        string $message = '',
    ): void {
        if (file_exists($filename) && !is_readable($filename)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a path is not readable',
            extra: [
                'filename' => self::exportValue($filename),
            ],
            detail: $message,
        );
    }

    public static function assertIsWritable(
        string $filename,
        string $message = '',
    ): void {
        if (is_writable($filename)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a path is writable',
            extra: [
                'filename' => self::exportValue($filename),
            ],
            detail: $message,
        );
    }

    public static function assertIsNotWritable(
        string $filename,
        string $message = '',
    ): void {
        if (file_exists($filename) && !is_writable($filename)) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a path is not writable',
            extra: [
                'filename' => self::exportValue($filename),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // File Format Assertions
    //
    // ----------------------------------------------------------------

    public static function assertFileMatchesFormat(
        string $format,
        string $actualFile,
        string $message = '',
    ): void {
        if (!is_file($actualFile) || !is_readable($actualFile)) {
            throw new InvalidArgumentException(
                detail: '$actualFile does not exist or is not'
                    . ' readable: ' . $actualFile,
            );
        }

        $actual = file_get_contents($actualFile);

        if ($actual === false) {
            throw new InvalidArgumentException(
                detail: '$actualFile could not be read: '
                    . $actualFile,
            );
        }

        $regex = self::formatToRegex($format);

        if (preg_match($regex, $actual) === 1) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file matches a format',
            extra: [
                'format' => self::exportValue($format),
                'actualFile' => self::exportValue($actualFile),
            ],
            detail: $message,
        );
    }

    public static function assertFileMatchesFormatFile(
        string $formatFile,
        string $actualFile,
        string $message = '',
    ): void {
        if (!is_file($formatFile) || !is_readable($formatFile)) {
            throw new InvalidArgumentException(
                detail: '$formatFile does not exist or is not'
                    . ' readable: ' . $formatFile,
            );
        }

        if (!is_file($actualFile) || !is_readable($actualFile)) {
            throw new InvalidArgumentException(
                detail: '$actualFile does not exist or is not'
                    . ' readable: ' . $actualFile,
            );
        }

        $format = file_get_contents($formatFile);

        if ($format === false) {
            throw new InvalidArgumentException(
                detail: '$formatFile could not be read: '
                    . $formatFile,
            );
        }

        $actual = file_get_contents($actualFile);

        if ($actual === false) {
            throw new InvalidArgumentException(
                detail: '$actualFile could not be read: '
                    . $actualFile,
            );
        }

        $regex = self::formatToRegex($format);

        if (preg_match($regex, $actual) === 1) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a file matches a format file',
            extra: [
                'formatFile' => self::exportValue($formatFile),
                'actualFile' => self::exportValue($actualFile),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // JSON Assertions
    //
    // ----------------------------------------------------------------

    public static function assertJson(
        string $actual,
        string $message = '',
    ): void {
        json_decode($actual);

        if (json_last_error() === JSON_ERROR_NONE) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a string is valid JSON',
            extra: [
                'actual' => self::exportValue($actual),
                'error' => json_last_error_msg(),
            ],
            detail: $message,
        );
    }

    public static function assertJsonStringEqualsJsonString(
        string $expectedJson,
        string $actualJson,
        string $message = '',
    ): void {
        $expected = json_decode($expectedJson);
        $actual = json_decode($actualJson);

        if ($expected == $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two JSON strings are equal',
            extra: [
                'expectedJson' => self::exportValue($expectedJson),
                'actualJson' => self::exportValue($actualJson),
            ],
            detail: $message,
        );
    }

    public static function assertJsonStringNotEqualsJsonString(
        string $expectedJson,
        string $actualJson,
        string $message = '',
    ): void {
        $expected = json_decode($expectedJson);
        $actual = json_decode($actualJson);

        if ($expected != $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two JSON strings are not equal',
            extra: [
                'expectedJson' => self::exportValue($expectedJson),
                'actualJson' => self::exportValue($actualJson),
            ],
            detail: $message,
        );
    }

    public static function assertJsonStringEqualsJsonFile(
        string $expectedFile,
        string $actualJson,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $expected = json_decode($expectedContents);
        $actual = json_decode($actualJson);

        if ($expected == $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a JSON string equals a JSON file',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualJson' => self::exportValue($actualJson),
            ],
            detail: $message,
        );
    }

    public static function assertJsonStringNotEqualsJsonFile(
        string $expectedFile,
        string $actualJson,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $expected = json_decode($expectedContents);
        $actual = json_decode($actualJson);

        if ($expected != $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that a JSON string does not equal a JSON file',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualJson' => self::exportValue($actualJson),
            ],
            detail: $message,
        );
    }

    public static function assertJsonFileEqualsJsonFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $actualContents = self::readFileContents($actualFile, 'actualFile');
        $expected = json_decode($expectedContents);
        $actual = json_decode($actualContents);

        if ($expected == $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two JSON files are equal',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualFile' => self::exportValue($actualFile),
            ],
            detail: $message,
        );
    }

    public static function assertJsonFileNotEqualsJsonFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $actualContents = self::readFileContents($actualFile, 'actualFile');
        $expected = json_decode($expectedContents);
        $actual = json_decode($actualContents);

        if ($expected != $actual) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two JSON files are not equal',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualFile' => self::exportValue($actualFile),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // XML Assertions
    //
    // ----------------------------------------------------------------

    public static function assertXmlStringEqualsXmlString(
        string $expectedXml,
        string $actualXml,
        string $message = '',
    ): void {
        $expectedDoc = self::loadXmlString($expectedXml);
        $actualDoc = self::loadXmlString($actualXml);

        if ($expectedDoc->C14N() === $actualDoc->C14N()) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two XML strings are equal',
            extra: [
                'expectedXml' => self::exportValue($expectedXml),
                'actualXml' => self::exportValue($actualXml),
            ],
            detail: $message,
        );
    }

    public static function assertXmlStringNotEqualsXmlString(
        string $expectedXml,
        string $actualXml,
        string $message = '',
    ): void {
        $expectedDoc = self::loadXmlString($expectedXml);
        $actualDoc = self::loadXmlString($actualXml);

        if ($expectedDoc->C14N() !== $actualDoc->C14N()) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two XML strings are not equal',
            extra: [
                'expectedXml' => self::exportValue($expectedXml),
                'actualXml' => self::exportValue($actualXml),
            ],
            detail: $message,
        );
    }

    public static function assertXmlStringEqualsXmlFile(
        string $expectedFile,
        string $actualXml,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $expectedDoc = self::loadXmlString($expectedContents);
        $actualDoc = self::loadXmlString($actualXml);

        if ($expectedDoc->C14N() === $actualDoc->C14N()) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that an XML string equals an XML file',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualXml' => self::exportValue($actualXml),
            ],
            detail: $message,
        );
    }

    public static function assertXmlStringNotEqualsXmlFile(
        string $expectedFile,
        string $actualXml,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $expectedDoc = self::loadXmlString($expectedContents);
        $actualDoc = self::loadXmlString($actualXml);

        if ($expectedDoc->C14N() !== $actualDoc->C14N()) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that an XML string does not equal an XML file',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualXml' => self::exportValue($actualXml),
            ],
            detail: $message,
        );
    }

    public static function assertXmlFileEqualsXmlFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $actualContents = self::readFileContents($actualFile, 'actualFile');
        $expectedDoc = self::loadXmlString($expectedContents);
        $actualDoc = self::loadXmlString($actualContents);

        if ($expectedDoc->C14N() === $actualDoc->C14N()) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two XML files are equal',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualFile' => self::exportValue($actualFile),
            ],
            detail: $message,
        );
    }

    public static function assertXmlFileNotEqualsXmlFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void {
        $expectedContents = self::readFileContents($expectedFile, 'expectedFile');
        $actualContents = self::readFileContents($actualFile, 'actualFile');
        $expectedDoc = self::loadXmlString($expectedContents);
        $actualDoc = self::loadXmlString($actualContents);

        if ($expectedDoc->C14N() !== $actualDoc->C14N()) {
            return;
        }

        throw new AssertionFailedException(
            title: 'Failed asserting that two XML files are not equal',
            extra: [
                'expectedFile' => self::exportValue($expectedFile),
                'actualFile' => self::exportValue($actualFile),
            ],
            detail: $message,
        );
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * Checks whether all items in the iterable match the
     * given type check callable.
     *
     * @param iterable<mixed> $haystack
     * - the collection to check
     * @param callable(mixed): bool $typeCheck
     * - the type checking function
     * @return bool
     * - true if all items match
     * - false if any item does not match
     */
    private static function allMatch(
        iterable $haystack,
        callable $typeCheck,
    ): bool {
        foreach ($haystack as $item) {
            if (!$typeCheck($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Counts elements in a Countable or iterable.
     *
     * @param Countable|iterable<mixed> $haystack
     * - the collection to count
     * @return int
     * - the number of elements
     */
    private static function countIterable(
        Countable|iterable $haystack,
    ): int {
        if ($haystack instanceof Countable || is_array($haystack)) {
            return count($haystack);
        }

        return iterator_count($haystack);
    }

    /**
     * Converts a PHPUnit format string to a regular
     * expression.
     *
     * Supports placeholders: %s, %S, %i, %d, %x, %f,
     * %c, %w, %e, %%.
     *
     * @param string $format
     * - the format string to convert
     * @return string
     * - the resulting regex pattern
     */
    private static function formatToRegex(string $format): string
    {
        $regex = preg_quote($format, '/');
        $replacements = [
            '%%' => '%',
            '%s' => '.+',
            '%S' => '.*',
            '%i' => '[+-]?\\d+',
            '%d' => '\\d+',
            '%x' => '[0-9a-fA-F]+',
            '%f' => '[+-]?(?:\\d+\\.?\\d*|\\d*\\.?\\d+)',
            '%c' => '.',
            '%w' => '\\s*',
            '%e' => '[/\\\\]',
        ];

        // Handle %% first to avoid double-replacement
        return '/^' . strtr($regex, $replacements) . '$/s';
    }

    /**
     * Normalizes line endings in a string.
     *
     * Replaces \r\n and \r with \n.
     *
     * @param string $value
     * - the string to normalize
     * @return string
     * - the string with normalized line endings
     */
    private static function normalizeLineEndings(
        string $value,
    ): string {
        return str_replace(
            ["\r\n", "\r"],
            "\n",
            $value,
        );
    }

    /**
     * Reads the contents of a file, throwing an
     * AssertionFailedException if the file cannot be read.
     *
     * @param string $filePath
     * - the path to the file to read
     * @param string $label
     * - a label for the file (used in error messages)
     * @return string
     * - the file contents
     * @throws InvalidArgumentException
     * - if the file does not exist or cannot be read
     */
    private static function readFileContents(
        string $filePath,
        string $label,
    ): string {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException(
                detail: '$' . $label
                    . ' does not exist or is not readable: '
                    . $filePath,
            );
        }

        $contents = file_get_contents($filePath);

        if ($contents === false) {
            throw new InvalidArgumentException(
                detail: '$' . $label
                    . ' could not be read: ' . $filePath,
            );
        }

        return $contents;
    }

    /**
     * Parses an XML string into a DOMDocument.
     *
     * @param string $xml
     * - the XML string to parse
     * @return \DOMDocument
     * - the parsed document
     * @throws InvalidArgumentException
     * - if the XML cannot be parsed
     */
    private static function loadXmlString(
        string $xml,
    ): \DOMDocument {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;

        // Use LIBXML_NOERROR to suppress warnings,
        // check result
        $result = $doc->loadXML($xml, LIBXML_NOERROR);

        if ($result === false) {
            throw new InvalidArgumentException(
                detail: '$xml is not valid XML',
            );
        }

        return $doc;
    }

    /**
     * Sorts arrays recursively by key for canonical
     * comparison.
     *
     * @param mixed $value
     * - the value to canonicalize
     * @return mixed
     * - the canonicalized value (arrays sorted by key)
     */
    private static function canonicalize(mixed $value): mixed
    {
        if (is_array($value)) {
            ksort($value);
            foreach ($value as &$item) {
                $item = self::canonicalize($item);
            }
        }

        return $value;
    }

    /**
     * Converts a value to a short string representation
     * for use in error messages.
     *
     * @param mixed $value
     * - the value to export
     * @return string
     * - a short string representation of the value
     */
    private static function exportValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            return "'" . $value . "'";
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return get_debug_type($value);
    }
}
