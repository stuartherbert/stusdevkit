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

/**
 * Describes all assertion methods available in PHPUnit's
 * Assert class.
 *
 * This interface extracts the public assertion API from
 * PHPUnit\Framework\Assert so that it can be implemented
 * by other assertion providers, enabling a common contract
 * for assertion behaviour across the codebase.
 *
 * All methods are static, matching PHPUnit's assertion
 * calling convention.
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
     * @param array<mixed>              $actual
     * @param non-empty-list<array-key> $keysToBeConsidered
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
     * @param array<mixed>              $actual
     * @param non-empty-list<array-key> $keysToBeIgnored
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
     * @param array<mixed>|ArrayAccess<array-key, mixed> $array
     */
    public static function assertArrayHasKey(
        mixed $key,
        array|ArrayAccess $array,
        string $message = '',
    ): void;

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param array<mixed>|ArrayAccess<array-key, mixed> $array
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
     * @phpstan-assert list<mixed> $array
     */
    public static function assertIsList(
        mixed $array,
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
     * Asserts that a haystack contains a needle.
     *
     * @param iterable<mixed> $haystack
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
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsEquals(
        mixed $needle,
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @param iterable<mixed> $haystack
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
     * @param iterable<mixed> $haystack
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
     * @phpstan-assert iterable<array<mixed>> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyArray(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * bool.
     *
     * @phpstan-assert iterable<bool> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyBool(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * callable.
     *
     * @phpstan-assert iterable<callable> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyCallable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * float.
     *
     * @phpstan-assert iterable<float> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyFloat(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * int.
     *
     * @phpstan-assert iterable<int> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyInt(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * iterable.
     *
     * @phpstan-assert iterable<iterable<mixed>> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyIterable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * null.
     *
     * @phpstan-assert iterable<null> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyNull(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * numeric.
     *
     * @phpstan-assert iterable<numeric> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyNumeric(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * object.
     *
     * @phpstan-assert iterable<object> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyObject(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * resource.
     *
     * @phpstan-assert iterable<resource> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * closed resource.
     *
     * @phpstan-assert iterable<resource> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyClosedResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * scalar.
     *
     * @phpstan-assert iterable<scalar> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyScalar(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only values of type
     * string.
     *
     * @phpstan-assert iterable<string> $haystack
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsOnlyString(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack contains only instances of a
     * specified interface or class name.
     *
     * @template T
     *
     * @phpstan-assert iterable<T> $haystack
     *
     * @param class-string<T> $className
     * @param iterable<mixed> $haystack
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
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyArray(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type bool.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyBool(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type callable.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyCallable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type float.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyFloat(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type int.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyInt(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type iterable.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyIterable(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type null.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyNull(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type numeric.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyNumeric(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type object.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyObject(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type resource.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type closed resource.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyClosedResource(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type scalar.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyScalar(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only values
     * of type string.
     *
     * @param iterable<mixed> $haystack
     */
    public static function assertContainsNotOnlyString(
        iterable $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a haystack does not contain only instances
     * of a specified interface or class name.
     *
     * @param class-string    $className
     * @param iterable<mixed> $haystack
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
     */
    public static function assertEquals(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are equal (canonicalizing).
     */
    public static function assertEqualsCanonicalizing(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are equal (ignoring case).
     */
    public static function assertEqualsIgnoringCase(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are equal (with delta).
     */
    public static function assertEqualsWithDelta(
        mixed $expected,
        mixed $actual,
        float $delta,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal.
     */
    public static function assertNotEquals(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal
     * (canonicalizing).
     */
    public static function assertNotEqualsCanonicalizing(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal
     * (ignoring case).
     */
    public static function assertNotEqualsIgnoringCase(
        mixed $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two variables are not equal (with delta).
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
     */
    public static function assertEmpty(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not empty.
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
     */
    public static function assertGreaterThan(
        mixed $minimum,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a value is greater than or equal to
     * another value.
     */
    public static function assertGreaterThanOrEqual(
        mixed $minimum,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a value is smaller than another value.
     */
    public static function assertLessThan(
        mixed $maximum,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a value is smaller than or equal to
     * another value.
     */
    public static function assertLessThanOrEqual(
        mixed $maximum,
        mixed $actual,
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
     */
    public static function assertFileEquals(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is equal to the
     * contents of another file (canonicalizing).
     */
    public static function assertFileEqualsCanonicalizing(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is equal to the
     * contents of another file (ignoring case).
     */
    public static function assertFileEqualsIgnoringCase(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is not equal to
     * the contents of another file.
     */
    public static function assertFileNotEquals(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is not equal to
     * the contents of another file (canonicalizing).
     */
    public static function assertFileNotEqualsCanonicalizing(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of one file is not equal to
     * the contents of another file (ignoring case).
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
     */
    public static function assertStringEqualsFile(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (canonicalizing).
     */
    public static function assertStringEqualsFileCanonicalizing(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (ignoring case).
     */
    public static function assertStringEqualsFileIgnoringCase(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     */
    public static function assertStringNotEqualsFile(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (canonicalizing).
     */
    public static function assertStringNotEqualsFileCanonicalizing(
        string $expectedFile,
        string $actualString,
        string $message = '',
    ): void;

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (ignoring case).
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
     */
    public static function assertIsReadable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file/dir exists and is not readable.
     */
    public static function assertIsNotReadable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file/dir exists and is writable.
     */
    public static function assertIsWritable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file/dir exists and is not writable.
     */
    public static function assertIsNotWritable(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists.
     */
    public static function assertDirectoryExists(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory does not exist.
     */
    public static function assertDirectoryDoesNotExist(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is readable.
     */
    public static function assertDirectoryIsReadable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is not readable.
     */
    public static function assertDirectoryIsNotReadable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is writable.
     */
    public static function assertDirectoryIsWritable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a directory exists and is not writable.
     */
    public static function assertDirectoryIsNotWritable(
        string $directory,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists.
     */
    public static function assertFileExists(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file does not exist.
     */
    public static function assertFileDoesNotExist(
        string $filename,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is readable.
     */
    public static function assertFileIsReadable(
        string $file,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is not readable.
     */
    public static function assertFileIsNotReadable(
        string $file,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is writable.
     */
    public static function assertFileIsWritable(
        string $file,
        string $message = '',
    ): void;

    /**
     * Asserts that a file exists and is not writable.
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
     */
    public static function assertTrue(
        mixed $condition,
        string $message = '',
    ): void;

    /**
     * Asserts that a condition is not true.
     *
     * @phpstan-assert !true $condition
     */
    public static function assertNotTrue(
        mixed $condition,
        string $message = '',
    ): void;

    /**
     * Asserts that a condition is false.
     *
     * @phpstan-assert false $condition
     */
    public static function assertFalse(
        mixed $condition,
        string $message = '',
    ): void;

    /**
     * Asserts that a condition is not false.
     *
     * @phpstan-assert !false $condition
     */
    public static function assertNotFalse(
        mixed $condition,
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
     */
    public static function assertNull(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not null.
     *
     * @phpstan-assert !null $actual
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
     */
    public static function assertFinite(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is infinite.
     */
    public static function assertInfinite(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is nan.
     */
    public static function assertNan(
        mixed $actual,
        string $message = '',
    ): void;

    // ================================================================
    //
    // Object Property Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that an object has a specified property.
     */
    public static function assertObjectHasProperty(
        string $propertyName,
        object $object,
        string $message = '',
    ): void;

    /**
     * Asserts that an object does not have a specified
     * property.
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
     *
     * @phpstan-assert =ExpectedType $actual
     */
    public static function assertInstanceOf(
        string $expected,
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of a given type.
     *
     * @template ExpectedType of object
     *
     * @param class-string<ExpectedType> $expected
     *
     * @phpstan-assert !ExpectedType $actual
     */
    public static function assertNotInstanceOf(
        string $expected,
        mixed $actual,
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
     */
    public static function assertIsArray(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type bool.
     *
     * @phpstan-assert bool $actual
     */
    public static function assertIsBool(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type float.
     *
     * @phpstan-assert float $actual
     */
    public static function assertIsFloat(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type int.
     *
     * @phpstan-assert int $actual
     */
    public static function assertIsInt(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type numeric.
     *
     * @phpstan-assert numeric $actual
     */
    public static function assertIsNumeric(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type object.
     *
     * @phpstan-assert object $actual
     */
    public static function assertIsObject(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type resource.
     *
     * @phpstan-assert resource $actual
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
     */
    public static function assertIsClosedResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type string.
     *
     * @phpstan-assert string $actual
     */
    public static function assertIsString(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type scalar.
     *
     * @phpstan-assert scalar $actual
     */
    public static function assertIsScalar(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type callable.
     *
     * @phpstan-assert callable $actual
     */
    public static function assertIsCallable(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is of type iterable.
     *
     * @phpstan-assert iterable<mixed> $actual
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
     */
    public static function assertIsNotArray(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type bool.
     *
     * @phpstan-assert !bool $actual
     */
    public static function assertIsNotBool(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type float.
     *
     * @phpstan-assert !float $actual
     */
    public static function assertIsNotFloat(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type int.
     *
     * @phpstan-assert !int $actual
     */
    public static function assertIsNotInt(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type numeric.
     *
     * @phpstan-assert !numeric $actual
     */
    public static function assertIsNotNumeric(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type object.
     *
     * @phpstan-assert !object $actual
     */
    public static function assertIsNotObject(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type resource.
     *
     * @phpstan-assert !resource $actual
     */
    public static function assertIsNotResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type resource.
     *
     * @phpstan-assert !resource $actual
     */
    public static function assertIsNotClosedResource(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type string.
     *
     * @phpstan-assert !string $actual
     */
    public static function assertIsNotString(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type scalar.
     *
     * @phpstan-assert !scalar $actual
     */
    public static function assertIsNotScalar(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type callable.
     *
     * @phpstan-assert !callable $actual
     */
    public static function assertIsNotCallable(
        mixed $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a variable is not of type iterable.
     *
     * @phpstan-assert !iterable<mixed> $actual
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
     */
    public static function assertMatchesRegularExpression(
        string $pattern,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string does not match a given regular
     * expression.
     */
    public static function assertDoesNotMatchRegularExpression(
        string $pattern,
        string $string,
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
     * @param Countable|iterable<mixed> $expected
     * @param Countable|iterable<mixed> $actual
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
     * @param Countable|iterable<mixed> $expected
     * @param Countable|iterable<mixed> $actual
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
     */
    public static function assertStringContainsStringIgnoringLineEndings(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that two strings are equal except for line
     * endings.
     */
    public static function assertStringEqualsStringIgnoringLineEndings(
        string $expected,
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that a string matches a given format string.
     */
    public static function assertStringMatchesFormat(
        string $format,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string matches a given format file.
     */
    public static function assertStringMatchesFormatFile(
        string $formatFile,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string starts with a given prefix.
     *
     * @param non-empty-string $prefix
     */
    public static function assertStringStartsWith(
        string $prefix,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param non-empty-string $prefix
     */
    public static function assertStringStartsNotWith(
        string $prefix,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string contains another string.
     */
    public static function assertStringContainsString(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string contains another string
     * (ignoring case).
     */
    public static function assertStringContainsStringIgnoringCase(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string does not contain another string.
     */
    public static function assertStringNotContainsString(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string does not contain another string
     * (ignoring case).
     */
    public static function assertStringNotContainsStringIgnoringCase(
        string $needle,
        string $haystack,
        string $message = '',
    ): void;

    /**
     * Asserts that a string ends with a given suffix.
     *
     * @param non-empty-string $suffix
     */
    public static function assertStringEndsWith(
        string $suffix,
        string $string,
        string $message = '',
    ): void;

    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @param non-empty-string $suffix
     */
    public static function assertStringEndsNotWith(
        string $suffix,
        string $string,
        string $message = '',
    ): void;

    // ================================================================
    //
    // File Format Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that a file's contents matches a given format
     * string.
     */
    public static function assertFileMatchesFormat(
        string $format,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that a file's contents matches a given format
     * file.
     */
    public static function assertFileMatchesFormatFile(
        string $formatFile,
        string $actualFile,
        string $message = '',
    ): void;

    // ================================================================
    //
    // XML Assertions
    //
    // ----------------------------------------------------------------

    /**
     * Asserts that two XML files are equal.
     */
    public static function assertXmlFileEqualsXmlFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML files are not equal.
     */
    public static function assertXmlFileNotEqualsXmlFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are equal.
     */
    public static function assertXmlStringEqualsXmlFile(
        string $expectedFile,
        string $actualXml,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are not equal.
     */
    public static function assertXmlStringNotEqualsXmlFile(
        string $expectedFile,
        string $actualXml,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are equal.
     */
    public static function assertXmlStringEqualsXmlString(
        string $expectedXml,
        string $actualXml,
        string $message = '',
    ): void;

    /**
     * Asserts that two XML documents are not equal.
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
     */
    public static function assertJson(
        string $actual,
        string $message = '',
    ): void;

    /**
     * Asserts that two given JSON encoded objects or arrays
     * are equal.
     */
    public static function assertJsonStringEqualsJsonString(
        string $expectedJson,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that two given JSON encoded objects or arrays
     * are not equal.
     */
    public static function assertJsonStringNotEqualsJsonString(
        string $expectedJson,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that the generated JSON encoded object and the
     * content of the given file are equal.
     */
    public static function assertJsonStringEqualsJsonFile(
        string $expectedFile,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that the generated JSON encoded object and the
     * content of the given file are not equal.
     */
    public static function assertJsonStringNotEqualsJsonFile(
        string $expectedFile,
        string $actualJson,
        string $message = '',
    ): void;

    /**
     * Asserts that two JSON files are equal.
     */
    public static function assertJsonFileEqualsJsonFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;

    /**
     * Asserts that two JSON files are not equal.
     */
    public static function assertJsonFileNotEqualsJsonFile(
        string $expectedFile,
        string $actualFile,
        string $message = '',
    ): void;
}
