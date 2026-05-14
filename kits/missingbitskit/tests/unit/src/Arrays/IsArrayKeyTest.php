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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Arrays;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use stdClass;
use StusDevKit\MissingBitsKit\Arrays\IsArrayKey;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

/**
 * Contract + behaviour tests for the IsArrayKey type guard.
 *
 * The combination reads as "here's what it IS, here's what it DOES":
 * identity and shape pin the published surface, and the behaviour
 * sections exercise both the static ::check() entry point and the
 * invokable ->__invoke() form against the full set of PHP types
 * that PHP itself accepts (or rejects) as an array key.
 */
#[TestDox(IsArrayKey::class)]
class IsArrayKeyTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Arrays namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import the class by FQN, so moving it is a breaking change
        // that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Arrays';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(IsArrayKey::class))
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

        // IsArrayKey is a concrete class - not an interface or trait.
        // The invokable form requires `new IsArrayKey()`, so a switch
        // to an interface or trait would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(IsArrayKey::class);

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
        $reflection = new ReflectionClass(IsArrayKey::class);

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
        // `new IsArrayKey()` call site stays valid.

        // ----------------------------------------------------------------
        // setup your test

        // (no setup - the act of constructing is the test)

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IsArrayKey();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(IsArrayKey::class, $unit);
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
        // callers can store an IsArrayKey instance in a `callable`
        // slot and call it with `$guard($value)`. A switch to static
        // would break that idiom.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsArrayKey::class, '__invoke');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertFalse($isStatic);
    }

    #[TestDox('->__invoke() declares $input as its only parameter')]
    public function test_invoke_declares_input_as_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the published surface -
        // callers using named arguments rely on it. Pin both the
        // name AND the singleton-ness (by enumeration) so renames
        // and accidental extra parameters are caught.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['input'];
        $method   = new ReflectionMethod(IsArrayKey::class, '__invoke');

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

        // the return type is `bool`, not nullable, not a union. The
        // `@phpstan-assert-if-true array-key $input` annotation in
        // the docblock relies on the runtime return being a strict
        // boolean for PHPStan to narrow the caller's type.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsArrayKey::class, '__invoke');

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
        // use it as `IsArrayKey::check($value)` without constructing
        // an instance. A switch to instance would force every call
        // site to allocate.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsArrayKey::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::check() declares $input as its only parameter')]
    public function test_check_declares_input_as_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the published surface - and
        // it must match ->__invoke()'s parameter name so the two
        // forms are interchangeable for named-argument callers.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['input'];
        $method   = new ReflectionMethod(IsArrayKey::class, 'check');

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

        // the return type is `bool`, not nullable, not a union. The
        // `@phpstan-assert-if-true array-key $input` annotation in
        // the docblock relies on the runtime return being a strict
        // boolean for PHPStan to narrow the caller's type.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsArrayKey::class, 'check');

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
    // check() - accepted types (returns true)
    //
    // ----------------------------------------------------------------

    /**
     * Inputs that PHP accepts as native array keys.
     *
     * The data set names ($description) are picked so the
     * interpolated TestDox sentence reads naturally:
     *   "::check() returns true for a zero integer".
     *
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function acceptedInputProvider(): array
    {
        return [
            'a zero integer'      => ['a zero integer', 0],
            'a positive integer'  => ['a positive integer', 42],
            'a negative integer'  => ['a negative integer', -7],
            'PHP_INT_MAX'         => ['PHP_INT_MAX', PHP_INT_MAX],
            'PHP_INT_MIN'         => ['PHP_INT_MIN', PHP_INT_MIN],
            'an empty string'     => ['an empty string', ''],
            'a non-empty string'  => ['a non-empty string', 'key'],
            'a numeric string'    => ['a numeric string', '42'],
            'a coercible "0"'     => ['a coercible "0"', '0'],
        ];
    }

    /**
     * @param mixed $input
     */
    #[DataProvider('acceptedInputProvider')]
    #[TestDox('::check() returns true for $description')]
    public function test_check_returns_true_for_accepted_input(
        string $description,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's native array-key types are `int` and `string` -
        // everything else triggers a coercion (with a deprecation
        // notice on PHP 8.1+) or an error. The guard's contract is
        // to mirror PHP's accepted set exactly, so any value that
        // PHP would accept without coercion must come back as true.

        // ----------------------------------------------------------------
        // setup your test

        // (no local setup - $input arrives via the data provider;
        // $description is for TestDox readability only)
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsArrayKey::check($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // check() - rejected types (returns false)
    //
    // ----------------------------------------------------------------

    /**
     * Inputs that PHP would coerce (or refuse) when used as an array
     * key, and that the guard therefore rejects.
     *
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function rejectedInputProvider(): array
    {
        return [
            'null'           => ['null', null],
            'a true boolean' => ['a true boolean', true],
            'a false boolean'=> ['a false boolean', false],
            'a zero float'   => ['a zero float', 0.0],
            'a positive float' => ['a positive float', 1.5],
            'a negative float' => ['a negative float', -1.5],
            'an empty array' => ['an empty array', []],
            'a populated array' => ['a populated array', [1, 2, 3]],
            'a stdClass instance' => ['a stdClass instance', new stdClass()],
        ];
    }

    /**
     * @param mixed $input
     */
    #[DataProvider('rejectedInputProvider')]
    #[TestDox('::check() returns false for $description')]
    public function test_check_returns_false_for_rejected_input(
        string $description,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // PHP would coerce these values to int or string when used
        // as an array key (or, for objects and arrays, raise an
        // error). The guard's contract is to reject every type that
        // is not natively `int|string` so callers can refuse the
        // input before PHP applies its silent coercion.

        // ----------------------------------------------------------------
        // setup your test

        // (no local setup - $input arrives via the data provider;
        // $description is for TestDox readability only)
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsArrayKey::check($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    // ================================================================
    //
    // __invoke() - accepted types (returns true)
    //
    // ----------------------------------------------------------------

    /**
     * @param mixed $input
     */
    #[DataProvider('acceptedInputProvider')]
    #[TestDox('->__invoke() returns true for $description')]
    public function test_invoke_returns_true_for_accepted_input(
        string $description,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every accepted input. This test pins that ->__invoke()
        // is a thin shim over ::check() - if the two ever diverge,
        // callers who pass an IsArrayKey instance as a `callable`
        // would see different behaviour from callers using the
        // static entry point.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsArrayKey();
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // __invoke() - rejected types (returns false)
    //
    // ----------------------------------------------------------------

    /**
     * @param mixed $input
     */
    #[DataProvider('rejectedInputProvider')]
    #[TestDox('->__invoke() returns false for $description')]
    public function test_invoke_returns_false_for_rejected_input(
        string $description,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every rejected input. Without this pinning, the two forms
        // could silently disagree and produce subtly different
        // call-site behaviour depending on which entry point a
        // caller picked.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsArrayKey();
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
