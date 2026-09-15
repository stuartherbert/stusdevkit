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

namespace StusDevKit\AssertionsKit\Contracts;

use ArrayAccess;
use Countable;
use DateTimeInterface;
use StusDevKit\AssertionsKit\Exceptions\AssertionFailedException;
use StusDevKit\ExceptionsKit\Exceptions\FileNotFoundException;
use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;
use StusDevKit\ExceptionsKit\Exceptions\UnableToReadFileException;

/**
 * A comprehensive set of general assertion methods.
 *
 * Based on PHPUnit's Assert class's API, especially it's
 * `expected, actual` parameter order and approach :chefs-kiss:
 *
 * Strict Typing
 * =============
 *
 * AssertionKit is designed for use in production code, where strict
 * typing is the norm.
 *
 * Parameters are typed as `mixed` when the assertion's purpose is:
 * - to refine an unknown type,
 * - to perform type-narrowing, or
 * - when the operation legitimately accepts any type.
 *
 * Some parameters are typed as `mixed` and then narrowed via PHPStan
 * pseudo-types. This is mostly for forwards-compatibility (e.g. if
 * a future version of PHP accepts a wider `array-key` set of types).
 *
 * All other parameters have been given explicit types, even if
 * the equivalent PHPUnit assertion accepts `mixed`.
 */
