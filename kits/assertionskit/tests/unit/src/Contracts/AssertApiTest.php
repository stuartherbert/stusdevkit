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

namespace StusDevKit\AssertionsKit\Tests\Unit\Contracts;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\AssertionsKit\Contracts\AssertApi;

/**
 * Contract test for AssertApi.
 *
 * AssertApi is the published interface describing the assertion
 * method set shared between PHPUnit's `Assert` class and StusDevKit's
 * own `StusDevKit\AssertionsKit\Assert` implementation. Third parties
 * can also implement this interface to plug their own assertion
 * providers into code that only depends on the contract.
 *
 * The interface carries 163 static methods. The tests below pin the
 * published shape (namespace, kind, method set, per-method
 * declaration / visibility / static / return type) so any drift
 * becomes a named diff rather than a silent breakage in downstream
 * implementers.
 *
 * Identity is pinned by enumerating the full method set. Per-method
 * Shape is pinned via data-provider tests that walk every method
 * name, because writing 163 hand-rolled Shape test methods would be
 * unreadable without adding any extra coverage over the data-provider
 * form.
 */
#[TestDox(AssertApi::class)]
class AssertApiTest extends TestCase
{
    // ================================================================
    //
    // Interface identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\AssertionsKit\\Contracts namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - every
        // implementer imports the interface by FQN, so moving it
        // is a breaking change that must go through a major version
        // bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\AssertionsKit\\Contracts';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(AssertApi::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // AssertApi is the contract that *many* classes can implement
        // - Assert.php in this kit, PHPUnit's own Assert, and any
        // future third-party provider. Turning it into a class or a
        // trait would break every `implements AssertApi` declaration
        // downstream.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(AssertApi::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('extends no parent interfaces')]
    public function test_extends_no_parent_interfaces(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // AssertApi deliberately stands alone. Pulling in a parent
        // interface (for example, PHPUnit's internal contracts) would
        // drag every one of its methods into the pinned method set
        // and tie the published contract to PHPUnit's release cadence.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $reflection = new ReflectionClass(AssertApi::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('exposes exactly the published 163-method assertion surface')]
    public function test_exposes_the_published_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method set IS the contract. Pin it by enumeration, not
        // by count - when it drifts, the failure names the offender
        // rather than just saying "expected 163, got 164".
        //
        // this same list serves as the lockdown for Assert.php's
        // published surface because Assert implements AssertApi and
        // PHPStan's return-type checking prevents it from declaring
        // extra static methods that the interface doesn't know about
        // (any such method would be outside the contract, not part
        // of it).

        // ----------------------------------------------------------------
        // setup your test

        // the set of every method name the interface must publish.
        // Keep this list sorted alphabetically so a new method
        // appears in its expected place rather than at the end.
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
        $reflection = new ReflectionClass(AssertApi::class);

        // ----------------------------------------------------------------
        // perform the change

        // collect every public method declared on the interface,
        // sorted alphabetically so the comparison against $expected
        // is order-stable.
        $actual = array_map(
            static fn (ReflectionMethod $method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Per-method Shape
    //
    // ----------------------------------------------------------------

    #[DataProvider('provideAssertionMethodNames')]
    #[TestDox('::$methodName() is declared on the interface')]
    public function test_assertion_method_is_declared(
        string $methodName,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // pin each assertion method as an individual reflection
        // hit. Running one assertion per data row means a drift
        // on a single method shows up as "::assertXxx() is
        // declared on the interface" failing in the TestDox
        // output, naming the offender directly.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(AssertApi::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod($methodName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[DataProvider('provideAssertionMethodNames')]
    #[TestDox('::$methodName() is public')]
    public function test_assertion_method_is_public(string $methodName): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // interface methods are public by default, but pinning
        // the modifier makes the contract explicit and locks it
        // down against a future PHP version that might widen
        // interface visibility syntax.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(AssertApi::class))
            ->getMethod($methodName);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[DataProvider('provideAssertionMethodNames')]
    #[TestDox('::$methodName() is static')]
    public function test_assertion_method_is_static(string $methodName): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every assertion in PHPUnit's calling convention is a
        // static method - `Assert::assertTrue($x)` - so the
        // interface must declare them static too. A non-static
        // method here would make Assert::assertTrue() incompatible
        // with the interface contract and break `implements
        // AssertApi` at compile time.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(AssertApi::class))
            ->getMethod($methodName);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[DataProvider('provideAssertionMethodNames')]
    #[TestDox('::$methodName() returns void')]
    public function test_assertion_method_returns_void(
        string $methodName,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // every assertion in PHPUnit's convention signals failure
        // by throwing and signals success by returning normally.
        // A non-void return type would imply the caller gets
        // something back - which would be a different contract
        // (closer to a predicate than an assertion).

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'void';
        $method = (new ReflectionClass(AssertApi::class))
            ->getMethod($methodName);
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[DataProvider('provideAssertionMethodNames')]
    #[TestDox('::$methodName() takes $message as its final parameter, typed as string with default \'\'')]
    public function test_assertion_method_has_trailing_message_parameter(
        string $methodName,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // every assertion accepts an optional trailing `$message`
        // string, exactly mirroring PHPUnit's assertion API. Pinning
        // this shape - name, type, position, default - is what lets
        // callers drop in `MyAssertions::assertTrue($x, 'why this
        // matters')` without worrying which implementation they're
        // hitting.
        //
        // use the interface method name in the test to catch
        // rename-by-accident drift, and lock the parameter default
        // to the empty string because that's the marker PHPUnit's
        // helpers use to mean "no user message was supplied".

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(AssertApi::class))
            ->getMethod($methodName);
        $parameters = $method->getParameters();

        // the trailing parameter is always $message.
        $trailing = $parameters[count($parameters) - 1];
        $trailingType = $trailing->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $trailingType);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $trailing->getName();
        $actualType = $trailingType->getName();
        $actualHasDefault = $trailing->isDefaultValueAvailable();
        $actualDefault = $actualHasDefault
            ? $trailing->getDefaultValue()
            : null;

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('message', $actualName);
        $this->assertSame('string', $actualType);
        $this->assertTrue($actualHasDefault);
        $this->assertSame('', $actualDefault);
    }

    // ================================================================
    //
    // Data providers
    //
    // ----------------------------------------------------------------

    /**
     * Yields every assertion method name published by AssertApi.
     *
     * The Identity test above pins the full set; this provider
     * feeds that same set into the per-method Shape tests so
     * reflection can walk each method's declaration, visibility,
     * staticness, return type, and trailing-parameter shape
     * without 163 hand-rolled test methods.
     *
     * @return iterable<string, array{string}>
     */
    public static function provideAssertionMethodNames(): iterable
    {
        // discover the method names via reflection. This keeps the
        // provider in sync with the interface automatically - and
        // the Identity test above is what guards the total set, so
        // the provider can trust whatever reflection yields here.
        $reflection = new ReflectionClass(AssertApi::class);
        foreach (
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method
        ) {
            $name = $method->getName();
            yield $name => [$name];
        }
    }
}
