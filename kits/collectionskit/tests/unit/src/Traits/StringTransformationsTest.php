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

namespace StusDevKit\CollectionsKit\Tests\Unit\Traits;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\CollectionsKit\Lists\ListOfStrings;
use StusDevKit\CollectionsKit\Traits\StringTransformations;

/**
 * Contract + behaviour tests for the StringTransformations trait.
 *
 * These tests act as a lockdown on the trait's published shape and
 * observed runtime behaviour: renaming a method, altering a
 * parameter, or changing the in-place transformation contract must
 * be an intentional act that updates these tests at the same time.
 *
 * Behaviour is exercised through ListOfStrings, the canonical
 * using-class. ListOfStrings adds nothing to the trait beyond
 * `use StringTransformations;`, so it acts as a thin harness that
 * lets us reach the trait's methods through a real collection.
 */
#[TestDox(StringTransformations::class)]
class StringTransformationsTest extends TestCase
{
    // ================================================================
    //
    // Trait identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as a trait')]
    public function test_is_declared_as_a_trait(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // StringTransformations must be a trait (not a class or
        // interface). Using collections rely on this so they can
        // declare `use StringTransformations;` in their body.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(StringTransformations::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Traits namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - using
        // collections import the trait by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Traits';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(StringTransformations::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('exposes only applyTrim, applyLtrim, and applyRtrim methods')]
    public function test_exposes_only_the_three_apply_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the trait exists to supply three in-place string trimming
        // methods. Adding a fourth is a surface-area expansion that
        // every using collection inherits, so the method set is
        // pinned by enumeration - any addition fails with a diff
        // that names the new method, rather than a cryptic count
        // mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['applyTrim', 'applyLtrim', 'applyRtrim'];
        $reflection = new ReflectionClass(StringTransformations::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // applyTrim() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::applyTrim() is declared')]
    public function test_declares_an_applyTrim_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // renaming applyTrim is a breaking change for every using
        // collection's callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(StringTransformations::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('applyTrim');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::applyTrim() is public')]
    public function test_applyTrim_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public so callers can invoke it on the
        // using collection instance.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyTrim',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::applyTrim() is an instance method, not static')]
    public function test_applyTrim_is_not_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // applyTrim mutates the using collection's stored data, so
        // it must be an instance method. A silent upgrade to static
        // would break the in-place contract.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyTrim',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('::applyTrim() declares a single `characters` string parameter')]
    public function test_applyTrim_parameter_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the public contract because
        // callers use named arguments. Renaming it is a breaking
        // change. The type must be `string` because we pass the
        // value straight through to PHP's trim().

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyTrim',
        );
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $type = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $parameters[0]->getName();
        $actualType = $type->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('characters', $actualName);
        $this->assertSame('string', $actualType);
    }