interface AssertApi
{
    // ================================================================
    //
    // Array Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that two arrays are equal while only considering
     * a list of keys.
     *
     * @param array<mixed>              $expected
     *     the array that you are expecting
     * @param array<mixed>              $actual
     *     the array that you are testing
     * @param non-empty-list<array-key> $keysToBeConsidered
     *     a list of array keys to extract from `$actual` before
     *     comparing to `$expected`
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArrayIsEqualToArrayOnlyConsideringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeConsidered,
        string $message = '',
    ): void;

    /**
     * Asserts that two arrays are equal while ignoring a list
     * of keys.
     *
     * @param array<mixed>              $expected
     *      the array that you are expecting
     * @param array<mixed>              $actual
     *      the array that you are testing
     * @param non-empty-list<array-key> $keysToBeIgnored
     *      a list of array keys to remove from `$actual` before
     *      testing against `$expected`
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArrayIsEqualToArrayIgnoringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeIgnored,
        string $message = '',
    ): void;

    /**
     * Asserts that two arrays are identical while only
     * considering a list of keys.
     *
     * @param array<mixed>              $expected
     * @param array<mixed>              $actual
     * @param non-empty-list<array-key> $keysToBeConsidered
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeConsidered,
        string $message = '',
    ): void;

    /**
     * Asserts that two arrays are identical while ignoring a
     * list of keys.
     *
     * @param array<mixed>              $expected
     * @param array<mixed>              $actual
     * @param non-empty-list<array-key> $keysToBeIgnored
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArrayIsIdenticalToArrayIgnoringListOfKeys(
        array $expected,
        array $actual,
        array $keysToBeIgnored,
        string $message = '',
    ): void;

    /**
     * Asserts that an array has a specified key.
     *
     * @param array-key $key
     * @param array<mixed>|ArrayAccess<array-key, mixed> $array
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArrayHasKey(
        mixed $key,
        array|ArrayAccess $array,
        string $message = '',
    ): void;

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param array-key $key
     * @param array<mixed>|ArrayAccess<array-key, mixed> $array
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArrayNotHasKey(
        mixed $key,
        array|ArrayAccess $array,
        string $message = '',
    ): void;

    /**
     * Asserts that the given value is a list (sequential
     * integer keys starting from 0).
     *
     * @phpstan-assert list<mixed> $actual
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsList(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays are identical.
     *
     * The (key, value) relationship matters, the order of
     * the (key, value) pairs in the array matters, and keys
     * as well as values are compared strictly.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysAreIdentical(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays are identical while ignoring
     * the order of their values.
     *
     * The (key, value) relationship matters, the order of
     * the (key, value) pairs in the array does not matter,
     * and keys as well as values are compared strictly.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysAreIdenticalIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays have identical values.
     *
     * The (key, value) relationship does not matter, the
     * order of the (key, value) pairs in the array matters,
     * and values are compared strictly.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysHaveIdenticalValues(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays have identical values while
     * ignoring the order of these values.
     *
     * The (key, value) relationship does not matter, the
     * order of the (key, value) pairs in the array does not
     * matter, and values are compared strictly.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysHaveIdenticalValuesIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays are equal.
     *
     * The (key, value) relationship matters, the order of
     * the (key, value) pairs in the array matters, and keys
     * as well as values are compared loosely.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysAreEqual(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays are equal while ignoring the
     * order of their values.
     *
     * The (key, value) relationship matters, the order of
     * the (key, value) pairs in the array does not matter,
     * and keys as well as values are compared loosely.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysAreEqualIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays have equal values.
     *
     * The (key, value) relationship does not matter, the
     * order of the (key, value) pairs in the array matters,
     * and values are compared loosely.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysHaveEqualValues(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    /**
     * Assert that two arrays have equal values while
     * ignoring the order of these values.
     *
     * The (key, value) relationship does not matter, the
     * order of the (key, value) pairs in the array does not
     * matter, and values are compared loosely.
     *
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertArraysHaveEqualValuesIgnoringOrder(
        array $expected,
        array $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Contains Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a haystack contains a needle (strict comparison).
     *
     * @param mixed $needle
     *     the value that must be inside `$haystack`
     * @param iterable<mixed> $haystack
     *     the value being searched
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContains(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains a needle (using
     * loose comparison).
     *
     * @param mixed $needle
     *     the value that must be inside `$haystack`
     * @param iterable<mixed> $haystack
     *     the value being searched
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsEquals(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @param mixed $needle
     *     the value that must not be inside `$haystack`
     * @param iterable<mixed> $haystack
     *     the value being searched
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotContains(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain a needle
     * (using loose comparison).
     *
     * @param mixed $needle
     *     the value that must not be in `$haystack`
     * @param iterable<mixed> $haystack
     *     the value being searched
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotContainsEquals(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Contains Only Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a haystack contains only values of type
     * array.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<array<mixed>> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyArray(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * bool.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<bool> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyBool(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * callable.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<callable> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyCallable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * float.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<float> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyFloat(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * int.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<int> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyInt(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * iterable.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<iterable<mixed>> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyIterable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * null.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<null> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyNull(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * numeric.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<numeric> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyNumeric(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * object.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<object> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyObject(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * resource.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<resource> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * closed resource.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<resource> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyClosedResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * scalar.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<scalar> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyScalar(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * string.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @phpstan-assert iterable<string> $haystack
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyString(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only instances of a
     * specified interface or class name.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack passes. With no items there is no
     * counterexample, so the "contains only" rule is vacuously
     * satisfied and an empty haystack silently slips through.
     * Guard for emptiness yourself if that is wrong for your
     * caller.
     *
     * @template T
     *
     * @phpstan-assert iterable<T> $haystack
     *
     * @param class-string<T> $className
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsOnlyInstancesOf(
        string $className,
        iterable $haystack,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Contains Not Only Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a haystack does not contain only values
     * of type array.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyArray(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type bool.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyBool(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type callable.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyCallable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type float.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyFloat(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type int.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyInt(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type iterable.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyIterable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type null.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyNull(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type numeric.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyNumeric(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type object.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyObject(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type resource.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type closed resource.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyClosedResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type scalar.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyScalar(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type string.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyString(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only instances
     * of a specified interface or class name.
     *
     * Here Be Dragons
     * ===============
     *
     * An empty haystack throws. With no items the "contains
     * only" rule holds vacuously, so its negation is false -
     * a possibly-empty haystack is a footgun here. Guard for
     * emptiness yourself if that is wrong for your caller.
     *
     * @param class-string    $className
     * @param iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertContainsNotOnlyInstancesOf(
        string $className,
        iterable $haystack,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Count Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts the number of elements of an array, Countable
     * or Traversable.
     *
     * @param Countable|iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertCount(
        int $expectedCount,
        Countable|iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts the number of elements of an array, Countable
     * or Traversable is not a specific count.
     *
     * @param Countable|iterable<mixed> $haystack
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotCount(
        int $expectedCount,
        Countable|iterable $haystack,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Equality Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that two variables are equal.
     *
     * @param mixed $expected
     *     the value that you need
     * @param mixed $actual
     *     the value to compare to `$expected`
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertEquals(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are structurally equal.
     *
     * Both variables are normalised (canonicalised in PHPUnit-speak)
     * before comparison.
     *
     * @param mixed $expected
     *     the value that you need
     * @param mixed $actual
     *     the value to compare to `$expected`
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertEqualsCanonicalizing(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are equal (ignoring case).
     *
     * @param mixed $expected
     *     the value that you need
     * @param mixed $actual
     *     the value to compare to `$expected`
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertEqualsIgnoringCase(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are equal (with delta).
     *
     * Both `$expected` and `$actual` must be numeric.
     *
     * @param mixed $expected
     *     the value that you need; must be numeric
     * @param mixed $actual
     *     the value to compare to `$expected`; must be numeric
     * @param float $delta
     *     how much drift is allowed between the two values
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$expected` or `$actual` is not numeric
     */
    public static function assertEqualsWithDelta(
        mixed $expected,
        mixed $actual,
        float $delta,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal.
     *
     * @param mixed $expected
     *     the value that you want to avoid
     * @param mixed $actual
     *     the value to compare to `$expected`
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotEquals(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not structurally equal
     *
     * Both variables are normalised (canonicalised in PHPUnit-speak)
     * before comparison.
     *
     * @param mixed $expected
     *     the value that you want to avoid
     * @param mixed $actual
     *     the value to compare to `$expected`
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotEqualsCanonicalizing(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal
     * (ignoring case).
     *
     * @param mixed $expected
     *     the value that you want to avoid
     * @param mixed $actual
     *     the value to compare to `$expected`
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotEqualsIgnoringCase(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal (with delta).
     *
     * Both `$expected` and `$actual` must be numeric.
     *
     * @param mixed $expected
     *     the value that you want to avoid; must be numeric
     * @param mixed $actual
     *     the value to compare to `$expected`; must be numeric
     * @param float $delta
     *     how close the two values can be
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$expected` or `$actual` is not numeric
     */
    public static function assertNotEqualsWithDelta(
        mixed $expected,
        mixed $actual,
        float $delta,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Object Equality Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that two objects are considered equal by
     * calling a comparator method on the actual object.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$actual` does not have the named comparator method
     */
    public static function assertObjectEquals(
        object $expected,
        object $actual,
        string $method = 'equals',
        string $message = '',
    ): void;

    /**
     * Asserts that two objects are not considered equal by
     * calling a comparator method on the actual object.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$actual` does not have the named comparator method
     */
    public static function assertObjectNotEquals(
        object $expected,
        object $actual,
        string $method = 'equals',
        string $message = '',
    ): void;

    // ================================================================
    //
    // Empty Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a variable is empty.
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertEmpty(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not empty.
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotEmpty(
        mixed $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Comparison Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a value is greater than another value.
     *
     * `$minimum` and `$actual` must be a comparable pair: both
     * scalar, or both `DateTimeInterface`.
     *
     * @param int|float|string|DateTimeInterface $minimum
     *     what value must `$actual` be greater than?
     * @param int|float|string|DateTimeInterface $actual
     *      the value to check `$minimum` against
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$minimum` and `$actual` are not a comparable pair
     */
    public static function assertGreaterThan(
        int|float|string|DateTimeInterface $minimum,
        int|float|string|DateTimeInterface $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a value is greater than or equal to
     * another value.
     *
     * `$minimum` and `$actual` must be a comparable pair: both
     * scalar, or both `DateTimeInterface`.
     *
     * @param int|float|string|DateTimeInterface $minimum
     *     what value must `$actual` be greater than or equal to?
     * @param int|float|string|DateTimeInterface $actual
     *      the value to check `$minimum` against
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$minimum` and `$actual` are not a comparable pair
     */
    public static function assertGreaterThanOrEqual(
        int|float|string|DateTimeInterface $minimum,
        int|float|string|DateTimeInterface $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a value is smaller than another value.
     *
     * `$maximum` and `$actual` must be a comparable pair: both
     * scalar, or both `DateTimeInterface`.
     *
     * @param int|float|string|DateTimeInterface $maximum
     *     what value must `$actual` be smaller than?
     * @param int|float|string|DateTimeInterface $actual
     *      the value to check `$maximum` against
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$maximum` and `$actual` are not a comparable pair
     */
    public static function assertLessThan(
        int|float|string|DateTimeInterface $maximum,
        int|float|string|DateTimeInterface $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a value is smaller than or equal to
     * another value.
     *
     * `$maximum` and `$actual` must be a comparable pair: both
     * scalar, or both `DateTimeInterface`.
     *
     * @param int|float|string|DateTimeInterface $maximum
     *     what value must `$actual` be smaller than or equal to?
     * @param int|float|string|DateTimeInterface $actual
     *      the value to check `$maximum` against
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when `$maximum` and `$actual` are not a comparable pair
     */
    public static function assertLessThanOrEqual(
        int|float|string|DateTimeInterface $maximum,
        int|float|string|DateTimeInterface $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that the contents of one file is equal to the
     * contents of another file.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertFileEquals(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is equal to the
     * contents of another file (ignoring case).
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertFileEqualsIgnoringCase(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is not equal to
     * the contents of another file.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertFileNotEquals(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is not equal to
     * the contents of another file (ignoring case).
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertFileNotEqualsIgnoringCase(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // String/File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertStringEqualsFile(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (ignoring case).
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertStringEqualsFileIgnoringCase(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertStringNotEqualsFile(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (ignoring case).
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertStringNotEqualsFileIgnoringCase(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Filesystem Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a file/dir is readable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsReadable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file/dir exists and is not readable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotReadable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file/dir exists and is writable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsWritable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file/dir exists and is not writable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotWritable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDirectoryExists(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory does not exist.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDirectoryDoesNotExist(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is readable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDirectoryIsReadable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is not readable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDirectoryIsNotReadable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is writable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDirectoryIsWritable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is not writable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDirectoryIsNotWritable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFileExists(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file does not exist.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFileDoesNotExist(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is readable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFileIsReadable(
        string $file,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is not readable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFileIsNotReadable(
        string $file,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is writable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFileIsWritable(
        string $file,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is not writable.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFileIsNotWritable(
        string $file,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Boolean Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a condition is true.
     *
     * @phpstan-assert true $condition
     *
     * @param bool   $condition
     *     the condition to check
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertTrue(
        bool $condition,
        string $message = '',
    ): void;

    /**
     * Asserts that a condition is false.
     *
     * @phpstan-assert false $condition
     *
     * @param bool   $condition
     *     the condition to test
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFalse(
        bool $condition,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Null Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a variable is null.
     *
     * @phpstan-assert null $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNull(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not null.
     *
     * @phpstan-assert !null $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotNull(
        mixed $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Numeric Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a variable is finite.
     *
     * @param float $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertFinite(
        float $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is infinite.
     *
     * @param float $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertInfinite(
        float $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is NaN (not a number).
     *
     * @param float $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNan(
        float $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Object Property Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that an object has a specified property.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertObjectHasProperty(
        string $propertyName,
        object $object,
        string $message = '',
    ): void;

    /**
     * Asserts that an object does not have a specified
     * property.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertObjectNotHasProperty(
        string $propertyName,
        object $object,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Identity Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that two variables have the same type and
     * value. Used on objects, it asserts that two variables
     * reference the same object.
     *
     * @template ExpectedType
     *
     * @param ExpectedType $expected
     *     the value to compare `$actual` against
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     *
     * @phpstan-assert =ExpectedType $actual
     */
    public static function assertSame(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables do not have the same type
     * and value. Used on objects, it asserts that two
     * variables do not reference the same object.
     *
     * @param mixed $expected
     *     the value to compare `$actual` against
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotSame(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Instance Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a variable is of a given type.
     *
     * @template ExpectedType of object
     *
     * @param class-string<ExpectedType> $expected
     *     the value to compare `$actual` against
     * @param object $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     *
     * @phpstan-assert =ExpectedType $actual
     */
    public static function assertInstanceOf(
        string $expected,
        object $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of a given type.
     *
     * @template ExpectedType of object
     *
     * @param class-string<ExpectedType> $expected
     *     the value to compare `$actual` against
     * @param object $actual
     *     the value to check
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     *
     * @phpstan-assert !ExpectedType $actual
     */
    public static function assertNotInstanceOf(
        string $expected,
        object $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Type Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a variable is of type array.
     *
     * @phpstan-assert array<mixed> $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsArray(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type bool.
     *
     * @phpstan-assert bool $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsBool(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type float.
     *
     * @phpstan-assert float $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsFloat(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type int.
     *
     * @phpstan-assert int $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsInt(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type numeric.
     *
     * @phpstan-assert numeric $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNumeric(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type object.
     *
     * @phpstan-assert object $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsObject(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type resource.
     *
     * @phpstan-assert resource $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type resource and is
     * closed.
     *
     * @phpstan-assert resource $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsClosedResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type string.
     *
     * @phpstan-assert string $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsString(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type scalar.
     *
     * @phpstan-assert scalar $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsScalar(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type callable.
     *
     * @phpstan-assert callable $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsCallable(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type iterable.
     *
     * @phpstan-assert iterable<mixed> $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsIterable(
        mixed $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Negated Type Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a variable is not of type array.
     *
     * @phpstan-assert !array<mixed> $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotArray(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type bool.
     *
     * @phpstan-assert !bool $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotBool(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type float.
     *
     * @phpstan-assert !float $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotFloat(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type int.
     *
     * @phpstan-assert !int $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotInt(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type numeric.
     *
     * @phpstan-assert !numeric $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotNumeric(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type object.
     *
     * @phpstan-assert !object $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotObject(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type resource.
     *
     * @phpstan-assert !resource $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type resource.
     *
     * @phpstan-assert !resource $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotClosedResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type string.
     *
     * @phpstan-assert !string $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotString(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type scalar.
     *
     * @phpstan-assert !scalar $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotScalar(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type callable.
     *
     * @phpstan-assert !callable $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotCallable(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type iterable.
     *
     * @phpstan-assert !iterable<mixed> $actual
     *
     * @param mixed $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertIsNotIterable(
        mixed $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Regular Expression Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a string matches a given regular
     * expression.
     *
     * @param string $pattern
     *     the regex that `$actual` must match
     * @param string $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertMatchesRegularExpression(
        string $pattern,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a string does not match a given regular
     * expression.
     *
     * @param string $pattern
     *     the regex that `$actual` must not match
     * @param string $actual
     *     the value to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertDoesNotMatchRegularExpression(
        string $pattern,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a file's contents match a given regular
     * expression.
     *
     * @param string $pattern
     *     the regex that `$actualFile`'s contents must match
     * @param string $actualFile
     *     the path to the file whose contents to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertFileMatchesRegularExpression(
        string $pattern,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that a file's contents do not match a given
     * regular expression.
     *
     * @param string $pattern
     *     the regex that `$actualFile`'s contents must not match
     * @param string $actualFile
     *     the path to the file whose contents to check
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertFileDoesNotMatchRegularExpression(
        string $pattern,
        string $actualFile,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Size Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Assert that the size of two arrays (or `Countable` or
     * `Traversable` objects) is the same.
     *
     * Here Be Dragons
     * ===============
     *
     * Counting a `Generator` or any one-shot `Iterator` consumes
     * it. This assertion walks such an iterable to its end to size
     * it, so the input is exhausted once the assertion returns — an
     * assertion that mutates its own input is a footgun.
     *
     * Worse, an iterator that has already been partially walked
     * reports only its *remaining* items, so the assertion can fail
     * because of consumption state rather than because the sizes
     * truly differ. Hand the same one-shot iterator in as both
     * arguments and the first count drains it while the second sees
     * an empty husk — a guaranteed spurious failure.
     *
     * Arrays and `Countable` objects are sized without iterating
     * and are immune. To size-check a one-shot iterable safely,
     * draw it into an array yourself first and pass that.
     *
     * @param Countable|iterable<mixed> $expected
     * @param Countable|iterable<mixed> $actual
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertSameSize(
        Countable|iterable $expected,
        Countable|iterable $actual,
        string $message = '',
    ): void;

    /**
     * Assert that the size of two arrays (or `Countable` or
     * `Traversable` objects) is not the same.
     *
     * Here Be Dragons
     * ===============
     *
     * Counting a `Generator` or any one-shot `Iterator` consumes
     * it. This assertion walks such an iterable to its end to size
     * it, so the input is exhausted once the assertion returns — an
     * assertion that mutates its own input is a footgun.
     *
     * Worse, an iterator that has already been partially walked
     * reports only its *remaining* items, so the assertion can pass
     * because of consumption state rather than because the sizes
     * truly differ. Hand the same one-shot iterator in as both
     * arguments and the first count drains it while the second sees
     * an empty husk — so two iterables that really are the same
     * size slip through as "not the same size".
     *
     * Arrays and `Countable` objects are sized without iterating
     * and are immune. To size-check a one-shot iterable safely,
     * draw it into an array yourself first and pass that.
     *
     * @param Countable|iterable<mixed> $expected
     * @param Countable|iterable<mixed> $actual
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertNotSameSize(
        Countable|iterable $expected,
        Countable|iterable $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // String Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a string contains another string,
     * ignoring line endings.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringContainsStringIgnoringLineEndings(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that two strings are equal except for line
     * endings.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringEqualsStringIgnoringLineEndings(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a string starts with a given prefix.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringStartsWith(
        string $prefix,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringStartsNotWith(
        string $prefix,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string contains another string.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringContainsString(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string contains another string
     * (ignoring case).
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringContainsStringIgnoringCase(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string does not contain another string.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringNotContainsString(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string does not contain another string
     * (ignoring case).
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringNotContainsStringIgnoringCase(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string ends with a given suffix.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringEndsWith(
        string $suffix,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertStringEndsNotWith(
        string $suffix,
        string $string,
        string $message = '',
    ): void;

    // ================================================================
    //
    // XML Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that two XML files are equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     * @throws InvalidArgumentException
     *     when a document being compared is not valid XML
     */
    public static function assertXmlFileEqualsXmlFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML files are not equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     * @throws InvalidArgumentException
     *     when a document being compared is not valid XML
     */
    public static function assertXmlFileNotEqualsXmlFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     * @throws InvalidArgumentException
     *     when a document being compared is not valid XML
     */
    public static function assertXmlStringEqualsXmlFile(
        string $expectedFile,
        string $actualXml,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     * @throws InvalidArgumentException
     *     when a document being compared is not valid XML
     */
    public static function assertXmlStringNotEqualsXmlFile(
        string $expectedFile,
        string $actualXml,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when either argument is not valid XML
     */
    public static function assertXmlStringEqualsXmlString(
        string $expectedXml,
        string $actualXml,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws InvalidArgumentException
     *     when either argument is not valid XML
     */
    public static function assertXmlStringNotEqualsXmlString(
        string $expectedXml,
        string $actualXml,
        string $message = '',
    ): void;

    // ================================================================
    //
    // JSON Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a string is a valid JSON string.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertJson(
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two given JSON encoded objects or arrays
     * are equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertJsonStringEqualsJsonString(
        string $expectedJson,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that two given JSON encoded objects or arrays
     * are not equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     */
    public static function assertJsonStringNotEqualsJsonString(
        string $expectedJson,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that the generated JSON encoded object and the
     * content of the given file are equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertJsonStringEqualsJsonFile(
        string $expectedFile,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that the generated JSON encoded object and the
     * content of the given file are not equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertJsonStringNotEqualsJsonFile(
        string $expectedFile,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that two JSON files are equal.
     *
     * @param string                    $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertJsonFileEqualsJsonFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that two JSON files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     *     optional error message
     *     will be included in any thrown exception
     *
     * @throws AssertionFailedException
     *     when the assertion fails
     * @throws FileNotFoundException
     *     when a file referenced by this assertion does not exist
     * @throws UnableToReadFileException
     *     when a referenced file exists but cannot be read
     */
    public static function assertJsonFileNotEqualsJsonFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;
}
