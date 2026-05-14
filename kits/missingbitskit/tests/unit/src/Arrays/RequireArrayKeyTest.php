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
use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;
use StusDevKit\MissingBitsKit\Arrays\RequireArrayKey;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

/**
 * Contract + behaviour tests for the RequireArrayKey type guarantee.
 *
 * The combination reads as "here's what it IS, here's what it DOES":
 * identity and shape pin the published surface, the accept-side
 * behaviour proves the guarantee returns silently for every input
 * PHP would treat as a native array key, and the reject-side
 * behaviour proves the exception class, full message and code are
 * stable contracts that callers can rely on.
 */
#[TestDox(RequireArrayKey::class)]
class RequireArrayKeyTest extends TestCase
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

        $actual = (new ReflectionClass(RequireArrayKey::class))
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

        // RequireArrayKey is a concrete class - not an interface or
        // trait. The invokable form requires `new RequireArrayKey()`,
        // so a switch to an interface or trait would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(RequireArrayKey::class);

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
        $reflection = new ReflectionClass(RequireArrayKey::class);

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
        // `new RequireArrayKey()` call site stays valid.

        // ----------------------------------------------------------------
        // setup your test

        // (no setup - the act of constructing is the test)

        // ----------------------------------------------------------------
        // perform the change

        $unit = new RequireArrayKey();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(RequireArrayKey::class, $unit);
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
        // callers can store a RequireArrayKey instance in a `callable`
        // slot and call it with `$guarantee($value)`. A switch to
        // static would break that idiom.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(RequireArrayKey::class, '__invoke');

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
        $method   = new ReflectionMethod(RequireArrayKey::class, '__invoke');

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

    #[TestDox('->__invoke() returns void')]
    public function test_invoke_return_type_is_void(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the guarantee form returns void - it either succeeds
        // silently or throws. Pinning the return type forbids a
        // future drift to a `bool` return (which would re-create
        // the type guard) or to a `static` return (which would
        // re-create the fluent-builder idiom).

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(RequireArrayKey::class, '__invoke');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('void', $returnType->getName());
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
        // use it as `RequireArrayKey::check($value)` without
        // constructing an instance. A switch to instance would force
        // every call site to allocate.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(RequireArrayKey::class, 'check');

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
        $method   = new ReflectionMethod(RequireArrayKey::class, 'check');

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

    #[TestDox('::check() returns void')]
    public function test_check_return_type_is_void(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the guarantee form returns void - it either succeeds
        // silently or throws. Pinning the return type forbids a
        // future drift to a `bool` return (which would re-create
        // the type guard) or to a `static` return (which would
        // re-create the fluent-builder idiom).

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(RequireArrayKey::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('void', $returnType->getName());
    }

    // ================================================================
    //
    // check() - accepted types (returns silently)
    //
    // ----------------------------------------------------------------

    /**
     * Inputs that PHP accepts as native array keys; the guarantee
     * must return silently for each one.
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
    #[TestDox('::check() returns silently for $description')]
    public function test_check_accepts_valid_input(
        string $description,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the guarantee's contract on the accept side is "return
        // silently" - no return value, no side effect, no thrown
        // exception. This test pins that contract for every input
        // PHP treats as a native array key.

        // ----------------------------------------------------------------
        // setup your test

        // (no local setup - $input arrives via the data provider;
        // $description is for TestDox readability only)
        unset($description);

        // ----------------------------------------------------------------
        // set test expectations

        // the call must complete with no thrown exception. PHPUnit
        // marks the test risky without at least one assertion or
        // expectation, so we declare the no-assertion intent
        // up-front.
        $this->expectNotToPerformAssertions();

        // ----------------------------------------------------------------
        // perform the change

        RequireArrayKey::check($input);
    }

    // ================================================================
    //
    // check() - rejected types (throws InvalidArgumentException)
    //
    // ----------------------------------------------------------------

    /**
     * Inputs that PHP would coerce (or refuse) when used as an array
     * key, and that the guarantee therefore rejects with a thrown
     * `InvalidArgumentException`.
     *
     * The third element of each row is the expected `actual_type`
     * string the exception's `extra` array carries for that input.
     * Written out as a literal so the test never derives its
     * expected value from the same `get_debug_type()` call the
     * implementation uses.
     *
     * @return array<string, array{0: string, 1: mixed, 2: string}>
     */
    public static function rejectedInputProvider(): array
    {
        return [
            'null'                => ['null', null, 'null'],
            'a true boolean'      => ['a true boolean', true, 'bool'],
            'a false boolean'     => ['a false boolean', false, 'bool'],
            'a zero float'        => ['a zero float', 0.0, 'float'],
            'a positive float'    => ['a positive float', 1.5, 'float'],
            'a negative float'    => ['a negative float', -1.5, 'float'],
            'an empty array'      => ['an empty array', [], 'array'],
            'a populated array'   => ['a populated array', [1, 2, 3], 'array'],
            'a stdClass instance' => ['a stdClass instance', new stdClass(), 'stdClass'],
        ];
    }

    /**
     * @param mixed $input
     */
    #[DataProvider('rejectedInputProvider')]
    #[TestDox('::check() throws InvalidArgumentException for $description')]
    public function test_check_rejects_invalid_input(
        string $description,
        mixed $input,
        string $expectedActualType,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the guarantee's contract on the reject side has three
        // parts: the exception class is InvalidArgumentException,
        // the message is the constant
        // `input is not a supported PHP array-key`, and the
        // exception code is 0 (Rfc9457ProblemDetailsException does
        // not pass the HTTP status through to \Exception's code).
        // All three are contracts our callers can rely on, so all
        // three are pinned here. The structured `actual_type`
        // detail is verified by a separate test below; this one
        // focuses on the class / message / code triple.

        // ----------------------------------------------------------------
        // setup your test

        unset($description, $expectedActualType);

        // ----------------------------------------------------------------
        // set test expectations

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('input is not a supported PHP array-key');
        $this->expectExceptionCode(0);

        // ----------------------------------------------------------------
        // perform the change

        RequireArrayKey::check($input);
    }

    /**
     * @param mixed $input
     */
    #[DataProvider('rejectedInputProvider')]
    #[TestDox('::check() records actual type "$expectedActualType" in the exception extra for $description')]
    public function test_check_records_actual_type_in_extra(
        string $description,
        mixed $input,
        string $expectedActualType,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // RFC 9457-style structured details live in the exception's
        // `extra` array, not in the message. The guarantee promises
        // two keys: `expected_type` (always `array-key`) and
        // `actual_type` (the get_debug_type() reading of the
        // rejected input). Callers that surface structured error
        // detail to clients rely on this shape, so it is pinned
        // as a contract.

        // ----------------------------------------------------------------
        // setup your test

        $expectedExtra = [
            'expected_type' => 'array-key',
            'actual_type'   => $expectedActualType,
        ];
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        try {
            RequireArrayKey::check($input);
            // the line above must throw; reaching this point means
            // the contract was broken.
            $this->fail(
                'Expected InvalidArgumentException was not thrown',
            );
        } catch (InvalidArgumentException $exception) {
            $actualExtra = $exception->getExtra();
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedExtra, $actualExtra);
    }

    // ================================================================
    //
    // __invoke() - accepted types (returns silently)
    //
    // ----------------------------------------------------------------

    /**
     * @param mixed $input
     */
    #[DataProvider('acceptedInputProvider')]
    #[TestDox('->__invoke() returns silently for $description')]
    public function test_invoke_accepts_valid_input(
        string $description,
        mixed $input,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every accepted input. This test pins that ->__invoke()
        // is a thin shim over ::check() - if the two ever diverge,
        // callers who pass a RequireArrayKey instance as a
        // `callable` would see different behaviour from callers
        // using the static entry point.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new RequireArrayKey();
        unset($description);

        // ----------------------------------------------------------------
        // set test expectations

        $this->expectNotToPerformAssertions();

        // ----------------------------------------------------------------
        // perform the change

        $unit($input);
    }

    // ================================================================
    //
    // __invoke() - rejected types (throws InvalidArgumentException)
    //
    // ----------------------------------------------------------------

    /**
     * @param mixed $input
     */
    #[DataProvider('rejectedInputProvider')]
    #[TestDox('->__invoke() throws InvalidArgumentException for $description')]
    public function test_invoke_rejects_invalid_input(
        string $description,
        mixed $input,
        string $expectedActualType,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every rejected input - same exception class, same
        // message, same code. Without this pinning, the two forms
        // could silently disagree and produce subtly different
        // call-site behaviour depending on which entry point a
        // caller picked.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new RequireArrayKey();
        unset($description, $expectedActualType);

        // ----------------------------------------------------------------
        // set test expectations

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('input is not a supported PHP array-key');
        $this->expectExceptionCode(0);

        // ----------------------------------------------------------------
        // perform the change

        $unit($input);
    }

    /**
     * @param mixed $input
     */
    #[DataProvider('rejectedInputProvider')]
    #[TestDox('->__invoke() records actual type "$expectedActualType" in the exception extra for $description')]
    public function test_invoke_records_actual_type_in_extra(
        string $description,
        mixed $input,
        string $expectedActualType,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must carry the same structured detail
        // as the static form - same `expected_type`, same
        // `actual_type` derivation. Pinning the extra-array shape
        // on both entry points stops the two from drifting apart
        // in how they describe a rejection to a structured-error
        // surface.

        // ----------------------------------------------------------------
        // setup your test

        $unit          = new RequireArrayKey();
        $expectedExtra = [
            'expected_type' => 'array-key',
            'actual_type'   => $expectedActualType,
        ];
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        try {
            $unit($input);
            $this->fail(
                'Expected InvalidArgumentException was not thrown',
            );
        } catch (InvalidArgumentException $exception) {
            $actualExtra = $exception->getExtra();
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedExtra, $actualExtra);
    }
}
