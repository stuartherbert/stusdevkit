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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Collections;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Collections\TupleSchema;

#[TestDox('TupleSchema')]
class TupleSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Collections namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // TupleSchema is the fixed-length ordered sibling of
        // ArraySchema; it belongs with its variable-key cousin
        // RecordSchema in the Collections namespace.
        $reflection = new ReflectionClass(TupleSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Collections',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(TupleSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('can be constructed from a list of positional schemas')]
    public function test_can_be_constructed(): void
    {
        // the positional schemas ARE the shape of the tuple;
        // the ordering is load-bearing, hence the list type.
        $unit = new TupleSchema(schemas: [
            new StringSchema(),
            new IntSchema(),
        ]);

        $this->assertInstanceOf(TupleSchema::class, $unit);
    }

    #[TestDox('exposes the positional schemas via schemas()')]
    public function test_exposes_positional_schemas(): void
    {
        $first = new StringSchema();
        $second = new IntSchema();
        $unit = new TupleSchema(schemas: [$first, $second]);

        $this->assertSame([$first, $second], $unit->schemas());
    }

    #[TestDox('maybeRestSchema() returns null by default (no rest schema configured)')]
    public function test_maybeRestSchema_returns_null_by_default(): void
    {
        // null is the default; the documented semantic is
        // "exact length enforced". This pins that default.
        $unit = new TupleSchema(schemas: [new StringSchema()]);

        $this->assertNull($unit->maybeRestSchema());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() accepts an input whose positions match every positional schema')]
    public function test_parse_accepts_matching_tuple(): void
    {
        $unit = new TupleSchema(schemas: [
            new StringSchema(),
            new IntSchema(),
        ]);

        $actual = $unit->parse(['hello', 42]);

        $this->assertSame(['hello', 42], $actual);
    }

    #[TestDox('parse() rejects an input whose position has the wrong type')]
    public function test_parse_rejects_wrong_position_type(): void
    {
        // tuples are ordered — swapping an int and a string
        // must fail, unlike a list-of-mixed.
        $unit = new TupleSchema(schemas: [
            new StringSchema(),
            new IntSchema(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse([42, 'hello']);
    }

    #[TestDox('parse() rejects an input that is too short for the prefix')]
    public function test_parse_rejects_too_short(): void
    {
        $unit = new TupleSchema(schemas: [
            new StringSchema(),
            new IntSchema(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse(['only-one']);
    }

    #[TestDox('parse() rejects an input that has too many items when no rest schema is set')]
    public function test_parse_rejects_too_long_without_rest_schema(): void
    {
        // exact-length enforcement is the default; extra
        // items must fail until items() is called.
        $unit = new TupleSchema(schemas: [
            new StringSchema(),
            new IntSchema(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse(['hello', 42, 'extra']);
    }

    #[TestDox('parse() rejects non-array input')]
    public function test_parse_rejects_non_array(): void
    {
        $unit = new TupleSchema(schemas: [new StringSchema()]);

        $this->expectException(ValidationException::class);
        $unit->parse('not-a-tuple');
    }

    #[TestDox('items() allows extra items validated against the rest schema')]
    public function test_items_allows_matching_extra_items(): void
    {
        // once items() sets a rest schema, the tuple becomes
        // variable-length: prefix is fixed, rest is checked
        // against the given schema.
        $unit = (new TupleSchema(schemas: [new StringSchema()]))
            ->items(new IntSchema());

        $actual = $unit->parse(['hello', 1, 2, 3]);

        $this->assertSame(['hello', 1, 2, 3], $actual);
    }

    #[TestDox('items() rejects an extra item that fails the rest schema')]
    public function test_items_rejects_bad_extra_item(): void
    {
        $unit = (new TupleSchema(schemas: [new StringSchema()]))
            ->items(new IntSchema());

        $this->expectException(ValidationException::class);
        $unit->parse(['hello', 1, 'not-an-int']);
    }

    #[TestDox('items(false) explicitly forbids extra items beyond the prefix')]
    public function test_items_false_forbids_extra(): void
    {
        // items(false) is the JSON Schema way to say
        // "additionalItems: false"; it must reject extras
        // rather than allow them.
        $unit = (new TupleSchema(schemas: [new StringSchema()]))
            ->items(false);

        $this->expectException(ValidationException::class);
        $unit->parse(['hello', 'extra']);
    }

    #[TestDox('maybeRestSchema() returns the schema set via items()')]
    public function test_maybeRestSchema_reports_configured_schema(): void
    {
        $rest = new IntSchema();
        $unit = (new TupleSchema(schemas: [new StringSchema()]))
            ->items($rest);

        $this->assertSame($rest, $unit->maybeRestSchema());
    }

    #[TestDox('maybeRestSchema() returns false when items(false) has been called')]
    public function test_maybeRestSchema_reports_false(): void
    {
        // pin the three-way distinction (null / false /
        // schema) — they mean three different things.
        $unit = (new TupleSchema(schemas: [new StringSchema()]))
            ->items(false);

        $this->assertFalse($unit->maybeRestSchema());
    }

    #[TestDox('items() returns a new schema instance (immutability)')]
    public function test_items_is_immutable(): void
    {
        $original = new TupleSchema(schemas: [new StringSchema()]);
        $withRest = $original->items(new IntSchema());

        $this->assertNotSame($original, $withRest);
        $this->assertNull($original->maybeRestSchema());
    }

    #[TestDox('maybeUnevaluatedItemsSchema() returns null by default')]
    public function test_maybeUnevaluatedItemsSchema_defaults_null(): void
    {
        $unit = new TupleSchema(schemas: [new StringSchema()]);

        $this->assertNull($unit->maybeUnevaluatedItemsSchema());
    }

    #[TestDox('unevaluatedItems() returns a new schema exposing the configured unevaluated-items schema')]
    public function test_unevaluatedItems_is_exposed(): void
    {
        $schema = new IntSchema();
        $unit = (new TupleSchema(schemas: [new StringSchema()]))
            ->unevaluatedItems($schema);

        $this->assertSame(
            $schema,
            $unit->maybeUnevaluatedItemsSchema(),
        );
    }
}
