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
// COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
// BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
// LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
// CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
// ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\AssertionsKit\Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\TestCase;
use StusDevKit\AssertionsKit\Assert;
use StusDevKit\AssertionsKit\Exceptions\AssertionFailedException;
use StusDevKit\AssertionsKit\Tests\Fixtures\ValueObject;
use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;

#[TestDox('Assert')]
class AssertTest extends TestCase
{
    // ================================================================
    //
    // Boolean Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertTrue() passes when given true')]
    public function test_assertTrue_passes_when_given_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertTrue does not throw when the
        // condition is true.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertTrue(true);

        // ----------------------------------------------------------------
        // test the results
        //
        // If we get here, the assertion passed.

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertTrue() throws when given false')]
    public function test_assertTrue_throws_when_given_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertTrue throws when the condition
        // is false.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertTrue(false);
    }

    #[TestDox('assertNotTrue() passes when given false')]
    public function test_assertNotTrue_passes_when_given_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotTrue does not throw when
        // the condition is not true.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotTrue(false);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotTrue() throws when given true')]
    public function test_assertNotTrue_throws_when_given_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotTrue throws when the
        // condition is true.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotTrue(true);
    }

    #[TestDox('assertFalse() passes when given false')]
    public function test_assertFalse_passes_when_given_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFalse does not throw when the
        // condition is false.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFalse(false);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertFalse() throws when given true')]
    public function test_assertFalse_throws_when_given_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFalse throws when the condition
        // is true.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFalse(true);
    }

    #[TestDox('assertNotFalse() passes when given true')]
    public function test_assertNotFalse_passes_when_given_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotFalse does not throw when
        // the condition is not false.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotFalse(true);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotFalse() throws when given false')]
    public function test_assertNotFalse_throws_when_given_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotFalse throws when the
        // condition is false.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotFalse(false);
    }

    // ================================================================
    //
    // Null Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertNull() passes when given null')]
    public function test_assertNull_passes_when_given_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNull does not throw when the
        // value is null.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNull(null);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNull() throws when given non-null')]
    public function test_assertNull_throws_when_given_non_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNull throws when the value is
        // not null.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNull('not null');
    }

    #[TestDox('assertNotNull() passes when given non-null')]
    public function test_assertNotNull_passes_when_given_non_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotNull does not throw when
        // the value is not null.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotNull('not null');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotNull() throws when given null')]
    public function test_assertNotNull_throws_when_given_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotNull throws when the value
        // is null.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotNull(null);
    }

    // ================================================================
    //
    // Empty Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertEmpty() passes when given empty value')]
    public function test_assertEmpty_passes_when_given_empty_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEmpty does not throw when the
        // value is empty.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEmpty([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertEmpty() throws when given non-empty value')]
    public function test_assertEmpty_throws_when_given_non_empty_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEmpty throws when the value is
        // not empty.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEmpty([1, 2, 3]);
    }

    #[TestDox('assertNotEmpty() passes when given non-empty value')]
    public function test_assertNotEmpty_passes_when_given_non_empty_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEmpty does not throw when
        // the value is not empty.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEmpty([1, 2, 3]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotEmpty() throws when given empty value')]
    public function test_assertNotEmpty_throws_when_given_empty_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEmpty throws when the value
        // is empty.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEmpty([]);
    }

    // ================================================================
    //
    // Numeric Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertFinite() passes when given finite number')]
    public function test_assertFinite_passes_when_given_finite_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFinite does not throw when
        // the value is a finite number.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFinite(42.0);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertFinite() throws when given infinite number')]
    public function test_assertFinite_throws_when_given_infinite_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFinite throws when the value
        // is infinite.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFinite(INF);
    }

    #[TestDox('assertInfinite() passes when given infinite number')]
    public function test_assertInfinite_passes_when_given_infinite_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertInfinite does not throw when
        // the value is infinite.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInfinite(INF);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertInfinite() throws when given finite number')]
    public function test_assertInfinite_throws_when_given_finite_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertInfinite throws when the value
        // is finite.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInfinite(42.0);
    }

    #[TestDox('assertNan() passes when given NaN')]
    public function test_assertNan_passes_when_given_nan(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNan does not throw when the
        // value is NaN.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNan(NAN);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNan() throws when given a number')]
    public function test_assertNan_throws_when_given_a_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNan throws when the value is
        // a regular number.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNan(42.0);
    }

    // ================================================================
    //
    // Comparison Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertGreaterThan() passes when actual is greater')]
    public function test_assertGreaterThan_passes_when_actual_is_greater(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertGreaterThan does not throw
        // when the actual value is greater than the
        // minimum.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertGreaterThan(
            minimum: 5,
            actual: 10,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertGreaterThan() throws when actual is equal')]
    public function test_assertGreaterThan_throws_when_actual_is_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertGreaterThan throws when the
        // actual value equals the minimum (must be strictly
        // greater).

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertGreaterThan(
            minimum: 5,
            actual: 5,
        );
    }

    #[TestDox('assertGreaterThanOrEqual() passes when actual is equal')]
    public function test_assertGreaterThanOrEqual_passes_when_actual_is_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertGreaterThanOrEqual does not
        // throw when the actual value equals the minimum.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertGreaterThanOrEqual(
            minimum: 5,
            actual: 5,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertGreaterThanOrEqual() throws when actual is less')]
    public function test_assertGreaterThanOrEqual_throws_when_actual_is_less(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertGreaterThanOrEqual throws when
        // the actual value is less than the minimum.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertGreaterThanOrEqual(
            minimum: 5,
            actual: 3,
        );
    }

    #[TestDox('assertLessThan() passes when actual is less')]
    public function test_assertLessThan_passes_when_actual_is_less(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertLessThan does not throw when
        // the actual value is less than the maximum.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertLessThan(
            maximum: 10,
            actual: 5,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertLessThan() throws when actual is equal')]
    public function test_assertLessThan_throws_when_actual_is_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertLessThan throws when the
        // actual value equals the maximum (must be strictly
        // less).

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertLessThan(
            maximum: 5,
            actual: 5,
        );
    }

    #[TestDox('assertLessThanOrEqual() passes when actual is equal')]
    public function test_assertLessThanOrEqual_passes_when_actual_is_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertLessThanOrEqual does not throw
        // when the actual value equals the maximum.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertLessThanOrEqual(
            maximum: 5,
            actual: 5,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertLessThanOrEqual() throws when actual is greater')]
    public function test_assertLessThanOrEqual_throws_when_actual_is_greater(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertLessThanOrEqual throws when
        // the actual value is greater than the maximum.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertLessThanOrEqual(
            maximum: 5,
            actual: 10,
        );
    }

    // ================================================================
    //
    // Type Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertIsArray() passes when given an array')]
    public function test_assertIsArray_passes_when_given_an_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsArray does not throw when
        // the value is an array.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsArray([1, 2, 3]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsArray() throws when given a non-array')]
    public function test_assertIsArray_throws_when_given_a_non_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsArray throws when the value
        // is not an array.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsArray('string');
    }

    #[TestDox('assertIsBool() passes when given a bool')]
    public function test_assertIsBool_passes_when_given_a_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsBool does not throw when
        // the value is a bool.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsBool(true);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsBool() throws when given a non-bool')]
    public function test_assertIsBool_throws_when_given_a_non_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsBool throws when the value
        // is not a bool.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsBool(1);
    }

    #[TestDox('assertIsFloat() passes when given a float')]
    public function test_assertIsFloat_passes_when_given_a_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsFloat does not throw when
        // the value is a float.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsFloat(3.14);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsFloat() throws when given a non-float')]
    public function test_assertIsFloat_throws_when_given_a_non_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsFloat throws when the value
        // is not a float.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsFloat(1);
    }

    #[TestDox('assertIsInt() passes when given an int')]
    public function test_assertIsInt_passes_when_given_an_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsInt does not throw when
        // the value is an int.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsInt(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsInt() throws when given a non-int')]
    public function test_assertIsInt_throws_when_given_a_non_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsInt throws when the value
        // is not an int.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsInt('string');
    }

    #[TestDox('assertIsNumeric() passes when given a numeric value')]
    public function test_assertIsNumeric_passes_when_given_a_numeric_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNumeric does not throw when
        // the value is numeric (including numeric strings).

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNumeric('123');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNumeric() throws when given a non-numeric value')]
    public function test_assertIsNumeric_throws_when_given_a_non_numeric_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNumeric throws when the value
        // is not numeric.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNumeric('abc');
    }

    #[TestDox('assertIsObject() passes when given an object')]
    public function test_assertIsObject_passes_when_given_an_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsObject does not throw when
        // the value is an object.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsObject(new \stdClass());

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsObject() throws when given a non-object')]
    public function test_assertIsObject_throws_when_given_a_non_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsObject throws when the value
        // is not an object.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsObject('string');
    }

    #[TestDox('assertIsResource() passes when given a resource')]
    public function test_assertIsResource_passes_when_given_a_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsResource does not throw when
        // the value is an open resource.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsResource($resource);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);

        // ----------------------------------------------------------------
        // cleanup

        fclose($resource);
    }

    #[TestDox('assertIsResource() throws when given a non-resource')]
    public function test_assertIsResource_throws_when_given_a_non_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsResource throws when the
        // value is not a resource.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsResource('string');
    }

    #[TestDox('assertIsClosedResource() passes when given a closed resource')]
    public function test_assertIsClosedResource_passes_when_given_a_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsClosedResource does not throw
        // when the value is a closed resource handle.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsClosedResource($resource);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsClosedResource() throws when given a non-closed-resource')]
    public function test_assertIsClosedResource_throws_when_given_a_non_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsClosedResource throws when
        // the value is not a closed resource.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsClosedResource('string');
    }

    #[TestDox('assertIsString() passes when given a string')]
    public function test_assertIsString_passes_when_given_a_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsString does not throw when
        // the value is a string.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsString('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsString() throws when given a non-string')]
    public function test_assertIsString_throws_when_given_a_non_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsString throws when the value
        // is not a string.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsString(42);
    }

    #[TestDox('assertIsScalar() passes when given a scalar')]
    public function test_assertIsScalar_passes_when_given_a_scalar(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsScalar does not throw when
        // the value is a scalar (int, float, string, bool).

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsScalar(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsScalar() throws when given a non-scalar')]
    public function test_assertIsScalar_throws_when_given_a_non_scalar(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsScalar throws when the value
        // is not a scalar.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsScalar([]);
    }

    #[TestDox('assertIsCallable() passes when given a callable')]
    public function test_assertIsCallable_passes_when_given_a_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsCallable does not throw when
        // the value is callable.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsCallable('strlen');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsCallable() throws when given a non-callable')]
    public function test_assertIsCallable_throws_when_given_a_non_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsCallable throws when the
        // value is not callable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsCallable('not_a_function');
    }

    #[TestDox('assertIsIterable() passes when given an iterable')]
    public function test_assertIsIterable_passes_when_given_an_iterable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsIterable does not throw when
        // the value is iterable.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsIterable([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsIterable() throws when given a non-iterable')]
    public function test_assertIsIterable_throws_when_given_a_non_iterable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsIterable throws when the
        // value is not iterable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsIterable(42);
    }

    // ================================================================
    //
    // Negated Type Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertIsNotArray() passes when given a non-array')]
    public function test_assertIsNotArray_passes_when_given_a_non_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotArray does not throw when
        // the value is not an array.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotArray('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotArray() throws when given an array')]
    public function test_assertIsNotArray_throws_when_given_an_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotArray throws when the
        // value is an array.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotArray([1, 2, 3]);
    }

    #[TestDox('assertIsNotBool() passes when given a non-bool')]
    public function test_assertIsNotBool_passes_when_given_a_non_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotBool does not throw when
        // the value is not a bool.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotBool(1);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotBool() throws when given a bool')]
    public function test_assertIsNotBool_throws_when_given_a_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotBool throws when the
        // value is a bool.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotBool(true);
    }

    #[TestDox('assertIsNotFloat() passes when given a non-float')]
    public function test_assertIsNotFloat_passes_when_given_a_non_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotFloat does not throw when
        // the value is not a float.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotFloat(1);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotFloat() throws when given a float')]
    public function test_assertIsNotFloat_throws_when_given_a_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotFloat throws when the
        // value is a float.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotFloat(3.14);
    }

    #[TestDox('assertIsNotInt() passes when given a non-int')]
    public function test_assertIsNotInt_passes_when_given_a_non_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotInt does not throw when
        // the value is not an int.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotInt('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotInt() throws when given an int')]
    public function test_assertIsNotInt_throws_when_given_an_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotInt throws when the value
        // is an int.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotInt(42);
    }

    #[TestDox('assertIsNotNumeric() passes when given a non-numeric value')]
    public function test_assertIsNotNumeric_passes_when_given_a_non_numeric_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotNumeric does not throw
        // when the value is not numeric.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotNumeric('abc');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotNumeric() throws when given a numeric value')]
    public function test_assertIsNotNumeric_throws_when_given_a_numeric_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotNumeric throws when the
        // value is numeric.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotNumeric('123');
    }

    #[TestDox('assertIsNotObject() passes when given a non-object')]
    public function test_assertIsNotObject_passes_when_given_a_non_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotObject does not throw when
        // the value is not an object.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotObject('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotObject() throws when given an object')]
    public function test_assertIsNotObject_throws_when_given_an_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotObject throws when the
        // value is an object.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotObject(new \stdClass());
    }

    #[TestDox('assertIsNotResource() passes when given a non-resource')]
    public function test_assertIsNotResource_passes_when_given_a_non_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotResource does not throw
        // when the value is not a resource.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotResource('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotResource() throws when given a resource')]
    public function test_assertIsNotResource_throws_when_given_a_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotResource throws when the
        // value is a resource.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        try {
            Assert::assertIsNotResource($resource);
        } finally {
            fclose($resource);
        }
    }

    #[TestDox('assertIsNotClosedResource() passes when given a non-closed-resource')]
    public function test_assertIsNotClosedResource_passes_when_given_a_non_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotClosedResource does not
        // throw when the value is not a closed resource.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotClosedResource('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotClosedResource() throws when given a closed resource')]
    public function test_assertIsNotClosedResource_throws_when_given_a_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotClosedResource throws when
        // the value is a closed resource.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotClosedResource($resource);
    }

    #[TestDox('assertIsNotString() passes when given a non-string')]
    public function test_assertIsNotString_passes_when_given_a_non_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotString does not throw when
        // the value is not a string.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotString(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotString() throws when given a string')]
    public function test_assertIsNotString_throws_when_given_a_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotString throws when the
        // value is a string.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotString('hello');
    }

    #[TestDox('assertIsNotScalar() passes when given a non-scalar')]
    public function test_assertIsNotScalar_passes_when_given_a_non_scalar(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotScalar does not throw when
        // the value is not a scalar.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotScalar([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotScalar() throws when given a scalar')]
    public function test_assertIsNotScalar_throws_when_given_a_scalar(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotScalar throws when the
        // value is a scalar.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotScalar(42);
    }

    #[TestDox('assertIsNotCallable() passes when given a non-callable')]
    public function test_assertIsNotCallable_passes_when_given_a_non_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotCallable does not throw
        // when the value is not callable.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotCallable('not_a_function');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotCallable() throws when given a callable')]
    public function test_assertIsNotCallable_throws_when_given_a_callable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotCallable throws when the
        // value is callable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotCallable('strlen');
    }

    #[TestDox('assertIsNotIterable() passes when given a non-iterable')]
    public function test_assertIsNotIterable_passes_when_given_a_non_iterable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotIterable does not throw
        // when the value is not iterable.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotIterable(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsNotIterable() throws when given an iterable')]
    public function test_assertIsNotIterable_throws_when_given_an_iterable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsNotIterable throws when the
        // value is iterable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotIterable([]);
    }

    // ================================================================
    //
    // Instance Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertInstanceOf() passes when value is an instance of the expected class')]
    public function test_assertInstanceOf_passes_when_value_is_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertInstanceOf does not throw when
        // the value is an instance of the expected class.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInstanceOf(
            expected: \stdClass::class,
            actual: new \stdClass(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertInstanceOf() throws when value is not an instance of the expected class')]
    public function test_assertInstanceOf_throws_when_value_is_not_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertInstanceOf throws when the
        // value is not an instance of the expected class.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInstanceOf(
            expected: \stdClass::class,
            actual: 'string',
        );
    }

    #[TestDox('assertNotInstanceOf() passes when value is not an instance of the expected class')]
    public function test_assertNotInstanceOf_passes_when_value_is_not_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotInstanceOf does not throw
        // when the value is not an instance of the expected
        // class.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotInstanceOf(
            expected: \stdClass::class,
            actual: 'string',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotInstanceOf() throws when value is an instance of the expected class')]
    public function test_assertNotInstanceOf_throws_when_value_is_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotInstanceOf throws when the
        // value is an instance of the expected class.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotInstanceOf(
            expected: \stdClass::class,
            actual: new \stdClass(),
        );
    }

    // ================================================================
    //
    // Equality Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertEquals() passes when values are equal')]
    public function test_assertEquals_passes_when_values_are_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEquals does not throw when
        // the values are loosely equal.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEquals(
            expected: 1,
            actual: '1',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertEquals() throws when values are not equal')]
    public function test_assertEquals_throws_when_values_are_not_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEquals throws when the values
        // are not equal.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEquals(
            expected: 1,
            actual: 2,
        );
    }

    #[TestDox('assertEqualsCanonicalizing() passes when arrays have same elements in different order')]
    public function test_assertEqualsCanonicalizing_passes_with_reordered_arrays(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEqualsCanonicalizing does not
        // throw when arrays have the same elements but
        // different key order.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsCanonicalizing(
            expected: ['b' => 2, 'a' => 1],
            actual: ['a' => 1, 'b' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertEqualsCanonicalizing() throws when arrays differ')]
    public function test_assertEqualsCanonicalizing_throws_when_arrays_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEqualsCanonicalizing throws
        // when arrays have different elements.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsCanonicalizing(
            expected: ['a' => 1],
            actual: ['a' => 2],
        );
    }

    #[TestDox('assertEqualsIgnoringCase() passes when strings differ only in case')]
    public function test_assertEqualsIgnoringCase_passes_when_strings_differ_in_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEqualsIgnoringCase does not
        // throw when strings are equal ignoring case.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsIgnoringCase(
            expected: 'Hello World',
            actual: 'hello world',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertEqualsIgnoringCase() throws when strings differ')]
    public function test_assertEqualsIgnoringCase_throws_when_strings_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEqualsIgnoringCase throws when
        // strings are not equal even ignoring case.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsIgnoringCase(
            expected: 'Hello',
            actual: 'World',
        );
    }

    #[TestDox('assertEqualsWithDelta() passes when values are within delta')]
    public function test_assertEqualsWithDelta_passes_when_within_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEqualsWithDelta does not throw
        // when the difference is within the allowed delta.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsWithDelta(
            expected: 1.0,
            actual: 1.05,
            delta: 0.1,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertEqualsWithDelta() throws when values exceed delta')]
    public function test_assertEqualsWithDelta_throws_when_exceeding_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertEqualsWithDelta throws when
        // the difference exceeds the allowed delta.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsWithDelta(
            expected: 1.0,
            actual: 2.0,
            delta: 0.1,
        );
    }

    #[TestDox('assertNotEquals() passes when values are not equal')]
    public function test_assertNotEquals_passes_when_values_are_not_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEquals does not throw when
        // the values are not equal.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEquals(
            expected: 1,
            actual: 2,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotEquals() throws when values are equal')]
    public function test_assertNotEquals_throws_when_values_are_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEquals throws when the
        // values are equal.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEquals(
            expected: 1,
            actual: 1,
        );
    }

    #[TestDox('assertNotEqualsCanonicalizing() passes when arrays differ')]
    public function test_assertNotEqualsCanonicalizing_passes_when_arrays_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEqualsCanonicalizing does
        // not throw when arrays have different elements.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsCanonicalizing(
            expected: ['a' => 1],
            actual: ['a' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotEqualsCanonicalizing() throws when arrays are equal after canonicalizing')]
    public function test_assertNotEqualsCanonicalizing_throws_when_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEqualsCanonicalizing throws
        // when arrays are equal after sorting by key.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsCanonicalizing(
            expected: ['b' => 2, 'a' => 1],
            actual: ['a' => 1, 'b' => 2],
        );
    }

    #[TestDox('assertNotEqualsIgnoringCase() passes when strings differ')]
    public function test_assertNotEqualsIgnoringCase_passes_when_strings_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEqualsIgnoringCase does not
        // throw when strings are different even ignoring
        // case.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsIgnoringCase(
            expected: 'Hello',
            actual: 'World',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotEqualsIgnoringCase() throws when strings are equal ignoring case')]
    public function test_assertNotEqualsIgnoringCase_throws_when_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEqualsIgnoringCase throws
        // when strings are equal ignoring case.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsIgnoringCase(
            expected: 'Hello',
            actual: 'hello',
        );
    }

    #[TestDox('assertNotEqualsWithDelta() passes when values exceed delta')]
    public function test_assertNotEqualsWithDelta_passes_when_exceeding_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEqualsWithDelta does not
        // throw when the difference exceeds the delta.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsWithDelta(
            expected: 1.0,
            actual: 2.0,
            delta: 0.1,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotEqualsWithDelta() throws when values are within delta')]
    public function test_assertNotEqualsWithDelta_throws_when_within_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotEqualsWithDelta throws when
        // the difference is within the allowed delta.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsWithDelta(
            expected: 1.0,
            actual: 1.05,
            delta: 0.1,
        );
    }

    // ================================================================
    //
    // Object Equality Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertObjectEquals() passes when objects are equal')]
    public function test_assertObjectEquals_passes_when_objects_are_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectEquals does not throw
        // when the comparator method returns true.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new ValueObject(value: 42);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectEquals(
            expected: $expected,
            actual: $actual,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertObjectEquals() throws when objects are not equal')]
    public function test_assertObjectEquals_throws_when_objects_are_not_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectEquals throws when the
        // comparator method returns false.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new ValueObject(value: 99);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectEquals(
            expected: $expected,
            actual: $actual,
        );
    }

    #[TestDox('assertObjectNotEquals() passes when objects are not equal')]
    public function test_assertObjectNotEquals_passes_when_objects_are_not_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectNotEquals does not throw
        // when the comparator method returns false.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new ValueObject(value: 99);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectNotEquals(
            expected: $expected,
            actual: $actual,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertObjectNotEquals() throws when objects are equal')]
    public function test_assertObjectNotEquals_throws_when_objects_are_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectNotEquals throws when
        // the comparator method returns true.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new ValueObject(value: 42);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectNotEquals(
            expected: $expected,
            actual: $actual,
        );
    }

    #[TestDox('assertObjectEquals() throws InvalidArgumentException when method does not exist')]
    public function test_assertObjectEquals_throws_InvalidArgumentException_when_method_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectEquals throws
        // InvalidArgumentException when the comparator
        // method does not exist on the actual object.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new ValueObject(value: 42);
        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectEquals(
            expected: $expected,
            actual: $actual,
            method: 'nonExistentMethod',
        );
    }

    #[TestDox('assertObjectNotEquals() throws InvalidArgumentException when method does not exist')]
    public function test_assertObjectNotEquals_throws_InvalidArgumentException_when_method_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectNotEquals throws
        // InvalidArgumentException when the comparator
        // method does not exist on the actual object.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new ValueObject(value: 42);
        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectNotEquals(
            expected: $expected,
            actual: $actual,
            method: 'nonExistentMethod',
        );
    }

    // ================================================================
    //
    // Identity Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertSame() passes when values are identical')]
    public function test_assertSame_passes_when_values_are_identical(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertSame does not throw when the
        // values are strictly identical.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertSame(
            expected: 1,
            actual: 1,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertSame() throws when values are not identical')]
    public function test_assertSame_throws_when_values_are_not_identical(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertSame throws when the values
        // are not strictly identical (e.g. different types).

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertSame(
            expected: 1,
            actual: '1',
        );
    }

    #[TestDox('assertNotSame() passes when values are not identical')]
    public function test_assertNotSame_passes_when_values_are_not_identical(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotSame does not throw when
        // the values are not strictly identical.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotSame(
            expected: 1,
            actual: '1',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotSame() throws when values are identical')]
    public function test_assertNotSame_throws_when_values_are_identical(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotSame throws when the values
        // are strictly identical.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotSame(
            expected: 1,
            actual: 1,
        );
    }

    // ================================================================
    //
    // Object Property Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertObjectHasProperty() passes when object has the property')]
    public function test_assertObjectHasProperty_passes_when_property_exists(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectHasProperty does not
        // throw when the object has the named property.

        // ----------------------------------------------------------------
        // setup your test

        $object = new ValueObject(value: 42);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectHasProperty(
            propertyName: 'value',
            object: $object,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertObjectHasProperty() throws when object lacks the property')]
    public function test_assertObjectHasProperty_throws_when_property_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectHasProperty throws when
        // the object does not have the named property.

        // ----------------------------------------------------------------
        // setup your test

        $object = new ValueObject(value: 42);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectHasProperty(
            propertyName: 'nonexistent',
            object: $object,
        );
    }

    #[TestDox('assertObjectNotHasProperty() passes when object lacks the property')]
    public function test_assertObjectNotHasProperty_passes_when_property_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectNotHasProperty does not
        // throw when the object lacks the named property.

        // ----------------------------------------------------------------
        // setup your test

        $object = new ValueObject(value: 42);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectNotHasProperty(
            propertyName: 'nonexistent',
            object: $object,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertObjectNotHasProperty() throws when object has the property')]
    public function test_assertObjectNotHasProperty_throws_when_property_exists(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertObjectNotHasProperty throws
        // when the object has the named property.

        // ----------------------------------------------------------------
        // setup your test

        $object = new ValueObject(value: 42);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectNotHasProperty(
            propertyName: 'value',
            object: $object,
        );
    }

    // ================================================================
    //
    // Constraint Assertion
    //
    // ----------------------------------------------------------------

    #[TestDox('assertThat() passes when constraint is satisfied')]
    public function test_assertThat_passes_when_constraint_is_satisfied(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertThat does not throw when the
        // constraint evaluates to true.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertThat(
            value: 42,
            constraint: new IsEqual(42),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertThat() throws when constraint is not satisfied')]
    public function test_assertThat_throws_when_constraint_is_not_satisfied(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertThat throws when the constraint
        // evaluates to false.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertThat(
            value: 42,
            constraint: new IsEqual(99),
        );
    }

    // ================================================================
    //
    // Array Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertArrayHasKey() passes when key exists')]
    public function test_assertArrayHasKey_passes_when_key_exists(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArrayHasKey does not throw
        // when the key exists in the array.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayHasKey(
            key: 'a',
            array: ['a' => 1, 'b' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArrayHasKey() throws when key missing')]
    public function test_assertArrayHasKey_throws_when_key_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArrayHasKey throws when the
        // key does not exist in the array.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayHasKey(
            key: 'c',
            array: ['a' => 1, 'b' => 2],
        );
    }

    #[TestDox('assertArrayNotHasKey() passes when key missing')]
    public function test_assertArrayNotHasKey_passes_when_key_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArrayNotHasKey does not throw
        // when the key does not exist.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayNotHasKey(
            key: 'c',
            array: ['a' => 1],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArrayNotHasKey() throws when key exists')]
    public function test_assertArrayNotHasKey_throws_when_key_exists(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArrayNotHasKey throws when
        // the key exists.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayNotHasKey(
            key: 'a',
            array: ['a' => 1],
        );
    }

    #[TestDox('assertArrayHasKey() throws InvalidArgumentException when key is not int or string')]
    public function test_assertArrayHasKey_throws_InvalidArgumentException_when_key_is_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArrayHasKey throws
        // InvalidArgumentException when the key is not
        // an int or string.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayHasKey(
            key: 1.5,
            array: ['a' => 1],
        );
    }

    #[TestDox('assertArrayNotHasKey() throws InvalidArgumentException when key is not int or string')]
    public function test_assertArrayNotHasKey_throws_InvalidArgumentException_when_key_is_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArrayNotHasKey throws
        // InvalidArgumentException when the key is not
        // an int or string.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayNotHasKey(
            key: 1.5,
            array: ['a' => 1],
        );
    }

    #[TestDox('assertIsList() passes when given a list')]
    public function test_assertIsList_passes_when_given_a_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsList does not throw when
        // the array is a list (sequential integer keys).

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsList(
            array: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertIsList() throws when given a non-list')]
    public function test_assertIsList_throws_when_given_a_non_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertIsList throws when the array
        // is not a list.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsList(
            array: ['a' => 1, 'b' => 2],
        );
    }

    #[TestDox('assertArraysAreIdentical() passes when arrays are identical')]
    public function test_assertArraysAreIdentical_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreIdentical does not
        // throw when arrays are strictly identical.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreIdentical(
            expected: ['a' => 1, 'b' => 2],
            actual: ['a' => 1, 'b' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysAreIdentical() throws when arrays differ')]
    public function test_assertArraysAreIdentical_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreIdentical throws
        // when arrays differ.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreIdentical(
            expected: ['a' => 1],
            actual: ['a' => '1'],
        );
    }

    #[TestDox('assertArraysAreIdenticalIgnoringOrder() passes with reordered keys')]
    public function test_assertArraysAreIdenticalIgnoringOrder_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreIdenticalIgnoringOrder
        // does not throw when arrays have same key-value
        // pairs in different order.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreIdenticalIgnoringOrder(
            expected: ['b' => 2, 'a' => 1],
            actual: ['a' => 1, 'b' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysAreIdenticalIgnoringOrder() throws when values differ')]
    public function test_assertArraysAreIdenticalIgnoringOrder_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreIdenticalIgnoringOrder
        // throws when values differ even after reordering.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreIdenticalIgnoringOrder(
            expected: ['a' => 1],
            actual: ['a' => '1'],
        );
    }

    #[TestDox('assertArraysHaveIdenticalValues() passes when values match')]
    public function test_assertArraysHaveIdenticalValues_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveIdenticalValues
        // does not throw when values match in order.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveIdenticalValues(
            expected: ['a' => 1, 'b' => 2],
            actual: ['x' => 1, 'y' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysHaveIdenticalValues() throws when values differ')]
    public function test_assertArraysHaveIdenticalValues_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveIdenticalValues
        // throws when values differ.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveIdenticalValues(
            expected: [1, 2],
            actual: [2, 1],
        );
    }

    #[TestDox('assertArraysHaveIdenticalValuesIgnoringOrder() passes')]
    public function test_assertArraysHaveIdenticalValuesIgnoringOrder_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveIdenticalValues
        // IgnoringOrder does not throw when values match
        // regardless of order.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveIdenticalValuesIgnoringOrder(
            expected: [2, 1],
            actual: [1, 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysHaveIdenticalValuesIgnoringOrder() throws')]
    public function test_assertArraysHaveIdenticalValuesIgnoringOrder_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveIdenticalValues
        // IgnoringOrder throws when values differ.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveIdenticalValuesIgnoringOrder(
            expected: [1, 2],
            actual: [1, 3],
        );
    }

    #[TestDox('assertArraysAreEqual() passes when arrays are loosely equal')]
    public function test_assertArraysAreEqual_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreEqual does not throw
        // when arrays are loosely equal.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreEqual(
            expected: ['a' => 1],
            actual: ['a' => '1'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysAreEqual() throws when arrays differ')]
    public function test_assertArraysAreEqual_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreEqual throws when
        // arrays are not loosely equal.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreEqual(
            expected: ['a' => 1],
            actual: ['a' => 2],
        );
    }

    #[TestDox('assertArraysAreEqualIgnoringOrder() passes')]
    public function test_assertArraysAreEqualIgnoringOrder_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreEqualIgnoringOrder
        // does not throw when arrays are loosely equal
        // after sorting by key.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreEqualIgnoringOrder(
            expected: ['b' => '2', 'a' => '1'],
            actual: ['a' => 1, 'b' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysAreEqualIgnoringOrder() throws')]
    public function test_assertArraysAreEqualIgnoringOrder_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysAreEqualIgnoringOrder
        // throws when arrays differ.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreEqualIgnoringOrder(
            expected: ['a' => 1],
            actual: ['a' => 2],
        );
    }

    #[TestDox('assertArraysHaveEqualValues() passes')]
    public function test_assertArraysHaveEqualValues_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveEqualValues does
        // not throw when values are loosely equal.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveEqualValues(
            expected: ['a' => 1],
            actual: ['x' => '1'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysHaveEqualValues() throws')]
    public function test_assertArraysHaveEqualValues_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveEqualValues throws
        // when values differ.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveEqualValues(
            expected: [1],
            actual: [2],
        );
    }

    #[TestDox('assertArraysHaveEqualValuesIgnoringOrder() passes')]
    public function test_assertArraysHaveEqualValuesIgnoringOrder_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveEqualValuesIgnoring
        // Order does not throw when values are loosely
        // equal regardless of order.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveEqualValuesIgnoringOrder(
            expected: ['2', '1'],
            actual: [1, 2],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArraysHaveEqualValuesIgnoringOrder() throws')]
    public function test_assertArraysHaveEqualValuesIgnoringOrder_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertArraysHaveEqualValuesIgnoring
        // Order throws when values differ.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysHaveEqualValuesIgnoringOrder(
            expected: [1, 2],
            actual: [1, 3],
        );
    }

    #[TestDox('assertArrayIsEqualToArrayOnlyConsideringListOfKeys() passes')]
    public function test_assertArrayIsEqualToArrayOnlyConsideringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method does not throw when the
        // considered keys have equal values.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsEqualToArrayOnlyConsideringListOfKeys(
            expected: ['a' => 1, 'b' => 2, 'c' => 3],
            actual: ['a' => '1', 'b' => '2', 'c' => 99],
            keysToBeConsidered: ['a', 'b'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArrayIsEqualToArrayOnlyConsideringListOfKeys() throws')]
    public function test_assertArrayIsEqualToArrayOnlyConsideringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method throws when the
        // considered keys have different values.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsEqualToArrayOnlyConsideringListOfKeys(
            expected: ['a' => 1],
            actual: ['a' => 2],
            keysToBeConsidered: ['a'],
        );
    }

    #[TestDox('assertArrayIsEqualToArrayIgnoringListOfKeys() passes')]
    public function test_assertArrayIsEqualToArrayIgnoringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method does not throw when
        // non-ignored keys have equal values.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsEqualToArrayIgnoringListOfKeys(
            expected: ['a' => 1, 'b' => 2],
            actual: ['a' => '1', 'b' => 99],
            keysToBeIgnored: ['b'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArrayIsEqualToArrayIgnoringListOfKeys() throws')]
    public function test_assertArrayIsEqualToArrayIgnoringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method throws when non-ignored
        // keys have different values.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsEqualToArrayIgnoringListOfKeys(
            expected: ['a' => 1, 'b' => 2],
            actual: ['a' => 2, 'b' => 99],
            keysToBeIgnored: ['b'],
        );
    }

    #[TestDox('assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys() passes')]
    public function test_assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method does not throw when the
        // considered keys have identical values.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys(
            expected: ['a' => 1, 'b' => 2, 'c' => 3],
            actual: ['a' => 1, 'b' => 2, 'c' => 99],
            keysToBeConsidered: ['a', 'b'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys() throws')]
    public function test_assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method throws when the
        // considered keys have non-identical values.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys(
            expected: ['a' => 1],
            actual: ['a' => '1'],
            keysToBeConsidered: ['a'],
        );
    }

    #[TestDox('assertArrayIsIdenticalToArrayIgnoringListOfKeys() passes')]
    public function test_assertArrayIsIdenticalToArrayIgnoringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method does not throw when
        // non-ignored keys have identical values.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsIdenticalToArrayIgnoringListOfKeys(
            expected: ['a' => 1, 'b' => 2],
            actual: ['a' => 1, 'b' => 99],
            keysToBeIgnored: ['b'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertArrayIsIdenticalToArrayIgnoringListOfKeys() throws')]
    public function test_assertArrayIsIdenticalToArrayIgnoringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that the method throws when non-ignored
        // keys have non-identical values.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayIsIdenticalToArrayIgnoringListOfKeys(
            expected: ['a' => 1, 'b' => 2],
            actual: ['a' => '1', 'b' => 99],
            keysToBeIgnored: ['b'],
        );
    }

    // ================================================================
    //
    // Contains Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertContains() passes when needle found (strict)')]
    public function test_assertContains_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertContains does not throw when
        // the needle is found via strict comparison.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContains(
            needle: 2,
            haystack: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContains() throws when needle not found')]
    public function test_assertContains_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertContains throws when the
        // needle is not found (strict comparison).

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContains(
            needle: '2',
            haystack: [1, 2, 3],
        );
    }

    #[TestDox('assertContainsEquals() passes when needle found (loose)')]
    public function test_assertContainsEquals_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertContainsEquals does not throw
        // when the needle is found via loose comparison.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsEquals(
            needle: '2',
            haystack: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsEquals() throws when needle not found')]
    public function test_assertContainsEquals_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertContainsEquals throws when
        // the needle is not found even with loose
        // comparison.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsEquals(
            needle: 99,
            haystack: [1, 2, 3],
        );
    }

    #[TestDox('assertNotContains() passes when needle not found')]
    public function test_assertNotContains_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotContains does not throw
        // when the needle is absent (strict).

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotContains(
            needle: '2',
            haystack: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotContains() throws when needle found')]
    public function test_assertNotContains_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotContains throws when the
        // needle is found (strict).

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotContains(
            needle: 2,
            haystack: [1, 2, 3],
        );
    }

    #[TestDox('assertNotContainsEquals() passes when needle not found')]
    public function test_assertNotContainsEquals_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotContainsEquals does not
        // throw when the needle is absent (loose).

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotContainsEquals(
            needle: 99,
            haystack: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotContainsEquals() throws when needle found')]
    public function test_assertNotContainsEquals_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotContainsEquals throws when
        // the needle is found (loose).

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotContainsEquals(
            needle: '2',
            haystack: [1, 2, 3],
        );
    }

    // ================================================================
    //
    // Contains Only Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertContainsOnlyArray() passes when all items are arrays')]
    public function test_assertContainsOnlyArray_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertContainsOnlyArray does not
        // throw when all items are arrays.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyArray(
            haystack: [[], [1, 2]],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyArray() throws when not all items are arrays')]
    public function test_assertContainsOnlyArray_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertContainsOnlyArray throws when
        // any item is not an array.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyArray(
            haystack: [[], 'string'],
        );
    }

    #[TestDox('assertContainsOnlyBool() passes')]
    public function test_assertContainsOnlyBool_passes(): void
    {
        Assert::assertContainsOnlyBool(haystack: [true, false]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyBool() throws')]
    public function test_assertContainsOnlyBool_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyBool(haystack: [true, 1]);
    }

    #[TestDox('assertContainsOnlyCallable() passes')]
    public function test_assertContainsOnlyCallable_passes(): void
    {
        Assert::assertContainsOnlyCallable(haystack: ['strlen', 'strtolower']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyCallable() throws')]
    public function test_assertContainsOnlyCallable_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyCallable(haystack: ['strlen', 'not_a_function']);
    }

    #[TestDox('assertContainsOnlyFloat() passes')]
    public function test_assertContainsOnlyFloat_passes(): void
    {
        Assert::assertContainsOnlyFloat(haystack: [1.0, 2.5]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyFloat() throws')]
    public function test_assertContainsOnlyFloat_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyFloat(haystack: [1.0, 1]);
    }

    #[TestDox('assertContainsOnlyInt() passes')]
    public function test_assertContainsOnlyInt_passes(): void
    {
        Assert::assertContainsOnlyInt(haystack: [1, 2, 3]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyInt() throws')]
    public function test_assertContainsOnlyInt_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyInt(haystack: [1, 'two']);
    }

    #[TestDox('assertContainsOnlyIterable() passes')]
    public function test_assertContainsOnlyIterable_passes(): void
    {
        Assert::assertContainsOnlyIterable(haystack: [[], [1]]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyIterable() throws')]
    public function test_assertContainsOnlyIterable_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyIterable(haystack: [[], 42]);
    }

    #[TestDox('assertContainsOnlyNull() passes')]
    public function test_assertContainsOnlyNull_passes(): void
    {
        Assert::assertContainsOnlyNull(haystack: [null, null]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyNull() throws')]
    public function test_assertContainsOnlyNull_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyNull(haystack: [null, 0]);
    }

    #[TestDox('assertContainsOnlyNumeric() passes')]
    public function test_assertContainsOnlyNumeric_passes(): void
    {
        Assert::assertContainsOnlyNumeric(haystack: [1, '2', 3.0]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyNumeric() throws')]
    public function test_assertContainsOnlyNumeric_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyNumeric(haystack: [1, 'abc']);
    }

    #[TestDox('assertContainsOnlyObject() passes')]
    public function test_assertContainsOnlyObject_passes(): void
    {
        Assert::assertContainsOnlyObject(
            haystack: [new \stdClass(), new \stdClass()],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyObject() throws')]
    public function test_assertContainsOnlyObject_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyObject(
            haystack: [new \stdClass(), 'string'],
        );
    }

    #[TestDox('assertContainsOnlyResource() passes')]
    public function test_assertContainsOnlyResource_passes(): void
    {
        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        Assert::assertContainsOnlyResource(haystack: [$r]);
        $this->addToAssertionCount(1);
        fclose($r);
    }

    #[TestDox('assertContainsOnlyResource() throws')]
    public function test_assertContainsOnlyResource_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyResource(haystack: ['string']);
    }

    #[TestDox('assertContainsOnlyClosedResource() passes')]
    public function test_assertContainsOnlyClosedResource_passes(): void
    {
        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        fclose($r);
        Assert::assertContainsOnlyClosedResource(haystack: [$r]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyClosedResource() throws')]
    public function test_assertContainsOnlyClosedResource_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyClosedResource(haystack: ['string']);
    }

    #[TestDox('assertContainsOnlyScalar() passes')]
    public function test_assertContainsOnlyScalar_passes(): void
    {
        Assert::assertContainsOnlyScalar(haystack: [1, 'two', true]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyScalar() throws')]
    public function test_assertContainsOnlyScalar_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyScalar(haystack: [1, []]);
    }

    #[TestDox('assertContainsOnlyString() passes')]
    public function test_assertContainsOnlyString_passes(): void
    {
        Assert::assertContainsOnlyString(haystack: ['a', 'b']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyString() throws')]
    public function test_assertContainsOnlyString_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyString(haystack: ['a', 1]);
    }

    #[TestDox('assertContainsOnlyInstancesOf() passes')]
    public function test_assertContainsOnlyInstancesOf_passes(): void
    {
        Assert::assertContainsOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), new \stdClass()],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsOnlyInstancesOf() throws')]
    public function test_assertContainsOnlyInstancesOf_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), 'string'],
        );
    }

    // ================================================================
    //
    // Contains Not Only Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertContainsNotOnlyArray() passes when not all are arrays')]
    public function test_assertContainsNotOnlyArray_passes(): void
    {
        Assert::assertContainsNotOnlyArray(haystack: [[], 'string']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyArray() throws when all are arrays')]
    public function test_assertContainsNotOnlyArray_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyArray(haystack: [[], [1]]);
    }

    #[TestDox('assertContainsNotOnlyBool() passes')]
    public function test_assertContainsNotOnlyBool_passes(): void
    {
        Assert::assertContainsNotOnlyBool(haystack: [true, 1]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyBool() throws')]
    public function test_assertContainsNotOnlyBool_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyBool(haystack: [true, false]);
    }

    #[TestDox('assertContainsNotOnlyCallable() passes')]
    public function test_assertContainsNotOnlyCallable_passes(): void
    {
        Assert::assertContainsNotOnlyCallable(
            haystack: ['strlen', 'not_a_function'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyCallable() throws')]
    public function test_assertContainsNotOnlyCallable_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyCallable(
            haystack: ['strlen', 'strtolower'],
        );
    }

    #[TestDox('assertContainsNotOnlyFloat() passes')]
    public function test_assertContainsNotOnlyFloat_passes(): void
    {
        Assert::assertContainsNotOnlyFloat(haystack: [1.0, 1]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyFloat() throws')]
    public function test_assertContainsNotOnlyFloat_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyFloat(haystack: [1.0, 2.5]);
    }

    #[TestDox('assertContainsNotOnlyInt() passes')]
    public function test_assertContainsNotOnlyInt_passes(): void
    {
        Assert::assertContainsNotOnlyInt(haystack: [1, 'two']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyInt() throws')]
    public function test_assertContainsNotOnlyInt_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyInt(haystack: [1, 2, 3]);
    }

    #[TestDox('assertContainsNotOnlyIterable() passes')]
    public function test_assertContainsNotOnlyIterable_passes(): void
    {
        Assert::assertContainsNotOnlyIterable(haystack: [[], 42]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyIterable() throws')]
    public function test_assertContainsNotOnlyIterable_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyIterable(haystack: [[], [1]]);
    }

    #[TestDox('assertContainsNotOnlyNull() passes')]
    public function test_assertContainsNotOnlyNull_passes(): void
    {
        Assert::assertContainsNotOnlyNull(haystack: [null, 0]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyNull() throws')]
    public function test_assertContainsNotOnlyNull_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyNull(haystack: [null, null]);
    }

    #[TestDox('assertContainsNotOnlyNumeric() passes')]
    public function test_assertContainsNotOnlyNumeric_passes(): void
    {
        Assert::assertContainsNotOnlyNumeric(haystack: [1, 'abc']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyNumeric() throws')]
    public function test_assertContainsNotOnlyNumeric_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyNumeric(haystack: [1, '2']);
    }

    #[TestDox('assertContainsNotOnlyObject() passes')]
    public function test_assertContainsNotOnlyObject_passes(): void
    {
        Assert::assertContainsNotOnlyObject(
            haystack: [new \stdClass(), 'string'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyObject() throws')]
    public function test_assertContainsNotOnlyObject_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyObject(
            haystack: [new \stdClass(), new \stdClass()],
        );
    }

    #[TestDox('assertContainsNotOnlyResource() passes')]
    public function test_assertContainsNotOnlyResource_passes(): void
    {
        Assert::assertContainsNotOnlyResource(haystack: ['string']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyResource() throws')]
    public function test_assertContainsNotOnlyResource_throws(): void
    {
        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        $this->expectException(AssertionFailedException::class);

        try {
            Assert::assertContainsNotOnlyResource(haystack: [$r]);
        } finally {
            fclose($r);
        }
    }

    #[TestDox('assertContainsNotOnlyClosedResource() passes')]
    public function test_assertContainsNotOnlyClosedResource_passes(): void
    {
        Assert::assertContainsNotOnlyClosedResource(
            haystack: ['string'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyClosedResource() throws')]
    public function test_assertContainsNotOnlyClosedResource_throws(): void
    {
        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        fclose($r);
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyClosedResource(haystack: [$r]);
    }

    #[TestDox('assertContainsNotOnlyScalar() passes')]
    public function test_assertContainsNotOnlyScalar_passes(): void
    {
        Assert::assertContainsNotOnlyScalar(haystack: [1, []]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyScalar() throws')]
    public function test_assertContainsNotOnlyScalar_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyScalar(haystack: [1, 'two']);
    }

    #[TestDox('assertContainsNotOnlyString() passes')]
    public function test_assertContainsNotOnlyString_passes(): void
    {
        Assert::assertContainsNotOnlyString(haystack: ['a', 1]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyString() throws')]
    public function test_assertContainsNotOnlyString_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyString(haystack: ['a', 'b']);
    }

    #[TestDox('assertContainsNotOnlyInstancesOf() passes')]
    public function test_assertContainsNotOnlyInstancesOf_passes(): void
    {
        Assert::assertContainsNotOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), 'string'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('assertContainsNotOnlyInstancesOf() throws')]
    public function test_assertContainsNotOnlyInstancesOf_throws(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), new \stdClass()],
        );
    }

    // ================================================================
    //
    // Count Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertCount() passes when count matches')]
    public function test_assertCount_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertCount does not throw when
        // the count matches.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertCount(
            expectedCount: 3,
            haystack: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertCount() throws when count differs')]
    public function test_assertCount_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertCount throws when the count
        // does not match.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertCount(
            expectedCount: 5,
            haystack: [1, 2, 3],
        );
    }

    #[TestDox('assertNotCount() passes when count differs')]
    public function test_assertNotCount_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotCount does not throw when
        // the count does not match.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotCount(
            expectedCount: 5,
            haystack: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotCount() throws when count matches')]
    public function test_assertNotCount_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotCount throws when the count
        // matches.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotCount(
            expectedCount: 3,
            haystack: [1, 2, 3],
        );
    }

    // ================================================================
    //
    // Size Assertions
    //
    // ----------------------------------------------------------------

    #[TestDox('assertSameSize() passes when sizes match')]
    public function test_assertSameSize_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertSameSize does not throw when
        // both collections have the same number of
        // elements.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertSameSize(
            expected: [1, 2, 3],
            actual: ['a', 'b', 'c'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertSameSize() throws when sizes differ')]
    public function test_assertSameSize_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertSameSize throws when the
        // collections have different sizes.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertSameSize(
            expected: [1, 2, 3],
            actual: ['a', 'b'],
        );
    }

    #[TestDox('assertNotSameSize() passes when sizes differ')]
    public function test_assertNotSameSize_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotSameSize does not throw
        // when the collections have different sizes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotSameSize(
            expected: [1, 2, 3],
            actual: ['a', 'b'],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('assertNotSameSize() throws when sizes match')]
    public function test_assertNotSameSize_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertNotSameSize throws when the
        // collections have the same size.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotSameSize(
            expected: [1, 2, 3],
            actual: ['a', 'b', 'c'],
        );
    }

    // ================================================================
    //
    // String Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertStringContainsString
     */
    #[TestDox('assertStringContainsString() passes when haystack contains needle')]
    public function test_assertStringContainsString_passes(): void
    {
        // this test verifies that the assertion passes
        // when the haystack contains the needle

        Assert::assertStringContainsString(
            needle: 'world',
            haystack: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringContainsString
     */
    #[TestDox('assertStringContainsString() throws when haystack does not contain needle')]
    public function test_assertStringContainsString_throws(): void
    {
        // this test verifies that the assertion throws
        // when the haystack does not contain the needle

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringContainsString(
            needle: 'xyz',
            haystack: 'hello world',
        );
    }

    /**
     * @covers ::assertStringContainsStringIgnoringCase
     */
    #[TestDox('assertStringContainsStringIgnoringCase() passes when haystack contains needle (ignoring case)')]
    public function test_assertStringContainsStringIgnoringCase_passes(): void
    {
        // this test verifies that the assertion passes
        // when the haystack contains the needle regardless
        // of case

        Assert::assertStringContainsStringIgnoringCase(
            needle: 'WORLD',
            haystack: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringContainsStringIgnoringCase
     */
    #[TestDox('assertStringContainsStringIgnoringCase() throws when haystack does not contain needle')]
    public function test_assertStringContainsStringIgnoringCase_throws(): void
    {
        // this test verifies that the assertion throws
        // when the haystack does not contain the needle
        // even when ignoring case

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringContainsStringIgnoringCase(
            needle: 'xyz',
            haystack: 'hello world',
        );
    }

    /**
     * @covers ::assertStringNotContainsString
     */
    #[TestDox('assertStringNotContainsString() passes when haystack does not contain needle')]
    public function test_assertStringNotContainsString_passes(): void
    {
        // this test verifies that the assertion passes
        // when the haystack does not contain the needle

        Assert::assertStringNotContainsString(
            needle: 'xyz',
            haystack: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringNotContainsString
     */
    #[TestDox('assertStringNotContainsString() throws when haystack contains needle')]
    public function test_assertStringNotContainsString_throws(): void
    {
        // this test verifies that the assertion throws
        // when the haystack contains the needle

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotContainsString(
            needle: 'world',
            haystack: 'hello world',
        );
    }

    /**
     * @covers ::assertStringNotContainsStringIgnoringCase
     */
    #[TestDox('assertStringNotContainsStringIgnoringCase() passes when haystack does not contain needle')]
    public function test_assertStringNotContainsStringIgnoringCase_passes(): void
    {
        // this test verifies that the assertion passes
        // when the haystack does not contain the needle
        // even when ignoring case

        Assert::assertStringNotContainsStringIgnoringCase(
            needle: 'xyz',
            haystack: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringNotContainsStringIgnoringCase
     */
    #[TestDox('assertStringNotContainsStringIgnoringCase() throws when haystack contains needle (ignoring case)')]
    public function test_assertStringNotContainsStringIgnoringCase_throws(): void
    {
        // this test verifies that the assertion throws
        // when the haystack contains the needle when
        // ignoring case

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotContainsStringIgnoringCase(
            needle: 'WORLD',
            haystack: 'hello world',
        );
    }

    /**
     * @covers ::assertStringStartsWith
     */
    #[TestDox('assertStringStartsWith() passes when string starts with prefix')]
    public function test_assertStringStartsWith_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string starts with the given prefix

        Assert::assertStringStartsWith(
            prefix: 'hello',
            string: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringStartsWith
     */
    #[TestDox('assertStringStartsWith() throws when string does not start with prefix')]
    public function test_assertStringStartsWith_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string does not start with the prefix

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringStartsWith(
            prefix: 'world',
            string: 'hello world',
        );
    }

    /**
     * @covers ::assertStringStartsNotWith
     */
    #[TestDox('assertStringStartsNotWith() passes when string does not start with prefix')]
    public function test_assertStringStartsNotWith_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string does not start with the prefix

        Assert::assertStringStartsNotWith(
            prefix: 'world',
            string: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringStartsNotWith
     */
    #[TestDox('assertStringStartsNotWith() throws when string starts with prefix')]
    public function test_assertStringStartsNotWith_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string starts with the prefix

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringStartsNotWith(
            prefix: 'hello',
            string: 'hello world',
        );
    }

    /**
     * @covers ::assertStringEndsWith
     */
    #[TestDox('assertStringEndsWith() passes when string ends with suffix')]
    public function test_assertStringEndsWith_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string ends with the given suffix

        Assert::assertStringEndsWith(
            suffix: 'world',
            string: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringEndsWith
     */
    #[TestDox('assertStringEndsWith() throws when string does not end with suffix')]
    public function test_assertStringEndsWith_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string does not end with the suffix

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEndsWith(
            suffix: 'hello',
            string: 'hello world',
        );
    }

    /**
     * @covers ::assertStringEndsNotWith
     */
    #[TestDox('assertStringEndsNotWith() passes when string does not end with suffix')]
    public function test_assertStringEndsNotWith_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string does not end with the suffix

        Assert::assertStringEndsNotWith(
            suffix: 'hello',
            string: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringEndsNotWith
     */
    #[TestDox('assertStringEndsNotWith() throws when string ends with suffix')]
    public function test_assertStringEndsNotWith_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string ends with the suffix

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEndsNotWith(
            suffix: 'world',
            string: 'hello world',
        );
    }

    /**
     * @covers ::assertStringContainsStringIgnoringLineEndings
     */
    #[TestDox('assertStringContainsStringIgnoringLineEndings() passes when haystack contains needle after normalizing line endings')]
    public function test_assertStringContainsStringIgnoringLineEndings_passes(): void
    {
        // this test verifies that the assertion passes
        // when the haystack contains the needle after
        // normalizing line endings (\r\n -> \n)

        Assert::assertStringContainsStringIgnoringLineEndings(
            needle: "hello\nworld",
            haystack: "hello\r\nworld\r\nfoo",
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringContainsStringIgnoringLineEndings
     */
    #[TestDox('assertStringContainsStringIgnoringLineEndings() throws when haystack does not contain needle')]
    public function test_assertStringContainsStringIgnoringLineEndings_throws(): void
    {
        // this test verifies that the assertion throws
        // when the haystack does not contain the needle
        // even after normalizing line endings

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringContainsStringIgnoringLineEndings(
            needle: "xyz",
            haystack: "hello\r\nworld",
        );
    }

    /**
     * @covers ::assertStringEqualsStringIgnoringLineEndings
     */
    #[TestDox('assertStringEqualsStringIgnoringLineEndings() passes when strings are equal after normalizing line endings')]
    public function test_assertStringEqualsStringIgnoringLineEndings_passes(): void
    {
        // this test verifies that the assertion passes
        // when the strings are equal after normalizing
        // line endings

        Assert::assertStringEqualsStringIgnoringLineEndings(
            expected: "hello\nworld",
            actual: "hello\r\nworld",
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringEqualsStringIgnoringLineEndings
     */
    #[TestDox('assertStringEqualsStringIgnoringLineEndings() throws when strings differ after normalizing line endings')]
    public function test_assertStringEqualsStringIgnoringLineEndings_throws(): void
    {
        // this test verifies that the assertion throws
        // when the strings differ after normalizing
        // line endings

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsStringIgnoringLineEndings(
            expected: "hello\nworld",
            actual: "hello\r\nfoo",
        );
    }

    /**
     * @covers ::assertStringMatchesFormat
     */
    #[TestDox('assertStringMatchesFormat() passes when string matches format')]
    public function test_assertStringMatchesFormat_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string matches the format pattern
        // using PHPUnit-style placeholders

        Assert::assertStringMatchesFormat(
            format: 'Hello %s, you are %d years old.',
            string: 'Hello World, you are 42 years old.',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringMatchesFormat
     */
    #[TestDox('assertStringMatchesFormat() throws when string does not match format')]
    public function test_assertStringMatchesFormat_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string does not match the format
        // pattern

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringMatchesFormat(
            format: 'Hello %s, you are %d years old.',
            string: 'Goodbye World',
        );
    }

    /**
     * @covers ::assertStringMatchesFormatFile
     */
    #[TestDox('assertStringMatchesFormatFile() passes when string matches format from file')]
    public function test_assertStringMatchesFormatFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string matches the format read from
        // a file

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        Assert::assertStringMatchesFormatFile(
            formatFile: $formatFile,
            string: 'Hello World, you are 42 years old.',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringMatchesFormatFile
     */
    #[TestDox('assertStringMatchesFormatFile() throws when string does not match format from file')]
    public function test_assertStringMatchesFormatFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string does not match the format
        // read from a file

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringMatchesFormatFile(
            formatFile: $formatFile,
            string: 'Goodbye World',
        );
    }

    #[TestDox('assertStringMatchesFormatFile() throws InvalidArgumentException when format file is not readable')]
    public function test_assertStringMatchesFormatFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertStringMatchesFormatFile throws
        // InvalidArgumentException when the format file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringMatchesFormatFile(
            formatFile: '/nonexistent/path/format.txt',
            string: 'hello',
        );
    }

    // ================================================================
    //
    // Regex Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertMatchesRegularExpression
     */
    #[TestDox('assertMatchesRegularExpression() passes when string matches pattern')]
    public function test_assertMatchesRegularExpression_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string matches the regular expression

        Assert::assertMatchesRegularExpression(
            pattern: '/^hello/',
            string: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertMatchesRegularExpression
     */
    #[TestDox('assertMatchesRegularExpression() throws when string does not match pattern')]
    public function test_assertMatchesRegularExpression_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string does not match the regular
        // expression

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertMatchesRegularExpression(
            pattern: '/^world/',
            string: 'hello world',
        );
    }

    /**
     * @covers ::assertDoesNotMatchRegularExpression
     */
    #[TestDox('assertDoesNotMatchRegularExpression() passes when string does not match pattern')]
    public function test_assertDoesNotMatchRegularExpression_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string does not match the regular
        // expression

        Assert::assertDoesNotMatchRegularExpression(
            pattern: '/^world/',
            string: 'hello world',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertDoesNotMatchRegularExpression
     */
    #[TestDox('assertDoesNotMatchRegularExpression() throws when string matches pattern')]
    public function test_assertDoesNotMatchRegularExpression_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string matches the regular expression

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDoesNotMatchRegularExpression(
            pattern: '/^hello/',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // Filesystem Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertFileExists
     */
    #[TestDox('assertFileExists() passes when file exists')]
    public function test_assertFileExists_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given file exists on disk

        Assert::assertFileExists(
            filename: __FILE__,
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileExists
     */
    #[TestDox('assertFileExists() throws when file does not exist')]
    public function test_assertFileExists_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given file does not exist

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileExists(
            filename: '/nonexistent/file.txt',
        );
    }

    /**
     * @covers ::assertFileDoesNotExist
     */
    #[TestDox('assertFileDoesNotExist() passes when file does not exist')]
    public function test_assertFileDoesNotExist_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given file does not exist on disk

        Assert::assertFileDoesNotExist(
            filename: '/nonexistent/file.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileDoesNotExist
     */
    #[TestDox('assertFileDoesNotExist() throws when file exists')]
    public function test_assertFileDoesNotExist_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given file exists

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileDoesNotExist(
            filename: __FILE__,
        );
    }

    /**
     * @covers ::assertFileIsReadable
     */
    #[TestDox('assertFileIsReadable() passes when file is readable')]
    public function test_assertFileIsReadable_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given file exists and is readable

        Assert::assertFileIsReadable(
            file: __FILE__,
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileIsReadable
     */
    #[TestDox('assertFileIsReadable() throws when file does not exist')]
    public function test_assertFileIsReadable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given file does not exist

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsReadable(
            file: '/nonexistent/file.txt',
        );
    }

    /**
     * @covers ::assertFileIsNotReadable
     */
    #[TestDox('assertFileIsNotReadable() throws when file is readable')]
    public function test_assertFileIsNotReadable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given file is readable (the negated
        // case fails)

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsNotReadable(
            file: __FILE__,
        );
    }

    /**
     * @covers ::assertFileIsWritable
     */
    #[TestDox('assertFileIsWritable() passes when file is writable')]
    public function test_assertFileIsWritable_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given file exists and is writable

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            Assert::assertFileIsWritable(
                file: $tmpFile,
            );

            // if we get here, the assertion passed
            $this->addToAssertionCount(1);
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @covers ::assertFileIsWritable
     */
    #[TestDox('assertFileIsWritable() throws when file does not exist')]
    public function test_assertFileIsWritable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given file does not exist

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsWritable(
            file: '/nonexistent/file.txt',
        );
    }

    /**
     * @covers ::assertFileIsNotWritable
     */
    #[TestDox('assertFileIsNotWritable() throws when file is writable')]
    public function test_assertFileIsNotWritable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given file is writable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileIsNotWritable(
                file: $tmpFile,
            );
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @covers ::assertDirectoryExists
     */
    #[TestDox('assertDirectoryExists() passes when directory exists')]
    public function test_assertDirectoryExists_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given directory exists on disk

        Assert::assertDirectoryExists(
            directory: __DIR__,
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertDirectoryExists
     */
    #[TestDox('assertDirectoryExists() throws when directory does not exist')]
    public function test_assertDirectoryExists_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given directory does not exist

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryExists(
            directory: '/nonexistent/directory',
        );
    }

    /**
     * @covers ::assertDirectoryDoesNotExist
     */
    #[TestDox('assertDirectoryDoesNotExist() passes when directory does not exist')]
    public function test_assertDirectoryDoesNotExist_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given directory does not exist

        Assert::assertDirectoryDoesNotExist(
            directory: '/nonexistent/directory',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertDirectoryDoesNotExist
     */
    #[TestDox('assertDirectoryDoesNotExist() throws when directory exists')]
    public function test_assertDirectoryDoesNotExist_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given directory exists

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryDoesNotExist(
            directory: __DIR__,
        );
    }

    /**
     * @covers ::assertDirectoryIsReadable
     */
    #[TestDox('assertDirectoryIsReadable() passes when directory is readable')]
    public function test_assertDirectoryIsReadable_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given directory exists and is readable

        Assert::assertDirectoryIsReadable(
            directory: __DIR__,
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertDirectoryIsReadable
     */
    #[TestDox('assertDirectoryIsReadable() throws when directory does not exist')]
    public function test_assertDirectoryIsReadable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given directory does not exist

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsReadable(
            directory: '/nonexistent/directory',
        );
    }

    /**
     * @covers ::assertDirectoryIsNotReadable
     */
    #[TestDox('assertDirectoryIsNotReadable() throws when directory is readable')]
    public function test_assertDirectoryIsNotReadable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given directory is readable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsNotReadable(
            directory: __DIR__,
        );
    }

    /**
     * @covers ::assertDirectoryIsWritable
     */
    #[TestDox('assertDirectoryIsWritable() passes when directory is writable')]
    public function test_assertDirectoryIsWritable_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given directory exists and is writable

        Assert::assertDirectoryIsWritable(
            directory: sys_get_temp_dir(),
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertDirectoryIsWritable
     */
    #[TestDox('assertDirectoryIsWritable() throws when directory does not exist')]
    public function test_assertDirectoryIsWritable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given directory does not exist

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsWritable(
            directory: '/nonexistent/directory',
        );
    }

    /**
     * @covers ::assertDirectoryIsNotWritable
     */
    #[TestDox('assertDirectoryIsNotWritable() throws when directory is writable')]
    public function test_assertDirectoryIsNotWritable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given directory is writable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsNotWritable(
            directory: sys_get_temp_dir(),
        );
    }

    /**
     * @covers ::assertIsReadable
     */
    #[TestDox('assertIsReadable() passes when path is readable')]
    public function test_assertIsReadable_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given path is readable

        Assert::assertIsReadable(
            filename: __FILE__,
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertIsReadable
     */
    #[TestDox('assertIsReadable() throws when path is not readable')]
    public function test_assertIsReadable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given path is not readable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsReadable(
            filename: '/nonexistent/file.txt',
        );
    }

    /**
     * @covers ::assertIsNotReadable
     */
    #[TestDox('assertIsNotReadable() throws when path is readable')]
    public function test_assertIsNotReadable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given path is readable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotReadable(
            filename: __FILE__,
        );
    }

    /**
     * @covers ::assertIsWritable
     */
    #[TestDox('assertIsWritable() passes when path is writable')]
    public function test_assertIsWritable_passes(): void
    {
        // this test verifies that the assertion passes
        // when the given path is writable

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            Assert::assertIsWritable(
                filename: $tmpFile,
            );

            // if we get here, the assertion passed
            $this->addToAssertionCount(1);
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @covers ::assertIsWritable
     */
    #[TestDox('assertIsWritable() throws when path is not writable')]
    public function test_assertIsWritable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given path is not writable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsWritable(
            filename: '/nonexistent/file.txt',
        );
    }

    /**
     * @covers ::assertIsNotWritable
     */
    #[TestDox('assertIsNotWritable() throws when path is writable')]
    public function test_assertIsNotWritable_throws(): void
    {
        // this test verifies that the assertion throws
        // when the given path is writable

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            // ----------------------------------------------------------------
            // perform the change

            Assert::assertIsNotWritable(
                filename: $tmpFile,
            );
        } finally {
            unlink($tmpFile);
        }
    }

    // ================================================================
    //
    // File Format Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertFileMatchesFormat
     */
    #[TestDox('assertFileMatchesFormat() passes when file contents matches format')]
    public function test_assertFileMatchesFormat_passes(): void
    {
        // this test verifies that the assertion passes
        // when the file contents matches the given
        // format string

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            file_put_contents(
                $tmpFile,
                'Hello World, you are 42 years old.',
            );

            Assert::assertFileMatchesFormat(
                format: 'Hello %s, you are %d years old.',
                actualFile: $tmpFile,
            );

            // if we get here, the assertion passed
            $this->addToAssertionCount(1);
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @covers ::assertFileMatchesFormat
     */
    #[TestDox('assertFileMatchesFormat() throws when file contents does not match format')]
    public function test_assertFileMatchesFormat_throws(): void
    {
        // this test verifies that the assertion throws
        // when the file contents does not match the
        // given format string

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            file_put_contents($tmpFile, 'Goodbye World');

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileMatchesFormat(
                format: 'Hello %s, you are %d years old.',
                actualFile: $tmpFile,
            );
        } finally {
            unlink($tmpFile);
        }
    }

    #[TestDox('assertFileMatchesFormat() throws InvalidArgumentException when actual file is not readable')]
    public function test_assertFileMatchesFormat_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileMatchesFormat throws
        // InvalidArgumentException when the actual file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileMatchesFormat(
            format: 'Hello %s',
            actualFile: '/nonexistent/path/file.txt',
        );
    }

    /**
     * @covers ::assertFileMatchesFormatFile
     */
    #[TestDox('assertFileMatchesFormatFile() passes when file contents matches format file')]
    public function test_assertFileMatchesFormatFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when the actual file contents matches the
        // format read from a format file

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            file_put_contents(
                $tmpFile,
                'Hello World, you are 42 years old.',
            );

            Assert::assertFileMatchesFormatFile(
                formatFile: $formatFile,
                actualFile: $tmpFile,
            );

            // if we get here, the assertion passed
            $this->addToAssertionCount(1);
        } finally {
            unlink($tmpFile);
        }
    }

    /**
     * @covers ::assertFileMatchesFormatFile
     */
    #[TestDox('assertFileMatchesFormatFile() throws when file contents does not match format file')]
    public function test_assertFileMatchesFormatFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when the actual file contents does not match
        // the format read from a format file

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            file_put_contents($tmpFile, 'Goodbye World');

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileMatchesFormatFile(
                formatFile: $formatFile,
                actualFile: $tmpFile,
            );
        } finally {
            unlink($tmpFile);
        }
    }

    #[TestDox('assertFileMatchesFormatFile() throws InvalidArgumentException when format file is not readable')]
    public function test_assertFileMatchesFormatFile_throws_InvalidArgumentException_when_formatFile_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileMatchesFormatFile throws
        // InvalidArgumentException when the format file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileMatchesFormatFile(
            formatFile: '/nonexistent/path/format.txt',
            actualFile: __FILE__,
        );
    }

    #[TestDox('assertFileMatchesFormatFile() throws InvalidArgumentException when actual file is not readable')]
    public function test_assertFileMatchesFormatFile_throws_InvalidArgumentException_when_actualFile_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileMatchesFormatFile throws
        // InvalidArgumentException when the actual file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileMatchesFormatFile(
            formatFile: $formatFile,
            actualFile: '/nonexistent/path/file.txt',
        );
    }

    // ================================================================
    //
    // File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertFileEquals
     */
    #[TestDox('assertFileEquals() passes when files have equal contents')]
    public function test_assertFileEquals_passes(): void
    {
        // this test verifies that the assertion passes
        // when two files have identical contents

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertFileEquals(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileEquals
     */
    #[TestDox('assertFileEquals() throws when files have different contents')]
    public function test_assertFileEquals_throws(): void
    {
        // this test verifies that the assertion throws
        // when two files have different contents

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEquals(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );
    }

    /**
     * @covers ::assertFileEqualsCanonicalizing
     */
    #[TestDox('assertFileEqualsCanonicalizing() passes when files are equal after sorting lines')]
    public function test_assertFileEqualsCanonicalizing_passes(): void
    {
        // this test verifies that the assertion passes
        // when two files have the same content after
        // sorting lines

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertFileEqualsCanonicalizing(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileEqualsCanonicalizing
     */
    #[TestDox('assertFileEqualsCanonicalizing() throws when files differ after sorting')]
    public function test_assertFileEqualsCanonicalizing_throws(): void
    {
        // this test verifies that the assertion throws
        // when two files differ even after sorting lines

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEqualsCanonicalizing(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );
    }

    /**
     * @covers ::assertFileEqualsIgnoringCase
     */
    #[TestDox('assertFileEqualsIgnoringCase() passes when files are equal ignoring case')]
    public function test_assertFileEqualsIgnoringCase_passes(): void
    {
        // this test verifies that the assertion passes
        // when two files are equal after case
        // normalization

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertFileEqualsIgnoringCase(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello-uppercase.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileEqualsIgnoringCase
     */
    #[TestDox('assertFileEqualsIgnoringCase() throws when files differ ignoring case')]
    public function test_assertFileEqualsIgnoringCase_throws(): void
    {
        // this test verifies that the assertion throws
        // when two files differ even after case
        // normalization

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEqualsIgnoringCase(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );
    }

    /**
     * @covers ::assertFileNotEquals
     */
    #[TestDox('assertFileNotEquals() passes when files have different contents')]
    public function test_assertFileNotEquals_passes(): void
    {
        // this test verifies that the assertion passes
        // when two files have different contents

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertFileNotEquals(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileNotEquals
     */
    #[TestDox('assertFileNotEquals() throws when files have equal contents')]
    public function test_assertFileNotEquals_throws(): void
    {
        // this test verifies that the assertion throws
        // when two files have the same contents

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEquals(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello.txt',
        );
    }

    /**
     * @covers ::assertFileNotEqualsCanonicalizing
     */
    #[TestDox('assertFileNotEqualsCanonicalizing() passes when files differ after sorting')]
    public function test_assertFileNotEqualsCanonicalizing_passes(): void
    {
        // this test verifies that the assertion passes
        // when two files differ after sorting lines

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertFileNotEqualsCanonicalizing(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileNotEqualsCanonicalizing
     */
    #[TestDox('assertFileNotEqualsCanonicalizing() throws when files are equal after sorting')]
    public function test_assertFileNotEqualsCanonicalizing_throws(): void
    {
        // this test verifies that the assertion throws
        // when two files are equal after sorting lines

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEqualsCanonicalizing(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello.txt',
        );
    }

    /**
     * @covers ::assertFileNotEqualsIgnoringCase
     */
    #[TestDox('assertFileNotEqualsIgnoringCase() passes when files differ ignoring case')]
    public function test_assertFileNotEqualsIgnoringCase_passes(): void
    {
        // this test verifies that the assertion passes
        // when two files differ even after case
        // normalization

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertFileNotEqualsIgnoringCase(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertFileNotEqualsIgnoringCase
     */
    #[TestDox('assertFileNotEqualsIgnoringCase() throws when files are equal ignoring case')]
    public function test_assertFileNotEqualsIgnoringCase_throws(): void
    {
        // this test verifies that the assertion throws
        // when two files are equal after case
        // normalization

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEqualsIgnoringCase(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello-uppercase.txt',
        );
    }

    #[TestDox('assertFileEquals() throws InvalidArgumentException when file is not readable')]
    public function test_assertFileEquals_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileEquals throws
        // InvalidArgumentException when the expected
        // file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEquals(
            expected: '/nonexistent/path/file.txt',
            actual: __FILE__,
        );
    }

    #[TestDox('assertFileEqualsCanonicalizing() throws InvalidArgumentException when file is not readable')]
    public function test_assertFileEqualsCanonicalizing_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileEqualsCanonicalizing
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEqualsCanonicalizing(
            expected: '/nonexistent/path/file.txt',
            actual: __FILE__,
        );
    }

    #[TestDox('assertFileEqualsIgnoringCase() throws InvalidArgumentException when file is not readable')]
    public function test_assertFileEqualsIgnoringCase_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileEqualsIgnoringCase throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEqualsIgnoringCase(
            expected: '/nonexistent/path/file.txt',
            actual: __FILE__,
        );
    }

    #[TestDox('assertFileNotEquals() throws InvalidArgumentException when file is not readable')]
    public function test_assertFileNotEquals_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileNotEquals throws
        // InvalidArgumentException when the expected
        // file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEquals(
            expected: '/nonexistent/path/file.txt',
            actual: __FILE__,
        );
    }

    #[TestDox('assertFileNotEqualsCanonicalizing() throws InvalidArgumentException when file is not readable')]
    public function test_assertFileNotEqualsCanonicalizing_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileNotEqualsCanonicalizing
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEqualsCanonicalizing(
            expected: '/nonexistent/path/file.txt',
            actual: __FILE__,
        );
    }

    #[TestDox('assertFileNotEqualsIgnoringCase() throws InvalidArgumentException when file is not readable')]
    public function test_assertFileNotEqualsIgnoringCase_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertFileNotEqualsIgnoringCase
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEqualsIgnoringCase(
            expected: '/nonexistent/path/file.txt',
            actual: __FILE__,
        );
    }

    // ================================================================
    //
    // String/File Content Equality Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertStringEqualsFile
     */
    #[TestDox('assertStringEqualsFile() passes when string equals file contents')]
    public function test_assertStringEqualsFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string equals the file contents

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertStringEqualsFile(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Hello World',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringEqualsFile
     */
    #[TestDox('assertStringEqualsFile() throws when string does not equal file contents')]
    public function test_assertStringEqualsFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string does not equal file contents

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFile(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );
    }

    /**
     * @covers ::assertStringEqualsFileCanonicalizing
     */
    #[TestDox('assertStringEqualsFileCanonicalizing() passes when string equals file after sorting')]
    public function test_assertStringEqualsFileCanonicalizing_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string equals file contents after
        // sorting lines

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertStringEqualsFileCanonicalizing(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Hello World',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringEqualsFileCanonicalizing
     */
    #[TestDox('assertStringEqualsFileCanonicalizing() throws when string differs from file after sorting')]
    public function test_assertStringEqualsFileCanonicalizing_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string differs from file contents
        // even after sorting lines

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFileCanonicalizing(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );
    }

    /**
     * @covers ::assertStringEqualsFileIgnoringCase
     */
    #[TestDox('assertStringEqualsFileIgnoringCase() passes when string equals file ignoring case')]
    public function test_assertStringEqualsFileIgnoringCase_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string equals file contents after
        // case normalization

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertStringEqualsFileIgnoringCase(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'HELLO WORLD',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringEqualsFileIgnoringCase
     */
    #[TestDox('assertStringEqualsFileIgnoringCase() throws when string differs from file ignoring case')]
    public function test_assertStringEqualsFileIgnoringCase_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string differs from file contents
        // even after case normalization

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFileIgnoringCase(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );
    }

    /**
     * @covers ::assertStringNotEqualsFile
     */
    #[TestDox('assertStringNotEqualsFile() passes when string does not equal file contents')]
    public function test_assertStringNotEqualsFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string does not equal file contents

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertStringNotEqualsFile(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringNotEqualsFile
     */
    #[TestDox('assertStringNotEqualsFile() throws when string equals file contents')]
    public function test_assertStringNotEqualsFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string equals file contents

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFile(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Hello World',
        );
    }

    /**
     * @covers ::assertStringNotEqualsFileCanonicalizing
     */
    #[TestDox('assertStringNotEqualsFileCanonicalizing() passes when string differs from file after sorting')]
    public function test_assertStringNotEqualsFileCanonicalizing_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string differs from file contents
        // after sorting lines

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertStringNotEqualsFileCanonicalizing(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringNotEqualsFileCanonicalizing
     */
    #[TestDox('assertStringNotEqualsFileCanonicalizing() throws when string equals file after sorting')]
    public function test_assertStringNotEqualsFileCanonicalizing_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string equals file contents after
        // sorting lines

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFileCanonicalizing(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Hello World',
        );
    }

    /**
     * @covers ::assertStringNotEqualsFileIgnoringCase
     */
    #[TestDox('assertStringNotEqualsFileIgnoringCase() passes when string differs from file ignoring case')]
    public function test_assertStringNotEqualsFileIgnoringCase_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string differs from file contents
        // even after case normalization

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        Assert::assertStringNotEqualsFileIgnoringCase(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertStringNotEqualsFileIgnoringCase
     */
    #[TestDox('assertStringNotEqualsFileIgnoringCase() throws when string equals file ignoring case')]
    public function test_assertStringNotEqualsFileIgnoringCase_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string equals file contents after
        // case normalization

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFileIgnoringCase(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'HELLO WORLD',
        );
    }

    #[TestDox('assertStringEqualsFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertStringEqualsFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertStringEqualsFile throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFile(
            expectedFile: '/nonexistent/path/file.txt',
            actualString: 'hello',
        );
    }

    #[TestDox('assertStringEqualsFileCanonicalizing() throws InvalidArgumentException when file is not readable')]
    public function test_assertStringEqualsFileCanonicalizing_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertStringEqualsFileCanonicalizing
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFileCanonicalizing(
            expectedFile: '/nonexistent/path/file.txt',
            actualString: 'hello',
        );
    }

    #[TestDox('assertStringEqualsFileIgnoringCase() throws InvalidArgumentException when file is not readable')]
    public function test_assertStringEqualsFileIgnoringCase_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertStringEqualsFileIgnoringCase
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFileIgnoringCase(
            expectedFile: '/nonexistent/path/file.txt',
            actualString: 'hello',
        );
    }

    #[TestDox('assertStringNotEqualsFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertStringNotEqualsFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertStringNotEqualsFile throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFile(
            expectedFile: '/nonexistent/path/file.txt',
            actualString: 'hello',
        );
    }

    #[TestDox('assertStringNotEqualsFileCanonicalizing() throws InvalidArgumentException when file is not readable')]
    public function test_assertStringNotEqualsFileCanonicalizing_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that
        // assertStringNotEqualsFileCanonicalizing throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFileCanonicalizing(
            expectedFile: '/nonexistent/path/file.txt',
            actualString: 'hello',
        );
    }

    #[TestDox('assertStringNotEqualsFileIgnoringCase() throws InvalidArgumentException when file is not readable')]
    public function test_assertStringNotEqualsFileIgnoringCase_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that
        // assertStringNotEqualsFileIgnoringCase throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFileIgnoringCase(
            expectedFile: '/nonexistent/path/file.txt',
            actualString: 'hello',
        );
    }

    // ================================================================
    //
    // JSON Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertJson
     */
    #[TestDox('assertJson() passes when string is valid JSON')]
    public function test_assertJson_passes(): void
    {
        // this test verifies that the assertion passes
        // when the string is valid JSON

        Assert::assertJson(
            actual: '{"name": "test", "value": 42}',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJson
     */
    #[TestDox('assertJson() throws when string is not valid JSON')]
    public function test_assertJson_throws(): void
    {
        // this test verifies that the assertion throws
        // when the string is not valid JSON

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJson(
            actual: 'not valid json{',
        );
    }

    /**
     * @covers ::assertJsonStringEqualsJsonString
     */
    #[TestDox('assertJsonStringEqualsJsonString() passes when JSON strings are equal')]
    public function test_assertJsonStringEqualsJsonString_passes(): void
    {
        // this test verifies that the assertion passes
        // when two JSON strings decode to equivalent
        // structures (key order does not matter)

        Assert::assertJsonStringEqualsJsonString(
            expectedJson: '{"name": "test", "value": 42}',
            actualJson: '{"value": 42, "name": "test"}',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJsonStringEqualsJsonString
     */
    #[TestDox('assertJsonStringEqualsJsonString() throws when JSON strings are not equal')]
    public function test_assertJsonStringEqualsJsonString_throws(): void
    {
        // this test verifies that the assertion throws
        // when two JSON strings decode to different
        // structures

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringEqualsJsonString(
            expectedJson: '{"name": "test", "value": 42}',
            actualJson: '{"name": "other", "value": 99}',
        );
    }

    /**
     * @covers ::assertJsonStringNotEqualsJsonString
     */
    #[TestDox('assertJsonStringNotEqualsJsonString() passes when JSON strings differ')]
    public function test_assertJsonStringNotEqualsJsonString_passes(): void
    {
        // this test verifies that the assertion passes
        // when two JSON strings decode to different
        // structures

        Assert::assertJsonStringNotEqualsJsonString(
            expectedJson: '{"name": "test", "value": 42}',
            actualJson: '{"name": "other", "value": 99}',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJsonStringNotEqualsJsonString
     */
    #[TestDox('assertJsonStringNotEqualsJsonString() throws when JSON strings are equal')]
    public function test_assertJsonStringNotEqualsJsonString_throws(): void
    {
        // this test verifies that the assertion throws
        // when two JSON strings decode to equivalent
        // structures

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringNotEqualsJsonString(
            expectedJson: '{"name": "test", "value": 42}',
            actualJson: '{"value": 42, "name": "test"}',
        );
    }

    /**
     * @covers ::assertJsonStringEqualsJsonFile
     */
    #[TestDox('assertJsonStringEqualsJsonFile() passes when JSON string equals file')]
    public function test_assertJsonStringEqualsJsonFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when a JSON string decodes to the same
        // structure as the JSON file

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        Assert::assertJsonStringEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualJson: '{"value": 42, "name": "test"}',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJsonStringEqualsJsonFile
     */
    #[TestDox('assertJsonStringEqualsJsonFile() throws when JSON string does not equal file')]
    public function test_assertJsonStringEqualsJsonFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when a JSON string decodes to a different
        // structure than the JSON file

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualJson: '{"name": "other", "value": 99}',
        );
    }

    /**
     * @covers ::assertJsonStringNotEqualsJsonFile
     */
    #[TestDox('assertJsonStringNotEqualsJsonFile() passes when JSON string differs from file')]
    public function test_assertJsonStringNotEqualsJsonFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when a JSON string decodes to a different
        // structure than the JSON file

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        Assert::assertJsonStringNotEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualJson: '{"name": "other", "value": 99}',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJsonStringNotEqualsJsonFile
     */
    #[TestDox('assertJsonStringNotEqualsJsonFile() throws when JSON string equals file')]
    public function test_assertJsonStringNotEqualsJsonFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when a JSON string decodes to the same
        // structure as the JSON file

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringNotEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualJson: '{"value": 42, "name": "test"}',
        );
    }

    /**
     * @covers ::assertJsonFileEqualsJsonFile
     */
    #[TestDox('assertJsonFileEqualsJsonFile() passes when JSON files are equal')]
    public function test_assertJsonFileEqualsJsonFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when two JSON files decode to equivalent
        // structures

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        Assert::assertJsonFileEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualFile: $fixtureDir . 'valid-reordered.json',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJsonFileEqualsJsonFile
     */
    #[TestDox('assertJsonFileEqualsJsonFile() throws when JSON files differ')]
    public function test_assertJsonFileEqualsJsonFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when two JSON files decode to different
        // structures

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonFileEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualFile: $fixtureDir . 'different.json',
        );
    }

    /**
     * @covers ::assertJsonFileNotEqualsJsonFile
     */
    #[TestDox('assertJsonFileNotEqualsJsonFile() passes when JSON files differ')]
    public function test_assertJsonFileNotEqualsJsonFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when two JSON files decode to different
        // structures

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        Assert::assertJsonFileNotEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualFile: $fixtureDir . 'different.json',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertJsonFileNotEqualsJsonFile
     */
    #[TestDox('assertJsonFileNotEqualsJsonFile() throws when JSON files are equal')]
    public function test_assertJsonFileNotEqualsJsonFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when two JSON files decode to equivalent
        // structures

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonFileNotEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualFile: $fixtureDir . 'valid-reordered.json',
        );
    }

    #[TestDox('assertJsonStringEqualsJsonFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertJsonStringEqualsJsonFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertJsonStringEqualsJsonFile
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringEqualsJsonFile(
            expectedFile: '/nonexistent/path/file.json',
            actualJson: '{"key": "value"}',
        );
    }

    #[TestDox('assertJsonStringNotEqualsJsonFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertJsonStringNotEqualsJsonFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertJsonStringNotEqualsJsonFile
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringNotEqualsJsonFile(
            expectedFile: '/nonexistent/path/file.json',
            actualJson: '{"key": "value"}',
        );
    }

    #[TestDox('assertJsonFileEqualsJsonFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertJsonFileEqualsJsonFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertJsonFileEqualsJsonFile throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonFileEqualsJsonFile(
            expectedFile: '/nonexistent/path/file.json',
            actualFile: __FILE__,
        );
    }

    #[TestDox('assertJsonFileNotEqualsJsonFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertJsonFileNotEqualsJsonFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertJsonFileNotEqualsJsonFile
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonFileNotEqualsJsonFile(
            expectedFile: '/nonexistent/path/file.json',
            actualFile: __FILE__,
        );
    }

    // ================================================================
    //
    // XML Assertions
    //
    // ----------------------------------------------------------------

    /**
     * @covers ::assertXmlStringEqualsXmlString
     */
    #[TestDox('assertXmlStringEqualsXmlString() passes when XML strings are equal')]
    public function test_assertXmlStringEqualsXmlString_passes(): void
    {
        // this test verifies that the assertion passes
        // when two XML strings are semantically equal
        // (canonical XML comparison)

        Assert::assertXmlStringEqualsXmlString(
            expectedXml: '<root><item>test</item></root>',
            actualXml: "<root>\n  <item>test</item>\n</root>",
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertXmlStringEqualsXmlString
     */
    #[TestDox('assertXmlStringEqualsXmlString() throws when XML strings differ')]
    public function test_assertXmlStringEqualsXmlString_throws(): void
    {
        // this test verifies that the assertion throws
        // when two XML strings are semantically
        // different

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringEqualsXmlString(
            expectedXml: '<root><item>test</item></root>',
            actualXml: '<root><item>other</item></root>',
        );
    }

    /**
     * @covers ::assertXmlStringNotEqualsXmlString
     */
    #[TestDox('assertXmlStringNotEqualsXmlString() passes when XML strings differ')]
    public function test_assertXmlStringNotEqualsXmlString_passes(): void
    {
        // this test verifies that the assertion passes
        // when two XML strings are semantically
        // different

        Assert::assertXmlStringNotEqualsXmlString(
            expectedXml: '<root><item>test</item></root>',
            actualXml: '<root><item>other</item></root>',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertXmlStringNotEqualsXmlString
     */
    #[TestDox('assertXmlStringNotEqualsXmlString() throws when XML strings are equal')]
    public function test_assertXmlStringNotEqualsXmlString_throws(): void
    {
        // this test verifies that the assertion throws
        // when two XML strings are semantically equal

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlString(
            expectedXml: '<root><item>test</item></root>',
            actualXml: "<root>\n  <item>test</item>\n</root>",
        );
    }

    #[TestDox('assertXmlStringEqualsXmlString() throws InvalidArgumentException when XML is invalid')]
    public function test_assertXmlStringEqualsXmlString_throws_InvalidArgumentException_when_xml_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertXmlStringEqualsXmlString
        // throws InvalidArgumentException when the
        // expected XML string is not valid XML.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringEqualsXmlString(
            expectedXml: 'not valid xml',
            actualXml: '<root/>',
        );
    }

    #[TestDox('assertXmlStringNotEqualsXmlString() throws InvalidArgumentException when XML is invalid')]
    public function test_assertXmlStringNotEqualsXmlString_throws_InvalidArgumentException_when_xml_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertXmlStringNotEqualsXmlString
        // throws InvalidArgumentException when the
        // expected XML string is not valid XML.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlString(
            expectedXml: 'not valid xml',
            actualXml: '<root/>',
        );
    }

    /**
     * @covers ::assertXmlStringEqualsXmlFile
     */
    #[TestDox('assertXmlStringEqualsXmlFile() passes when XML string equals file')]
    public function test_assertXmlStringEqualsXmlFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when an XML string is semantically equal to
        // the contents of an XML file

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        Assert::assertXmlStringEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualXml: '<root><item>test</item></root>',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertXmlStringEqualsXmlFile
     */
    #[TestDox('assertXmlStringEqualsXmlFile() throws when XML string does not equal file')]
    public function test_assertXmlStringEqualsXmlFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when an XML string is semantically different
        // from the contents of an XML file

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualXml: '<root><item>other</item></root>',
        );
    }

    /**
     * @covers ::assertXmlStringNotEqualsXmlFile
     */
    #[TestDox('assertXmlStringNotEqualsXmlFile() passes when XML string differs from file')]
    public function test_assertXmlStringNotEqualsXmlFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when an XML string is semantically different
        // from the contents of an XML file

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        Assert::assertXmlStringNotEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualXml: '<root><item>other</item></root>',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertXmlStringNotEqualsXmlFile
     */
    #[TestDox('assertXmlStringNotEqualsXmlFile() throws when XML string equals file')]
    public function test_assertXmlStringNotEqualsXmlFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when an XML string is semantically equal to
        // the contents of an XML file

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualXml: '<root><item>test</item></root>',
        );
    }

    /**
     * @covers ::assertXmlFileEqualsXmlFile
     */
    #[TestDox('assertXmlFileEqualsXmlFile() passes when XML files are equal')]
    public function test_assertXmlFileEqualsXmlFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when two XML files are semantically equal

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        Assert::assertXmlFileEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualFile: $fixtureDir . 'valid-equivalent.xml',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertXmlFileEqualsXmlFile
     */
    #[TestDox('assertXmlFileEqualsXmlFile() throws when XML files differ')]
    public function test_assertXmlFileEqualsXmlFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when two XML files are semantically different

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlFileEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualFile: $fixtureDir . 'different.xml',
        );
    }

    /**
     * @covers ::assertXmlFileNotEqualsXmlFile
     */
    #[TestDox('assertXmlFileNotEqualsXmlFile() passes when XML files differ')]
    public function test_assertXmlFileNotEqualsXmlFile_passes(): void
    {
        // this test verifies that the assertion passes
        // when two XML files are semantically different

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        Assert::assertXmlFileNotEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualFile: $fixtureDir . 'different.xml',
        );

        // if we get here, the assertion passed
        $this->addToAssertionCount(1);
    }

    /**
     * @covers ::assertXmlFileNotEqualsXmlFile
     */
    #[TestDox('assertXmlFileNotEqualsXmlFile() throws when XML files are equal')]
    public function test_assertXmlFileNotEqualsXmlFile_throws(): void
    {
        // this test verifies that the assertion throws
        // when two XML files are semantically equal

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlFileNotEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualFile: $fixtureDir . 'valid-equivalent.xml',
        );
    }

    #[TestDox('assertXmlStringEqualsXmlFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertXmlStringEqualsXmlFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertXmlStringEqualsXmlFile throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringEqualsXmlFile(
            expectedFile: '/nonexistent/path/file.xml',
            actualXml: '<root/>',
        );
    }

    #[TestDox('assertXmlStringNotEqualsXmlFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertXmlStringNotEqualsXmlFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertXmlStringNotEqualsXmlFile
        // throws InvalidArgumentException when the
        // expected file does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlFile(
            expectedFile: '/nonexistent/path/file.xml',
            actualXml: '<root/>',
        );
    }

    #[TestDox('assertXmlFileEqualsXmlFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertXmlFileEqualsXmlFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertXmlFileEqualsXmlFile throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlFileEqualsXmlFile(
            expectedFile: '/nonexistent/path/file.xml',
            actualFile: __FILE__,
        );
    }

    #[TestDox('assertXmlFileNotEqualsXmlFile() throws InvalidArgumentException when file is not readable')]
    public function test_assertXmlFileNotEqualsXmlFile_throws_InvalidArgumentException_when_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test
        //
        // Verify that assertXmlFileNotEqualsXmlFile throws
        // InvalidArgumentException when the expected file
        // does not exist or is not readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlFileNotEqualsXmlFile(
            expectedFile: '/nonexistent/path/file.xml',
            actualFile: __FILE__,
        );
    }
}
