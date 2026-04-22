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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Collections\RecordSchema;

#[TestDox('RecordSchema')]
class RecordSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Collections namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // RecordSchema belongs in Collections alongside its
        // fixed-length sibling TupleSchema; namespace lockdown
        // keeps the grouping stable.
        $reflection = new ReflectionClass(RecordSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Collections',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(RecordSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('can be constructed from a key schema and a value schema')]
    public function test_can_be_constructed(): void
    {
        // both schemas are required: a record is a dict where
        // keys AND values are validated — the key validation
        // is what distinguishes it from AssocArraySchema.
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema(),
        );

        $this->assertInstanceOf(RecordSchema::class, $unit);
    }

    #[TestDox('exposes the key schema via keySchema()')]
    public function test_exposes_key_schema(): void
    {
        $key = new StringSchema();
        $unit = new RecordSchema(
            keySchema: $key,
            valueSchema: new IntSchema(),
        );

        $this->assertSame($key, $unit->keySchema());
    }

    #[TestDox('exposes the value schema via valueSchema()')]
    public function test_exposes_value_schema(): void
    {
        $value = new IntSchema();
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: $value,
        );

        $this->assertSame($value, $unit->valueSchema());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() accepts a dict where every key and value matches its schema')]
    public function test_parse_accepts_matching_dict(): void
    {
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema(),
        );

        $actual = $unit->parse([
            'alice' => 100,
            'bob' => 85,
        ]);

        $this->assertSame(
            ['alice' => 100, 'bob' => 85],
            $actual,
        );
    }

    #[TestDox('parse() accepts an empty dict')]
    public function test_parse_accepts_empty_dict(): void
    {
        // no keys to validate, no values to validate — the
        // empty dict is always valid regardless of the inner
        // schemas.
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema(),
        );

        $this->assertSame([], $unit->parse([]));
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function provideNonArrayValues(): array
    {
        return [
            'string' => ['hello'],
            'int' => [42],
            'null' => [null],
            'object' => [(object) ['x' => 1]],
        ];
    }

    #[DataProvider('provideNonArrayValues')]
    #[TestDox('parse() rejects non-array inputs like $_dataName')]
    public function test_parse_rejects_non_array(
        mixed $input,
    ): void {
        // records travel as PHP associative arrays; object
        // inputs go to ObjectSchema, scalars to their own
        // schemas.
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema(),
        );

        $this->expectException(ValidationException::class);
        $unit->parse($input);
    }

    #[TestDox('parse() rejects a dict containing a value that fails the value schema')]
    public function test_parse_rejects_bad_value(): void
    {
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema(),
        );

        $this->expectException(ValidationException::class);
        $unit->parse([
            'alice' => 100,
            'bob' => 'not-an-int',
        ]);
    }

    #[TestDox('parse() runs the value schema for every entry (not just the first)')]
    public function test_parse_validates_every_value(): void
    {
        // guards against a loop-out-of-first-iteration bug
        // where validation stops after the first successful
        // entry.
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema()->gte(value: 0),
        );

        $this->expectException(ValidationException::class);
        $unit->parse([
            'alice' => 100,
            'bob' => 85,
            'carol' => -1,
        ]);
    }

    #[TestDox('parse() preserves the original key ordering in its output')]
    public function test_parse_preserves_key_order(): void
    {
        // callers often serialise the result to JSON; a
        // reordering here would be an observable behaviour
        // change.
        $unit = new RecordSchema(
            keySchema: new StringSchema(),
            valueSchema: new IntSchema(),
        );

        $actual = $unit->parse([
            'zulu' => 1,
            'alpha' => 2,
            'mike' => 3,
        ]);

        $this->assertSame(
            ['zulu', 'alpha', 'mike'],
            array_keys($actual),
        );
    }
}
