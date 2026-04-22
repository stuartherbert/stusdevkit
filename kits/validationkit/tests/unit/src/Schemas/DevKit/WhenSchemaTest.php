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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\DevKit;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use StusDevKit\DateTimeKit\When;
use StusDevKit\ValidationKit\Coercions\CoerceToWhen;
use StusDevKit\ValidationKit\Coercions\NoCoercion;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\DevKit\WhenSchema;

#[TestDox(WhenSchema::class)]
class WhenSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\DevKit namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the published namespace is part of the contract —
        // moving it breaks every caller that wires the schema
        // by FQN.
        $reflection = new ReflectionClass(WhenSchema::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\DevKit',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is not declared final so it can be extended by bespoke subclasses')]
    public function test_is_not_final(): void
    {
        // matches the rest of the schema family.
        $reflection = new ReflectionClass(WhenSchema::class);

        $this->assertFalse($reflection->isFinal());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema to inherit the full parse pipeline')]
    public function test_extends_BaseSchema(): void
    {
        // defaults / catch / steps / pipe all come from BaseSchema.
        $reflection = new ReflectionClass(WhenSchema::class);

        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares __construct, min, max and coerce as its own public methods')]
    public function test_declares_own_public_method_set(): void
    {
        // these four methods are the entire locally-declared
        // public API. Pinning the set catches accidental
        // surface changes.
        $reflection = new ReflectionClass(WhenSchema::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === WhenSchema::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame(
            ['__construct', 'coerce', 'max', 'min'],
            $ownMethods,
        );
    }

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        // the constructor accepts an optional error callback
        // override.
        $method = new ReflectionMethod(WhenSchema::class, '__construct');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(['typeCheckError'], $paramNames);
    }

    #[TestDox('->min() parameter names in order')]
    public function test_min_parameter_names(): void
    {
        // fluent constraint-builder API — callers use named
        // arguments.
        $method = new ReflectionMethod(WhenSchema::class, 'min');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(['date', 'error'], $paramNames);
    }

    #[TestDox('->max() parameter names in order')]
    public function test_max_parameter_names(): void
    {
        // `max()` mirrors `min()` — same shape.
        $method = new ReflectionMethod(WhenSchema::class, 'max');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(['date', 'error'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() returns the same When instance that was supplied')]
    public function test_parse_accepts_When_instance(): void
    {
        // happy path — a When instance satisfies the type
        // check and passes through unchanged.
        $input = When::from('2026-04-19');
        $unit = new WhenSchema();

        $actual = $unit->parse($input);

        $this->assertSame($input, $actual);
    }

    #[TestDox('->parse() throws ValidationException when the input is a DateTimeImmutable that is not a When')]
    public function test_parse_rejects_plain_DateTimeImmutable(): void
    {
        // a plain DateTimeImmutable is not a When — without
        // coercion enabled, it fails the type check.
        $unit = new WhenSchema();

        $this->expectException(ValidationException::class);
        $unit->parse(new DateTimeImmutable('2026-04-19T00:00:00+00:00'));
    }

    #[TestDox('->parse() throws ValidationException when the input is a string')]
    public function test_parse_rejects_string_input_without_coercion(): void
    {
        // without coercion, a date string is just a string —
        // rejected.
        $unit = new WhenSchema();

        $this->expectException(ValidationException::class);
        $unit->parse('2026-04-19');
    }

    #[TestDox('->parse() throws ValidationException when the input is an integer')]
    public function test_parse_rejects_integer_input_without_coercion(): void
    {
        // without coercion, a Unix timestamp is just an int —
        // rejected.
        $unit = new WhenSchema();

        $this->expectException(ValidationException::class);
        $unit->parse(1_700_000_000);
    }

    #[TestDox('->parse() throws ValidationException when the input is null')]
    public function test_parse_rejects_null_input(): void
    {
        // null is never a When — rejected outright.
        $unit = new WhenSchema();

        $this->expectException(ValidationException::class);
        $unit->parse(null);
    }

    #[TestDox('->coerce() returns a new instance (immutable builder)')]
    public function test_coerce_returns_a_new_instance(): void
    {
        // builder methods on BaseSchema clone before mutating.
        $unit = new WhenSchema();

        $coerced = $unit->coerce();

        $this->assertNotSame($unit, $coerced);
        $this->assertInstanceOf(WhenSchema::class, $coerced);
    }

    #[TestDox('->coerce() swaps the schema\'s coercion strategy to CoerceToWhen')]
    public function test_coerce_installs_CoerceToWhen(): void
    {
        // coerce() switches the internal coercion from
        // NoCoercion to CoerceToWhen. Reflection is the only
        // way to observe this without running parse().
        $unit = new WhenSchema();
        $coerced = $unit->coerce();

        $reflection = new ReflectionClass(BaseSchema::class);
        $coercionProperty = $reflection->getProperty('coercion');

        $this->assertInstanceOf(
            NoCoercion::class,
            $coercionProperty->getValue($unit),
        );
        $this->assertInstanceOf(
            CoerceToWhen::class,
            $coercionProperty->getValue($coerced),
        );
    }

    #[TestDox('->parse() promotes an ISO 8601 string to a When when coercion is enabled')]
    public function test_parse_coerces_string_to_When(): void
    {
        // once coerce() is on, ISO 8601 strings are promoted
        // to When via When::from() before the type check runs.
        $unit = (new WhenSchema())->coerce();

        $actual = $unit->parse('2026-04-19');

        $this->assertInstanceOf(When::class, $actual);
    }

    #[TestDox('->parse() promotes an integer Unix timestamp to a When when coercion is enabled')]
    public function test_parse_coerces_integer_to_When(): void
    {
        // coercion accepts Unix timestamps as well — they go
        // through When::from() and become full When instances.
        $unit = (new WhenSchema())->coerce();

        $actual = $unit->parse(1_700_000_000);

        $this->assertInstanceOf(When::class, $actual);
    }

    #[TestDox('->min() returns a new instance (immutable builder)')]
    public function test_min_returns_a_new_instance(): void
    {
        // adding a constraint produces a new schema — the
        // original is unaffected.
        $unit = new WhenSchema();

        $withMin = $unit->min(date: When::from('2020-01-01'));

        $this->assertNotSame($unit, $withMin);
        $this->assertInstanceOf(WhenSchema::class, $withMin);
    }

    #[TestDox('->max() returns a new instance (immutable builder)')]
    public function test_max_returns_a_new_instance(): void
    {
        // same immutability rule as `min()`.
        $unit = new WhenSchema();

        $withMax = $unit->max(date: When::from('2030-12-31'));

        $this->assertNotSame($unit, $withMax);
        $this->assertInstanceOf(WhenSchema::class, $withMax);
    }

    #[TestDox('->parse() throws ValidationException when the date is before the configured min')]
    public function test_parse_enforces_min_constraint(): void
    {
        // min is inclusive — a When before the minimum
        // violates the constraint and produces an exception.
        $unit = (new WhenSchema())->min(date: When::from('2020-01-01'));

        $this->expectException(ValidationException::class);
        $unit->parse(When::from('2019-12-31'));
    }

    #[TestDox('->parse() throws ValidationException when the date is after the configured max')]
    public function test_parse_enforces_max_constraint(): void
    {
        // max is inclusive — a When after the maximum violates
        // the constraint and produces an exception.
        $unit = (new WhenSchema())->max(date: When::from('2030-12-31'));

        $this->expectException(ValidationException::class);
        $unit->parse(When::from('2031-01-01'));
    }
}
