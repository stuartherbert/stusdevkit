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
use StusDevKit\ValidationKit\Schemas\Builtins\LiteralSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ObjectSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Logic\DiscriminatedAnyOfSchema;

#[TestDox(DiscriminatedAnyOfSchema::class)]
class DiscriminatedAnyOfSchemaTest extends TestCase
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
        // wire the schema by FQN, so moving it would break them.
        $reflection = new ReflectionClass(DiscriminatedAnyOfSchema::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Logic',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is not declared final so it can be extended by bespoke subclasses')]
    public function test_is_not_final(): void
    {
        // the broader schema family is all non-final, and this
        // schema inherits that openness.
        $reflection = new ReflectionClass(DiscriminatedAnyOfSchema::class);

        $this->assertFalse($reflection->isFinal());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema to inherit the full parse pipeline')]
    public function test_extends_BaseSchema(): void
    {
        // the shared parse pipeline (defaults, catch, coercion,
        // steps) comes from BaseSchema — this contract pins the
        // inheritance.
        $reflection = new ReflectionClass(DiscriminatedAnyOfSchema::class);

        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares __construct, discriminator and schemaMap as its own public methods')]
    public function test_declares_own_public_method_set(): void
    {
        // these three methods are the entire locally-declared
        // public API. Pinning the set catches accidental surface
        // changes.
        $reflection = new ReflectionClass(DiscriminatedAnyOfSchema::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === DiscriminatedAnyOfSchema::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);

        $this->assertSame(
            ['__construct', 'discriminator', 'schemaMap'],
            $ownMethods,
        );
    }

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        // callers use named arguments, so parameter names are
        // part of the public contract.
        $method = new ReflectionMethod(DiscriminatedAnyOfSchema::class, '__construct');

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        $this->assertSame(
            ['discriminator', 'schemas', 'typeCheckError'],
            $paramNames,
        );
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->discriminator() returns the field name stored at construction time')]
    public function test_discriminator_returns_field_name(): void
    {
        // the discriminator name is what the schema looks for in
        // the input's top-level properties.
        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [],
        );

        $this->assertSame('type', $unit->discriminator());
    }

    #[TestDox('->schemaMap() returns the map of discriminator values to their schemas')]
    public function test_schemaMap_returns_map_of_values_to_schemas(): void
    {
        // the schema map is built at construction time from the
        // supplied schemas by inspecting each ObjectSchema for a
        // LiteralSchema under the discriminator key.
        $clickSchema = new ObjectSchema([
            'type' => new LiteralSchema('click'),
            'x' => new IntSchema(),
        ]);
        $keypressSchema = new ObjectSchema([
            'type' => new LiteralSchema('keypress'),
            'key' => new StringSchema(),
        ]);

        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [$clickSchema, $keypressSchema],
        );

        $this->assertSame(
            [
                'click' => $clickSchema,
                'keypress' => $keypressSchema,
            ],
            $unit->schemaMap(),
        );
    }

    #[TestDox('->schemaMap() skips schemas that are not ObjectSchema instances')]
    public function test_schemaMap_skips_non_object_schemas(): void
    {
        // only ObjectSchema branches can carry a literal
        // discriminator field — anything else is ignored.
        $clickSchema = new ObjectSchema([
            'type' => new LiteralSchema('click'),
            'x' => new IntSchema(),
        ]);

        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [$clickSchema, new StringSchema()],
        );

        $this->assertSame(
            ['click' => $clickSchema],
            $unit->schemaMap(),
        );
    }

    #[TestDox('->schemaMap() skips ObjectSchema branches that lack a literal discriminator field')]
    public function test_schemaMap_skips_branches_without_literal_discriminator(): void
    {
        // an ObjectSchema whose discriminator slot is not a
        // LiteralSchema cannot be indexed — silently dropped.
        $clickSchema = new ObjectSchema([
            'type' => new LiteralSchema('click'),
        ]);
        $fuzzySchema = new ObjectSchema([
            'type' => new StringSchema(),
        ]);

        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [$clickSchema, $fuzzySchema],
        );

        $this->assertSame(
            ['click' => $clickSchema],
            $unit->schemaMap(),
        );
    }

    #[TestDox('->parse() picks the branch matching the discriminator value')]
    public function test_parse_selects_branch_by_discriminator_value(): void
    {
        // happy path — input carries a known discriminator value,
        // so the matching branch runs against the input.
        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [
                new ObjectSchema([
                    'type' => new LiteralSchema('click'),
                    'x' => new IntSchema(),
                ]),
                new ObjectSchema([
                    'type' => new LiteralSchema('keypress'),
                    'key' => new StringSchema(),
                ]),
            ],
        );

        $input = new \stdClass();
        $input->type = 'click';
        $input->x = 10;

        $actual = $unit->parse($input);

        $this->assertInstanceOf(\stdClass::class, $actual);
        $this->assertSame('click', $actual->type);
        $this->assertSame(10, $actual->x);
    }

    #[TestDox('->parse() throws ValidationException when the input is not array-like')]
    public function test_parse_rejects_non_array_input(): void
    {
        // a string cannot carry a discriminator field, so the
        // type check reports the issue.
        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [
                new ObjectSchema([
                    'type' => new LiteralSchema('click'),
                ]),
            ],
        );

        $this->expectException(ValidationException::class);
        $unit->parse('not an object');
    }

    #[TestDox('->parse() throws ValidationException when the discriminator field is missing')]
    public function test_parse_rejects_input_missing_discriminator(): void
    {
        // the discriminator field is required — without it, we
        // have no way to pick a branch.
        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [
                new ObjectSchema([
                    'type' => new LiteralSchema('click'),
                ]),
            ],
        );

        $this->expectException(ValidationException::class);
        $unit->parse(['x' => 10]);
    }

    #[TestDox('->parse() throws ValidationException when the discriminator value is unknown')]
    public function test_parse_rejects_unknown_discriminator_value(): void
    {
        // the discriminator is present but names a branch we
        // do not know about.
        $unit = new DiscriminatedAnyOfSchema(
            discriminator: 'type',
            schemas: [
                new ObjectSchema([
                    'type' => new LiteralSchema('click'),
                ]),
            ],
        );

        $this->expectException(ValidationException::class);
        $unit->parse(['type' => 'mystery']);
    }
}
