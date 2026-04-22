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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Validate;

#[TestDox('ArraySchema')]
class ArraySchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // identity lockdown: pins the home namespace so moves
        // surface as a failing contract test rather than silent
        // breakage for downstream callers.
        $reflection = new ReflectionClass(ArraySchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        // ArraySchema reuses the parse/encode pipeline from
        // BaseSchema; losing that link would silently drop
        // null/default/coercion handling.
        $reflection = new ReflectionClass(ArraySchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('can be constructed from an inner element schema')]
    public function test_can_be_constructed_with_element_schema(): void
    {
        // the inner schema parameter is the whole point of
        // ArraySchema — construction without an element schema
        // is not a valid configuration.
        $unit = new ArraySchema(
            elementSchema: new StringSchema(),
        );

        $this->assertInstanceOf(ArraySchema::class, $unit);
    }

    #[TestDox('exposes the element schema via elementSchema()')]
    public function test_exposes_element_schema(): void
    {
        // introspection access: tooling (e.g. JSON Schema
        // exporter) needs to walk the inner schema.
        $inner = new StringSchema();
        $unit = new ArraySchema(elementSchema: $inner);

        $this->assertSame($inner, $unit->elementSchema());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() accepts a list and returns it unchanged when every element matches the inner schema')]
    public function test_parse_accepts_matching_list(): void
    {
        $unit = new ArraySchema(
            elementSchema: new StringSchema(),
        );

        $actual = $unit->parse(['alpha', 'beta', 'gamma']);

        $this->assertSame(['alpha', 'beta', 'gamma'], $actual);
    }

    #[TestDox('parse() accepts an empty list')]
    public function test_parse_accepts_empty_list(): void
    {
        // the element schema is never consulted for an empty
        // list — this guards against a regression that might
        // eagerly probe a non-existent first element.
        $unit = new ArraySchema(
            elementSchema: new StringSchema(),
        );

        $actual = $unit->parse([]);

        $this->assertSame([], $actual);
    }

    #[TestDox('parse() validates each element against the inner schema')]
    public function test_parse_rejects_list_with_mismatched_element(): void
    {
        // mixed-type lists are the classic bug ArraySchema
        // must catch — one bad element must fail the whole
        // parse.
        $unit = new ArraySchema(
            elementSchema: new StringSchema(),
        );

        $this->expectException(ValidationException::class);
        $unit->parse(['alpha', 42, 'gamma']);
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function provideNonArrayValues(): array
    {
        return [
            'string' => ['hello'],
            'int' => [42],
            'float' => [3.14],
            'bool' => [true],
            'object' => [(object) ['x' => 1]],
        ];
    }

    #[DataProvider('provideNonArrayValues')]
    #[TestDox('parse() rejects non-array inputs like $_dataName')]
    public function test_parse_rejects_non_array_value(
        mixed $input,
    ): void {
        $unit = new ArraySchema(
            elementSchema: new StringSchema(),
        );

        $this->expectException(ValidationException::class);
        $unit->parse($input);
    }

    #[TestDox('min() attaches a minimum length constraint')]
    public function test_min_enforces_minimum_length(): void
    {
        // min() is a builder returning a new schema; the
        // original must remain unconstrained (immutability).
        $unit = (new ArraySchema(elementSchema: new StringSchema()))
            ->min(length: 2);

        $this->expectException(ValidationException::class);
        $unit->parse(['only-one']);
    }

    #[TestDox('max() attaches a maximum length constraint')]
    public function test_max_enforces_maximum_length(): void
    {
        $unit = (new ArraySchema(elementSchema: new StringSchema()))
            ->max(length: 2);

        $this->expectException(ValidationException::class);
        $unit->parse(['a', 'b', 'c']);
    }

    #[TestDox('length() attaches an exact length constraint')]
    public function test_length_enforces_exact_length(): void
    {
        $unit = (new ArraySchema(elementSchema: new StringSchema()))
            ->length(length: 2);

        $this->expectException(ValidationException::class);
        $unit->parse(['only-one']);
    }

    #[TestDox('notEmpty() rejects an empty list')]
    public function test_notEmpty_rejects_empty_list(): void
    {
        // notEmpty is a shorthand for min(length: 1); this
        // test locks in the semantic, not the implementation.
        $unit = (new ArraySchema(elementSchema: new StringSchema()))
            ->notEmpty();

        $this->expectException(ValidationException::class);
        $unit->parse([]);
    }

    #[TestDox('notEmpty() accepts a single-element list')]
    public function test_notEmpty_accepts_one_element(): void
    {
        $unit = (new ArraySchema(elementSchema: new StringSchema()))
            ->notEmpty();

        $actual = $unit->parse(['solo']);

        $this->assertSame(['solo'], $actual);
    }

    #[TestDox('uniqueItems() rejects a list with duplicate values')]
    public function test_uniqueItems_rejects_duplicates(): void
    {
        // strict comparison (===) is the promise; the test
        // uses identical strings so the hazard is unambiguous.
        $unit = (new ArraySchema(elementSchema: new StringSchema()))
            ->uniqueItems();

        $this->expectException(ValidationException::class);
        $unit->parse(['alpha', 'alpha']);
    }

    #[TestDox('contains() requires at least one element to match the given schema')]
    public function test_contains_requires_at_least_one_match(): void
    {
        // default contains() (no min/max) demands >= 1 match.
        $unit = Validate::array(Validate::string())
            ->contains(Validate::string()->min(length: 5));

        $this->expectException(ValidationException::class);
        $unit->parse(['a', 'b', 'c']);
    }

    #[TestDox('builder methods return a new schema instance (immutability)')]
    public function test_builder_methods_are_immutable(): void
    {
        // a common regression: a builder mutates $this and
        // returns it; callers that kept the original observe
        // unexpected constraints. This test pins immutability.
        $original = new ArraySchema(elementSchema: new StringSchema());
        $constrained = $original->min(length: 5);

        $this->assertNotSame($original, $constrained);

        // original still accepts short lists
        $this->assertSame(['x'], $original->parse(['x']));
    }

    #[TestDox('accepts an IntSchema as its inner element schema')]
    public function test_accepts_int_element_schema(): void
    {
        // paired with the StringSchema tests above — confirms
        // that ArraySchema is truly schema-agnostic and does
        // not secretly assume strings.
        $unit = new ArraySchema(elementSchema: new IntSchema());

        $actual = $unit->parse([1, 2, 3]);

        $this->assertSame([1, 2, 3], $actual);
    }

    #[TestDox('accepts any ValidationSchema implementation as its inner element schema')]
    public function test_accepts_validation_schema_contract(): void
    {
        // type-level check: constructor parameter is typed as
        // ValidationSchema, not a concrete class.
        $reflection = new ReflectionClass(ArraySchema::class);
        $constructor = $reflection->getConstructor();
        $this->assertNotNull($constructor);

        $params = $constructor->getParameters();
        $type = $params[0]->getType();
        $this->assertNotNull($type);
        /** @var \ReflectionNamedType $type */
        $this->assertSame(
            ValidationSchema::class,
            $type->getName(),
        );
    }
}
