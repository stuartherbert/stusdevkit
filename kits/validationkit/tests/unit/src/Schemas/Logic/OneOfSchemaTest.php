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
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Logic\OneOfSchema;
use StusDevKit\ValidationKit\Validate;

#[TestDox('OneOfSchema')]
class OneOfSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Logic namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(OneOfSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Logic',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new \ReflectionClass(OneOfSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('is not final (allows extension)')]
    public function test_is_not_final(): void
    {
        $reflection = new \ReflectionClass(OneOfSchema::class);
        $this->assertFalse($reflection->isFinal());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(OneOfSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['schemas', 'typeCheckError'], $paramNames);
    }

    #[TestDox('->schemas() returns the member schemas')]
    public function test_schemas_accessor_exists(): void
    {
        $inner = [Validate::string(), Validate::int()];
        $unit = new OneOfSchema(schemas: $inner);

        $this->assertSame($inner, $unit->schemas());
    }

    #[TestDox('->maybeUnevaluatedPropertiesSchema() returns null by default')]
    public function test_unevaluated_properties_default_is_null(): void
    {
        $unit = new OneOfSchema(schemas: [Validate::int()]);

        $this->assertNull($unit->maybeUnevaluatedPropertiesSchema());
    }

    #[TestDox('->maybeUnevaluatedItemsSchema() returns null by default')]
    public function test_unevaluated_items_default_is_null(): void
    {
        $unit = new OneOfSchema(schemas: [Validate::int()]);

        $this->assertNull($unit->maybeUnevaluatedItemsSchema());
    }

    #[TestDox('->unevaluatedProperties() returns a new instance (clone)')]
    public function test_unevaluated_properties_is_immutable(): void
    {
        $unit = new OneOfSchema(schemas: [Validate::int()]);
        $clone = $unit->unevaluatedProperties(false);

        $this->assertNotSame($unit, $clone);
        $this->assertFalse($clone->maybeUnevaluatedPropertiesSchema());
        $this->assertNull($unit->maybeUnevaluatedPropertiesSchema());
    }

    #[TestDox('->unevaluatedItems() returns a new instance (clone)')]
    public function test_unevaluated_items_is_immutable(): void
    {
        $unit = new OneOfSchema(schemas: [Validate::int()]);
        $clone = $unit->unevaluatedItems(false);

        $this->assertNotSame($unit, $clone);
        $this->assertFalse($clone->maybeUnevaluatedItemsSchema());
        $this->assertNull($unit->maybeUnevaluatedItemsSchema());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * JSON Schema "oneOf" accepts input only when exactly
     * one branch matches — a clean single winner.
     */
    #[TestDox('->parse() accepts input that matches exactly one branch')]
    public function test_parse_accepts_when_exactly_one_matches(): void
    {
        // branches are disjoint: a string never satisfies
        // an int schema and vice versa, so a string input
        // wins exactly once.
        $unit = new OneOfSchema(schemas: [
            Validate::string(),
            Validate::int(),
        ]);

        $result = $unit->parse('hello');

        $this->assertSame('hello', $result);
    }

    /**
     * Symmetric check: the int input must also be accepted
     * when it is the sole winner.
     */
    #[TestDox('->parse() accepts input that uniquely matches the second branch')]
    public function test_parse_accepts_unique_second_branch(): void
    {
        $unit = new OneOfSchema(schemas: [
            Validate::string(),
            Validate::int(),
        ]);

        $result = $unit->parse(42);

        $this->assertSame(42, $result);
    }

    /**
     * Unlike "anyOf", "oneOf" must REJECT when two or more
     * branches match — the input is ambiguous.
     */
    #[TestDox('->parse() rejects input that matches more than one branch')]
    public function test_parse_rejects_when_multiple_branches_match(): void
    {
        // both branches accept any string, so a string
        // input matches twice and "oneOf" must fail.
        $unit = new OneOfSchema(schemas: [
            Validate::string(),
            Validate::string(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse('hello');
    }

    /**
     * When no branch matches, "oneOf" fails for the same
     * reason "anyOf" would — there is no winner.
     */
    #[TestDox('->parse() rejects input that matches no branch')]
    public function test_parse_rejects_when_no_branch_matches(): void
    {
        $unit = new OneOfSchema(schemas: [
            Validate::string(),
            Validate::int(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse(true);
    }
}
