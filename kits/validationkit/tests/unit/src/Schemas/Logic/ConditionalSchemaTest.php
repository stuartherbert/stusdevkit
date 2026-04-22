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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Logic;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Logic\ConditionalSchema;

#[TestDox(ConditionalSchema::class)]
class ConditionalSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Logic namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the published namespace is part of the contract — callers
        // use the FQN when wiring the schema, so moving it is a
        // breaking change.
        $reflection = new ReflectionClass(ConditionalSchema::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Logic',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is not declared final so it can be extended by bespoke subclasses')]
    public function test_is_not_final(): void
    {
        // ConditionalSchema is intended to be a drop-in for the
        // JSON Schema if/then/else pattern, and leaving it open
        // matches the rest of the schema family.
        $reflection = new ReflectionClass(ConditionalSchema::class);

        $this->assertFalse($reflection->isFinal());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema to inherit the full parse pipeline')]
    public function test_extends_BaseSchema(): void
    {
        // every concrete schema in the kit extends BaseSchema so
        // that pipelines, defaults, catch() and coercion all
        // behave consistently.
        $reflection = new ReflectionClass(ConditionalSchema::class);

        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares __construct, ifSchema, maybeThenSchema and maybeElseSchema as its own public methods')]
    public function test_declares_own_public_method_set(): void
    {
        // these four methods are the entire locally-declared public
        // API. Pinning the set catches accidental additions or
        // removals that would reshape the contract.
        $reflection = new ReflectionClass(ConditionalSchema::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ConditionalSchema::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame(
            ['__construct', 'ifSchema', 'maybeElseSchema', 'maybeThenSchema'],
            $ownMethods,
        );
    }

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        // the constructor is the primary wiring point — callers
        // use named arguments, so the parameter names are part of
        // the public contract.
        $method = new ReflectionMethod(ConditionalSchema::class, '__construct');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(['if', 'then', 'else'], $paramNames);
    }

    #[TestDox('::__construct() $then and $else default to null so they can be omitted')]
    public function test_construct_optional_parameters(): void
    {
        // either branch can be omitted — the spec says data passes
        // through unchanged when the matching branch is missing.
        $method = new ReflectionMethod(ConditionalSchema::class, '__construct');
        $params = $method->getParameters();

        $this->assertFalse($params[0]->isOptional());
        $this->assertTrue($params[1]->isOptional());
        $this->assertTrue($params[2]->isOptional());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->ifSchema() returns the condition schema stored at construction time')]
    public function test_ifSchema_returns_the_condition(): void
    {
        // callers that introspect a schema (e.g. JSON Schema
        // exporters) need the original condition schema back.
        $ifSchema = new StringSchema();
        $unit = new ConditionalSchema(if: $ifSchema);

        $this->assertSame($ifSchema, $unit->ifSchema());
    }

    #[TestDox('->maybeThenSchema() returns the then schema when one was supplied')]
    public function test_maybeThenSchema_returns_the_then_branch(): void
    {
        $thenSchema = new StringSchema();
        $unit = new ConditionalSchema(
            if: new StringSchema(),
            then: $thenSchema,
        );

        $this->assertSame($thenSchema, $unit->maybeThenSchema());
    }

    #[TestDox('->maybeThenSchema() returns null when no then schema was supplied')]
    public function test_maybeThenSchema_returns_null_when_absent(): void
    {
        // the `maybe` prefix signals the nullable return — callers
        // are expected to handle the null branch.
        $unit = new ConditionalSchema(if: new StringSchema());

        $this->assertNull($unit->maybeThenSchema());
    }

    #[TestDox('->maybeElseSchema() returns the else schema when one was supplied')]
    public function test_maybeElseSchema_returns_the_else_branch(): void
    {
        $elseSchema = new IntSchema();
        $unit = new ConditionalSchema(
            if: new StringSchema(),
            else: $elseSchema,
        );

        $this->assertSame($elseSchema, $unit->maybeElseSchema());
    }

    #[TestDox('->maybeElseSchema() returns null when no else schema was supplied')]
    public function test_maybeElseSchema_returns_null_when_absent(): void
    {
        $unit = new ConditionalSchema(if: new StringSchema());

        $this->assertNull($unit->maybeElseSchema());
    }

    #[TestDox('->parse() applies the then schema when the condition passes')]
    public function test_parse_applies_then_when_condition_passes(): void
    {
        // when `if` accepts the input, `then` runs — classic
        // JSON Schema if/then/else semantics.
        $unit = new ConditionalSchema(
            if: new StringSchema(),
            then: new StringSchema(),
            else: new IntSchema(),
        );

        $actual = $unit->parse('hello');

        $this->assertSame('hello', $actual);
    }

    #[TestDox('->parse() applies the else schema when the condition fails')]
    public function test_parse_applies_else_when_condition_fails(): void
    {
        // `if` rejects non-strings so the `else` branch runs,
        // which happily accepts an int.
        $unit = new ConditionalSchema(
            if: new StringSchema(),
            then: new StringSchema(),
            else: new IntSchema(),
        );

        $actual = $unit->parse(42);

        $this->assertSame(42, $actual);
    }

    #[TestDox('->parse() returns input unchanged when condition passes and no then schema was given')]
    public function test_parse_passes_through_when_then_is_absent(): void
    {
        // condition matches but nothing asked us to validate
        // further — data flows through unmodified.
        $unit = new ConditionalSchema(if: new StringSchema());

        $actual = $unit->parse('hello');

        $this->assertSame('hello', $actual);
    }

    #[TestDox('->parse() returns input unchanged when condition fails and no else schema was given')]
    public function test_parse_passes_through_when_else_is_absent(): void
    {
        // condition rejects the input, but no else branch was
        // supplied — data passes through unchanged.
        $unit = new ConditionalSchema(if: new StringSchema());

        $actual = $unit->parse(42);

        $this->assertSame(42, $actual);
    }

    #[TestDox('->parse() throws ValidationException when the selected branch rejects the input')]
    public function test_parse_throws_when_selected_branch_rejects(): void
    {
        // condition passes (input is a string), so `then` runs.
        // `then` is an IntSchema, which rejects the string —
        // that failure is the caller's problem.
        $unit = new ConditionalSchema(
            if: new StringSchema(),
            then: new IntSchema(),
        );

        $this->expectException(ValidationException::class);
        $unit->parse('hello');
    }
}
