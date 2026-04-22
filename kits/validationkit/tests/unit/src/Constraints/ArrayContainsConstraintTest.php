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

namespace StusDevKit\ValidationKit\Tests\Unit\Constraints;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Constraints\ArrayContainsConstraint;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('ArrayContainsConstraint')]
class ArrayContainsConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ArrayContainsConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(ArrayContainsConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(ArrayContainsConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('declares __construct, schema, minContains, maxContains, process and skipOnIssues as its public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(ArrayContainsConstraint::class);
        $methodNames = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            $methodNames[] = $m->getName();
        }
        sort($methodNames);
        $this->assertSame(
            [
                '__construct',
                'maxContains',
                'minContains',
                'process',
                'schema',
                'skipOnIssues',
            ],
            $methodNames,
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(ArrayContainsConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(
            ['schema', 'minContains', 'maxContains', 'error'],
            $paramNames,
        );
    }

    #[TestDox('->schema() returns ValidationSchema')]
    public function test_schema_return_type(): void
    {
        $method = new \ReflectionMethod(ArrayContainsConstraint::class, 'schema');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Contracts\\ValidationSchema',
            $type->getName(),
        );
    }

    #[TestDox('->minContains() returns nullable int')]
    public function test_minContains_return_type(): void
    {
        $method = new \ReflectionMethod(ArrayContainsConstraint::class, 'minContains');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
        $this->assertTrue($type->allowsNull());
    }

    #[TestDox('->maxContains() returns nullable int')]
    public function test_maxContains_return_type(): void
    {
        $method = new \ReflectionMethod(ArrayContainsConstraint::class, 'maxContains');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
        $this->assertTrue($type->allowsNull());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(ArrayContainsConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input array unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new ArrayContainsConstraint(schema: Validate::string());

        $input = ['hello', 'world'];

        $actualResult = $unit->process(
            data: $input,
            context: new ValidationContext(),
        );

        $this->assertSame($input, $actualResult);
    }

    /**
     * Default behaviour (no bounds) requires at least one
     * element to match the schema.
     */
    #[TestDox('->process() records no issue when at least one element matches the schema (default bounds)')]
    public function test_process_passes_on_default_when_one_matches(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(schema: Validate::string());

        $unit->process(
            data: [1, 'hello', 3],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Default behaviour fails an array where zero elements
     * match the schema.
     */
    #[TestDox('->process() records an issue when no element matches the schema (default bounds)')]
    public function test_process_fails_on_default_when_none_match(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(schema: Validate::string());

        $unit->process(
            data: [1, 2, 3],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Boundary test for minContains: matchCount equal to
     * minContains must pass.
     */
    #[TestDox('->process() records no issue when the match count equals minContains (lower boundary)')]
    public function test_process_passes_at_minContains_boundary(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(
            schema: Validate::string(),
            minContains: 2,
        );

        $unit->process(
            data: ['a', 'b', 3],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Boundary test: match count one below minContains
     * must fail.
     */
    #[TestDox('->process() records an issue when the match count is below minContains')]
    public function test_process_fails_below_minContains(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(
            schema: Validate::string(),
            minContains: 2,
        );

        $unit->process(
            data: ['a', 2, 3],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Boundary test: match count equal to maxContains
     * must pass.
     */
    #[TestDox('->process() records no issue when the match count equals maxContains (upper boundary)')]
    public function test_process_passes_at_maxContains_boundary(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(
            schema: Validate::string(),
            maxContains: 2,
        );

        $unit->process(
            data: ['a', 'b', 3],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Boundary test: match count one above maxContains
     * must fail.
     */
    #[TestDox('->process() records an issue when the match count exceeds maxContains')]
    public function test_process_fails_above_maxContains(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(
            schema: Validate::string(),
            maxContains: 1,
        );

        $unit->process(
            data: ['a', 'b', 'c'],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * The custom error callback replaces the default
     * issue generator; its output must land on the context.
     */
    #[TestDox('->process() uses the custom error callback when validation fails')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayContainsConstraint(
            schema: Validate::string(),
            error: fn(mixed $data) => new ValidationIssue(
                type: 'https://example.com/errors/needs-a-string',
                input: $data,
                path: [],
                message: 'Custom contains failure',
            ),
        );

        $unit->process(
            data: [1, 2, 3],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }
}
