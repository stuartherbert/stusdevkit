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
use StusDevKit\ValidationKit\Constraints\ArrayExactLengthConstraint;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('ArrayExactLengthConstraint')]
class ArrayExactLengthConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ArrayExactLengthConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(ArrayExactLengthConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(ArrayExactLengthConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('declares __construct, length, process and skipOnIssues as its public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(ArrayExactLengthConstraint::class);
        $methodNames = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            $methodNames[] = $m->getName();
        }
        sort($methodNames);
        $this->assertSame(
            ['__construct', 'length', 'process', 'skipOnIssues'],
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
        $method = new \ReflectionMethod(ArrayExactLengthConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['length', 'error'], $paramNames);
    }

    #[TestDox('->length() returns int')]
    public function test_length_return_type(): void
    {
        $method = new \ReflectionMethod(ArrayExactLengthConstraint::class, 'length');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
        $this->assertFalse($type->allowsNull());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(ArrayExactLengthConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * ->length() returns the value passed to the constructor.
     */
    #[TestDox('->length() returns the constructor-supplied length')]
    public function test_length_returns_constructor_value(): void
    {
        $unit = new ArrayExactLengthConstraint(length: 5);

        $this->assertSame(5, $unit->length());
    }

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input array unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new ArrayExactLengthConstraint(length: 3);

        $input = ['a', 'b', 'c'];

        $actualResult = $unit->process(
            data: $input,
            context: new ValidationContext(),
        );

        $this->assertSame($input, $actualResult);
    }

    /**
     * Equal count must pass — this is the success path
     * for an exact-length check.
     */
    #[TestDox('->process() records no issue when the array count equals length')]
    public function test_process_passes_when_count_matches(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayExactLengthConstraint(length: 3);

        $unit->process(
            data: ['a', 'b', 'c'],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Boundary below: count one less than length must fail.
     */
    #[TestDox('->process() records an issue when the array is one element shorter than length')]
    public function test_process_fails_when_count_is_one_below(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayExactLengthConstraint(length: 3);

        $unit->process(
            data: ['a', 'b'],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Boundary above: count one more than length must fail.
     */
    #[TestDox('->process() records an issue when the array is one element longer than length')]
    public function test_process_fails_when_count_is_one_above(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayExactLengthConstraint(length: 3);

        $unit->process(
            data: ['a', 'b', 'c', 'd'],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Empty array where length is zero — the zero case
     * is a footgun if `count()` is ever compared loosely.
     */
    #[TestDox('->process() records no issue when the array is empty and length is zero')]
    public function test_process_passes_on_empty_when_length_is_zero(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayExactLengthConstraint(length: 0);

        $unit->process(
            data: [],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The custom error callback replaces the default
     * issue generator; its output must land on the context.
     */
    #[TestDox('->process() uses the custom error callback when validation fails')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayExactLengthConstraint(
            length: 5,
            error: fn(mixed $data) => new ValidationIssue(
                type: 'https://example.com/errors/wrong-length',
                input: $data,
                path: [],
                message: 'Custom exact-length failure',
            ),
        );

        $unit->process(
            data: ['a'],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }
}