    #[TestDox('::applyTrim() defaults the character mask to PHP\'s default trim set')]
    public function test_applyTrim_default_character_mask(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default value is the same whitespace mask PHP's trim()
        // uses. Pinning it here means a silent change to the default
        // (e.g. dropping the NUL byte) is caught by a failing test
        // rather than by a surprised caller.

        // ----------------------------------------------------------------
        // setup your test

        $expected = " \n\r\t\v\0";
        $parameter = (new ReflectionMethod(
            StringTransformations::class,
            'applyTrim',
        ))->getParameters()[0];
        $this->assertTrue($parameter->isDefaultValueAvailable());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $parameter->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::applyTrim() declares a `static` return type')]
    public function test_applyTrim_declares_a_static_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is `static` so that chained calls preserve
        // the using-collection's concrete type. A silent downgrade
        // to `self` or `void` would break fluent chaining against
        // subclasses.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'static';
        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyTrim',
        );
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // applyLtrim() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::applyLtrim() is declared')]
    public function test_declares_an_applyLtrim_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // renaming applyLtrim is a breaking change for every using
        // collection's callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(StringTransformations::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('applyLtrim');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::applyLtrim() is public')]
    public function test_applyLtrim_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public so callers can invoke it on the
        // using collection instance.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyLtrim',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::applyLtrim() is an instance method, not static')]
    public function test_applyLtrim_is_not_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // applyLtrim mutates the using collection's stored data, so
        // it must be an instance method.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyLtrim',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('::applyLtrim() declares a single `characters` string parameter')]
    public function test_applyLtrim_parameter_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the public contract because
        // callers use named arguments. The type must be `string`
        // because we pass the value straight through to PHP's
        // ltrim().

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyLtrim',
        );
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $type = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $parameters[0]->getName();
        $actualType = $type->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('characters', $actualName);
        $this->assertSame('string', $actualType);
    }

    #[TestDox('::applyLtrim() defaults the character mask to PHP\'s default trim set')]
    public function test_applyLtrim_default_character_mask(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default must match applyTrim's default so the three
        // methods have consistent defaults across the trait.

        // ----------------------------------------------------------------
        // setup your test

        $expected = " \n\r\t\v\0";
        $parameter = (new ReflectionMethod(
            StringTransformations::class,
            'applyLtrim',
        ))->getParameters()[0];
        $this->assertTrue($parameter->isDefaultValueAvailable());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $parameter->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::applyLtrim() declares a `static` return type')]
    public function test_applyLtrim_declares_a_static_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is `static` so chained calls preserve the
        // using-collection's concrete type.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'static';
        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyLtrim',
        );
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // applyRtrim() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::applyRtrim() is declared')]
    public function test_declares_an_applyRtrim_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // renaming applyRtrim is a breaking change for every using
        // collection's callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(StringTransformations::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('applyRtrim');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::applyRtrim() is public')]
    public function test_applyRtrim_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public so callers can invoke it on the
        // using collection instance.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyRtrim',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::applyRtrim() is an instance method, not static')]
    public function test_applyRtrim_is_not_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // applyRtrim mutates the using collection's stored data, so
        // it must be an instance method.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyRtrim',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('::applyRtrim() declares a single `characters` string parameter')]
    public function test_applyRtrim_parameter_signature(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter name is part of the public contract because
        // callers use named arguments. The type must be `string`
        // because we pass the value straight through to PHP's
        // rtrim().

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyRtrim',
        );
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $type = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $parameters[0]->getName();
        $actualType = $type->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('characters', $actualName);
        $this->assertSame('string', $actualType);
    }

    #[TestDox('::applyRtrim() defaults the character mask to PHP\'s default trim set')]
    public function test_applyRtrim_default_character_mask(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default must match applyTrim's default so the three
        // methods have consistent defaults across the trait.

        // ----------------------------------------------------------------
        // setup your test

        $expected = " \n\r\t\v\0";
        $parameter = (new ReflectionMethod(
            StringTransformations::class,
            'applyRtrim',
        ))->getParameters()[0];
        $this->assertTrue($parameter->isDefaultValueAvailable());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $parameter->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::applyRtrim() declares a `static` return type')]
    public function test_applyRtrim_declares_a_static_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is `static` so chained calls preserve the
        // using-collection's concrete type.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'static';
        $method = new ReflectionMethod(
            StringTransformations::class,
            'applyRtrim',
        );
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // applyTrim() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyTrim() strips default whitespace from both ends of every value')]
    public function test_applyTrim_strips_default_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when invoked with no argument, applyTrim must strip PHP's
        // default whitespace mask from both ends of every string in
        // the collection. We include a mix of leading, trailing, and
        // bilateral whitespace to exercise the full contract.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['apple', 'banana', 'cherry'];
        $unit = new ListOfStrings([
            '  apple',
            "banana\n",
            "\t cherry \r\n",
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    #[TestDox('->applyTrim() strips the caller-supplied character mask')]
    public function test_applyTrim_strips_custom_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when given a custom character mask, applyTrim must pass it
        // to PHP's trim() unchanged. We use punctuation characters
        // that are NOT in the default mask, so a silent fallback to
        // the default would leave them in place and fail the test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['apple', 'banana', 'cherry'];
        $unit = new ListOfStrings([
            '--apple!!',
            '!banana-',
            '-!cherry!!',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim(characters: '-!');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    #[TestDox('->applyTrim() returns $this for fluent chaining')]
    public function test_applyTrim_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must return the same collection instance so
        // callers can chain further operations. Returning a new
        // object would break in-place mutation semantics.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  apple  ']);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $actual);
    }

    #[TestDox('->applyTrim() is a no-op on an empty collection')]
    public function test_applyTrim_is_noop_on_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the minimum-size input - an empty collection - must still
        // complete without error and leave the collection empty.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyTrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    // ================================================================
    //
    // applyLtrim() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyLtrim() strips default whitespace from the left of every value')]
    public function test_applyLtrim_strips_default_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // applyLtrim must only remove whitespace from the left side.
        // The fixture includes trailing whitespace on each value so
        // that a silent upgrade to trim() would fail this test by
        // stripping too much.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['apple  ', "banana\t", "cherry \n"];
        $unit = new ListOfStrings([
            '  apple  ',
            "\tbanana\t",
            " \ncherry \n",
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    #[TestDox('->applyLtrim() strips the caller-supplied character mask')]
    public function test_applyLtrim_strips_custom_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when given a custom character mask, applyLtrim must pass
        // it to PHP's ltrim() unchanged. Trailing instances of the
        // mask characters must remain untouched.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['apple--', 'banana!', 'cherry-!'];
        $unit = new ListOfStrings([
            '--apple--',
            '!banana!',
            '-!cherry-!',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim(characters: '-!');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    #[TestDox('->applyLtrim() returns $this for fluent chaining')]
    public function test_applyLtrim_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must return the same collection instance so
        // callers can chain further operations.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['  apple']);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $actual);
    }

    #[TestDox('->applyLtrim() is a no-op on an empty collection')]
    public function test_applyLtrim_is_noop_on_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the minimum-size input - an empty collection - must still
        // complete without error and leave the collection empty.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyLtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    // ================================================================
    //
    // applyRtrim() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyRtrim() strips default whitespace from the right of every value')]
    public function test_applyRtrim_strips_default_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // applyRtrim must only remove whitespace from the right
        // side. The fixture includes leading whitespace on each
        // value so that a silent upgrade to trim() would fail this
        // test by stripping too much.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['  apple', "\tbanana", " \ncherry"];
        $unit = new ListOfStrings([
            '  apple  ',
            "\tbanana\t",
            " \ncherry \n",
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    #[TestDox('->applyRtrim() strips the caller-supplied character mask')]
    public function test_applyRtrim_strips_custom_characters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when given a custom character mask, applyRtrim must pass
        // it to PHP's rtrim() unchanged. Leading instances of the
        // mask characters must remain untouched.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['--apple', '!banana', '-!cherry'];
        $unit = new ListOfStrings([
            '--apple--',
            '!banana!',
            '-!cherry-!',
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim(characters: '-!');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }

    #[TestDox('->applyRtrim() returns $this for fluent chaining')]
    public function test_applyRtrim_returns_this(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must return the same collection instance so
        // callers can chain further operations.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new ListOfStrings(['apple  ']);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($unit, $actual);
    }

    #[TestDox('->applyRtrim() is a no-op on an empty collection')]
    public function test_applyRtrim_is_noop_on_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the minimum-size input - an empty collection - must still
        // complete without error and leave the collection empty.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $unit = new ListOfStrings();

        // ----------------------------------------------------------------
        // perform the change

        $unit->applyRtrim();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $unit->toArray());
    }
}
