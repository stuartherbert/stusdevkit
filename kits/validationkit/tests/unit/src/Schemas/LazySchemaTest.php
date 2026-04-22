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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\LazySchema;

#[TestDox(LazySchema::class)]
class LazySchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the lazy wrapper namespace is part of the contract —
        // Validate::lazy() imports the class by FQN.

        $expected = 'StusDevKit\\ValidationKit\\Schemas';

        $actual = (new ReflectionClass(LazySchema::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(LazySchema::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('implements ValidationSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(LazySchema::class);
        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('does not extend BaseSchema')]
    public function test_does_not_extend_BaseSchema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // LazySchema is a pure delegator — it implements the
        // ValidationSchema interface directly and forwards every
        // call to a resolved inner schema. Extending BaseSchema
        // would give it unused pipeline state.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(LazySchema::class);

        // ----------------------------------------------------------------
        // test the results

        $parent = $reflection->getParentClass();
        $this->assertFalse($parent);
    }

    // ================================================================
    //
    // Shape — own public method
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes resolvedSchema() as a public method')]
    public function test_resolvedSchema_is_public(): void
    {
        $reflection = new ReflectionClass(LazySchema::class);
        $this->assertTrue(
            $reflection->hasMethod('resolvedSchema'),
        );
        $this->assertTrue(
            $reflection->getMethod('resolvedSchema')->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — deferred resolution
    //
    // ----------------------------------------------------------------

    #[TestDox('the factory closure is not invoked during construction')]
    public function test_factory_not_called_on_construct(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the whole point of LazySchema is to defer resolution
        // until first validation — otherwise recursive schema
        // definitions could not be expressed in PHP.

        // ----------------------------------------------------------------
        // setup your test

        // counter to observe whether the factory has run yet
        $invocations = 0;

        // ----------------------------------------------------------------
        // perform the change

        $unit = new LazySchema(
            static function () use (&$invocations): IntSchema {
                $invocations++;
                return new IntSchema();
            },
        );
        unset($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $invocations);
    }

    #[TestDox('->parse() invokes the factory once on first use')]
    public function test_parse_resolves_factory_on_first_use(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $invocations = 0;
        $schema = new LazySchema(
            static function () use (&$invocations): IntSchema {
                $invocations++;
                return new IntSchema();
            },
        );

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $invocations);
    }

    #[TestDox('the factory closure is cached across multiple ->parse() calls')]
    public function test_factory_cached_across_calls(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $invocations = 0;
        $schema = new LazySchema(
            static function () use (&$invocations): IntSchema {
                $invocations++;
                return new IntSchema();
            },
        );

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse(1);
        $schema->parse(2);
        $schema->parse(3);

        // ----------------------------------------------------------------
        // test the results

        // one resolution shared by all three parses
        $this->assertSame(1, $invocations);
    }

    #[TestDox('->parse() delegates validation to the resolved schema')]
    public function test_parse_delegates_to_resolved(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new LazySchema(
            static fn(): IntSchema => new IntSchema(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('->parse() throws ValidationException when the resolved schema rejects the input')]
    public function test_parse_throws_when_resolved_rejects(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new LazySchema(
            static fn(): IntSchema => new IntSchema(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse('not-an-int');
    }

    #[TestDox('->safeParse() returns a successful ParseResult on valid input')]
    public function test_safeParse_ok_on_valid_input(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $schema = new LazySchema(
            static fn(): IntSchema => new IntSchema(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertSame(42, $result->data());
    }

    #[TestDox('->resolvedSchema() returns the inner schema produced by the factory')]
    public function test_resolvedSchema_returns_inner(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $inner = new IntSchema();
        $schema = new LazySchema(
            static fn(): IntSchema => $inner,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->resolvedSchema();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inner, $actual);
    }

    // ================================================================
    //
    // Behaviour — metadata delegation
    //
    // ----------------------------------------------------------------

    #[TestDox('->withTitle() round-trips through ->maybeTitle() on the lazy wrapper')]
    public function test_withTitle_round_trips(): void
    {
        $schema = new LazySchema(
            static fn(): IntSchema => new IntSchema(),
        );
        $clone = $schema->withTitle('Lazy Title');
        $this->assertSame('Lazy Title', $clone->maybeTitle());
    }
}
