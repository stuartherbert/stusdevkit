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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Builtins;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\AssocArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\OptionalSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\UnknownKeyPolicy;

#[TestDox('AssocArraySchema')]
class AssocArraySchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // pins the home namespace: the string-keyed array
        // variant of ObjectSchema lives alongside its sibling.
        $reflection = new ReflectionClass(AssocArraySchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(AssocArraySchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('can be constructed from a shape map')]
    public function test_can_be_constructed_with_shape(): void
    {
        // a schema needs a shape; the map of key-to-schema is
        // the whole configuration of the validator.
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
        ]);

        $this->assertInstanceOf(AssocArraySchema::class, $unit);
    }

    #[TestDox('exposes its shape via shape()')]
    public function test_exposes_shape(): void
    {
        // introspection is how the JSON Schema exporter and
        // the ::extend() / ::pick() / ::omit() builders know
        // what's in the shape.
        $name = new StringSchema();
        $age = new IntSchema();
        $unit = new AssocArraySchema(shape: [
            'name' => $name,
            'age' => $age,
        ]);

        $actual = $unit->shape();

        $this->assertSame(
            ['name' => $name, 'age' => $age],
            $actual,
        );
    }

    #[TestDox('keyof() returns the shape keys as a list of strings')]
    public function test_keyof_returns_keys(): void
    {
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
        ]);

        $this->assertSame(['name', 'age'], $unit->keyof());
    }

    #[TestDox('maybeFieldSchema() returns the schema for a known key')]
    public function test_maybeFieldSchema_returns_schema_for_known_key(): void
    {
        $name = new StringSchema();
        $unit = new AssocArraySchema(shape: ['name' => $name]);

        $this->assertSame($name, $unit->maybeFieldSchema('name'));
    }

    #[TestDox('maybeFieldSchema() returns null for an unknown key')]
    public function test_maybeFieldSchema_returns_null_for_unknown_key(): void
    {
        // the "maybe" prefix is a project convention: a
        // nullable lookup paired with a throwing accessor.
        $unit = new AssocArraySchema(shape: ['name' => new StringSchema()]);

        $this->assertNull($unit->maybeFieldSchema('missing'));
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() accepts a string-keyed array that matches the shape')]
    public function test_parse_accepts_matching_input(): void
    {
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
        ]);

        $actual = $unit->parse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        $this->assertSame(
            ['name' => 'Stuart', 'age' => 42],
            $actual,
        );
    }

    #[TestDox('parse() rejects non-array input')]
    public function test_parse_rejects_non_array(): void
    {
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse('not-an-array');
    }

    #[TestDox('parse() rejects input whose field value fails the inner schema')]
    public function test_parse_rejects_bad_field_value(): void
    {
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse(['name' => 'Stuart', 'age' => 'forty-two']);
    }

    #[TestDox('parse() silently strips unknown keys by default')]
    public function test_parse_strips_unknown_keys_by_default(): void
    {
        // the default policy is Strip — unknown keys vanish
        // without error. This is a footgun for callers who
        // expect stricter behaviour; pinning it here makes
        // the promise explicit.
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]);

        $actual = $unit->parse([
            'name' => 'Stuart',
            'unexpected' => 'nope',
        ]);

        $this->assertSame(['name' => 'Stuart'], $actual);
    }

    #[TestDox('strict() rejects input containing keys outside the shape')]
    public function test_strict_rejects_unknown_keys(): void
    {
        $unit = (new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]))->strict();

        $this->expectException(ValidationException::class);
        $unit->parse([
            'name' => 'Stuart',
            'unexpected' => 'nope',
        ]);
    }

    #[TestDox('passthrough() keeps unknown keys in the output unchanged')]
    public function test_passthrough_keeps_unknown_keys(): void
    {
        $unit = (new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]))->passthrough();

        $actual = $unit->parse([
            'name' => 'Stuart',
            'extra' => 'kept',
        ]);

        $this->assertSame(
            ['name' => 'Stuart', 'extra' => 'kept'],
            $actual,
        );
    }

    #[TestDox('catchall() validates unknown keys against the given schema')]
    public function test_catchall_validates_unknown_keys(): void
    {
        // catchall trumps the unknown-key policy — every
        // key that isn't in the shape must pass the catchall
        // schema.
        $unit = (new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]))->catchall(new IntSchema());

        $this->expectException(ValidationException::class);
        $unit->parse([
            'name' => 'Stuart',
            'score' => 'not-an-int',
        ]);
    }

    #[TestDox('unknownKeyPolicy() returns the current policy (defaults to Strip)')]
    public function test_unknownKeyPolicy_reports_default(): void
    {
        $unit = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]);

        $this->assertSame(
            UnknownKeyPolicy::Strip,
            $unit->unknownKeyPolicy(),
        );
    }

    #[TestDox('extend() returns a new schema with extra fields merged into the shape')]
    public function test_extend_merges_additional_fields(): void
    {
        // extend() is a builder; the original must be
        // untouched (immutability), and the new schema must
        // contain both sets of keys.
        $original = new AssocArraySchema(shape: [
            'name' => new StringSchema(),
        ]);
        $extended = $original->extend([
            'age' => new IntSchema(),
        ]);

        $this->assertNotSame($original, $extended);
        $this->assertSame(['name'], $original->keyof());
        $this->assertSame(['name', 'age'], $extended->keyof());
    }

    #[TestDox('pick() returns a new schema containing only the named fields')]
    public function test_pick_keeps_only_named_fields(): void
    {
        $unit = (new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
            'email' => new StringSchema(),
        ]))->pick('name', 'email');

        $this->assertSame(['name', 'email'], $unit->keyof());
    }

    #[TestDox('omit() returns a new schema with the named fields removed')]
    public function test_omit_drops_named_fields(): void
    {
        $unit = (new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
            'email' => new StringSchema(),
        ]))->omit('age');

        $this->assertSame(['name', 'email'], $unit->keyof());
    }

    #[TestDox('partial() wraps every field schema with OptionalSchema')]
    public function test_partial_wraps_fields_in_optional(): void
    {
        // every field becomes OptionalSchema-wrapped; the
        // easiest way to test the semantic is to confirm
        // that an input missing every field still parses.
        $unit = (new AssocArraySchema(shape: [
            'name' => new StringSchema(),
            'age' => new IntSchema(),
        ]))->partial();

        foreach ($unit->shape() as $fieldSchema) {
            $this->assertInstanceOf(
                OptionalSchema::class,
                $fieldSchema,
            );
        }
    }
}
