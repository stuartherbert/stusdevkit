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

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use stdClass;
use StusDevKit\MissingBitsKit\TypeInspectors\IsComparablePair;

/**
 * Contract + behaviour tests for the IsComparablePair predicate.
 *
 * The combination reads as "here's what it IS, here's what it DOES":
 * identity and shape pin the published surface, and the behaviour
 * sections exercise both the static ::check() entry point and the
 * invokable ->__invoke() form against the comparable and
 * non-comparable pairings the predicate must tell apart.
 */
#[TestDox(IsComparablePair::class)]
class IsComparablePairTest extends TestCase
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

        $actual = (new ReflectionClass(IsComparablePair::class))
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

        // IsComparablePair is a concrete class - not an interface or
        // trait. The invokable form requires `new IsComparablePair()`,
        // so a switch to an interface or trait would break every call
        // site.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(IsComparablePair::class);

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
        $reflection = new ReflectionClass(IsComparablePair::class);

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
        // `new IsComparablePair()` call site stays valid.

        // ----------------------------------------------------------------
        // setup your test

        // (no setup - the act of constructing is the test)

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IsComparablePair();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(IsComparablePair::class, $unit);
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
        // callers can store an IsComparablePair instance in a
        // `callable` slot and call it with `$guard($a, $b)`. A switch
        // to static would break that idiom.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsComparablePair::class, '__invoke');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertFalse($isStatic);
    }

    #[TestDox('->__invoke() declares $first and $second as its parameters')]
    public function test_invoke_declares_first_and_second_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter names are part of the published surface -
        // callers using named arguments rely on them. Pin both the
        // names AND the count (by enumeration) so renames and
        // accidental extra parameters are caught.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['first', 'second'];
        $method   = new ReflectionMethod(IsComparablePair::class, '__invoke');

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

        $method = new ReflectionMethod(IsComparablePair::class, '__invoke');

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
        // use it as `IsComparablePair::check($a, $b)` without
        // constructing an instance. A switch to instance would force
        // every call site to allocate.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(IsComparablePair::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::check() declares $first and $second as its parameters')]
    public function test_check_declares_first_and_second_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter names are part of the published surface - and
        // they must match ->__invoke()'s parameter names so the two
        // forms are interchangeable for named-argument callers.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['first', 'second'];
        $method   = new ReflectionMethod(IsComparablePair::class, 'check');

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

        $method = new ReflectionMethod(IsComparablePair::class, 'check');

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
    // check() - comparable pairs (returns true)
    //
    // ----------------------------------------------------------------

    /**
     * Pairs of values that PHP's relational operators can order
     * meaningfully - two scalars, or two DateTimeInterface instances.
     *
     * The data set names ($description) are picked so the
     * interpolated TestDox sentence reads naturally:
     *   "::check() returns true for two integers".
     *
     * @return array<string, array{0: string, 1: mixed, 2: mixed}>
     */
    public static function comparablePairProvider(): array
    {
        return [
            'two integers'
                => ['two integers', 1, 2],
            'an integer and a float'
                => ['an integer and a float', 1, 2.5],
            'two floats'
                => ['two floats', 1.5, 2.5],
            'an integer and a string'
                => ['an integer and a string', 1, 'abc'],
            'two strings'
                => ['two strings', 'abc', 'def'],
            'an integer and a boolean'
                => ['an integer and a boolean', 1, true],
            'two booleans'
                => ['two booleans', true, false],
            'a string and a boolean'
                => ['a string and a boolean', 'abc', false],
            'two DateTimeImmutable instances'
                => [
                    'two DateTimeImmutable instances',
                    new DateTimeImmutable(),
                    new DateTimeImmutable(),
                ],
            'a DateTime and a DateTimeImmutable'
                => [
                    'a DateTime and a DateTimeImmutable',
                    new DateTime(),
                    new DateTimeImmutable(),
                ],
        ];
    }

    /**
     * @param mixed $first
     * @param mixed $second
     */
    #[DataProvider('comparablePairProvider')]
    #[TestDox('::check() returns true for $description')]
    public function test_check_returns_true_for_comparable_pair(
        string $description,
        mixed $first,
        mixed $second,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's relational operators order any two scalars, and any
        // two DateTimeInterface instances, meaningfully. The predicate
        // reports such a pair as comparable so callers can hand the
        // two values straight to `<` / `>`.

        // ----------------------------------------------------------------
        // setup your test

        // (no local setup - the pair arrives via the data provider;
        // $description is for TestDox readability only)
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsComparablePair::check(first: $first, second: $second);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // check() - non-comparable pairs (returns false)
    //
    // ----------------------------------------------------------------

    /**
     * Pairs of values that PHP's relational operators cannot order
     * meaningfully - chiefly a scalar paired with a DateTime, where
     * PHP reports the object as greater regardless of its value.
     *
     * @return array<string, array{0: string, 1: mixed, 2: mixed}>
     */
    public static function nonComparablePairProvider(): array
    {
        return [
            'an integer and a DateTimeImmutable'
                => [
                    'an integer and a DateTimeImmutable',
                    1,
                    new DateTimeImmutable(),
                ],
            'a DateTimeImmutable and an integer'
                => [
                    'a DateTimeImmutable and an integer',
                    new DateTimeImmutable(),
                    1,
                ],
            'a string and a DateTimeImmutable'
                => [
                    'a string and a DateTimeImmutable',
                    'abc',
                    new DateTimeImmutable(),
                ],
            'a float and a DateTimeImmutable'
                => [
                    'a float and a DateTimeImmutable',
                    1.5,
                    new DateTimeImmutable(),
                ],
            'a boolean and a DateTimeImmutable'
                => [
                    'a boolean and a DateTimeImmutable',
                    true,
                    new DateTimeImmutable(),
                ],
            'an integer and null'
                => ['an integer and null', 1, null],
            'null and an integer'
                => ['null and an integer', null, 1],
            'two nulls'
                => ['two nulls', null, null],
            'an integer and an array'
                => ['an integer and an array', 1, [1, 2]],
            'two arrays'
                => ['two arrays', [1], [2]],
            'an integer and a stdClass'
                => ['an integer and a stdClass', 1, new stdClass()],
            'two stdClass instances'
                => [
                    'two stdClass instances',
                    new stdClass(),
                    new stdClass(),
                ],
            'a DateTimeImmutable and a stdClass'
                => [
                    'a DateTimeImmutable and a stdClass',
                    new DateTimeImmutable(),
                    new stdClass(),
                ],
        ];
    }

    /**
     * @param mixed $first
     * @param mixed $second
     */
    #[DataProvider('nonComparablePairProvider')]
    #[TestDox('::check() returns false for $description')]
    public function test_check_returns_false_for_non_comparable_pair(
        string $description,
        mixed $first,
        mixed $second,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // these pairings cannot be ordered meaningfully. The headline
        // case is a scalar paired with a DateTime: PHP reports any
        // object as greater than any scalar, so the comparison would
        // be decided by type, not value. The predicate reports such a
        // pair as non-comparable so callers can refuse it.

        // ----------------------------------------------------------------
        // setup your test

        // (no local setup - the pair arrives via the data provider;
        // $description is for TestDox readability only)
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = IsComparablePair::check(first: $first, second: $second);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    // ================================================================
    //
    // __invoke() - comparable pairs (returns true)
    //
    // ----------------------------------------------------------------

    /**
     * @param mixed $first
     * @param mixed $second
     */
    #[DataProvider('comparablePairProvider')]
    #[TestDox('->__invoke() returns true for $description')]
    public function test_invoke_returns_true_for_comparable_pair(
        string $description,
        mixed $first,
        mixed $second,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every comparable pair. This test pins that ->__invoke() is
        // a thin shim over ::check() - if the two ever diverge,
        // callers who pass an IsComparablePair instance as a
        // `callable` would see different behaviour from callers using
        // the static entry point.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsComparablePair();
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($first, $second);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // __invoke() - non-comparable pairs (returns false)
    //
    // ----------------------------------------------------------------

    /**
     * @param mixed $first
     * @param mixed $second
     */
    #[DataProvider('nonComparablePairProvider')]
    #[TestDox('->__invoke() returns false for $description')]
    public function test_invoke_returns_false_for_non_comparable_pair(
        string $description,
        mixed $first,
        mixed $second,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the invokable form must agree with the static form for
        // every non-comparable pair. Without this pinning, the two
        // forms could silently disagree and produce subtly different
        // call-site behaviour depending on which entry point a caller
        // picked.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IsComparablePair();
        unset($description);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($first, $second);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
