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
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use stdClass;
use StusDevKit\AssertionsKit\Assert;
use StusDevKit\AssertionsKit\Contracts\AssertApi;
use StusDevKit\AssertionsKit\Exceptions\AssertionFailedException;
use StusDevKit\AssertionsKit\Tests\Fixtures\ValueObject;
use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;

#[TestDox(Assert::class)]
class AssertTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\AssertionsKit namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import Assert by its FQN, so moving it is a breaking change
        // that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\AssertionsKit';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(Assert::class))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Assert must be a concrete class - not an interface, abstract
        // class, or trait. Callers invoke its static methods by class
        // name (`Assert::assertTrue(...)`), and interface / trait kinds
        // would break that call syntax.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Assert::class);

        // ----------------------------------------------------------------
        // perform the change

        $actualIsInterface = $reflection->isInterface();
        $actualIsAbstract = $reflection->isAbstract();
        $actualIsTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        // a concrete class means: not interface, not abstract, not trait
        $this->assertFalse($actualIsInterface);
        $this->assertFalse($actualIsAbstract);
        $this->assertFalse($actualIsTrait);
    }

    #[TestDox('implements the AssertApi contract')]
    public function test_implements_AssertApi(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Assert IS the reference implementation of AssertApi - it's
        // the point of this class. The `implements AssertApi` compile-
        // time check is what guarantees Assert's public static methods
        // match the shape pinned by AssertApiTest, which is why this
        // test file doesn't need to duplicate per-method Shape tests.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Assert::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->implementsInterface(AssertApi::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('publishes exactly the 163-method assertion surface declared by AssertApi')]
    public function test_publishes_the_expected_public_static_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Assert's published surface IS the AssertApi method set - no
        // more, no less. Pin the full list by enumeration so drift on
        // a single method name shows up as a named diff rather than
        // "expected 163, got 164".
        //
        // the enumerated list is kept alphabetically sorted so a new
        // method lands in its natural place, not at the end.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'assertArrayHasKey',
            'assertArrayIsEqualToArrayIgnoringListOfKeys',
            'assertArrayIsEqualToArrayOnlyConsideringListOfKeys',
            'assertArrayIsIdenticalToArrayIgnoringListOfKeys',
            'assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys',
            'assertArrayNotHasKey',
            'assertArraysAreEqual',
            'assertArraysAreEqualIgnoringOrder',
            'assertArraysAreIdentical',
            'assertArraysAreIdenticalIgnoringOrder',
            'assertArraysHaveEqualValues',
            'assertArraysHaveEqualValuesIgnoringOrder',
            'assertArraysHaveIdenticalValues',
            'assertArraysHaveIdenticalValuesIgnoringOrder',
            'assertContains',
            'assertContainsEquals',
            'assertContainsNotOnlyArray',
            'assertContainsNotOnlyBool',
            'assertContainsNotOnlyCallable',
            'assertContainsNotOnlyClosedResource',
            'assertContainsNotOnlyFloat',
            'assertContainsNotOnlyInstancesOf',
            'assertContainsNotOnlyInt',
            'assertContainsNotOnlyIterable',
            'assertContainsNotOnlyNull',
            'assertContainsNotOnlyNumeric',
            'assertContainsNotOnlyObject',
            'assertContainsNotOnlyResource',
            'assertContainsNotOnlyScalar',
            'assertContainsNotOnlyString',
            'assertContainsOnlyArray',
            'assertContainsOnlyBool',
            'assertContainsOnlyCallable',
            'assertContainsOnlyClosedResource',
            'assertContainsOnlyFloat',
            'assertContainsOnlyInstancesOf',
            'assertContainsOnlyInt',
            'assertContainsOnlyIterable',
            'assertContainsOnlyNull',
            'assertContainsOnlyNumeric',
            'assertContainsOnlyObject',
            'assertContainsOnlyResource',
            'assertContainsOnlyScalar',
            'assertContainsOnlyString',
            'assertCount',
            'assertDirectoryDoesNotExist',
            'assertDirectoryExists',
            'assertDirectoryIsNotReadable',
            'assertDirectoryIsNotWritable',
            'assertDirectoryIsReadable',
            'assertDirectoryIsWritable',
            'assertDoesNotMatchRegularExpression',
            'assertEmpty',
            'assertEquals',
            'assertEqualsCanonicalizing',
            'assertEqualsIgnoringCase',
            'assertEqualsWithDelta',
            'assertFalse',
            'assertFileDoesNotExist',
            'assertFileEquals',
            'assertFileEqualsCanonicalizing',
            'assertFileEqualsIgnoringCase',
            'assertFileExists',
            'assertFileIsNotReadable',
            'assertFileIsNotWritable',
            'assertFileIsReadable',
            'assertFileIsWritable',
            'assertFileMatchesFormat',
            'assertFileMatchesFormatFile',
            'assertFileNotEquals',
            'assertFileNotEqualsCanonicalizing',
            'assertFileNotEqualsIgnoringCase',
            'assertFinite',
            'assertGreaterThan',
            'assertGreaterThanOrEqual',
            'assertInfinite',
            'assertInstanceOf',
            'assertIsArray',
            'assertIsBool',
            'assertIsCallable',
            'assertIsClosedResource',
            'assertIsFloat',
            'assertIsInt',
            'assertIsIterable',
            'assertIsList',
            'assertIsNotArray',
            'assertIsNotBool',
            'assertIsNotCallable',
            'assertIsNotClosedResource',
            'assertIsNotFloat',
            'assertIsNotInt',
            'assertIsNotIterable',
            'assertIsNotNumeric',
            'assertIsNotObject',
            'assertIsNotReadable',
            'assertIsNotResource',
            'assertIsNotScalar',
            'assertIsNotString',
            'assertIsNotWritable',
            'assertIsNumeric',
            'assertIsObject',
            'assertIsReadable',
            'assertIsResource',
            'assertIsScalar',
            'assertIsString',
            'assertIsWritable',
            'assertJson',
            'assertJsonFileEqualsJsonFile',
            'assertJsonFileNotEqualsJsonFile',
            'assertJsonStringEqualsJsonFile',
            'assertJsonStringEqualsJsonString',
            'assertJsonStringNotEqualsJsonFile',
            'assertJsonStringNotEqualsJsonString',
            'assertLessThan',
            'assertLessThanOrEqual',
            'assertMatchesRegularExpression',
            'assertNan',
            'assertNotContains',
            'assertNotContainsEquals',
            'assertNotCount',
            'assertNotEmpty',
            'assertNotEquals',
            'assertNotEqualsCanonicalizing',
            'assertNotEqualsIgnoringCase',
            'assertNotEqualsWithDelta',
            'assertNotFalse',
            'assertNotInstanceOf',
            'assertNotNull',
            'assertNotSame',
            'assertNotSameSize',
            'assertNotTrue',
            'assertNull',
            'assertObjectEquals',
            'assertObjectHasProperty',
            'assertObjectNotEquals',
            'assertObjectNotHasProperty',
            'assertSame',
            'assertSameSize',
            'assertStringContainsString',
            'assertStringContainsStringIgnoringCase',
            'assertStringContainsStringIgnoringLineEndings',
            'assertStringEndsNotWith',
            'assertStringEndsWith',
            'assertStringEqualsFile',
            'assertStringEqualsFileCanonicalizing',
            'assertStringEqualsFileIgnoringCase',
            'assertStringEqualsStringIgnoringLineEndings',
            'assertStringMatchesFormat',
            'assertStringMatchesFormatFile',
            'assertStringNotContainsString',
            'assertStringNotContainsStringIgnoringCase',
            'assertStringNotEqualsFile',
            'assertStringNotEqualsFileCanonicalizing',
            'assertStringNotEqualsFileIgnoringCase',
            'assertStringStartsNotWith',
            'assertStringStartsWith',
            'assertTrue',
            'assertXmlFileEqualsXmlFile',
            'assertXmlFileNotEqualsXmlFile',
            'assertXmlStringEqualsXmlFile',
            'assertXmlStringEqualsXmlString',
            'assertXmlStringNotEqualsXmlFile',
            'assertXmlStringNotEqualsXmlString',
        ];
        $reflection = new ReflectionClass(Assert::class);

        // ----------------------------------------------------------------
        // perform the change

        // collect every public method declared directly on Assert,
        // sorted alphabetically so the comparison against $expected
        // is order-stable. We deliberately include non-static methods
        // too - if any accidental instance method lands on the class,
        // it will show up in the diff against the all-static $expected
        // list and name the offender directly.
        $actual = array_values(array_filter(
            array_map(
                static fn (ReflectionMethod $m) => (
                    $m->getDeclaringClass()->getName() === Assert::class
                ) ? $m->getName() : null,
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            ),
            static fn (?string $name) => $name !== null,
        ));
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::assertTrue() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertTrue() passes when given boolean true')]
    public function test_assertTrue_passes_on_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the sole value that satisfies `=== true` is
        // the boolean literal true, so the assertion must succeed
        // silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertTrue(true);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertTrue() throws AssertionFailedException when given boolean false')]
    public function test_assertTrue_throws_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: false is the nearest neighbour of true and
        // is the most common incorrect value the caller would want
        // flagged, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertTrue(false);
    }

    #[TestDox('::assertTrue() throws AssertionFailedException when given a truthy non-boolean')]
    public function test_assertTrue_throws_on_truthy_non_boolean(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertTrue uses strict identity (`=== true`),
        // not truthiness, so a non-empty string - which would pass
        // `if ($x)` - must still raise here. This is the whole
        // reason to reach for assertTrue over assertNotEmpty.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertTrue(1);
    }

    // ================================================================
    //
    // ::assertNotTrue() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotTrue() passes when given boolean false')]
    public function test_assertNotTrue_passes_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: false is the obvious not-true value, so the
        // assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotTrue(false);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotTrue() passes when given a truthy non-boolean')]
    public function test_assertNotTrue_passes_on_truthy_non_boolean(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertNotTrue rejects only the literal boolean
        // true. A truthy integer is NOT identical to true, so the
        // assertion must succeed - mirroring the strict-identity
        // rule of its assertTrue sibling.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotTrue(1);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotTrue() throws AssertionFailedException when given boolean true')]
    public function test_assertNotTrue_throws_on_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the literal boolean true is the one and
        // only value this assertion rejects, so passing it must
        // raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotTrue(true);
    }

    // ================================================================
    //
    // ::assertFalse() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFalse() passes when given boolean false')]
    public function test_assertFalse_passes_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the sole value that satisfies `=== false` is
        // the boolean literal false, so the assertion must succeed
        // silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFalse(false);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFalse() throws AssertionFailedException when given boolean true')]
    public function test_assertFalse_throws_on_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: true is the nearest neighbour of false and
        // is the most common incorrect value the caller would want
        // flagged, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFalse(true);
    }

    #[TestDox('::assertFalse() throws AssertionFailedException when given a falsy non-boolean')]
    public function test_assertFalse_throws_on_falsy_non_boolean(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertFalse uses strict identity (`=== false`),
        // not falsiness, so integer 0 - which would pass `if (!$x)`
        // - must still raise here. This is the whole reason to
        // reach for assertFalse over assertEmpty.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFalse(0);
    }

    // ================================================================
    //
    // ::assertNotFalse() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotFalse() passes when given boolean true')]
    public function test_assertNotFalse_passes_on_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: true is the obvious not-false value, so the
        // assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotFalse(true);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotFalse() passes when given a falsy non-boolean')]
    public function test_assertNotFalse_passes_on_falsy_non_boolean(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertNotFalse rejects only the literal
        // boolean false. Integer 0 is NOT identical to false, so
        // the assertion must succeed - this is the whole point of
        // the "did the function return the sentinel false, or some
        // other value?" contract that PHP's stream_get_contents-style
        // APIs rely on.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotFalse(0);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotFalse() throws AssertionFailedException when given boolean false')]
    public function test_assertNotFalse_throws_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the literal boolean false is the one and
        // only value this assertion rejects, so passing it must
        // raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotFalse(false);
    }

    // ================================================================
    //
    // ::assertNull() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNull() passes when given null')]
    public function test_assertNull_passes_on_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: null is the one value that satisfies
        // `=== null`, so the assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNull(null);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNull() throws AssertionFailedException when given a non-null value')]
    public function test_assertNull_throws_on_non_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: any value other than null must raise.
        // A non-empty string is a typical "something got returned
        // when nothing should have" scenario.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNull('not null');
    }

    #[TestDox('::assertNull() throws AssertionFailedException when given boolean false')]
    public function test_assertNull_throws_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertNull uses strict identity. false is
        // loosely equal to null (`false == null`) but NOT
        // identical, so the assertion must raise - this catches
        // the classic "nullable bool" bug where a caller conflates
        // the two.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNull(false);
    }

    // ================================================================
    //
    // ::assertNotNull() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotNull() passes when given a non-null value')]
    public function test_assertNotNull_passes_on_non_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: any value other than null satisfies the
        // assertion, so a non-empty string must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotNull('not null');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotNull() passes when given boolean false')]
    public function test_assertNotNull_passes_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertNotNull uses strict identity, so false
        // - which is loosely equal to null but not identical -
        // must succeed. This is the whole reason to reach for
        // assertNotNull over assertNotEmpty.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotNull(false);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotNull() throws AssertionFailedException when given null')]
    public function test_assertNotNull_throws_on_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: null is the one and only value this
        // assertion rejects, so passing it must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotNull(null);
    }

    // ================================================================
    //
    // ::assertEmpty() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertEmpty() passes when given an empty array')]
    public function test_assertEmpty_passes_on_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: assertEmpty delegates to PHP's empty(),
        // which treats an array with zero elements as empty.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEmpty([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertEmpty() passes when given the empty string')]
    public function test_assertEmpty_passes_on_empty_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: empty() treats the empty string as empty,
        // so assertEmpty must accept it too. This keeps assertEmpty
        // uniform across the full set of PHP "empty" values
        // (string, array, 0, 0.0, '0', null, false) rather than
        // restricting it to collections.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEmpty('');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertEmpty() throws AssertionFailedException when given a non-empty array')]
    public function test_assertEmpty_throws_on_non_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: any array with at least one element is
        // non-empty by empty()'s rules, so the assertion must
        // raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEmpty([1, 2, 3]);
    }

    // ================================================================
    //
    // ::assertNotEmpty() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotEmpty() passes when given a non-empty array')]
    public function test_assertNotEmpty_passes_on_non_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: an array with elements is non-empty, so
        // the assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEmpty([1, 2, 3]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotEmpty() throws AssertionFailedException when given an empty array')]
    public function test_assertNotEmpty_throws_on_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the zero-element array is the canonical
        // empty collection, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEmpty([]);
    }

    #[TestDox('::assertNotEmpty() throws AssertionFailedException when given integer zero')]
    public function test_assertNotEmpty_throws_on_zero(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: PHP's empty() treats 0 as empty, so this
        // assertion must too. Callers who want "not the integer
        // 0" should use assertGreaterThan(0, ...) instead - this
        // test pins the footgun rather than fixing it.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEmpty(0);
    }

    // ================================================================
    //
    // ::assertFinite() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFinite() passes when given a finite float')]
    public function test_assertFinite_passes_on_finite_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a plain float value is finite - neither
        // INF nor NAN - so the assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFinite(42.0);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFinite() throws AssertionFailedException when given positive infinity')]
    public function test_assertFinite_throws_on_infinity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: INF is the IEEE-754 representation of an
        // overflow or division by zero. assertFinite exists
        // specifically to catch it, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFinite(INF);
    }

    #[TestDox('::assertFinite() throws AssertionFailedException when given an integer')]
    public function test_assertFinite_throws_on_integer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: assertFinite gates on `is_float`, not
        // `is_numeric`. An integer is mathematically finite but
        // fails the type guard, so the assertion must raise.
        // Callers feeding a mixed numeric through this check need
        // to cast to float first.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFinite(42);
    }

    // ================================================================
    //
    // ::assertInfinite() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertInfinite() passes when given positive infinity')]
    public function test_assertInfinite_passes_on_infinity(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: INF is the one value this assertion is
        // designed to recognise, so it must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInfinite(INF);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertInfinite() throws AssertionFailedException when given a finite float')]
    public function test_assertInfinite_throws_on_finite_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a plain float value is finite, so the
        // assertion must raise - this is the inverse of
        // assertFinite's happy path.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInfinite(42.0);
    }

    // ================================================================
    //
    // ::assertNan() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNan() passes when given NAN')]
    public function test_assertNan_passes_on_nan(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: NAN is the IEEE-754 "not a number" sentinel
        // produced by undefined operations like 0/0 or sqrt(-1).
        // assertNan exists to detect exactly this value, so it
        // must succeed silently. Footgun: `NAN === NAN` is false,
        // which is precisely why this assertion has to use
        // is_nan() rather than strict identity.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNan(NAN);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNan() throws AssertionFailedException when given a regular float')]
    public function test_assertNan_throws_on_regular_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: any well-defined float value is
        // distinguishable from NAN, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNan(42.0);
    }

    // ================================================================
    //
    // ::assertGreaterThan() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertGreaterThan() passes when actual is strictly greater than minimum')]
    public function test_assertGreaterThan_passes_on_strictly_greater(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: 10 > 5 under PHP's `>` operator, so the
        // assertion must succeed silently.

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

    #[TestDox('::assertGreaterThan() throws AssertionFailedException when actual equals minimum')]
    public function test_assertGreaterThan_throws_on_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the relation is STRICTLY greater, so
        // equality must raise. This is the whole reason
        // assertGreaterThan exists as a sibling of
        // assertGreaterThanOrEqual.

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

    #[TestDox('::assertGreaterThan() throws AssertionFailedException when actual is less than minimum')]
    public function test_assertGreaterThan_throws_on_less(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a value below the minimum clearly fails
        // the `>` comparison, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertGreaterThan(
            minimum: 5,
            actual: 3,
        );
    }

    // ================================================================
    //
    // ::assertGreaterThanOrEqual() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertGreaterThanOrEqual() passes when actual equals minimum')]
    public function test_assertGreaterThanOrEqual_passes_on_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: equality satisfies `>=`. Accepting the
        // boundary value is the distinguishing feature of the
        // OrEqual variant over assertGreaterThan.

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

    #[TestDox('::assertGreaterThanOrEqual() passes when actual is strictly greater than minimum')]
    public function test_assertGreaterThanOrEqual_passes_on_strictly_greater(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: strictly greater also satisfies `>=`,
        // so the assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertGreaterThanOrEqual(
            minimum: 5,
            actual: 10,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertGreaterThanOrEqual() throws AssertionFailedException when actual is less than minimum')]
    public function test_assertGreaterThanOrEqual_throws_on_less(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a value below the minimum fails `>=`,
        // so the assertion must raise.

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

    // ================================================================
    //
    // ::assertLessThan() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertLessThan() passes when actual is strictly less than maximum')]
    public function test_assertLessThan_passes_on_strictly_less(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: 5 < 10 under PHP's `<` operator, so the
        // assertion must succeed silently.

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

    #[TestDox('::assertLessThan() throws AssertionFailedException when actual equals maximum')]
    public function test_assertLessThan_throws_on_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the relation is STRICTLY less, so
        // equality must raise. This is the whole reason
        // assertLessThan exists as a sibling of
        // assertLessThanOrEqual.

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

    #[TestDox('::assertLessThan() throws AssertionFailedException when actual is greater than maximum')]
    public function test_assertLessThan_throws_on_greater(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a value above the maximum clearly fails
        // the `<` comparison, so the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertLessThan(
            maximum: 5,
            actual: 10,
        );
    }

    // ================================================================
    //
    // ::assertLessThanOrEqual() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertLessThanOrEqual() passes when actual equals maximum')]
    public function test_assertLessThanOrEqual_passes_on_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: equality satisfies `<=`. Accepting the
        // boundary value is the distinguishing feature of the
        // OrEqual variant over assertLessThan.

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

    #[TestDox('::assertLessThanOrEqual() passes when actual is strictly less than maximum')]
    public function test_assertLessThanOrEqual_passes_on_strictly_less(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: strictly less also satisfies `<=`, so
        // the assertion must succeed silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertLessThanOrEqual(
            maximum: 10,
            actual: 5,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertLessThanOrEqual() throws AssertionFailedException when actual is greater than maximum')]
    public function test_assertLessThanOrEqual_throws_on_greater(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a value above the maximum fails `<=`,
        // so the assertion must raise.

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
    // ::assertIsArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsArray() passes when given an array')]
    public function test_assertIsArray_passes_when_given_an_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a real PHP array satisfies is_array(), so the
        // assertion returns silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsArray([1, 2, 3]);

        // ----------------------------------------------------------------
        // test the results

        // all done - reaching here means no exception fired.
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsArray() passes when given an empty array')]
    public function test_assertIsArray_passes_on_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: an empty array is still an array. The list/hash
        // distinction is irrelevant here - only the outer type matters.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsArray([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsArray() throws AssertionFailedException when given a non-array')]
    public function test_assertIsArray_throws_on_non_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a string is the nearest "array-ish" footgun -
        // PHP callers sometimes hand a CSV-style string where an array
        // was expected. The assertion must reject it.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsArray('string');
    }

    // ================================================================
    //
    // ::assertIsBool() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsBool() passes when given boolean true')]
    public function test_assertIsBool_passes_on_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: `true` is one of the two values of type bool,
        // so the assertion must return silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsBool(true);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsBool() passes when given boolean false')]
    public function test_assertIsBool_passes_on_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: `false` is the other value of type bool. The
        // assertion must accept it as readily as `true`.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsBool(false);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsBool() throws AssertionFailedException when given a truthy non-bool')]
    public function test_assertIsBool_throws_on_truthy_non_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: `1` is truthy but is NOT of type bool. The
        // whole point of assertIsBool over assertNotEmpty is to reject
        // truthy-but-mistyped values.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsBool(1);
    }

    // ================================================================
    //
    // ::assertIsFloat() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsFloat() passes when given a float')]
    public function test_assertIsFloat_passes_on_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: 3.14 is a float literal, so is_float() returns
        // true and the assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsFloat(3.14);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsFloat() throws AssertionFailedException when given an int')]
    public function test_assertIsFloat_throws_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: PHP treats int and float as distinct scalar
        // types. `1` is an int, not a float - the assertion must
        // reject it even though `(float) 1 === 1.0` is cheap.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsFloat(1);
    }

    // ================================================================
    //
    // ::assertIsInt() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsInt() passes when given an int')]
    public function test_assertIsInt_passes_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: 42 is an int literal; is_int() is true; the
        // assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsInt(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsInt() throws AssertionFailedException when given a numeric string')]
    public function test_assertIsInt_throws_on_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a numeric string silently wins under is_numeric()
        // but must fail is_int(). This is the footgun JSON-decoded input
        // often trips over.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsInt('string');
    }

    // ================================================================
    //
    // ::assertIsNumeric() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNumeric() passes when given a numeric string')]
    public function test_assertIsNumeric_passes_on_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the method's own selling point over assertIsInt
        // is that a numeric string counts as numeric. Lock it down.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNumeric('123');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNumeric() throws AssertionFailedException when given a non-numeric string')]
    public function test_assertIsNumeric_throws_on_non_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: "abc" contains no digits and no leading sign,
        // so is_numeric() returns false and the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNumeric('abc');
    }

    // ================================================================
    //
    // ::assertIsObject() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsObject() passes when given an object')]
    public function test_assertIsObject_passes_on_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: stdClass is the simplest object instance
        // available - the assertion must accept it without demanding
        // a specific class.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsObject(new stdClass());

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsObject() throws AssertionFailedException when given a string')]
    public function test_assertIsObject_throws_on_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a plain string is not an object, so is_object()
        // is false and the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsObject('string');
    }

    // ================================================================
    //
    // ::assertIsResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsResource() passes when given an open resource')]
    public function test_assertIsResource_passes_on_open_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: fopen() returns a resource handle. While it is
        // still open, is_resource() returns true. Close it once the
        // assertion has run so the handle does not leak.

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

    #[TestDox('::assertIsResource() throws AssertionFailedException when given a string')]
    public function test_assertIsResource_throws_on_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a string is not a resource handle, so
        // is_resource() is false and the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsResource('string');
    }

    #[TestDox('::assertIsResource() throws AssertionFailedException when given a closed resource')]
    public function test_assertIsResource_throws_on_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // here be dragons! A closed resource is of type
        // `resource (closed)` and is_resource() silently returns FALSE
        // for it - pairing with assertIsClosedResource below to prove
        // the open/closed distinction is real.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsResource($resource);
    }

    // ================================================================
    //
    // ::assertIsClosedResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsClosedResource() passes when given a closed resource')]
    public function test_assertIsClosedResource_passes_on_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: open a resource, close it, then assert. The
        // closed handle reports get_debug_type() === 'resource (closed)'
        // - the exact shape the assertion checks for.

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

    #[TestDox('::assertIsClosedResource() throws AssertionFailedException when given an open resource')]
    public function test_assertIsClosedResource_throws_on_open_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: an open resource has debug type `resource`, not
        // `resource (closed)` - the assertion must reject it so callers
        // who need "definitely closed" get a clear signal when they
        // still hold a live handle.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        try {
            Assert::assertIsClosedResource($resource);
        } finally {
            // cleanup - make sure the handle is released even when
            // the assertion raises and unwinds the stack.
            fclose($resource);
        }
    }

    #[TestDox('::assertIsClosedResource() throws AssertionFailedException when given a non-resource')]
    public function test_assertIsClosedResource_throws_on_non_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a string is not a resource at all, so it can
        // never be a closed resource. The assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsClosedResource('string');
    }

    // ================================================================
    //
    // ::assertIsString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsString() passes when given a string')]
    public function test_assertIsString_passes_on_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "hello" is a string literal, so is_string() is
        // true and the assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsString('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsString() throws AssertionFailedException when given an int')]
    public function test_assertIsString_throws_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: PHP auto-stringifies ints in many contexts,
        // but is_string() is strict. An int must be rejected.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsString(42);
    }

    // ================================================================
    //
    // ::assertIsScalar() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsScalar() passes when given an int')]
    public function test_assertIsScalar_passes_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: is_scalar() accepts int, float, string, and bool.
        // Pin one member to prove the check is live.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsScalar(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsScalar() throws AssertionFailedException when given an array')]
    public function test_assertIsScalar_throws_on_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: an array is NOT scalar. This is the most
        // common non-scalar value a caller may accidentally pass.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsScalar([]);
    }

    #[TestDox('::assertIsScalar() throws AssertionFailedException when given null')]
    public function test_assertIsScalar_throws_on_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // here be dragons! null is NOT scalar under is_scalar(). This
        // has bitten plenty of callers who expected "scalar" to mean
        // "not an array and not an object".

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsScalar(null);
    }

    // ================================================================
    //
    // ::assertIsCallable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsCallable() passes when given a built-in function name')]
    public function test_assertIsCallable_passes_on_function_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "strlen" is a resolvable function name, so
        // is_callable() is true and the assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsCallable('strlen');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsCallable() passes when given a closure')]
    public function test_assertIsCallable_passes_on_closure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: closures are first-class callables. Pin this
        // shape so a future refactor that restricted to strings-only
        // would fail loudly.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsCallable(static fn (): int => 42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsCallable() throws AssertionFailedException when given a non-existent function name')]
    public function test_assertIsCallable_throws_on_missing_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a string that does not name any defined
        // function is not callable. The assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsCallable('not_a_function');
    }

    // ================================================================
    //
    // ::assertIsIterable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsIterable() passes when given an array')]
    public function test_assertIsIterable_passes_on_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: all arrays are iterable. An empty array is the
        // minimal shape.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsIterable([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsIterable() passes when given a Traversable')]
    public function test_assertIsIterable_passes_on_traversable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a Generator is a Traversable, which is the other
        // half of `iterable`'s union. Pin it so a future refactor that
        // accidentally narrowed to is_array() would be caught.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand - a cheap Generator via a one-shot closure.
        $generator = (static function () {
            yield 1;
        })();

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsIterable($generator);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsIterable() throws AssertionFailedException when given an int')]
    public function test_assertIsIterable_throws_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: an int is neither an array nor a Traversable,
        // so is_iterable() is false and the assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsIterable(42);
    }

    // ================================================================
    //
    // ::assertIsNotArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotArray() passes when given a non-array')]
    public function test_assertIsNotArray_passes_on_non_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string is not an array, so is_array() is
        // false and the negated assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotArray('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotArray() throws AssertionFailedException when given an array')]
    public function test_assertIsNotArray_throws_on_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the value IS an array, which is exactly
        // what the negated form is built to reject.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotArray([1, 2, 3]);
    }

    // ================================================================
    //
    // ::assertIsNotBool() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotBool() passes when given a non-bool')]
    public function test_assertIsNotBool_passes_on_non_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: an int is not a bool under is_bool() - PHP
        // does not treat 1 and true as the same type here, so the
        // negated assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotBool(1);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotBool() throws AssertionFailedException when given a bool')]
    public function test_assertIsNotBool_throws_on_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: true IS a bool, so the negated assertion
        // must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotBool(true);
    }

    // ================================================================
    //
    // ::assertIsNotFloat() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotFloat() passes when given a non-float')]
    public function test_assertIsNotFloat_passes_on_non_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: an int is not a float under is_float(). PHP
        // keeps int and float as distinct types here even though
        // numeric contexts often mix them.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotFloat(1);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotFloat() throws AssertionFailedException when given a float')]
    public function test_assertIsNotFloat_throws_on_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: 3.14 is a float literal, so the negated
        // assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotFloat(3.14);
    }

    // ================================================================
    //
    // ::assertIsNotInt() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotInt() passes when given a non-int')]
    public function test_assertIsNotInt_passes_on_non_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string is not an int under is_int(), even
        // if it looks numeric. The negated assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotInt('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotInt() throws AssertionFailedException when given an int')]
    public function test_assertIsNotInt_throws_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: 42 IS an int, so the negated assertion
        // must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotInt(42);
    }

    // ================================================================
    //
    // ::assertIsNotNumeric() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotNumeric() passes when given a non-numeric string')]
    public function test_assertIsNotNumeric_passes_on_non_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "abc" has no numeric interpretation under
        // is_numeric(), so the negated assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotNumeric('abc');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotNumeric() throws AssertionFailedException when given a numeric string')]
    public function test_assertIsNotNumeric_throws_on_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! is_numeric() accepts numeric STRINGS as well as
        // int/float. "123" counts as numeric, so the negated
        // assertion must raise even though the value is a string.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotNumeric('123');
    }

    // ================================================================
    //
    // ::assertIsNotObject() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotObject() passes when given a non-object')]
    public function test_assertIsNotObject_passes_on_non_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string is not an object, so the negated
        // assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotObject('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotObject() throws AssertionFailedException when given an object')]
    public function test_assertIsNotObject_throws_on_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: stdClass IS an object, so the negated
        // assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotObject(new stdClass());
    }

    // ================================================================
    //
    // ::assertIsNotResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotResource() passes when given a non-resource')]
    public function test_assertIsNotResource_passes_on_non_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string is not a resource, so the negated
        // assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotResource('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotResource() throws AssertionFailedException when given a resource')]
    public function test_assertIsNotResource_throws_on_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a live php://memory handle IS a resource,
        // so the negated assertion must raise.

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
            // cleanup - release the handle even when the assertion
            // raises and unwinds the stack.
            fclose($resource);
        }
    }

    // ================================================================
    //
    // ::assertIsNotClosedResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotClosedResource() passes when given a non-resource value')]
    public function test_assertIsNotClosedResource_passes_on_non_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string is not a closed resource (it is
        // not a resource at all), so the negated assertion passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotClosedResource('string');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotClosedResource() throws AssertionFailedException when given a closed resource')]
    public function test_assertIsNotClosedResource_throws_on_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a resource that has been through fclose()
        // IS a closed resource, so the negated assertion must
        // raise.

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

    // ================================================================
    //
    // ::assertIsNotString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotString() passes when given a non-string')]
    public function test_assertIsNotString_passes_on_non_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: an int is not a string under is_string(),
        // so the negated assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotString(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotString() throws AssertionFailedException when given a string')]
    public function test_assertIsNotString_throws_on_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: "hello" IS a string, so the negated
        // assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotString('hello');
    }

    // ================================================================
    //
    // ::assertIsNotScalar() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotScalar() passes when given an array')]
    public function test_assertIsNotScalar_passes_on_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: an array is NOT scalar under is_scalar(),
        // so the negated assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotScalar([]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotScalar() throws AssertionFailedException when given an int')]
    public function test_assertIsNotScalar_throws_on_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: is_scalar() accepts int/float/string/bool,
        // so 42 counts as scalar and the negated assertion must
        // raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotScalar(42);
    }

    // ================================================================
    //
    // ::assertIsNotCallable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotCallable() passes when given a non-existent function name')]
    public function test_assertIsNotCallable_passes_on_missing_function(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string that does not name any defined
        // function is not callable, so the negated assertion
        // passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotCallable('not_a_function');

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotCallable() throws AssertionFailedException when given a callable function name')]
    public function test_assertIsNotCallable_throws_on_function_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: "strlen" resolves to a built-in function,
        // so is_callable() is true and the negated assertion must
        // raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotCallable('strlen');
    }

    // ================================================================
    //
    // ::assertIsNotIterable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsNotIterable() passes when given a non-iterable')]
    public function test_assertIsNotIterable_passes_on_non_iterable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: an int is neither an array nor a Traversable,
        // so is_iterable() is false and the negated assertion
        // passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotIterable(42);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsNotIterable() throws AssertionFailedException when given an array')]
    public function test_assertIsNotIterable_throws_on_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: an empty array IS iterable, so the negated
        // assertion must raise. Arrays are the most common iterable
        // shape a caller will pass.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotIterable([]);
    }

    // ================================================================
    //
    // ::assertInstanceOf() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertInstanceOf() passes when value is an instance of the expected class')]
    public function test_assertInstanceOf_passes_on_exact_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the value is an instance of exactly the class
        // named. `$value instanceof stdClass` is true and the assertion
        // passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInstanceOf(
            expected: stdClass::class,
            actual: new stdClass(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertInstanceOf() passes when value is an instance of a parent class')]
    public function test_assertInstanceOf_passes_on_parent_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! `instanceof` walks the inheritance chain, so a
        // RuntimeException IS an Exception. Pin this so a future
        // refactor that checked `get_class() === $expected` would be
        // caught.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInstanceOf(
            expected: \Exception::class,
            actual: new \RuntimeException('boom'),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertInstanceOf() passes when value is an instance of an implemented interface')]
    public function test_assertInstanceOf_passes_on_implemented_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! `instanceof` also matches implemented interfaces.
        // ArrayObject implements Countable, so an ArrayObject IS a
        // Countable. Guards the same refactor-hazard as the parent
        // class test, on the interface axis.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInstanceOf(
            expected: \Countable::class,
            actual: new \ArrayObject(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertInstanceOf() throws AssertionFailedException when value is not an instance of the expected class')]
    public function test_assertInstanceOf_throws_on_unrelated_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a plain string has no class relationship to
        // stdClass at all. The assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertInstanceOf(
            expected: stdClass::class,
            actual: 'string',
        );
    }

    // ================================================================
    //
    // ::assertNotInstanceOf() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotInstanceOf() passes when value is of an unrelated type')]
    public function test_assertNotInstanceOf_passes_on_unrelated_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a string has no `instanceof` relationship to
        // stdClass, so the negated assertion passes silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotInstanceOf(
            expected: stdClass::class,
            actual: 'string',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotInstanceOf() throws AssertionFailedException when value is an instance of the expected class')]
    public function test_assertNotInstanceOf_throws_on_exact_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the value IS a stdClass. The negated assertion
        // must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotInstanceOf(
            expected: stdClass::class,
            actual: new stdClass(),
        );
    }

    #[TestDox('::assertNotInstanceOf() throws AssertionFailedException when value is an instance of a parent class')]
    public function test_assertNotInstanceOf_throws_on_parent_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the negated form is just as sensitive to the
        // inheritance chain as the positive form. A RuntimeException
        // IS an Exception, so assertNotInstanceOf(Exception, ...) must
        // raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotInstanceOf(
            expected: \Exception::class,
            actual: new \RuntimeException('boom'),
        );
    }

    // ================================================================
    //
    // ::assertEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertEquals() passes when values compare equal under loose equality')]
    public function test_assertEquals_passes_on_loose_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // assertEquals uses PHP's `==` operator. Happy path: an
        // int and its string form are loosely equal, so the
        // assertion must succeed silently.

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

    #[TestDox('::assertEquals() throws AssertionFailedException when values are not loosely equal')]
    public function test_assertEquals_throws_when_values_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the two values cannot be made equal even
        // under loose comparison, so the assertion must raise
        // AssertionFailedException.

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

    // ================================================================
    //
    // ::assertEqualsCanonicalizing() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertEqualsCanonicalizing() passes when arrays hold the same elements in a different key order')]
    public function test_assertEqualsCanonicalizing_passes_on_reordered_arrays(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // canonicalizing sorts both sides before comparing, so
        // two arrays that differ only in key order are considered
        // equal. This is the whole reason the Canonicalizing
        // variant exists as a sibling of assertEquals.

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

    #[TestDox('::assertEqualsCanonicalizing() throws AssertionFailedException when arrays hold different values')]
    public function test_assertEqualsCanonicalizing_throws_on_different_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: canonicalizing cannot rescue arrays that
        // disagree on actual values. Sorting both sides still
        // leaves `1 !== 2`, so the assertion must raise.

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

    // ================================================================
    //
    // ::assertEqualsIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertEqualsIgnoringCase() passes when strings differ only in letter case')]
    public function test_assertEqualsIgnoringCase_passes_on_case_only_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method lowercases both strings before comparing,
        // so mixed-case vs lowercase must be treated as equal.
        // This is the distinguishing feature of the IgnoringCase
        // variant.

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

    #[TestDox('::assertEqualsIgnoringCase() throws AssertionFailedException when strings differ beyond case')]
    public function test_assertEqualsIgnoringCase_throws_on_content_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: case-folding cannot rescue strings that
        // hold different letters. "hello" and "world" remain
        // unequal once lowercased, so the assertion must raise.

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

    #[TestDox('::assertEqualsIgnoringCase() falls back to loose equality when either side is not a string')]
    public function test_assertEqualsIgnoringCase_falls_back_for_non_strings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // edge case: the case-folding path only triggers when
        // BOTH sides are strings. Mixed types fall through to
        // the same `==` semantics as assertEquals, so `1 == '1'`
        // must still succeed here.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsIgnoringCase(
            expected: 1,
            actual: '1',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertEqualsWithDelta() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertEqualsWithDelta() passes when the absolute difference is within the delta')]
    public function test_assertEqualsWithDelta_passes_when_within_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // floating-point values rarely compare exactly equal, so
        // the delta parameter provides a tolerance. Happy path:
        // |1.0 - 1.05| = 0.05, which is within 0.1.

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

    #[TestDox('::assertEqualsWithDelta() passes on the delta boundary (equality is inclusive)')]
    public function test_assertEqualsWithDelta_passes_on_boundary(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // boundary pin! the implementation uses `<=`, which means
        // the boundary itself counts as a pass. |1.0 - 1.5| = 0.5
        // exactly equals the delta, and this test locks in the
        // inclusive semantics so a future `<` refactor would fail
        // loudly. Uses values whose difference is exactly
        // representable in IEEE 754 to avoid floating-point drift
        // around the boundary.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertEqualsWithDelta(
            expected: 1.0,
            actual: 1.5,
            delta: 0.5,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertEqualsWithDelta() throws AssertionFailedException when the difference exceeds the delta')]
    public function test_assertEqualsWithDelta_throws_when_exceeding_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: |1.0 - 2.0| = 1.0, which is way outside
        // a delta of 0.1. The assertion must raise.

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

    // ================================================================
    //
    // ::assertNotEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotEquals() passes when values do not compare equal under loose equality')]
    public function test_assertNotEquals_passes_when_values_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // assertNotEquals is the polar-opposite sibling of
        // assertEquals: it passes when `!=` holds. Happy path:
        // 1 and 2 are unequal under any flavour of comparison.

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

    #[TestDox('::assertNotEquals() throws AssertionFailedException when values compare equal under loose equality')]
    public function test_assertNotEquals_throws_when_values_are_equal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the two values are loosely equal, which
        // is exactly what assertNotEquals is built to reject.

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

    #[TestDox('::assertNotEquals() throws when values differ in type but compare equal loosely')]
    public function test_assertNotEquals_throws_on_loose_but_not_strict_equality(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `1 == '1'` is true under loose comparison, so
        // assertNotEquals must reject this pair even though `===`
        // would consider them different. This is the dividing line
        // between the `Equals` and `Same` families.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEquals(
            expected: 1,
            actual: '1',
        );
    }

    // ================================================================
    //
    // ::assertNotEqualsCanonicalizing() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotEqualsCanonicalizing() passes when arrays hold different elements')]
    public function test_assertNotEqualsCanonicalizing_passes_on_different_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: sorting both sides still leaves them
        // unequal because the values themselves differ. The
        // assertion must succeed silently.

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

    #[TestDox('::assertNotEqualsCanonicalizing() throws AssertionFailedException when arrays match after sorting')]
    public function test_assertNotEqualsCanonicalizing_throws_on_reordered_arrays(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: two arrays with the same elements in a
        // different key order canonicalize to the same sorted
        // form, so assertNotEqualsCanonicalizing must raise.
        // This is the exact case the Canonicalizing variant is
        // meant to catch.

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

    // ================================================================
    //
    // ::assertNotEqualsIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotEqualsIgnoringCase() passes when strings differ beyond case')]
    public function test_assertNotEqualsIgnoringCase_passes_on_content_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "Hello" and "World" disagree on letters,
        // not just case, so they remain unequal even when
        // lowercased. The assertion must succeed.

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

    #[TestDox('::assertNotEqualsIgnoringCase() throws AssertionFailedException when strings differ only in case')]
    public function test_assertNotEqualsIgnoringCase_throws_on_case_only_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: case-folding collapses "Hello" and "hello"
        // onto the same lowercase string, so the assertion must
        // raise. This is the exact case the IgnoringCase variant
        // is designed to catch.

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

    // ================================================================
    //
    // ::assertNotEqualsWithDelta() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotEqualsWithDelta() passes when the difference exceeds the delta')]
    public function test_assertNotEqualsWithDelta_passes_when_exceeding_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: |1.0 - 2.0| = 1.0, which is well outside
        // a delta of 0.1. The values are far enough apart, so
        // the assertion must succeed.

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

    #[TestDox('::assertNotEqualsWithDelta() throws AssertionFailedException when the difference is within the delta')]
    public function test_assertNotEqualsWithDelta_throws_when_within_delta(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: |1.0 - 1.05| = 0.05, which sits inside
        // a delta of 0.1. The values are indistinguishable at
        // that tolerance, so the assertion must raise.

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

    #[TestDox('::assertNotEqualsWithDelta() throws on the delta boundary (the inclusive-pass rule flips)')]
    public function test_assertNotEqualsWithDelta_throws_on_boundary(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // boundary pin! the implementation uses `> $delta` to
        // decide pass, which means the boundary itself counts
        // as a failure. |1.0 - 1.5| = 0.5 exactly equals the
        // delta, so assertNotEqualsWithDelta must raise. Locks
        // in the complement of assertEqualsWithDelta's inclusive
        // pass rule. Uses values whose difference is exactly
        // representable in IEEE 754 to avoid floating-point drift
        // around the boundary.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotEqualsWithDelta(
            expected: 1.0,
            actual: 1.5,
            delta: 0.5,
        );
    }

    // ================================================================
    //
    // ::assertObjectEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertObjectEquals() passes when the comparator method returns true')]
    public function test_assertObjectEquals_passes_when_comparator_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // assertObjectEquals delegates equality to a method on
        // the actual object (defaulting to `equals`). Happy path:
        // both value objects hold the same value, so
        // ValueObject::equals() returns true.

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

    #[TestDox('::assertObjectEquals() throws AssertionFailedException when the comparator method returns false')]
    public function test_assertObjectEquals_throws_when_comparator_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the two value objects hold different
        // values, so ValueObject::equals() returns false and
        // the assertion must raise.

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

    #[TestDox('::assertObjectEquals() calls the comparator method on the actual object with the expected object as argument')]
    public function test_assertObjectEquals_calls_comparator_on_actual(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // delegation pin! the docblock says "on the actual
        // object", so the method is invoked as
        // `$actual->equals($expected)`. A future refactor that
        // swapped the roles would still behave identically for
        // symmetric comparators like ValueObject::equals(), so
        // we use an anonymous class whose comparator only
        // returns true if it's the receiver to prove direction.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);

        // $actual's comparator returns true only when it itself
        // is the receiver (`$this`). If assertObjectEquals
        // mistakenly swapped to `$expected->equals($actual)`,
        // the stock ValueObject::equals() on $expected would
        // still succeed symmetrically - but we'd never have
        // gone through $actual's tracker flag, so flip it here.
        $actual = new class (value: 42) extends ValueObject {
            public bool $wasReceiver = false;

            public function equals(ValueObject $other): bool
            {
                $this->wasReceiver = true;
                return $this->value === $other->value;
            }
        };

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectEquals(
            expected: $expected,
            actual: $actual,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual->wasReceiver);
    }

    #[TestDox('::assertObjectEquals() accepts a custom $method name for the comparator')]
    public function test_assertObjectEquals_accepts_custom_method_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `$method` parameter lets callers name their own
        // comparator (e.g. `isSameAs`, `matches`). Pin that the
        // custom name actually gets dispatched by handing over
        // an object whose only comparator lives under that name.

        // ----------------------------------------------------------------
        // setup your test

        $expected = new ValueObject(value: 42);
        $actual = new class (value: 42) {
            public function __construct(public readonly int $value)
            {
            }

            public function isSameAs(ValueObject $other): bool
            {
                return $this->value === $other->value;
            }
        };

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertObjectEquals(
            expected: $expected,
            actual: $actual,
            method: 'isSameAs',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertObjectEquals() throws InvalidArgumentException when the named comparator method does not exist')]
    public function test_assertObjectEquals_throws_InvalidArgumentException_when_method_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing comparator is caller error, not
        // an equality failure. Distinguish the two by raising
        // InvalidArgumentException (caller bug) rather than
        // AssertionFailedException (values disagree).

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

    // ================================================================
    //
    // ::assertObjectNotEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertObjectNotEquals() passes when the comparator method returns false')]
    public function test_assertObjectNotEquals_passes_when_comparator_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // assertObjectNotEquals is the mirror image of
        // assertObjectEquals: it passes when the comparator
        // returns false. Happy path: the two value objects
        // hold different values.

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

    #[TestDox('::assertObjectNotEquals() throws AssertionFailedException when the comparator method returns true')]
    public function test_assertObjectNotEquals_throws_when_comparator_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the two value objects hold the same
        // value, so ValueObject::equals() returns true and the
        // assertion - whose contract is "these must NOT be
        // equal" - must raise.

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

    #[TestDox('::assertObjectNotEquals() throws InvalidArgumentException when the named comparator method does not exist')]
    public function test_assertObjectNotEquals_throws_InvalidArgumentException_when_method_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! same as the Equals sibling: a missing
        // comparator is caller error, distinguished by
        // InvalidArgumentException rather than an assertion
        // failure.

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
    // ::assertSame() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertSame() passes when two ints are identical')]
    public function test_assertSame_passes_on_identical_ints(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: two int literals with the same value satisfy
        // `=== `, so the assertion passes silently.

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

    #[TestDox('::assertSame() passes when two references point to the same object')]
    public function test_assertSame_passes_on_same_object_reference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! for objects, `===` checks reference identity,
        // not value equality. Two variables referencing the exact same
        // instance must pass.

        // ----------------------------------------------------------------
        // setup your test

        $object = new stdClass();

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertSame(
            expected: $object,
            actual: $object,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertSame() throws AssertionFailedException when an int and a numeric string look alike')]
    public function test_assertSame_throws_on_int_versus_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // here be dragons! `1 == "1"` is true under loose equality,
        // but `1 === "1"` is false. This is THE difference between
        // assertSame and assertEquals. Pin it so the strict-equality
        // contract cannot silently loosen.

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

    #[TestDox('::assertSame() throws AssertionFailedException for two equal-valued but distinct object instances')]
    public function test_assertSame_throws_on_distinct_object_instances(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // here be dragons! two ValueObject(42) instances hold the same
        // value but are distinct references. assertSame checks identity,
        // not equality - the sibling assertEquals / assertObjectEquals
        // covers the value-equality case.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertSame(
            expected: new ValueObject(value: 42),
            actual: new ValueObject(value: 42),
        );
    }

    // ================================================================
    //
    // ::assertNotSame() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotSame() passes when an int and a numeric string differ only in type')]
    public function test_assertNotSame_passes_on_int_versus_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: `1 !== "1"` under strict equality, so the negated
        // assertion passes. Mirrors the assertSame int-vs-string case.

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

    #[TestDox('::assertNotSame() passes for two equal-valued but distinct object instances')]
    public function test_assertNotSame_passes_on_distinct_object_instances(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: two distinct instances fail reference identity
        // even when they hold the same value. The negated assertion
        // passes.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotSame(
            expected: new ValueObject(value: 42),
            actual: new ValueObject(value: 42),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotSame() throws AssertionFailedException when two ints are identical')]
    public function test_assertNotSame_throws_on_identical_ints(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: `1 === 1`, so the negated assertion must raise.

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

    #[TestDox('::assertNotSame() throws AssertionFailedException when two references point to the same object')]
    public function test_assertNotSame_throws_on_same_object_reference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a single instance compared against itself IS
        // the same reference. The negated assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $object = new stdClass();
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotSame(
            expected: $object,
            actual: $object,
        );
    }

    // ================================================================
    //
    // ::assertObjectHasProperty() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertObjectHasProperty() passes when the property is declared on the object')]
    public function test_assertObjectHasProperty_passes_on_declared_property(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: ValueObject declares a public `$value` property,
        // so property_exists() returns true and the assertion passes.

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

    #[TestDox('::assertObjectHasProperty() throws AssertionFailedException when the named property is absent')]
    public function test_assertObjectHasProperty_throws_on_missing_property(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: ValueObject does not declare `nonexistent`,
        // so property_exists() is false and the assertion must raise.

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

    // ================================================================
    //
    // ::assertObjectNotHasProperty() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertObjectNotHasProperty() passes when the named property is absent')]
    public function test_assertObjectNotHasProperty_passes_on_missing_property(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: `nonexistent` is not declared on ValueObject, so
        // the negated assertion passes silently.

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

    #[TestDox('::assertObjectNotHasProperty() throws AssertionFailedException when the property is declared on the object')]
    public function test_assertObjectNotHasProperty_throws_on_declared_property(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: `value` IS declared on ValueObject. The negated
        // assertion must raise.

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
    // ::assertArrayHasKey() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArrayHasKey() passes when the key exists in a plain array')]
    public function test_assertArrayHasKey_passes_when_key_exists_in_plain_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path: `$key` is a string that does appear as a
        // key in the `$array`. The assertion must return without
        // throwing. Use `addToAssertionCount()` to record the
        // "did not throw" branch as a pass.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayHasKey(
            key: 'a',
            array: ['a' => 1, 'b' => 2],
        );

        // ----------------------------------------------------------------
        // test the results

        // all done - reaching here means no exception fired.
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertArrayHasKey() passes when the key exists on an ArrayAccess object')]
    public function test_assertArrayHasKey_passes_when_key_exists_on_ArrayAccess(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method accepts `array|ArrayAccess` - locking down the
        // ArrayAccess branch guards against a future refactor that
        // accidentally short-circuits to `array_key_exists()` and
        // silently drops ArrayAccess support.

        // ----------------------------------------------------------------
        // setup your test

        // build a small ArrayAccess fixture inline - anonymous class
        // keeps the dependency local to the one test that needs it.
        $haystack = new class implements \ArrayAccess {
            /** @var array<string,int> */
            private array $data = ['a' => 1];

            public function offsetExists(mixed $offset): bool
            {
                // robustness!
                //
                // ArrayAccess types `$offset` as `mixed`, but PHP
                // arrays only accept int|string keys. Guard here
                // so phpstan can see the narrowing before we hand
                // the value to array_key_exists().
                if (!is_int($offset) && !is_string($offset)) {
                    return false;
                }

                return array_key_exists($offset, $this->data);
            }

            public function offsetGet(mixed $offset): mixed
            {
                // robustness! same narrowing as offsetExists().
                if (!is_int($offset) && !is_string($offset)) {
                    return null;
                }

                return $this->data[$offset] ?? null;
            }

            public function offsetSet(mixed $offset, mixed $value): void
            {
                // read-only fixture; ignore
            }

            public function offsetUnset(mixed $offset): void
            {
                // read-only fixture; ignore
            }
        };

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArrayHasKey(
            key: 'a',
            array: $haystack,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertArrayHasKey() throws AssertionFailedException when the key is missing')]
    public function test_assertArrayHasKey_throws_when_key_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the failing path: `$key` does not appear in `$array`, so
        // the assertion must throw AssertionFailedException. Pin the
        // exception type (not just "throws any exception") so the
        // failure surfaces through the assertion-failed exception
        // channel rather than a different exception type.

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

    #[TestDox('::assertArrayHasKey() throws InvalidArgumentException when $key is neither int nor string')]
    public function test_assertArrayHasKey_throws_InvalidArgumentException_when_key_is_not_int_or_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! PHP's native array keys are int or string
        // only, so passing (say) a float is a caller bug rather
        // than an assertion failure. The method distinguishes the
        // two by throwing InvalidArgumentException (caller bug)
        // instead of AssertionFailedException (assertion did its
        // job and rejected the input).

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

    // ================================================================
    //
    // ::assertArrayNotHasKey() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArrayNotHasKey() passes when the key is absent from a plain array')]
    public function test_assertArrayNotHasKey_passes_when_key_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the happy path for the negative assertion: `$key` does
        // NOT appear in the array, so the assertion must return
        // without throwing.

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

    #[TestDox('::assertArrayNotHasKey() throws AssertionFailedException when the key exists')]
    public function test_assertArrayNotHasKey_throws_when_key_exists(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the failing path: the key IS in the array, so the
        // "must not have key" contract is violated and the method
        // must raise AssertionFailedException.

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

    #[TestDox('::assertArrayNotHasKey() throws InvalidArgumentException when $key is neither int nor string')]
    public function test_assertArrayNotHasKey_throws_InvalidArgumentException_when_key_is_not_int_or_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! same caller-bug discrimination as
        // assertArrayHasKey - a float key is not a valid PHP
        // array key, so this is a programming error rather than
        // an assertion failure, and the exception type flags it
        // as such.

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

    // ================================================================
    //
    // ::assertIsList() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsList() passes when given a zero-indexed sequential array')]
    public function test_assertIsList_passes_when_given_a_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a "list" is PHP's term for a zero-indexed array with
        // sequential integer keys - `[1, 2, 3]`, not `['a' => 1]`.
        // This is the happy path, so the method must return
        // without throwing.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsList(
            array: [1, 2, 3],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsList() passes when given an empty array')]
    public function test_assertIsList_passes_when_given_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty array qualifies as a list under PHP's
        // `array_is_list()` rules. Pin this explicitly because the
        // edge case is easy to trip on in a future refactor
        // ("lists must have at least one item").

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsList(
            array: [],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsList() throws when given an associative array')]
    public function test_assertIsList_throws_when_given_an_associative_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // associative arrays (string keys, or non-sequential int
        // keys) are not lists. The assertion must reject them.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsList(
            array: ['a' => 1, 'b' => 2],
        );
    }

    #[TestDox('::assertIsList() throws when given a non-array value')]
    public function test_assertIsList_throws_when_given_a_non_array_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter is typed `mixed`, so the method must
        // also cope with callers who hand over, say, a string.
        // The right behaviour is still AssertionFailedException -
        // "your value isn't a list" - not a type error.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsList(
            array: 'not an array',
        );
    }

    // ================================================================
    //
    // ::assertArraysAreIdentical() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysAreIdentical() passes when both arrays match by strict equality')]
    public function test_assertArraysAreIdentical_passes_when_arrays_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // strict equality (`===`) on arrays means same keys, same
        // values, same types, same order. Pin the happy path.

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

    #[TestDox('::assertArraysAreIdentical() throws when values differ by type only')]
    public function test_assertArraysAreIdentical_throws_on_type_mismatch(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `1 === '1'` is false. This is exactly the case
        // that distinguishes assertArraysAreIdentical (strict)
        // from assertArraysAreEqual (loose), so it has to be
        // pinned here.

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

    #[TestDox('::assertArraysAreIdentical() throws when the key order differs')]
    public function test_assertArraysAreIdentical_throws_on_different_key_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // strict array equality in PHP is order-sensitive: the
        // two arrays must have keys in the same insertion order.
        // This is what separates assertArraysAreIdentical from
        // assertArraysAreIdenticalIgnoringOrder.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertArraysAreIdentical(
            expected: ['a' => 1, 'b' => 2],
            actual: ['b' => 2, 'a' => 1],
        );
    }

    // ================================================================
    //
    // ::assertArraysAreIdenticalIgnoringOrder() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysAreIdenticalIgnoringOrder() passes when arrays match after ksort()')]
    public function test_assertArraysAreIdenticalIgnoringOrder_passes_on_reorder(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the variant that sorts both sides by key before
        // comparing - so `['b' => 2, 'a' => 1]` and
        // `['a' => 1, 'b' => 2]` compare as identical.

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

    #[TestDox('::assertArraysAreIdenticalIgnoringOrder() throws when values differ by type')]
    public function test_assertArraysAreIdenticalIgnoringOrder_throws_on_type_mismatch(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "ignoring order" only relaxes order, not strictness.
        // The comparison after ksort() is still `===`, so a type
        // mismatch still fails.

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

    // ================================================================
    //
    // ::assertArraysHaveIdenticalValues() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysHaveIdenticalValues() passes when the value sequences match (ignoring keys)')]
    public function test_assertArraysHaveIdenticalValues_passes_on_same_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this variant compares `array_values()` of each side,
        // so the keys are irrelevant but value order is not.
        // `['a' => 1, 'b' => 2]` and `['x' => 1, 'y' => 2]` both
        // yield `[1, 2]` after array_values().

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

    #[TestDox('::assertArraysHaveIdenticalValues() throws when the value order differs')]
    public function test_assertArraysHaveIdenticalValues_throws_on_different_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // value order still matters in this variant - it only
        // ignores KEYS, not insertion order. `[1, 2]` and `[2, 1]`
        // must not compare as equal.

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

    // ================================================================
    //
    // ::assertArraysHaveIdenticalValuesIgnoringOrder() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysHaveIdenticalValuesIgnoringOrder() passes when both sides have the same multiset of values')]
    public function test_assertArraysHaveIdenticalValuesIgnoringOrder_passes_on_same_multiset(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the most relaxed of the "identical" family:
        // keys ignored, order ignored, only values and types
        // still strict. `[1, 2]` and `[2, 1]` both sort to
        // `[1, 2]`, so they pass.

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

    #[TestDox('::assertArraysHaveIdenticalValuesIgnoringOrder() throws when the value sets differ')]
    public function test_assertArraysHaveIdenticalValuesIgnoringOrder_throws_when_values_differ(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // still a strict comparison under the hood - sort both
        // sides, then `===`. `[1, 2]` vs `[1, 3]` must fail.

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

    // ================================================================
    //
    // ::assertArraysAreEqual() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysAreEqual() passes for loosely-equal arrays that differ only by value type')]
    public function test_assertArraysAreEqual_passes_on_loose_equality(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // loose equality (`==`) is what separates this from
        // assertArraysAreIdentical - `1 == '1'` is true, so the
        // two arrays compare equal even though their value types
        // differ.

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

    #[TestDox('::assertArraysAreEqual() throws when the arrays hold different values')]
    public function test_assertArraysAreEqual_throws_on_different_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // different values (not just different types of the same
        // value) still fail under loose equality.

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

    // ================================================================
    //
    // ::assertArraysAreEqualIgnoringOrder() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysAreEqualIgnoringOrder() passes when arrays are loosely equal after key sort')]
    public function test_assertArraysAreEqualIgnoringOrder_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // combines both relaxations: ksort both sides, then `==`.
        // So `['b' => '2', 'a' => '1']` matches `['a' => 1, 'b' => 2]`.

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

    #[TestDox('::assertArraysAreEqualIgnoringOrder() throws when the values are not loosely equal')]
    public function test_assertArraysAreEqualIgnoringOrder_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // reorder + loose-equality still isn't enough to rescue
        // genuinely different values.

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

    // ================================================================
    //
    // ::assertArraysHaveEqualValues() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysHaveEqualValues() passes when the value sequences are loosely equal')]
    public function test_assertArraysHaveEqualValues_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ignores keys, preserves value order, compares with `==`.
        // `['a' => 1]` and `['x' => '1']` both yield value
        // sequences that compare loosely-equal.

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

    #[TestDox('::assertArraysHaveEqualValues() throws when the value sequences hold different values')]
    public function test_assertArraysHaveEqualValues_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `[1]` and `[2]` are different values; loose equality
        // doesn't rescue them.

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

    // ================================================================
    //
    // ::assertArraysHaveEqualValuesIgnoringOrder() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArraysHaveEqualValuesIgnoringOrder() passes when multisets are loosely equal')]
    public function test_assertArraysHaveEqualValuesIgnoringOrder_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // most relaxed member of the family: keys ignored, order
        // ignored, types coerced. `['2', '1']` and `[1, 2]`
        // collapse to the same sorted multiset under `==`.

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

    #[TestDox('::assertArraysHaveEqualValuesIgnoringOrder() throws when the multisets hold different values')]
    public function test_assertArraysHaveEqualValuesIgnoringOrder_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `[1, 2]` vs `[1, 3]` - different multisets, fails
        // regardless of how lenient we get with order and type
        // coercion.

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

    // ================================================================
    //
    // ::assertArrayIsEqualToArrayOnlyConsideringListOfKeys() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArrayIsEqualToArrayOnlyConsideringListOfKeys() passes when the considered keys are loosely equal')]
    public function test_assertArrayIsEqualToArrayOnlyConsideringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method projects each array onto `$keysToBeConsidered`
        // via array_intersect_key and compares those slices with
        // `==`. So a difference at `'c'` is invisible when only
        // `['a', 'b']` are considered.

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

    #[TestDox('::assertArrayIsEqualToArrayOnlyConsideringListOfKeys() throws when a considered key has a different value')]
    public function test_assertArrayIsEqualToArrayOnlyConsideringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the projection does turn up a difference, the
        // assertion has to fire - otherwise "considering keys"
        // would be a no-op.

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

    // ================================================================
    //
    // ::assertArrayIsEqualToArrayIgnoringListOfKeys() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArrayIsEqualToArrayIgnoringListOfKeys() passes when the non-ignored keys are loosely equal')]
    public function test_assertArrayIsEqualToArrayIgnoringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the inverse projection - array_diff_key drops the
        // ignored keys before comparison. A difference at 'b' is
        // erased when 'b' is on the ignore list.

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

    #[TestDox('::assertArrayIsEqualToArrayIgnoringListOfKeys() throws when a non-ignored key has a different value')]
    public function test_assertArrayIsEqualToArrayIgnoringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // differences OUTSIDE the ignore list still have to
        // fail the assertion.

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

    // ================================================================
    //
    // ::assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys() passes when the considered keys are strictly identical')]
    public function test_assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the strict-comparison cousin of
        // assertArrayIsEqualToArrayOnlyConsideringListOfKeys.
        // After projection, comparison is `===`, so types must
        // match too.

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

    #[TestDox('::assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys() throws on a type mismatch within the considered keys')]
    public function test_assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `1` vs `'1'` survives loose comparison but not strict
        // comparison - the differentiator between the "equal"
        // and "identical" variants.

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

    // ================================================================
    //
    // ::assertArrayIsIdenticalToArrayIgnoringListOfKeys() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertArrayIsIdenticalToArrayIgnoringListOfKeys() passes when non-ignored keys are strictly identical')]
    public function test_assertArrayIsIdenticalToArrayIgnoringListOfKeys_passes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ignore-list projection plus strict comparison. 'b' is
        // ignored, so `['a' => 1, 'b' => 2]` and `['a' => 1, 'b' => 99]`
        // collapse to matching `['a' => 1]` slices.

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

    #[TestDox('::assertArrayIsIdenticalToArrayIgnoringListOfKeys() throws on a type mismatch at a non-ignored key')]
    public function test_assertArrayIsIdenticalToArrayIgnoringListOfKeys_throws(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `1` vs `'1'` at 'a' is a strict-comparison failure
        // even though 'b' is ignored.

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
    // ::assertContains() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContains() passes when the needle is present by strict equality')]
    public function test_assertContains_passes_when_needle_present(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // assertContains walks the haystack looking for `$item === $needle`.
        // Happy path: the needle is in the haystack at the expected type.

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

    #[TestDox('::assertContains() accepts any iterable haystack, not just arrays')]
    public function test_assertContains_accepts_iterable_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `$haystack` parameter is typed `iterable`, so a
        // Generator, ArrayIterator, or any Traversable is legal.
        // Pin this so someone can't quietly tighten the type to
        // `array` without breaking this test.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand - feed the needle via a Generator
        $haystack = (static function (): iterable {
            yield 1;
            yield 2;
            yield 3;
        })();

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContains(
            needle: 2,
            haystack: $haystack,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContains() throws when the needle matches only loosely (different type)')]
    public function test_assertContains_throws_on_loose_match_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `'2' == 2` but `'2' !== 2`. assertContains
        // uses strict comparison, so a string needle must NOT
        // match an int haystack element. This is the difference
        // from assertContainsEquals.

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

    #[TestDox('::assertContains() throws when the haystack is empty')]
    public function test_assertContains_throws_on_empty_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty haystack trivially contains no needle. Pin
        // the edge case so a future "fast path" doesn't skip it
        // and declare victory.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContains(
            needle: 1,
            haystack: [],
        );
    }

    // ================================================================
    //
    // ::assertContainsEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsEquals() passes when the needle is in the haystack under loose equality')]
    public function test_assertContainsEquals_passes_on_loose_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the "Equals" variant uses `==`, so `'2'` matches
        // `2` inside `[1, 2, 3]`. This is exactly the case
        // assertContains rejects.

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

    #[TestDox('::assertContainsEquals() throws when the needle is absent even under loose equality')]
    public function test_assertContainsEquals_throws_when_needle_absent(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // loose equality still fails when there's no match to
        // coerce to. 99 vs [1,2,3] has no `==` hit.

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

    // ================================================================
    //
    // ::assertNotContains() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotContains() passes when the haystack has no strict match for the needle')]
    public function test_assertNotContains_passes_on_no_strict_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the negative assertion's happy path: no haystack
        // element is strictly equal to the needle. `'2'` vs
        // `[1, 2, 3]` qualifies - there's a loose match but
        // strict comparison rejects it.

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

    #[TestDox('::assertNotContains() passes when the haystack is empty')]
    public function test_assertNotContains_passes_on_empty_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an empty haystack can't contain anything, so the
        // "does not contain" assertion passes by vacuous truth.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertNotContains(
            needle: 1,
            haystack: [],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertNotContains() throws when the haystack has a strict match for the needle')]
    public function test_assertNotContains_throws_on_strict_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // if a strict match exists, the negative assertion
        // must fire.

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

    // ================================================================
    //
    // ::assertNotContainsEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotContainsEquals() passes when the haystack has no loose match for the needle')]
    public function test_assertNotContainsEquals_passes_on_no_loose_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path for the loose negative: no element is
        // `==` to the needle, so the haystack truly does
        // not contain it.

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

    #[TestDox('::assertNotContainsEquals() throws when the haystack has a loose match for the needle')]
    public function test_assertNotContainsEquals_throws_on_loose_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `'2'` loosely equals the int `2`, so the
        // haystack DOES contain an equivalent and the negative
        // assertion has to fire. This is what distinguishes
        // assertNotContainsEquals from assertNotContains.

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
    // ::assertContainsOnlyArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyArray() passes when every item is an array')]
    public function test_assertContainsOnlyArray_passes_when_all_are_arrays(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a homogeneous haystack of arrays satisfies
        // the "only arrays" contract.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyArray(
            haystack: [[], [1, 2]],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyArray() throws when any item is not an array')]
    public function test_assertContainsOnlyArray_throws_on_mixed_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a single non-array poisons the whole haystack. The
        // first non-array short-circuits allMatch.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyArray(
            haystack: [[], 'string'],
        );
    }

    #[TestDox('::assertContainsOnlyArray() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyArray_passes_on_empty_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! an empty haystack trivially satisfies "all
        // items are arrays" because there are no counterexamples.
        // Pin the vacuous-truth edge so a future guard doesn't
        // silently reject emptiness.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyArray(haystack: []);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyBool() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyBool() passes when every item is a bool')]
    public function test_assertContainsOnlyBool_passes_when_all_are_bools(): void
    {
        Assert::assertContainsOnlyBool(haystack: [true, false]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyBool() throws when an int sneaks in as 1 alongside true')]
    public function test_assertContainsOnlyBool_throws_on_int_alongside_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `1` casts loosely to `true`, but `is_bool(1)`
        // is false. The assertion uses strict type checks, so an
        // int must NOT pass as a bool here.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyBool(haystack: [true, 1]);
    }

    #[TestDox('::assertContainsOnlyBool() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyBool_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyBool(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyCallable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyCallable() passes when every item is a callable')]
    public function test_assertContainsOnlyCallable_passes_when_all_are_callable(): void
    {
        Assert::assertContainsOnlyCallable(haystack: ['strlen', 'strtolower']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyCallable() throws when a string does not resolve to a function')]
    public function test_assertContainsOnlyCallable_throws_on_non_callable_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // is_callable() resolves strings against the function
        // table. An unknown function name is not callable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyCallable(haystack: ['strlen', 'not_a_function']);
    }

    #[TestDox('::assertContainsOnlyCallable() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyCallable_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyCallable(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyFloat() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyFloat() passes when every item is a float')]
    public function test_assertContainsOnlyFloat_passes_when_all_are_floats(): void
    {
        Assert::assertContainsOnlyFloat(haystack: [1.0, 2.5]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyFloat() throws when an int is present alongside floats')]
    public function test_assertContainsOnlyFloat_throws_on_int_alongside_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `1` and `1.0` compare equal, but `is_float(1)`
        // is false. Ints do not pass a float-only check.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyFloat(haystack: [1.0, 1]);
    }

    #[TestDox('::assertContainsOnlyFloat() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyFloat_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyFloat(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyInt() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyInt() passes when every item is an int')]
    public function test_assertContainsOnlyInt_passes_when_all_are_ints(): void
    {
        Assert::assertContainsOnlyInt(haystack: [1, 2, 3]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyInt() throws when a string is present alongside ints')]
    public function test_assertContainsOnlyInt_throws_on_string_alongside_int(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyInt(haystack: [1, 'two']);
    }

    #[TestDox('::assertContainsOnlyInt() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyInt_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyInt(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyIterable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyIterable() passes when every item is iterable')]
    public function test_assertContainsOnlyIterable_passes_when_all_are_iterable(): void
    {
        Assert::assertContainsOnlyIterable(haystack: [[], [1]]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyIterable() throws when a scalar is present alongside arrays')]
    public function test_assertContainsOnlyIterable_throws_on_scalar_alongside_array(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyIterable(haystack: [[], 42]);
    }

    #[TestDox('::assertContainsOnlyIterable() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyIterable_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyIterable(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyNull() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyNull() passes when every item is null')]
    public function test_assertContainsOnlyNull_passes_when_all_are_null(): void
    {
        Assert::assertContainsOnlyNull(haystack: [null, null]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyNull() throws when zero is present alongside null')]
    public function test_assertContainsOnlyNull_throws_on_zero_alongside_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! `0 == null` under loose comparison, but
        // `is_null(0)` is false. The assertion uses strict
        // null-ness, so zero must NOT pass.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyNull(haystack: [null, 0]);
    }

    #[TestDox('::assertContainsOnlyNull() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyNull_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyNull(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyNumeric() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyNumeric() passes when items mix ints, numeric strings, and floats')]
    public function test_assertContainsOnlyNumeric_passes_on_mixed_numeric_forms(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // is_numeric() is intentionally permissive: it accepts
        // int, float, and numeric strings. This is the whole
        // point of the "numeric" check - it doesn't care about
        // the underlying type.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyNumeric(haystack: [1, '2', 3.0]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyNumeric() throws when a non-numeric string is present')]
    public function test_assertContainsOnlyNumeric_throws_on_non_numeric_string(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyNumeric(haystack: [1, 'abc']);
    }

    #[TestDox('::assertContainsOnlyNumeric() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyNumeric_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyNumeric(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyObject() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyObject() passes when every item is an object')]
    public function test_assertContainsOnlyObject_passes_when_all_are_objects(): void
    {
        Assert::assertContainsOnlyObject(
            haystack: [new \stdClass(), new \stdClass()],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyObject() throws when a string is present alongside objects')]
    public function test_assertContainsOnlyObject_throws_on_string_alongside_object(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyObject(
            haystack: [new \stdClass(), 'string'],
        );
    }

    #[TestDox('::assertContainsOnlyObject() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyObject_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyObject(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyResource() passes when every item is an open resource')]
    public function test_assertContainsOnlyResource_passes_when_all_are_resources(): void
    {
        // shorthand - a minimal open stream resource
        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);

        Assert::assertContainsOnlyResource(haystack: [$r]);
        $this->addToAssertionCount(1);

        fclose($r);
    }

    #[TestDox('::assertContainsOnlyResource() throws when a non-resource is present')]
    public function test_assertContainsOnlyResource_throws_on_non_resource(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyResource(haystack: ['string']);
    }

    #[TestDox('::assertContainsOnlyResource() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyResource_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyResource(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyClosedResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyClosedResource() passes when every item is a closed resource')]
    public function test_assertContainsOnlyClosedResource_passes_when_all_are_closed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // closed resources are detected via `get_debug_type()`
        // returning the string `'resource (closed)'`. This is
        // the only reliable way - `is_resource()` returns false
        // on closed resources, and there is no `is_closed_resource()`.

        // ----------------------------------------------------------------
        // setup your test

        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        fclose($r);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyClosedResource(haystack: [$r]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyClosedResource() throws when a non-resource is present')]
    public function test_assertContainsOnlyClosedResource_throws_on_non_resource(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyClosedResource(haystack: ['string']);
    }

    #[TestDox('::assertContainsOnlyClosedResource() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyClosedResource_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyClosedResource(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyScalar() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyScalar() passes when items mix ints, strings, and bools')]
    public function test_assertContainsOnlyScalar_passes_on_mixed_scalars(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // is_scalar() is a catch-all: int, float, string, bool
        // all qualify. Arrays, objects, null, and resources do
        // not. Show the inclusive side here.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyScalar(haystack: [1, 'two', true]);

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyScalar() throws when an array is present alongside scalars')]
    public function test_assertContainsOnlyScalar_throws_on_array_alongside_scalar(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyScalar(haystack: [1, []]);
    }

    #[TestDox('::assertContainsOnlyScalar() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyScalar_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyScalar(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyString() passes when every item is a string')]
    public function test_assertContainsOnlyString_passes_when_all_are_strings(): void
    {
        Assert::assertContainsOnlyString(haystack: ['a', 'b']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyString() throws when an int is present alongside strings')]
    public function test_assertContainsOnlyString_throws_on_int_alongside_string(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsOnlyString(haystack: ['a', 1]);
    }

    #[TestDox('::assertContainsOnlyString() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyString_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyString(haystack: []);
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsOnlyInstancesOf() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsOnlyInstancesOf() passes when every item is an instance of the named class')]
    public function test_assertContainsOnlyInstancesOf_passes_when_all_match(): void
    {
        Assert::assertContainsOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), new \stdClass()],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsOnlyInstancesOf() throws when a non-instance is present')]
    public function test_assertContainsOnlyInstancesOf_throws_on_non_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a plain string is not an instance of stdClass, so the
        // `instanceof` check fails on the second item.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), 'string'],
        );
    }

    #[TestDox('::assertContainsOnlyInstancesOf() passes on an empty haystack (vacuous truth)')]
    public function test_assertContainsOnlyInstancesOf_passes_on_empty_haystack(): void
    {
        Assert::assertContainsOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [],
        );
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyArray() passes when at least one item is not an array')]
    public function test_assertContainsNotOnlyArray_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyArray(haystack: [[], 'string']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyArray() throws when every item is an array')]
    public function test_assertContainsNotOnlyArray_throws_when_all_are_arrays(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyArray(haystack: [[], [1]]);
    }

    #[TestDox('::assertContainsNotOnlyArray() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyArray_throws_on_empty_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // footgun! an empty haystack vacuously satisfies "all
        // are arrays", so the negative assertion has to throw.
        // Silently wins for the positive form, silently fails
        // for the negative form - pin both edges.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsNotOnlyArray(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyBool() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyBool() passes when at least one item is not a bool')]
    public function test_assertContainsNotOnlyBool_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyBool(haystack: [true, 1]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyBool() throws when every item is a bool')]
    public function test_assertContainsNotOnlyBool_throws_when_all_are_bools(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyBool(haystack: [true, false]);
    }

    #[TestDox('::assertContainsNotOnlyBool() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyBool_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyBool(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyCallable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyCallable() passes when at least one item is not a callable')]
    public function test_assertContainsNotOnlyCallable_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyCallable(
            haystack: ['strlen', 'not_a_function'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyCallable() throws when every item is a callable')]
    public function test_assertContainsNotOnlyCallable_throws_when_all_are_callable(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyCallable(
            haystack: ['strlen', 'strtolower'],
        );
    }

    #[TestDox('::assertContainsNotOnlyCallable() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyCallable_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyCallable(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyFloat() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyFloat() passes when at least one item is not a float')]
    public function test_assertContainsNotOnlyFloat_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyFloat(haystack: [1.0, 1]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyFloat() throws when every item is a float')]
    public function test_assertContainsNotOnlyFloat_throws_when_all_are_floats(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyFloat(haystack: [1.0, 2.5]);
    }

    #[TestDox('::assertContainsNotOnlyFloat() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyFloat_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyFloat(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyInt() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyInt() passes when at least one item is not an int')]
    public function test_assertContainsNotOnlyInt_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyInt(haystack: [1, 'two']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyInt() throws when every item is an int')]
    public function test_assertContainsNotOnlyInt_throws_when_all_are_ints(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyInt(haystack: [1, 2, 3]);
    }

    #[TestDox('::assertContainsNotOnlyInt() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyInt_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyInt(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyIterable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyIterable() passes when at least one item is not iterable')]
    public function test_assertContainsNotOnlyIterable_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyIterable(haystack: [[], 42]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyIterable() throws when every item is iterable')]
    public function test_assertContainsNotOnlyIterable_throws_when_all_are_iterable(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyIterable(haystack: [[], [1]]);
    }

    #[TestDox('::assertContainsNotOnlyIterable() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyIterable_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyIterable(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyNull() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyNull() passes when at least one item is not null')]
    public function test_assertContainsNotOnlyNull_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyNull(haystack: [null, 0]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyNull() throws when every item is null')]
    public function test_assertContainsNotOnlyNull_throws_when_all_are_null(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyNull(haystack: [null, null]);
    }

    #[TestDox('::assertContainsNotOnlyNull() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyNull_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyNull(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyNumeric() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyNumeric() passes when at least one item is not numeric')]
    public function test_assertContainsNotOnlyNumeric_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyNumeric(haystack: [1, 'abc']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyNumeric() throws when every item is numeric (int and numeric string)')]
    public function test_assertContainsNotOnlyNumeric_throws_when_all_are_numeric(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyNumeric(haystack: [1, '2']);
    }

    #[TestDox('::assertContainsNotOnlyNumeric() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyNumeric_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyNumeric(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyObject() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyObject() passes when at least one item is not an object')]
    public function test_assertContainsNotOnlyObject_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyObject(
            haystack: [new \stdClass(), 'string'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyObject() throws when every item is an object')]
    public function test_assertContainsNotOnlyObject_throws_when_all_are_objects(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyObject(
            haystack: [new \stdClass(), new \stdClass()],
        );
    }

    #[TestDox('::assertContainsNotOnlyObject() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyObject_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyObject(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyResource() passes when at least one item is not a resource')]
    public function test_assertContainsNotOnlyResource_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyResource(haystack: ['string']);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyResource() throws when every item is an open resource')]
    public function test_assertContainsNotOnlyResource_throws_when_all_are_resources(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        // correctness! always close the resource even when the
        // assertion throws - leaking fds across tests leads to
        // "too many open files" under long suites.
        try {
            Assert::assertContainsNotOnlyResource(haystack: [$r]);
        } finally {
            fclose($r);
        }
    }

    #[TestDox('::assertContainsNotOnlyResource() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyResource_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyResource(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyClosedResource() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyClosedResource() passes when at least one item is not a closed resource')]
    public function test_assertContainsNotOnlyClosedResource_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyClosedResource(
            haystack: ['string'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyClosedResource() throws when every item is a closed resource')]
    public function test_assertContainsNotOnlyClosedResource_throws_when_all_are_closed(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $r = fopen('php://memory', 'r');
        self::assertIsResource($r);
        fclose($r);
        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertContainsNotOnlyClosedResource(haystack: [$r]);
    }

    #[TestDox('::assertContainsNotOnlyClosedResource() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyClosedResource_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyClosedResource(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyScalar() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyScalar() passes when at least one item is not a scalar')]
    public function test_assertContainsNotOnlyScalar_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyScalar(haystack: [1, []]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyScalar() throws when every item is a scalar')]
    public function test_assertContainsNotOnlyScalar_throws_when_all_are_scalars(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyScalar(haystack: [1, 'two']);
    }

    #[TestDox('::assertContainsNotOnlyScalar() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyScalar_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyScalar(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyString() passes when at least one item is not a string')]
    public function test_assertContainsNotOnlyString_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyString(haystack: ['a', 1]);
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyString() throws when every item is a string')]
    public function test_assertContainsNotOnlyString_throws_when_all_are_strings(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyString(haystack: ['a', 'b']);
    }

    #[TestDox('::assertContainsNotOnlyString() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyString_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyString(haystack: []);
    }

    // ================================================================
    //
    // ::assertContainsNotOnlyInstancesOf() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertContainsNotOnlyInstancesOf() passes when at least one item is not an instance of the named class')]
    public function test_assertContainsNotOnlyInstancesOf_passes_on_mixed_types(): void
    {
        Assert::assertContainsNotOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), 'string'],
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertContainsNotOnlyInstancesOf() throws when every item is an instance of the named class')]
    public function test_assertContainsNotOnlyInstancesOf_throws_when_all_match(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [new \stdClass(), new \stdClass()],
        );
    }

    #[TestDox('::assertContainsNotOnlyInstancesOf() throws on an empty haystack (vacuous truth)')]
    public function test_assertContainsNotOnlyInstancesOf_throws_on_empty_haystack(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertContainsNotOnlyInstancesOf(
            className: \stdClass::class,
            haystack: [],
        );
    }

    // ================================================================
    //
    // ::assertCount() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertCount() passes when the haystack has exactly the expected count')]
    public function test_assertCount_passes_when_count_matches(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: three elements, asking for three. The
        // assertion must succeed silently.

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

    #[TestDox('::assertCount() accepts any iterable haystack, not just arrays')]
    public function test_assertCount_accepts_iterable_haystack(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `$haystack` parameter is typed
        // `Countable|iterable`, which covers Generators and
        // other Traversables. Pin that so tightening the type
        // to `array` would break this test loudly.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand - feed three elements via a Generator
        $haystack = (static function (): iterable {
            yield 1;
            yield 2;
            yield 3;
        })();

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertCount(
            expectedCount: 3,
            haystack: $haystack,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertCount() throws AssertionFailedException when the haystack count differs from the expected count')]
    public function test_assertCount_throws_when_count_differs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: asking for five elements when the
        // haystack holds three. The assertion must raise.

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

    // ================================================================
    //
    // ::assertNotCount() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotCount() passes when the haystack count differs from the expected count')]
    public function test_assertNotCount_passes_when_count_differs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // assertNotCount is the polar-opposite sibling of
        // assertCount: it passes when the counts disagree.
        // Happy path: three elements, rejecting five.

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

    #[TestDox('::assertNotCount() throws AssertionFailedException when the haystack has exactly the expected count')]
    public function test_assertNotCount_throws_when_count_matches(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: three elements, rejecting three. The
        // counts match, which is exactly the state
        // assertNotCount is built to reject.

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
    // ::assertSameSize() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertSameSize() passes when two collections hold the same number of elements')]
    public function test_assertSameSize_passes_on_equal_counts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: both collections have 3 elements. The values
        // themselves don't need to match - only the count. Pin
        // this so a future refactor that checked array_diff() or
        // element equality would fail loudly.

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

    #[TestDox('::assertSameSize() throws AssertionFailedException when two collections hold different numbers of elements')]
    public function test_assertSameSize_throws_on_different_counts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: 3 vs 2. The counts disagree, so the
        // assertion must raise.

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

    // ================================================================
    //
    // ::assertNotSameSize() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertNotSameSize() passes when two collections hold different numbers of elements')]
    public function test_assertNotSameSize_passes_on_different_counts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: 3 vs 2. The counts disagree, which is
        // exactly what the negated form is built to accept.

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

    #[TestDox('::assertNotSameSize() throws AssertionFailedException when two collections hold the same number of elements')]
    public function test_assertNotSameSize_throws_on_equal_counts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: both collections have 3 elements. The
        // negated form must raise whenever the counts agree,
        // regardless of whether the values themselves match.

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
    // ::assertStringContainsString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringContainsString() passes when the haystack contains the needle as a substring')]
    public function test_assertStringContainsString_passes_when_haystack_contains_needle(): void
    {
        // substring match is case-sensitive and position-independent:
        // the needle appears somewhere inside the haystack.

        Assert::assertStringContainsString(
            needle: 'world',
            haystack: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringContainsString() throws AssertionFailedException when the haystack does not contain the needle')]
    public function test_assertStringContainsString_throws_when_haystack_lacks_needle(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringContainsString(
            needle: 'xyz',
            haystack: 'hello world',
        );
    }

    #[TestDox('::assertStringContainsString() is case-sensitive and throws when only the casing differs')]
    public function test_assertStringContainsString_is_case_sensitive(): void
    {
        // pin the case-sensitivity contract: this is the footgun
        // that distinguishes it from IgnoringCase. If this passes,
        // the two methods have silently merged.

        $this->expectException(AssertionFailedException::class);
        Assert::assertStringContainsString(
            needle: 'WORLD',
            haystack: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringContainsStringIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringContainsStringIgnoringCase() passes when the haystack contains the needle in a different case')]
    public function test_assertStringContainsStringIgnoringCase_passes_on_case_mismatch(): void
    {
        Assert::assertStringContainsStringIgnoringCase(
            needle: 'WORLD',
            haystack: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringContainsStringIgnoringCase() throws AssertionFailedException when the needle is absent regardless of case')]
    public function test_assertStringContainsStringIgnoringCase_throws_when_needle_absent(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringContainsStringIgnoringCase(
            needle: 'xyz',
            haystack: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringNotContainsString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringNotContainsString() passes when the haystack does not contain the needle')]
    public function test_assertStringNotContainsString_passes_when_needle_absent(): void
    {
        Assert::assertStringNotContainsString(
            needle: 'xyz',
            haystack: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringNotContainsString() throws AssertionFailedException when the haystack contains the needle')]
    public function test_assertStringNotContainsString_throws_when_needle_present(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringNotContainsString(
            needle: 'world',
            haystack: 'hello world',
        );
    }

    #[TestDox('::assertStringNotContainsString() is case-sensitive and passes when only the casing matches')]
    public function test_assertStringNotContainsString_is_case_sensitive(): void
    {
        // "WORLD" is not a substring of "hello world" because
        // the lowercase "world" has different bytes - the negative
        // assertion has to pass here.

        Assert::assertStringNotContainsString(
            needle: 'WORLD',
            haystack: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    // ================================================================
    //
    // ::assertStringNotContainsStringIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringNotContainsStringIgnoringCase() passes when the needle is absent regardless of case')]
    public function test_assertStringNotContainsStringIgnoringCase_passes_when_needle_absent(): void
    {
        Assert::assertStringNotContainsStringIgnoringCase(
            needle: 'xyz',
            haystack: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringNotContainsStringIgnoringCase() throws AssertionFailedException when the needle is present in a different case')]
    public function test_assertStringNotContainsStringIgnoringCase_throws_on_case_mismatch(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringNotContainsStringIgnoringCase(
            needle: 'WORLD',
            haystack: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringStartsWith() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringStartsWith() passes when the string starts with the prefix')]
    public function test_assertStringStartsWith_passes_on_matching_prefix(): void
    {
        Assert::assertStringStartsWith(
            prefix: 'hello',
            string: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringStartsWith() throws AssertionFailedException when the prefix appears elsewhere in the string')]
    public function test_assertStringStartsWith_throws_when_prefix_not_at_start(): void
    {
        // "world" appears in "hello world" but not at position 0.
        // Pin the start-anchoring - a substring-anywhere
        // implementation would silently pass this.

        $this->expectException(AssertionFailedException::class);
        Assert::assertStringStartsWith(
            prefix: 'world',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringStartsNotWith() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringStartsNotWith() passes when the string does not start with the prefix')]
    public function test_assertStringStartsNotWith_passes_when_prefix_not_at_start(): void
    {
        Assert::assertStringStartsNotWith(
            prefix: 'world',
            string: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringStartsNotWith() throws AssertionFailedException when the string starts with the prefix')]
    public function test_assertStringStartsNotWith_throws_on_matching_prefix(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringStartsNotWith(
            prefix: 'hello',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringEndsWith() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringEndsWith() passes when the string ends with the suffix')]
    public function test_assertStringEndsWith_passes_on_matching_suffix(): void
    {
        Assert::assertStringEndsWith(
            suffix: 'world',
            string: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringEndsWith() throws AssertionFailedException when the suffix appears elsewhere in the string')]
    public function test_assertStringEndsWith_throws_when_suffix_not_at_end(): void
    {
        // "hello" appears in "hello world" but not at the tail.
        // Pin the end-anchoring - a substring-anywhere
        // implementation would silently pass this.

        $this->expectException(AssertionFailedException::class);
        Assert::assertStringEndsWith(
            suffix: 'hello',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringEndsNotWith() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringEndsNotWith() passes when the string does not end with the suffix')]
    public function test_assertStringEndsNotWith_passes_when_suffix_not_at_end(): void
    {
        Assert::assertStringEndsNotWith(
            suffix: 'hello',
            string: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringEndsNotWith() throws AssertionFailedException when the string ends with the suffix')]
    public function test_assertStringEndsNotWith_throws_on_matching_suffix(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringEndsNotWith(
            suffix: 'world',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertStringContainsStringIgnoringLineEndings() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringContainsStringIgnoringLineEndings() passes when the needle uses LF and the haystack uses CRLF')]
    public function test_assertStringContainsStringIgnoringLineEndings_passes_on_lf_vs_crlf(): void
    {
        // pin the \n vs \r\n normalization: the two strings are
        // byte-different but semantically identical once line
        // endings are collapsed to \n.

        Assert::assertStringContainsStringIgnoringLineEndings(
            needle: "hello\nworld",
            haystack: "hello\r\nworld\r\nfoo",
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringContainsStringIgnoringLineEndings() passes when the haystack uses bare CR that gets normalized to LF')]
    public function test_assertStringContainsStringIgnoringLineEndings_passes_on_cr_vs_lf(): void
    {
        // classic-Mac-style bare \r must also normalize to \n,
        // otherwise Windows/Unix portability is incomplete.

        Assert::assertStringContainsStringIgnoringLineEndings(
            needle: "hello\nworld",
            haystack: "hello\rworld",
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringContainsStringIgnoringLineEndings() throws AssertionFailedException when the needle is absent even after normalization')]
    public function test_assertStringContainsStringIgnoringLineEndings_throws_when_needle_absent(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringContainsStringIgnoringLineEndings(
            needle: 'xyz',
            haystack: "hello\r\nworld",
        );
    }

    // ================================================================
    //
    // ::assertStringEqualsStringIgnoringLineEndings() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringEqualsStringIgnoringLineEndings() passes when the two strings differ only in LF versus CRLF')]
    public function test_assertStringEqualsStringIgnoringLineEndings_passes_on_lf_vs_crlf(): void
    {
        Assert::assertStringEqualsStringIgnoringLineEndings(
            expected: "hello\nworld",
            actual: "hello\r\nworld",
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringEqualsStringIgnoringLineEndings() passes when the two strings differ only in bare CR versus LF')]
    public function test_assertStringEqualsStringIgnoringLineEndings_passes_on_cr_vs_lf(): void
    {
        Assert::assertStringEqualsStringIgnoringLineEndings(
            expected: "hello\nworld",
            actual: "hello\rworld",
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringEqualsStringIgnoringLineEndings() throws AssertionFailedException when the strings differ in more than line endings')]
    public function test_assertStringEqualsStringIgnoringLineEndings_throws_on_content_mismatch(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringEqualsStringIgnoringLineEndings(
            expected: "hello\nworld",
            actual: "hello\r\nfoo",
        );
    }

    // ================================================================
    //
    // ::assertStringMatchesFormat() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringMatchesFormat() passes when the string matches a %s placeholder (one-or-more characters)')]
    public function test_assertStringMatchesFormat_passes_with_s_placeholder(): void
    {
        // %s stands for "one or more of any character" (greedy).
        // It's the workhorse placeholder - pin it in isolation so
        // a regression in %s handling surfaces here.

        Assert::assertStringMatchesFormat(
            format: 'Hello %s!',
            string: 'Hello World!',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringMatchesFormat() passes when the string matches a %d placeholder (one-or-more digits)')]
    public function test_assertStringMatchesFormat_passes_with_d_placeholder(): void
    {
        // %d stands for "one or more digits" - reject anything that
        // isn't numeric. The counterexample below locks down that
        // contrast.

        Assert::assertStringMatchesFormat(
            format: 'Age: %d',
            string: 'Age: 42',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringMatchesFormat() passes when the string matches combined %s and %d placeholders')]
    public function test_assertStringMatchesFormat_passes_with_combined_placeholders(): void
    {
        Assert::assertStringMatchesFormat(
            format: 'Hello %s, you are %d years old.',
            string: 'Hello World, you are 42 years old.',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringMatchesFormat() throws AssertionFailedException when a %d placeholder meets a non-numeric substring')]
    public function test_assertStringMatchesFormat_throws_when_d_meets_non_digit(): void
    {
        // counterexample to the %d contract: "forty-two" is not
        // digits, so the match has to fail. A lazier implementation
        // that treated %d like %s would silently pass this.

        $this->expectException(AssertionFailedException::class);
        Assert::assertStringMatchesFormat(
            format: 'Age: %d',
            string: 'Age: forty-two',
        );
    }

    #[TestDox('::assertStringMatchesFormat() throws AssertionFailedException when the string does not match the format')]
    public function test_assertStringMatchesFormat_throws_on_mismatch(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertStringMatchesFormat(
            format: 'Hello %s, you are %d years old.',
            string: 'Goodbye World',
        );
    }

    // ================================================================
    //
    // ::assertStringMatchesFormatFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringMatchesFormatFile() passes when the string matches the format loaded from the fixture file')]
    public function test_assertStringMatchesFormatFile_passes_on_matching_string(): void
    {
        // the fixture at tests/fixtures/format-template.txt holds
        // the format 'Hello %s, you are %d years old.' - we pin
        // the file-loading + match path together here.

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        Assert::assertStringMatchesFormatFile(
            formatFile: $formatFile,
            string: 'Hello World, you are 42 years old.',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringMatchesFormatFile() throws AssertionFailedException when the string does not match the format loaded from the file')]
    public function test_assertStringMatchesFormatFile_throws_on_mismatch(): void
    {
        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        $this->expectException(AssertionFailedException::class);
        Assert::assertStringMatchesFormatFile(
            formatFile: $formatFile,
            string: 'Goodbye World',
        );
    }

    #[TestDox('::assertStringMatchesFormatFile() throws InvalidArgumentException when the format file does not exist')]
    public function test_assertStringMatchesFormatFile_throws_InvalidArgumentException_on_missing_file(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Assert::assertStringMatchesFormatFile(
            formatFile: '/nonexistent/path/format.txt',
            string: 'hello',
        );
    }

    // ================================================================
    //
    // ::assertMatchesRegularExpression() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertMatchesRegularExpression() passes when the string matches the delimited pattern')]
    public function test_assertMatchesRegularExpression_passes_on_match(): void
    {
        // the pattern must be fully delimited (e.g. /pattern/) -
        // the method passes it straight to preg_match. A bare
        // pattern would raise a PCRE warning instead.

        Assert::assertMatchesRegularExpression(
            pattern: '/^hello/',
            string: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertMatchesRegularExpression() throws AssertionFailedException when the string does not match the pattern')]
    public function test_assertMatchesRegularExpression_throws_on_mismatch(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertMatchesRegularExpression(
            pattern: '/^world/',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertDoesNotMatchRegularExpression() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertDoesNotMatchRegularExpression() passes when the string does not match the delimited pattern')]
    public function test_assertDoesNotMatchRegularExpression_passes_on_non_match(): void
    {
        Assert::assertDoesNotMatchRegularExpression(
            pattern: '/^world/',
            string: 'hello world',
        );
        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertDoesNotMatchRegularExpression() throws AssertionFailedException when the string matches the pattern')]
    public function test_assertDoesNotMatchRegularExpression_throws_on_match(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::assertDoesNotMatchRegularExpression(
            pattern: '/^hello/',
            string: 'hello world',
        );
    }

    // ================================================================
    //
    // ::assertFileExists() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileExists() passes when the path names an existing regular file')]
    public function test_assertFileExists_passes_on_existing_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the test file itself is a regular file that
        // exists on disk. Pointing the assertion at __FILE__ is the
        // cheapest way to pin the accept case.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileExists(
            filename: __FILE__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileExists() throws AssertionFailedException when the path does not exist')]
    public function test_assertFileExists_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: nothing at /nonexistent/file.txt, so the
        // assertion must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileExists(
            filename: '/nonexistent/file.txt',
        );
    }

    #[TestDox('::assertFileExists() throws AssertionFailedException when the path names a directory')]
    public function test_assertFileExists_throws_on_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the assertion demands a regular file, not just
        // any existing path. Pointing it at a directory exercises the
        // is_file() branch of the guard - a footgun if callers assume
        // "exists" is enough.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileExists(
            filename: __DIR__,
        );
    }

    // ================================================================
    //
    // ::assertFileDoesNotExist() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileDoesNotExist() passes when no path of that name exists')]
    public function test_assertFileDoesNotExist_passes_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: /nonexistent/file.txt has nothing on disk,
        // which is exactly what the negated form is built to accept.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileDoesNotExist(
            filename: '/nonexistent/file.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileDoesNotExist() throws AssertionFailedException when the path names an existing file')]
    public function test_assertFileDoesNotExist_throws_on_existing_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the test file itself is on disk, so the
        // negated form must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileDoesNotExist(
            filename: __FILE__,
        );
    }

    #[TestDox('::assertFileDoesNotExist() throws AssertionFailedException when the path names a directory')]
    public function test_assertFileDoesNotExist_throws_on_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the method's guard is !file_exists(), which
        // returns false for ANY existing path - including a
        // directory. Callers who expect "no file of that name" to
        // accept a same-named directory will get a surprise; this
        // test pins the actual behaviour so the contract is not lost
        // in translation.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileDoesNotExist(
            filename: __DIR__,
        );
    }

    // ================================================================
    //
    // ::assertFileIsReadable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileIsReadable() passes when the path names an existing readable file')]
    public function test_assertFileIsReadable_passes_on_readable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the test file itself is readable by the test
        // runner. That pins the accept case without any filesystem
        // setup.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsReadable(
            file: __FILE__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileIsReadable() throws AssertionFailedException when the path does not exist')]
    public function test_assertFileIsReadable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a path that does not exist cannot be
        // readable. This is the most common real-world failure for
        // this assertion - the caller typo'd the path.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsReadable(
            file: '/nonexistent/file.txt',
        );
    }

    #[TestDox('::assertFileIsReadable() throws AssertionFailedException when the path names a directory')]
    public function test_assertFileIsReadable_throws_on_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_file() && is_readable(), so a
        // readable directory still fails. Pinning this keeps the
        // file-vs-path distinction visible in the spec.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsReadable(
            file: __DIR__,
        );
    }

    // ================================================================
    //
    // ::assertFileIsNotReadable() behaviour
    //
    // ----------------------------------------------------------------

    // Here Be Dragons: no "passes" test. The docker test container
    // runs as root, so is_readable() returns true even on a chmod-0
    // file. Producing a genuinely-unreadable file would require
    // dropping privilege or an unusual filesystem - neither is worth
    // the machinery. The negated form is therefore pinned only on
    // its throw paths.

    #[TestDox('::assertFileIsNotReadable() throws AssertionFailedException when the path names a readable file')]
    public function test_assertFileIsNotReadable_throws_on_readable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the test file itself is readable, so the
        // negated form must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsNotReadable(
            file: __FILE__,
        );
    }

    #[TestDox('::assertFileIsNotReadable() throws AssertionFailedException when the path does not exist')]
    public function test_assertFileIsNotReadable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_file() && !is_readable(). A
        // missing path fails is_file(), so the negated form raises
        // rather than accepting. Without this test a reader would
        // reasonably assume "not readable" covers "does not exist".
        // It does not.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsNotReadable(
            file: '/nonexistent/file.txt',
        );
    }

    // ================================================================
    //
    // ::assertFileIsWritable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileIsWritable() passes when the path names an existing writable file')]
    public function test_assertFileIsWritable_passes_on_writable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a freshly-created temp file is writable by
        // the test runner that created it. Scratch file wrapped in
        // try/finally so it is cleaned up even on throw.

        // ----------------------------------------------------------------
        // setup your test

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileIsWritable(
                file: $tmpFile,
            );

            // ----------------------------------------------------------------
            // test the results

            $this->addToAssertionCount(1);
        } finally {
            // cleanup - remove scratch file even if the assertion raised.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertFileIsWritable() throws AssertionFailedException when the path does not exist')]
    public function test_assertFileIsWritable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a path that does not exist cannot be
        // writable from this assertion's point of view - the guard
        // requires is_file().

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsWritable(
            file: '/nonexistent/file.txt',
        );
    }

    #[TestDox('::assertFileIsWritable() throws AssertionFailedException when the path names a directory')]
    public function test_assertFileIsWritable_throws_on_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_file() && is_writable().
        // sys_get_temp_dir() is writable but is not a file, so the
        // assertion rejects it. Pinning this keeps the file-vs-path
        // distinction visible.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsWritable(
            file: sys_get_temp_dir(),
        );
    }

    // ================================================================
    //
    // ::assertFileIsNotWritable() behaviour
    //
    // ----------------------------------------------------------------

    // Here Be Dragons: no "passes" test. Same footgun as
    // ::assertFileIsNotReadable() - the test container runs as root,
    // so is_writable() stays true even on a chmod-0 file. The
    // negated form is pinned only on its throw paths here.

    #[TestDox('::assertFileIsNotWritable() throws AssertionFailedException when the path names a writable file')]
    public function test_assertFileIsNotWritable_throws_on_writable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a freshly-created temp file is writable, so
        // the negated form must raise.

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
            // cleanup - scratch file removed even though the
            // assertion was expected to throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertFileIsNotWritable() throws AssertionFailedException when the path does not exist')]
    public function test_assertFileIsNotWritable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_file() && !is_writable(). A
        // missing path fails is_file(), so the negated form raises
        // rather than accepting. "Not writable" is a different claim
        // from "does not exist".

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileIsNotWritable(
            file: '/nonexistent/file.txt',
        );
    }

    // ================================================================
    //
    // ::assertDirectoryExists() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertDirectoryExists() passes when the path names an existing directory')]
    public function test_assertDirectoryExists_passes_on_existing_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the directory holding this test file is
        // obviously a real directory. Points the assertion at it to
        // pin the accept case.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryExists(
            directory: __DIR__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertDirectoryExists() throws AssertionFailedException when the path does not exist')]
    public function test_assertDirectoryExists_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: nothing at /nonexistent/directory.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryExists(
            directory: '/nonexistent/directory',
        );
    }

    #[TestDox('::assertDirectoryExists() throws AssertionFailedException when the path names a file')]
    public function test_assertDirectoryExists_throws_on_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_dir() - so a regular file
        // fails even though it exists. Pins the directory-vs-file
        // distinction explicitly.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryExists(
            directory: __FILE__,
        );
    }

    // ================================================================
    //
    // ::assertDirectoryDoesNotExist() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertDirectoryDoesNotExist() passes when the path does not exist')]
    public function test_assertDirectoryDoesNotExist_passes_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: /nonexistent/directory has nothing on disk.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryDoesNotExist(
            directory: '/nonexistent/directory',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertDirectoryDoesNotExist() passes when the path names a regular file')]
    public function test_assertDirectoryDoesNotExist_passes_on_regular_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is !file_exists() || !is_dir(), so
        // a regular file passes - there is no directory with that
        // name. Pinning this keeps the contract readable: the claim
        // is "no DIRECTORY here", not "nothing here".

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryDoesNotExist(
            directory: __FILE__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertDirectoryDoesNotExist() throws AssertionFailedException when the path names an existing directory')]
    public function test_assertDirectoryDoesNotExist_throws_on_existing_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the directory holding this test file does
        // exist, so the negated form must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryDoesNotExist(
            directory: __DIR__,
        );
    }

    // ================================================================
    //
    // ::assertDirectoryIsReadable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertDirectoryIsReadable() passes when the path names an existing readable directory')]
    public function test_assertDirectoryIsReadable_passes_on_readable_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the directory holding this test file is
        // readable by the test runner.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsReadable(
            directory: __DIR__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertDirectoryIsReadable() throws AssertionFailedException when the path does not exist')]
    public function test_assertDirectoryIsReadable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the guard requires is_dir(), which fails
        // for any non-existent path.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsReadable(
            directory: '/nonexistent/directory',
        );
    }

    #[TestDox('::assertDirectoryIsReadable() throws AssertionFailedException when the path names a regular file')]
    public function test_assertDirectoryIsReadable_throws_on_regular_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_dir() && is_readable(). A
        // regular file is readable but fails is_dir(), so the
        // assertion rejects. Pins the file-vs-directory distinction.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsReadable(
            directory: __FILE__,
        );
    }

    // ================================================================
    //
    // ::assertDirectoryIsNotReadable() behaviour
    //
    // ----------------------------------------------------------------

    // Here Be Dragons: no "passes" test. Producing an unreadable
    // directory would require dropping root privilege inside the
    // test container. The negated form is pinned only on its throw
    // paths here.

    #[TestDox('::assertDirectoryIsNotReadable() throws AssertionFailedException when the path names a readable directory')]
    public function test_assertDirectoryIsNotReadable_throws_on_readable_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the directory holding this test file is
        // readable, so the negated form must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsNotReadable(
            directory: __DIR__,
        );
    }

    #[TestDox('::assertDirectoryIsNotReadable() throws AssertionFailedException when the path does not exist')]
    public function test_assertDirectoryIsNotReadable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_dir() && !is_readable(). A
        // missing path fails is_dir(), so the negated form raises
        // rather than accepting. "Not readable" is a different claim
        // from "does not exist".

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsNotReadable(
            directory: '/nonexistent/directory',
        );
    }

    // ================================================================
    //
    // ::assertDirectoryIsWritable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertDirectoryIsWritable() passes when the path names an existing writable directory')]
    public function test_assertDirectoryIsWritable_passes_on_writable_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the system temp directory is writable by
        // definition; that is its whole purpose.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsWritable(
            directory: sys_get_temp_dir(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertDirectoryIsWritable() throws AssertionFailedException when the path does not exist')]
    public function test_assertDirectoryIsWritable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the guard requires is_dir(), which fails
        // for any non-existent path.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsWritable(
            directory: '/nonexistent/directory',
        );
    }

    #[TestDox('::assertDirectoryIsWritable() throws AssertionFailedException when the path names a regular file')]
    public function test_assertDirectoryIsWritable_throws_on_regular_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_dir() && is_writable(). A
        // writable file still fails is_dir(), so the assertion
        // rejects. Pins the file-vs-directory distinction.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            // ----------------------------------------------------------------
            // perform the change

            Assert::assertDirectoryIsWritable(
                directory: $tmpFile,
            );
        } finally {
            // cleanup - scratch file removed even though the
            // assertion was expected to throw.
            unlink($tmpFile);
        }
    }

    // ================================================================
    //
    // ::assertDirectoryIsNotWritable() behaviour
    //
    // ----------------------------------------------------------------

    // Here Be Dragons: no "passes" test. Producing a non-writable
    // directory would require dropping root privilege inside the
    // test container. The negated form is pinned only on its throw
    // paths here.

    #[TestDox('::assertDirectoryIsNotWritable() throws AssertionFailedException when the path names a writable directory')]
    public function test_assertDirectoryIsNotWritable_throws_on_writable_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the system temp directory is writable, so
        // the negated form must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsNotWritable(
            directory: sys_get_temp_dir(),
        );
    }

    #[TestDox('::assertDirectoryIsNotWritable() throws AssertionFailedException when the path does not exist')]
    public function test_assertDirectoryIsNotWritable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is is_dir() && !is_writable(). A
        // missing path fails is_dir(), so the negated form raises
        // rather than accepting. "Not writable" is a different claim
        // from "does not exist".

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertDirectoryIsNotWritable(
            directory: '/nonexistent/directory',
        );
    }

    // ================================================================
    //
    // ::assertIsReadable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsReadable() passes when the path names a readable regular file')]
    public function test_assertIsReadable_passes_on_readable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the test file itself is readable. Pins one
        // half of the polymorphic contract - this assertion accepts
        // EITHER a file OR a directory.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsReadable(
            filename: __FILE__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsReadable() passes when the path names a readable directory')]
    public function test_assertIsReadable_passes_on_readable_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! ::assertIsReadable() is polymorphic over
        // files and directories - the guard is bare is_readable(),
        // with no is_file() / is_dir() check. That is what
        // distinguishes it from ::assertFileIsReadable() and
        // ::assertDirectoryIsReadable(). This test pins the
        // directory half of that contract.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsReadable(
            filename: __DIR__,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsReadable() throws AssertionFailedException when the path is not readable')]
    public function test_assertIsReadable_throws_on_unreadable_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a non-existent path cannot be readable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsReadable(
            filename: '/nonexistent/file.txt',
        );
    }

    // ================================================================
    //
    // ::assertIsNotReadable() behaviour
    //
    // ----------------------------------------------------------------

    // Here Be Dragons: no "passes" test. The test container runs as
    // root, so is_readable() stays true on chmod-0 paths. Producing
    // a genuinely-unreadable existing path is out of reach here, so
    // the negated form is pinned only on its throw paths.

    #[TestDox('::assertIsNotReadable() throws AssertionFailedException when the path names a readable file')]
    public function test_assertIsNotReadable_throws_on_readable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the test file itself is readable, so the
        // negated form must raise.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotReadable(
            filename: __FILE__,
        );
    }

    #[TestDox('::assertIsNotReadable() throws AssertionFailedException when the path does not exist')]
    public function test_assertIsNotReadable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is file_exists() && !is_readable().
        // A missing path fails file_exists(), so the negated form
        // raises rather than accepting. "Not readable" here means
        // "the path exists but cannot be read", not "the path is
        // absent".

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotReadable(
            filename: '/nonexistent/file.txt',
        );
    }

    // ================================================================
    //
    // ::assertIsWritable() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertIsWritable() passes when the path names a writable regular file')]
    public function test_assertIsWritable_passes_on_writable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a freshly-created temp file is writable. Pins
        // one half of the polymorphic contract - this assertion
        // accepts EITHER a file OR a directory.

        // ----------------------------------------------------------------
        // setup your test

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            // ----------------------------------------------------------------
            // perform the change

            Assert::assertIsWritable(
                filename: $tmpFile,
            );

            // ----------------------------------------------------------------
            // test the results

            $this->addToAssertionCount(1);
        } finally {
            // cleanup - remove scratch file even on throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertIsWritable() passes when the path names a writable directory')]
    public function test_assertIsWritable_passes_on_writable_directory(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! ::assertIsWritable() is polymorphic over
        // files and directories - the guard is bare is_writable(),
        // with no is_file() / is_dir() check. That is what
        // distinguishes it from ::assertFileIsWritable() and
        // ::assertDirectoryIsWritable(). This test pins the
        // directory half of that contract.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsWritable(
            filename: sys_get_temp_dir(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertIsWritable() throws AssertionFailedException when the path is not writable')]
    public function test_assertIsWritable_throws_on_unwritable_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a non-existent path cannot be writable.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsWritable(
            filename: '/nonexistent/file.txt',
        );
    }

    // ================================================================
    //
    // ::assertIsNotWritable() behaviour
    //
    // ----------------------------------------------------------------

    // Here Be Dragons: no "passes" test. Same footgun as the other
    // "NotReadable" / "NotWritable" variants - root in the test
    // container keeps is_writable() returning true on chmod-0
    // paths. The negated form is pinned only on its throw paths.

    #[TestDox('::assertIsNotWritable() throws AssertionFailedException when the path names a writable file')]
    public function test_assertIsNotWritable_throws_on_writable_file(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a freshly-created temp file is writable, so
        // the negated form must raise.

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
            // cleanup - scratch file removed even though the
            // assertion was expected to throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertIsNotWritable() throws AssertionFailedException when the path does not exist')]
    public function test_assertIsNotWritable_throws_on_missing_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // correctness! the guard is file_exists() && !is_writable().
        // A missing path fails file_exists(), so the negated form
        // raises rather than accepting. "Not writable" here means
        // "the path exists but cannot be written", not "the path is
        // absent".

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertIsNotWritable(
            filename: '/nonexistent/file.txt',
        );
    }

    // ================================================================
    //
    // ::assertFileMatchesFormat() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileMatchesFormat() passes when file contents match the sprintf-style format')]
    public function test_assertFileMatchesFormat_passes_on_matching_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the file holds a concrete string that the
        // format placeholders (%s, %d) cover exactly. Scratch file
        // wrapped in try/finally so it is cleaned up even on throw.

        // ----------------------------------------------------------------
        // setup your test

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            file_put_contents(
                $tmpFile,
                'Hello World, you are 42 years old.',
            );

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileMatchesFormat(
                format: 'Hello %s, you are %d years old.',
                actualFile: $tmpFile,
            );

            // ----------------------------------------------------------------
            // test the results

            $this->addToAssertionCount(1);
        } finally {
            // cleanup - scratch file removed even on throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertFileMatchesFormat() throws AssertionFailedException when file contents do not match the format')]
    public function test_assertFileMatchesFormat_throws_on_non_matching_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: "Goodbye World" cannot be squared with a
        // format that demands "Hello %s, you are %d years old."

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
            // cleanup - scratch file removed even though the
            // assertion was expected to throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertFileMatchesFormat() throws InvalidArgumentException when the actual file is not readable')]
    public function test_assertFileMatchesFormat_throws_InvalidArgumentException_when_actual_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing actual-file path is a caller error,
        // not an assertion failure. The method distinguishes the two
        // by raising InvalidArgumentException before touching the
        // format regex.

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

    // ================================================================
    //
    // ::assertFileMatchesFormatFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileMatchesFormatFile() passes when actual file contents match the format file')]
    public function test_assertFileMatchesFormatFile_passes_on_matching_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the format template lives in a fixture file,
        // the actual file is a scratch temp file with matching
        // contents. Pins the round-trip through the filesystem.

        // ----------------------------------------------------------------
        // setup your test

        $formatFile = __DIR__
            . '/../../fixtures/format-template.txt';

        $tmpFile = tempnam(sys_get_temp_dir(), 'assert_test_');
        self::assertIsString($tmpFile);

        try {
            file_put_contents(
                $tmpFile,
                'Hello World, you are 42 years old.',
            );

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileMatchesFormatFile(
                formatFile: $formatFile,
                actualFile: $tmpFile,
            );

            // ----------------------------------------------------------------
            // test the results

            $this->addToAssertionCount(1);
        } finally {
            // cleanup - scratch file removed even on throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertFileMatchesFormatFile() throws AssertionFailedException when actual file contents do not match the format file')]
    public function test_assertFileMatchesFormatFile_throws_on_non_matching_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the fixture format demands "Hello %s, you
        // are %d years old." which "Goodbye World" does not
        // satisfy.

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
            // cleanup - scratch file removed even though the
            // assertion was expected to throw.
            unlink($tmpFile);
        }
    }

    #[TestDox('::assertFileMatchesFormatFile() throws InvalidArgumentException when the format file is not readable')]
    public function test_assertFileMatchesFormatFile_throws_InvalidArgumentException_when_format_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing format-file path is a caller error,
        // not an assertion failure. The readability check on the
        // format file runs first, so it surfaces its own exception
        // type.

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

    #[TestDox('::assertFileMatchesFormatFile() throws InvalidArgumentException when the actual file is not readable')]
    public function test_assertFileMatchesFormatFile_throws_InvalidArgumentException_when_actual_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing actual-file path is a caller error.
        // The format file is valid here, so this pins the second
        // readability check - the one that fires after the format
        // file has already been accepted.

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
    // ::assertFileEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileEquals() passes when both files hold byte-identical contents')]
    public function test_assertFileEquals_passes_on_identical_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: compare hello.txt to itself. Byte-for-byte
        // identical contents is the trivial pass case that pins
        // the basic shape of the assertion.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEquals(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileEquals() throws AssertionFailedException when file contents differ')]
    public function test_assertFileEquals_throws_on_different_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: "Hello World" vs "Goodbye World". The
        // bytes disagree, so the assertion must raise.

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

    #[TestDox('::assertFileEquals() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertFileEquals_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing expected-file path is a caller
        // error, not an assertion failure. The method must
        // distinguish the two by raising InvalidArgumentException
        // rather than AssertionFailedException.

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

    // ================================================================
    //
    // ::assertFileEqualsCanonicalizing() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileEqualsCanonicalizing() passes when two files hold the same lines in a different order')]
    public function test_assertFileEqualsCanonicalizing_passes_on_reordered_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this pins the whole point of the Canonicalizing variant:
        // raw assertFileEquals would FAIL on these two files
        // because "alpha\nbeta" and "beta\nalpha" differ byte-for-
        // byte, but canonicalizing sorts lines before comparing
        // so the two files are considered equal.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand - paired temp files wrapped in try/finally so
        // both get cleaned up even when the assertion raises.
        $expectedFile = tempnam(sys_get_temp_dir(), 'assert_canon_exp_');
        self::assertIsString($expectedFile);
        $actualFile = tempnam(sys_get_temp_dir(), 'assert_canon_act_');
        self::assertIsString($actualFile);

        try {
            file_put_contents($expectedFile, "alpha\nbeta\ngamma");
            file_put_contents($actualFile, "gamma\nalpha\nbeta");

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileEqualsCanonicalizing(
                expected: $expectedFile,
                actual: $actualFile,
            );

            // ----------------------------------------------------------------
            // test the results

            $this->addToAssertionCount(1);
        } finally {
            // cleanup - release both scratch files even on throw.
            unlink($expectedFile);
            unlink($actualFile);
        }
    }

    #[TestDox('::assertFileEqualsCanonicalizing() throws AssertionFailedException when files hold genuinely different lines')]
    public function test_assertFileEqualsCanonicalizing_throws_on_different_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: sorting cannot rescue files whose line SETS
        // disagree. "Hello World" vs "Goodbye World" produce
        // different sorted lists, so the assertion must raise.

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

    #[TestDox('::assertFileEqualsCanonicalizing() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertFileEqualsCanonicalizing_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.
        // Same contract as assertFileEquals - the Canonicalizing
        // variant must surface it the same way.

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

    // ================================================================
    //
    // ::assertFileEqualsIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileEqualsIgnoringCase() passes when two files differ only in letter case')]
    public function test_assertFileEqualsIgnoringCase_passes_on_case_only_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this pins the whole point of the IgnoringCase variant:
        // hello.txt holds "Hello World" while hello-uppercase.txt
        // holds "HELLO WORLD". Raw assertFileEquals would fail on
        // the byte difference, but case-folding brings both sides
        // to the same string.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileEqualsIgnoringCase(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'hello-uppercase.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileEqualsIgnoringCase() throws AssertionFailedException when files differ beyond case')]
    public function test_assertFileEqualsIgnoringCase_throws_on_content_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: case-folding cannot rescue files whose
        // letters themselves disagree. "Hello World" and
        // "Goodbye World" remain unequal once lowercased.

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

    #[TestDox('::assertFileEqualsIgnoringCase() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertFileEqualsIgnoringCase_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.
        // Same contract as assertFileEquals - the IgnoringCase
        // variant must surface it the same way.

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

    // ================================================================
    //
    // ::assertFileNotEquals() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileNotEquals() passes when file contents differ')]
    public function test_assertFileNotEquals_passes_on_different_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "Hello World" vs "Goodbye World". The bytes
        // disagree, which is exactly what the negated form is
        // built to accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEquals(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileNotEquals() throws AssertionFailedException when files hold byte-identical contents')]
    public function test_assertFileNotEquals_throws_on_identical_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the file equals itself, so the negated
        // form must raise.

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

    #[TestDox('::assertFileNotEquals() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertFileNotEquals_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error
        // even in the negated form. The readability check runs
        // before the comparison, so the exception type must not
        // depend on which assertion form the caller chose.

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

    // ================================================================
    //
    // ::assertFileNotEqualsCanonicalizing() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileNotEqualsCanonicalizing() passes when files hold genuinely different lines')]
    public function test_assertFileNotEqualsCanonicalizing_passes_on_different_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "Hello World" vs "Goodbye World". Sorting
        // both sides still leaves different sorted lists, so the
        // negated form must accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEqualsCanonicalizing(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileNotEqualsCanonicalizing() throws AssertionFailedException when files hold the same lines in a different order')]
    public function test_assertFileNotEqualsCanonicalizing_throws_on_reordered_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: two files whose lines sort to the same
        // list canonicalize to equal. The negated form must raise
        // - this is exactly the case the Canonicalizing variant
        // is meant to catch.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $expectedFile = tempnam(sys_get_temp_dir(), 'assert_canon_exp_');
        self::assertIsString($expectedFile);
        $actualFile = tempnam(sys_get_temp_dir(), 'assert_canon_act_');
        self::assertIsString($actualFile);

        try {
            file_put_contents($expectedFile, "alpha\nbeta\ngamma");
            file_put_contents($actualFile, "gamma\nalpha\nbeta");

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertFileNotEqualsCanonicalizing(
                expected: $expectedFile,
                actual: $actualFile,
            );
        } finally {
            // cleanup - release both scratch files even on throw.
            unlink($expectedFile);
            unlink($actualFile);
        }
    }

    #[TestDox('::assertFileNotEqualsCanonicalizing() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertFileNotEqualsCanonicalizing_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.

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

    // ================================================================
    //
    // ::assertFileNotEqualsIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertFileNotEqualsIgnoringCase() passes when files differ beyond case')]
    public function test_assertFileNotEqualsIgnoringCase_passes_on_content_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: "Hello World" vs "Goodbye World" remain
        // unequal even after lowercasing, so the negated form
        // must accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertFileNotEqualsIgnoringCase(
            expected: $fixtureDir . 'hello.txt',
            actual: $fixtureDir . 'different.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertFileNotEqualsIgnoringCase() throws AssertionFailedException when files differ only in case')]
    public function test_assertFileNotEqualsIgnoringCase_throws_on_case_only_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: "Hello World" vs "HELLO WORLD" lowercases
        // to the same string, so the negated form must raise.
        // This is the exact case the IgnoringCase variant is
        // meant to catch.

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

    #[TestDox('::assertFileNotEqualsIgnoringCase() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertFileNotEqualsIgnoringCase_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.

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
    // ::assertStringEqualsFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringEqualsFile() passes when the string is byte-identical to the file contents')]
    public function test_assertStringEqualsFile_passes_on_matching_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: hello.txt holds exactly "Hello World", so
        // the string-to-file comparison passes.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFile(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Hello World',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringEqualsFile() throws AssertionFailedException when the string differs from the file contents')]
    public function test_assertStringEqualsFile_throws_on_different_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the file holds "Hello World" but the
        // string is "Goodbye World". The bytes disagree.

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

    #[TestDox('::assertStringEqualsFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertStringEqualsFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing expected-file path is a caller
        // error, not an assertion failure.

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

    // ================================================================
    //
    // ::assertStringEqualsFileCanonicalizing() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringEqualsFileCanonicalizing() passes when the string holds the file lines in a different order')]
    public function test_assertStringEqualsFileCanonicalizing_passes_on_reordered_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this pins the Canonicalizing variant: raw
        // assertStringEqualsFile would fail because the string
        // ordering differs from the file, but canonicalizing
        // sorts both sides' lines before comparing.

        // ----------------------------------------------------------------
        // setup your test

        $expectedFile = tempnam(sys_get_temp_dir(), 'assert_canon_str_');
        self::assertIsString($expectedFile);

        try {
            file_put_contents($expectedFile, "alpha\nbeta\ngamma");

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertStringEqualsFileCanonicalizing(
                expectedFile: $expectedFile,
                actualString: "gamma\nalpha\nbeta",
            );

            // ----------------------------------------------------------------
            // test the results

            $this->addToAssertionCount(1);
        } finally {
            // cleanup - release the scratch file even on throw.
            unlink($expectedFile);
        }
    }

    #[TestDox('::assertStringEqualsFileCanonicalizing() throws AssertionFailedException when the string holds different lines')]
    public function test_assertStringEqualsFileCanonicalizing_throws_on_different_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the line sets disagree, so sorting cannot
        // make them match. The assertion must raise.

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

    #[TestDox('::assertStringEqualsFileCanonicalizing() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertStringEqualsFileCanonicalizing_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.

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

    // ================================================================
    //
    // ::assertStringEqualsFileIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringEqualsFileIgnoringCase() passes when the string differs from the file only in letter case')]
    public function test_assertStringEqualsFileIgnoringCase_passes_on_case_only_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this pins the IgnoringCase variant: hello.txt holds
        // "Hello World" but the caller is passing "HELLO WORLD".
        // Raw assertStringEqualsFile would fail on the byte
        // difference; case-folding brings both sides to the
        // same string.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringEqualsFileIgnoringCase(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'HELLO WORLD',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringEqualsFileIgnoringCase() throws AssertionFailedException when the string differs from the file beyond case')]
    public function test_assertStringEqualsFileIgnoringCase_throws_on_content_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the letters themselves disagree, so
        // case-folding cannot bridge "Hello World" and
        // "Goodbye World".

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

    #[TestDox('::assertStringEqualsFileIgnoringCase() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertStringEqualsFileIgnoringCase_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.

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

    // ================================================================
    //
    // ::assertStringNotEqualsFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringNotEqualsFile() passes when the string differs from the file contents')]
    public function test_assertStringNotEqualsFile_passes_on_different_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the file holds "Hello World" but the
        // string is "Goodbye World". The bytes disagree, which
        // is exactly what the negated form is built to accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFile(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringNotEqualsFile() throws AssertionFailedException when the string matches the file contents')]
    public function test_assertStringNotEqualsFile_throws_on_matching_contents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: hello.txt holds exactly "Hello World",
        // so the negated form must raise when given the same
        // string.

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

    #[TestDox('::assertStringNotEqualsFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertStringNotEqualsFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error
        // even in the negated form. The readability check runs
        // before the comparison.

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

    // ================================================================
    //
    // ::assertStringNotEqualsFileCanonicalizing() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringNotEqualsFileCanonicalizing() passes when the string holds different lines to the file')]
    public function test_assertStringNotEqualsFileCanonicalizing_passes_on_different_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: sorted line sets disagree, so canonicalizing
        // still treats them as unequal. The negated form accepts.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFileCanonicalizing(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringNotEqualsFileCanonicalizing() throws AssertionFailedException when the string holds the file lines in a different order')]
    public function test_assertStringNotEqualsFileCanonicalizing_throws_on_reordered_lines(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the string and file contain the same
        // lines in different order. Canonicalizing sorts both
        // sides and finds them equal, so the negated form must
        // raise - this is the exact hazard the Canonicalizing
        // variant is meant to catch.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $expectedFile = tempnam(sys_get_temp_dir(), 'assert_canon_str_');
        self::assertIsString($expectedFile);

        try {
            file_put_contents($expectedFile, "alpha\nbeta\ngamma");

            // ----------------------------------------------------------------
            // perform the change

            Assert::assertStringNotEqualsFileCanonicalizing(
                expectedFile: $expectedFile,
                actualString: "gamma\nalpha\nbeta",
            );
        } finally {
            // cleanup - release the scratch file even on throw.
            unlink($expectedFile);
        }
    }

    #[TestDox('::assertStringNotEqualsFileCanonicalizing() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertStringNotEqualsFileCanonicalizing_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.

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

    // ================================================================
    //
    // ::assertStringNotEqualsFileIgnoringCase() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertStringNotEqualsFileIgnoringCase() passes when the string differs from the file beyond case')]
    public function test_assertStringNotEqualsFileIgnoringCase_passes_on_content_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the letters themselves disagree, so
        // case-folding still leaves them unequal. The negated
        // form accepts.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/text/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertStringNotEqualsFileIgnoringCase(
            expectedFile: $fixtureDir . 'hello.txt',
            actualString: 'Goodbye World',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertStringNotEqualsFileIgnoringCase() throws AssertionFailedException when the string differs from the file only in case')]
    public function test_assertStringNotEqualsFileIgnoringCase_throws_on_case_only_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: hello.txt holds "Hello World" and the
        // caller passes "HELLO WORLD". Case-folding makes both
        // sides equal, so the negated form must raise.

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

    #[TestDox('::assertStringNotEqualsFileIgnoringCase() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertStringNotEqualsFileIgnoringCase_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.

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
    // ::assertJson() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJson() passes when the string is valid JSON')]
    public function test_assertJson_passes_on_valid_json(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: a well-formed JSON object decodes cleanly,
        // so json_last_error() reports JSON_ERROR_NONE and the
        // assertion returns silently.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJson(
            actual: '{"name": "test", "value": 42}',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJson() throws AssertionFailedException when the string is not valid JSON')]
    public function test_assertJson_throws_on_malformed_json(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: a trailing "{" leaves json_decode() in an
        // error state. assertJson must raise an assertion failure,
        // not swallow the malformed input.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJson(
            actual: 'not valid json{',
        );
    }

    // ================================================================
    //
    // ::assertJsonStringEqualsJsonString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJsonStringEqualsJsonString() passes when the two JSON strings decode to equivalent structures')]
    public function test_assertJsonStringEqualsJsonString_passes_on_reordered_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // semantic-equality pin! the two strings differ byte-for-byte
        // ("name" first vs "value" first) but decode to the same
        // object. The implementation compares the decoded values
        // with `==`, so key reordering is treated as equal - this
        // is the footgun the caller needs to know about, so we
        // lock it in.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringEqualsJsonString(
            expectedJson: '{"name": "test", "value": 42}',
            actualJson: '{"value": 42, "name": "test"}',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJsonStringEqualsJsonString() throws AssertionFailedException when the decoded structures disagree')]
    public function test_assertJsonStringEqualsJsonString_throws_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the decoded values hold different field
        // values, so even loose `==` cannot bridge them.

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

    // ================================================================
    //
    // ::assertJsonStringNotEqualsJsonString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJsonStringNotEqualsJsonString() passes when the decoded structures disagree')]
    public function test_assertJsonStringNotEqualsJsonString_passes_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the decoded values hold different field
        // values, so the negated form accepts.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringNotEqualsJsonString(
            expectedJson: '{"name": "test", "value": 42}',
            actualJson: '{"name": "other", "value": 99}',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJsonStringNotEqualsJsonString() throws AssertionFailedException when the decoded structures are equivalent despite different key order')]
    public function test_assertJsonStringNotEqualsJsonString_throws_on_reordered_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: byte-distinct JSON that decodes to the same
        // object must fail the negated form, because the underlying
        // `==` sees them as equal. This is the mirror of the
        // positive semantic-equality pin.

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

    // ================================================================
    //
    // ::assertJsonStringEqualsJsonFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJsonStringEqualsJsonFile() passes when the JSON string decodes to the same structure as the file')]
    public function test_assertJsonStringEqualsJsonFile_passes_on_reordered_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // semantic-equality pin! valid.json holds
        // `{"name": "test", "value": 42}`, while the string
        // presents the same fields in reversed order. Both decode
        // to the same object, so the assertion must accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualJson: '{"value": 42, "name": "test"}',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJsonStringEqualsJsonFile() throws AssertionFailedException when the string decodes to a different structure than the file')]
    public function test_assertJsonStringEqualsJsonFile_throws_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the string holds different field values
        // than valid.json, so decoded equality cannot hold.

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

    #[TestDox('::assertJsonStringEqualsJsonFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertJsonStringEqualsJsonFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! a missing expected-file path is a caller
        // error, not an assertion failure. The readability check
        // runs before any JSON decoding.

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

    // ================================================================
    //
    // ::assertJsonStringNotEqualsJsonFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJsonStringNotEqualsJsonFile() passes when the string decodes to a different structure than the file')]
    public function test_assertJsonStringNotEqualsJsonFile_passes_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the string holds different field values
        // than valid.json, which is exactly what the negated
        // form is built to accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonStringNotEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualJson: '{"name": "other", "value": 99}',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJsonStringNotEqualsJsonFile() throws AssertionFailedException when the string and file decode to the same structure despite different key order')]
    public function test_assertJsonStringNotEqualsJsonFile_throws_on_reordered_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: byte-distinct JSON that decodes to the
        // same object must fail the negated form. This is the
        // mirror of the positive semantic-equality pin - the
        // negated form inherits the exact same footgun.

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

    #[TestDox('::assertJsonStringNotEqualsJsonFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertJsonStringNotEqualsJsonFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error,
        // even in the negated form.

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

    // ================================================================
    //
    // ::assertJsonFileEqualsJsonFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJsonFileEqualsJsonFile() passes when the two files decode to the same structure despite different key order')]
    public function test_assertJsonFileEqualsJsonFile_passes_on_reordered_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // semantic-equality pin! valid.json and
        // valid-reordered.json hold the same fields in different
        // order. Both decode to the same object, so the assertion
        // must accept - the file-vs-file variant carries the same
        // key-order blindness as the string-vs-string variant.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonFileEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualFile: $fixtureDir . 'valid-reordered.json',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJsonFileEqualsJsonFile() throws AssertionFailedException when the two files decode to different structures')]
    public function test_assertJsonFileEqualsJsonFile_throws_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: different.json holds different field
        // values, so decoded equality cannot hold.

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

    #[TestDox('::assertJsonFileEqualsJsonFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertJsonFileEqualsJsonFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.
        // The readability check runs before any JSON decoding.

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

    // ================================================================
    //
    // ::assertJsonFileNotEqualsJsonFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertJsonFileNotEqualsJsonFile() passes when the two files decode to different structures')]
    public function test_assertJsonFileNotEqualsJsonFile_passes_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: different.json holds different field values
        // than valid.json, which is exactly what the negated form
        // is built to accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/json/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertJsonFileNotEqualsJsonFile(
            expectedFile: $fixtureDir . 'valid.json',
            actualFile: $fixtureDir . 'different.json',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertJsonFileNotEqualsJsonFile() throws AssertionFailedException when the two files decode to the same structure despite different key order')]
    public function test_assertJsonFileNotEqualsJsonFile_throws_on_reordered_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: valid.json and valid-reordered.json
        // decode to the same object despite being byte-distinct.
        // The negated form must raise - same footgun as the
        // string-vs-string variant.

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

    #[TestDox('::assertJsonFileNotEqualsJsonFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertJsonFileNotEqualsJsonFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error,
        // even in the negated form.

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
    // ::assertXmlStringEqualsXmlString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertXmlStringEqualsXmlString() passes when the two strings canonicalise to the same XML despite whitespace differences')]
    public function test_assertXmlStringEqualsXmlString_passes_on_whitespace_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // canonicalisation pin! the implementation runs both
        // documents through DOMDocument::C14N(), which normalises
        // insignificant whitespace between elements. Byte-distinct
        // inputs that describe the same tree therefore count as
        // equal - lock that in so a future comparator change
        // cannot silently regress to byte equality.

        $expectedXml = '<root><item>test</item></root>';
        $actualXml = <<<'XML'
            <root>
              <item>test</item>
            </root>
            XML;

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringEqualsXmlString(
            expectedXml: $expectedXml,
            actualXml: $actualXml,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertXmlStringEqualsXmlString() throws AssertionFailedException when the two strings canonicalise to different XML')]
    public function test_assertXmlStringEqualsXmlString_throws_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the element content differs ("test" vs
        // "other"), which survives canonicalisation. The
        // assertion must raise.

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

    #[TestDox('::assertXmlStringEqualsXmlString() throws InvalidArgumentException when the expected XML is malformed')]
    public function test_assertXmlStringEqualsXmlString_throws_when_xml_is_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! malformed XML is a caller error, not an
        // equality failure. Distinguish the two by raising
        // InvalidArgumentException rather than
        // AssertionFailedException.

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

    // ================================================================
    //
    // ::assertXmlStringNotEqualsXmlString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertXmlStringNotEqualsXmlString() passes when the two strings canonicalise to different XML')]
    public function test_assertXmlStringNotEqualsXmlString_passes_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the element content differs ("test" vs
        // "other"), which survives canonicalisation. The negated
        // form accepts.

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlString(
            expectedXml: '<root><item>test</item></root>',
            actualXml: '<root><item>other</item></root>',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertXmlStringNotEqualsXmlString() throws AssertionFailedException when the two strings canonicalise to the same XML despite whitespace differences')]
    public function test_assertXmlStringNotEqualsXmlString_throws_on_whitespace_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: byte-distinct XML that canonicalises to
        // the same tree must fail the negated form. Mirror of
        // the positive canonicalisation pin.

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(AssertionFailedException::class);

        $expectedXml = '<root><item>test</item></root>';
        $actualXml = <<<'XML'
            <root>
              <item>test</item>
            </root>
            XML;

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlString(
            expectedXml: $expectedXml,
            actualXml: $actualXml,
        );
    }

    #[TestDox('::assertXmlStringNotEqualsXmlString() throws InvalidArgumentException when the expected XML is malformed')]
    public function test_assertXmlStringNotEqualsXmlString_throws_when_xml_is_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! malformed XML is a caller error, not an
        // equality disagreement - same classification as the
        // positive form.

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

    // ================================================================
    //
    // ::assertXmlStringEqualsXmlFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertXmlStringEqualsXmlFile() passes when the string canonicalises to the same XML as the file')]
    public function test_assertXmlStringEqualsXmlFile_passes_on_matching_structure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: valid.xml holds
        // `<root><item>test</item></root>` and the string is
        // byte-identical, so canonicalisation leaves both sides
        // equal.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualXml: '<root><item>test</item></root>',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertXmlStringEqualsXmlFile() throws AssertionFailedException when the string canonicalises to different XML than the file')]
    public function test_assertXmlStringEqualsXmlFile_throws_on_different_structure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: the element content differs ("test" in
        // the file vs "other" in the string), which survives
        // canonicalisation.

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

    #[TestDox('::assertXmlStringEqualsXmlFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertXmlStringEqualsXmlFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error,
        // not an assertion failure. The readability check runs
        // before any XML parsing.

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

    // ================================================================
    //
    // ::assertXmlStringNotEqualsXmlFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertXmlStringNotEqualsXmlFile() passes when the string canonicalises to different XML than the file')]
    public function test_assertXmlStringNotEqualsXmlFile_passes_on_different_structure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: the element content differs ("test" in
        // the file vs "other" in the string), which survives
        // canonicalisation. The negated form accepts.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlStringNotEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualXml: '<root><item>other</item></root>',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertXmlStringNotEqualsXmlFile() throws AssertionFailedException when the string canonicalises to the same XML as the file')]
    public function test_assertXmlStringNotEqualsXmlFile_throws_on_matching_structure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: valid.xml holds
        // `<root><item>test</item></root>` and the string is
        // byte-identical. The negated form must raise.

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

    #[TestDox('::assertXmlStringNotEqualsXmlFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertXmlStringNotEqualsXmlFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error,
        // even in the negated form.

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

    // ================================================================
    //
    // ::assertXmlFileEqualsXmlFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertXmlFileEqualsXmlFile() passes when the two files canonicalise to the same XML despite whitespace differences')]
    public function test_assertXmlFileEqualsXmlFile_passes_on_whitespace_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // canonicalisation pin! valid.xml is on one line,
        // valid-equivalent.xml inserts indentation and a
        // newline between the elements. Canonicalisation
        // normalises the whitespace, so they count as equal.
        // The file-vs-file variant carries the same
        // normalisation semantics as the string-vs-string
        // variant.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlFileEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualFile: $fixtureDir . 'valid-equivalent.xml',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertXmlFileEqualsXmlFile() throws AssertionFailedException when the two files canonicalise to different XML')]
    public function test_assertXmlFileEqualsXmlFile_throws_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: different.xml holds different element
        // content than valid.xml, which survives canonicalisation.

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

    #[TestDox('::assertXmlFileEqualsXmlFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertXmlFileEqualsXmlFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error.
        // The readability check runs before any XML parsing.

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

    // ================================================================
    //
    // ::assertXmlFileNotEqualsXmlFile() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::assertXmlFileNotEqualsXmlFile() passes when the two files canonicalise to different XML')]
    public function test_assertXmlFileNotEqualsXmlFile_passes_on_different_structures(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // happy path: different.xml holds different element
        // content than valid.xml, which is exactly what the
        // negated form is built to accept.

        // ----------------------------------------------------------------
        // setup your test

        $fixtureDir = __DIR__ . '/../../fixtures/xml/';

        // ----------------------------------------------------------------
        // perform the change

        Assert::assertXmlFileNotEqualsXmlFile(
            expectedFile: $fixtureDir . 'valid.xml',
            actualFile: $fixtureDir . 'different.xml',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->addToAssertionCount(1);
    }

    #[TestDox('::assertXmlFileNotEqualsXmlFile() throws AssertionFailedException when the two files canonicalise to the same XML despite whitespace differences')]
    public function test_assertXmlFileNotEqualsXmlFile_throws_on_whitespace_difference(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // failure mode: valid.xml and valid-equivalent.xml
        // canonicalise to the same tree despite the whitespace
        // difference. The negated form must raise - same
        // footgun as the string-vs-string variant.

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

    #[TestDox('::assertXmlFileNotEqualsXmlFile() throws InvalidArgumentException when the expected file is not readable')]
    public function test_assertXmlFileNotEqualsXmlFile_throws_when_expected_file_not_readable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // robustness! missing expected file is a caller error,
        // even in the negated form.

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
