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
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AllOfSchema;
use StusDevKit\ValidationKit\Validate;

#[TestDox('AllOfSchema')]
class AllOfSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Logic namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(AllOfSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Logic',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new \ReflectionClass(AllOfSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('is not final (allows extension)')]
    public function test_is_not_final(): void
    {
        $reflection = new \ReflectionClass(AllOfSchema::class);
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
        $method = new \ReflectionMethod(AllOfSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['schemas', 'typeCheckError'], $paramNames);
    }

    #[TestDox('->schemas() returns the member schemas')]
    public function test_schemas_accessor_exists(): void
    {
        $inner = [Validate::int(), Validate::int()];
        $unit = new AllOfSchema(schemas: $inner);

        $this->assertSame($inner, $unit->schemas());
    }

    #[TestDox('->maybeUnevaluatedPropertiesSchema() returns null by default')]
    public function test_unevaluated_properties_default_is_null(): void
    {
        $unit = new AllOfSchema(schemas: [Validate::int()]);

        $this->assertNull($unit->maybeUnevaluatedPropertiesSchema());
    }

    #[TestDox('->maybeUnevaluatedItemsSchema() returns null by default')]
    public function test_unevaluated_items_default_is_null(): void
    {
        $unit = new AllOfSchema(schemas: [Validate::int()]);

        $this->assertNull($unit->maybeUnevaluatedItemsSchema());
    }

    #[TestDox('->unevaluatedProperties() returns a new instance (clone)')]
    public function test_unevaluated_properties_is_immutable(): void
    {
        $unit = new AllOfSchema(schemas: [Validate::int()]);
        $clone = $unit->unevaluatedProperties(false);

        $this->assertNotSame($unit, $clone);
        $this->assertFalse($clone->maybeUnevaluatedPropertiesSchema());
        $this->assertNull($unit->maybeUnevaluatedPropertiesSchema());
    }

    #[TestDox('->unevaluatedItems() returns a new instance (clone)')]
    public function test_unevaluated_items_is_immutable(): void
    {
        $unit = new AllOfSchema(schemas: [Validate::int()]);
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
     * JSON Schema "allOf" requires every branch to accept
     * the input. When all branches pass, the data flows
     * through unchanged.
     */
    #[TestDox('->parse() accepts input that passes every branch')]
    public function test_parse_accepts_when_all_branches_pass(): void
    {
        // a value that is both a string AND matches the
        // string schema is trivially "both a string"; we
        // use two identical string branches so we can prove
        // both ran.
        $unit = new AllOfSchema(schemas: [
            Validate::string(),
            Validate::string(),
        ]);

        $result = $unit->parse('hello');

        $this->assertSame('hello', $result);
    }

    /**
     * JSON Schema "allOf" rejects input the moment any
     * branch disagrees; here the first branch accepts but
     * the second fails, so the whole thing must fail.
     */
    #[TestDox('->parse() rejects input when the second branch fails')]
    public function test_parse_rejects_when_second_branch_fails(): void
    {
        // an int will pass Validate::int() but fail
        // Validate::string()
        $unit = new AllOfSchema(schemas: [
            Validate::int(),
            Validate::string(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse(42);
    }

    /**
     * Symmetry check: if the first branch fails, the
     * whole "allOf" must fail too.
     */
    #[TestDox('->parse() rejects input when the first branch fails')]
    public function test_parse_rejects_when_first_branch_fails(): void
    {
        $unit = new AllOfSchema(schemas: [
            Validate::int(),
            Validate::string(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse('not-an-int');
    }

    /**
     * When every branch disagrees with the input, the
     * failure must still be reported — not swallowed.
     */
    #[TestDox('->parse() rejects input that fails every branch')]
    public function test_parse_rejects_when_all_branches_fail(): void
    {
        $unit = new AllOfSchema(schemas: [
            Validate::int(),
            Validate::string(),
        ]);

        $this->expectException(ValidationException::class);
        $unit->parse(true);
    }

    /**
     * With a single branch, "allOf" behaves like a simple
     * wrapper — the branch's verdict is the final verdict.
     */
    #[TestDox('->parse() with a single branch accepts valid input')]
    public function test_parse_single_branch_accepts(): void
    {
        $unit = new AllOfSchema(schemas: [Validate::int()]);

        $result = $unit->parse(123);

        $this->assertSame(123, $result);
    }
}
