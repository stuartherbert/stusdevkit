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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Contracts;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison;
use StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext;

#[TestDox(NormalisesForComparison::class)]
class NormalisesForComparisonTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // NormalisesForComparison is a single-method contract that
        // implementors opt into. Downgrading to a class or trait
        // would break every `implements NormalisesForComparison`
        // and every `instanceof NormalisesForComparison` check.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(NormalisesForComparison::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Contracts namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract. Callers
        // import NormalisesForComparison by its FQN, so moving
        // it is a breaking change that must go through a major
        // version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Contracts';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(NormalisesForComparison::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('extends no other interface')]
    public function test_extends_no_other_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the contract is standalone by design. Accidentally
        // declaring `extends Stringable` (or any other interface)
        // would silently add a requirement to every implementor,
        // and broaden every `instanceof NormalisesForComparison`
        // check to also match the parent interface's implementors.
        // Pin the no-parent shape so that drift fails this test
        // rather than landing as a silent breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $reflection = new ReflectionClass(NormalisesForComparison::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('publishes exactly the getNormalisedForComparison method')]
    public function test_publishes_the_expected_method_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the enumerated method set. The interface is a single
        // method by design - extending it with siblings is a
        // major-version event, so drift shows up here as a named
        // diff rather than "expected 1, got 2".

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['getNormalisedForComparison'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method): string => $method->getName(),
            (new ReflectionClass(NormalisesForComparison::class))->getMethods(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::getNormalisedForComparison() is public')]
    public function test_method_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public for callers (like
        // GetNormalisedForComparison) to invoke it on an arbitrary
        // implementor without reflection. Interface methods are
        // always public in PHP, but pin it explicitly so a future
        // refactor that swapped to an abstract class would still
        // be caught.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisesForComparison::class))
            ->getMethod('getNormalisedForComparison');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::getNormalisedForComparison() takes exactly one parameter')]
    public function test_method_takes_one_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the contract carries one parameter - the
        // NormalisationContext threaded down from the parent walk -
        // and that is the whole API surface. Adding a second
        // parameter changes the contract for every implementor;
        // dropping the one we have re-introduces the cycle-loop
        // footgun. Pin the count so drift fails this test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 1;
        $method = (new ReflectionClass(NormalisesForComparison::class))
            ->getMethod('getNormalisedForComparison');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::getNormalisedForComparison() names its parameter $context')]
    public function test_method_parameter_is_named_context(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // implementors copy the parameter name into their own
        // signature (PHP allows renaming but every implementor in
        // the kit uses `$context`), and docblock prose addresses
        // the parameter by name. Pin the name so a rename in the
        // interface flushes both downstream copies and docs at
        // once.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'context';
        $method = (new ReflectionClass(NormalisesForComparison::class))
            ->getMethod('getNormalisedForComparison');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getParameters()[0]->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::getNormalisedForComparison()\'s parameter is typed as NormalisationContext')]
    public function test_method_parameter_is_typed_as_normalisation_context(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the typed parameter is what makes the cycle-safety
        // guarantee load-bearing: an implementor cannot accept the
        // context without typing it correctly, and the caller
        // (GetNormalisedForComparison) cannot supply anything else.
        // Widening to `mixed` or `object` would let implementors
        // accept the wrong thing silently.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisesForComparison::class))
            ->getMethod('getNormalisedForComparison');

        // ----------------------------------------------------------------
        // perform the change

        $type = $method->getParameters()[0]->getType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame(NormalisationContext::class, $type->getName());
    }

    #[TestDox('::getNormalisedForComparison()\'s parameter is required, not optional')]
    public function test_method_parameter_is_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a default-null parameter would re-introduce the loop
        // footgun: implementors could forget to thread the context
        // through their recursive calls and the compiler would let
        // them. Pin that the parameter is required so forgetting
        // becomes a static error rather than a runtime infinite
        // loop.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NormalisesForComparison::class))
            ->getMethod('getNormalisedForComparison');

        // ----------------------------------------------------------------
        // perform the change

        $parameter = $method->getParameters()[0];

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($parameter->isOptional());
        $this->assertFalse($parameter->allowsNull());
    }

    #[TestDox('::getNormalisedForComparison() returns mixed')]
    public function test_method_returns_mixed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // implementors return a fully-normalised value of their
        // own choosing - a scalar for value objects, a keyed
        // array for dicts, an ordered array for lists, etc.
        // Narrowing the return type to (say) `array` would force
        // every implementor to wrap scalars or refuse them
        // outright.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'mixed';
        $method = (new ReflectionClass(NormalisesForComparison::class))
            ->getMethod('getNormalisedForComparison');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame($expected, $returnType->getName());
    }
}
