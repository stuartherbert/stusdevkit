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

namespace StusDevKit\MissingBitsKit\Tests\Unit\TypeInspectors;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use stdClass;
use StusDevKit\MissingBitsKit\TypeInspectors\IsClosedResource;

/**
 * Contract + behaviour tests for the IsClosedResource predicate.
 *
 * The combination reads as "here's what it IS, here's what it DOES":
 * identity and shape pin the published surface, and the behaviour
 * sections exercise both the static ::check() entry point and the
 * invokable ->__invoke() form against a closed resource, an open
 * resource, and the full spread of non-resource values.
 */
#[TestDox(IsClosedResource::class)]
class IsClosedResourceTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\TypeInspectors namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import the class by FQN, so moving it is a breaking change
        // that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\TypeInspectors';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(IsClosedResource::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // IsClosedResource is a concrete class - not an interface or
        // trait. The invokable form requires `new IsClosedResource()`,
        // so a switch to an interface or trait would break every call
        // site.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(IsClosedResource::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait     = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('exposes only __invoke() and check() as its public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the class exposes exactly two public methods: the invokable
        // ->__invoke() form for stored callables, and the static
        // ::check() form for inline use. Pin the method set by
        // enumeration - any addition fails with a diff that names
        // the new method, rather than a cryptic count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__invoke', 'check'];
        $reflection = new ReflectionClass(IsClosedResource::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Class structure
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() returns a new instance')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form requires construction. The default
        // constructor accepts no arguments; this test pins that the
        // class remains instantiable without configuration, so the
        // `new IsClosedResource()` call site stays valid.

        // ----------------------------------------------------------------
        // setup your test

        // (no setup - the act of constructing is the test)

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IsClosedResource();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(IsClosedResource::class, $unit);
    }

    // ================================================================
    //
    // __invoke() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('->__invoke() is declared public, non-static')]
    public function test_invoke_is_public_non_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must be a public instance method so
        // callers can store an IsClosedResource instance in a
        // `callable` slot and call it with `$guard($value)`. A switch
        // to static would break that idiom.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsClosedResource::class, '__invoke');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertFalse($isStatic);
    }

    #[TestDox('->__invoke() declares $value as its only parameter')]
    public function test_invoke_declares_value_as_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the published surface -
        // callers using named arguments rely on it. Pin both the
        // name AND the singleton-ness (by enumeration) so renames
        // and accidental extra parameters are caught.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['value'];
        $method   = new ReflectionMethod(IsClosedResource::class, '__invoke');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->__invoke() returns bool')]
    public function test_invoke_return_type_is_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is `bool`, not nullable, not a union -
        // callers branch on the result directly, so a widened return
        // type would silently change call-site behaviour.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsClosedResource::class, '__invoke');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $this->assertFalse($returnType->allowsNull());
    }

    // ================================================================
    //
    // check() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('::check() is declared public, static')]
    public function test_check_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the static form is the no-allocation entry point - callers
        // use it as `IsClosedResource::check($value)` without
        // constructing an instance. A switch to instance would force
        // every call site to allocate.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsClosedResource::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::check() declares $value as its only parameter')]
    public function test_check_declares_value_as_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the published surface - and
        // it must match ->__invoke()'s parameter name so the two
        // forms are interchangeable for named-argument callers.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['value'];
        $method   = new ReflectionMethod(IsClosedResource::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::check() returns bool')]
    public function test_check_return_type_is_bool(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is `bool`, not nullable, not a union -
        // callers branch on the result directly, so a widened return
        // type would silently change call-site behaviour.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsClosedResource::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('bool', $returnType->getName());
        $this->assertFalse($returnType->allowsNull());
    }

    // ================================================================
    //
    // check() - behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::check() returns true for a closed resource')]
    public function test_check_returns_true_for_a_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a resource that has been closed is the one and only value
        // this predicate accepts. PHP keeps the resource zval type
        // after fclose(), and recognising that is the whole job.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsClosedResource::check(value: $resource);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::check() returns false for an open resource')]
    public function test_check_returns_false_for_an_open_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an open resource is NOT a closed resource. The predicate
        // narrows to the closed state specifically, so a live handle
        // must come back false - callers wanting "is this a resource
        // at all?" use is_resource() instead.

        // ----------------------------------------------------------------
        // setup your test

        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsClosedResource::check(value: $resource);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
        fclose($resource);
    }

    /**
     * Values that are not resources at all - the predicate rejects
     * every one of them.
     *
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function nonResourceProvider(): array
    {
        return [
            'a zero integer'     => ['a zero integer', 0],
            'a positive integer' => ['a positive integer', 42],
            'a float'            => ['a float', 1.5],
            'an empty string'    => ['an empty string', ''],
            'a non-empty string' => ['a non-empty string', 'hello'],
            'the string "resource (closed)"'
                => ['the string "resource (closed)"', 'resource (closed)'],
            'a true boolean'     => ['a true boolean', true],
            'a false boolean'    => ['a false boolean', false],
            'null'               => ['null', null],
            'an empty array'     => ['an empty array', []],
            'a populated array'  => ['a populated array', [1, 2, 3]],
            'a stdClass instance'
                => ['a stdClass instance', new stdClass()],
        ];
    }

    /**
     * @param mixed $value
     */
    #[DataProvider('nonResourceProvider')]
    #[TestDox('::check() returns false for $description')]
    public function test_check_returns_false_for_non_resource(
        string $description,
        mixed $value,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // a value that is not a resource cannot be a closed resource.
        // The "resource (closed)" string case proves the predicate
        // inspects the value's type, not its content - a string that
        // happens to spell the marker is still just a string.

        // ----------------------------------------------------------------
        // setup your test

        // (no local setup - $value arrives via the data provider;
        // $description is for TestDox readability only)
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsClosedResource::check(value: $value);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    // ================================================================
    //
    // __invoke() - behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->__invoke() returns true for a closed resource')]
    public function test_invoke_returns_true_for_a_closed_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form: a
        // closed resource comes back true through either entry point.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsClosedResource();
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);
        fclose($resource);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($resource);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->__invoke() returns false for an open resource')]
    public function test_invoke_returns_false_for_an_open_resource(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form: an
        // open resource comes back false through either entry point.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsClosedResource();
        $resource = fopen('php://memory', 'r');
        self::assertIsResource($resource);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($resource);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
        fclose($resource);
    }

    /**
     * @param mixed $value
     */
    #[DataProvider('nonResourceProvider')]
    #[TestDox('->__invoke() returns false for $description')]
    public function test_invoke_returns_false_for_non_resource(
        string $description,
        mixed $value,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every non-resource value. Without this pinning, the two
        // forms could silently disagree depending on which entry
        // point a caller picked.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsClosedResource();
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($value);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
