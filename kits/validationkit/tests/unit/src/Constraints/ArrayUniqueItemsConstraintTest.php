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
use StusDevKit\ValidationKit\Constraints\ArrayUniqueItemsConstraint;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('ArrayUniqueItemsConstraint')]
class ArrayUniqueItemsConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ArrayUniqueItemsConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(ArrayUniqueItemsConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(ArrayUniqueItemsConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('declares __construct, process and skipOnIssues as its public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(ArrayUniqueItemsConstraint::class);
        $methodNames = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            $methodNames[] = $m->getName();
        }
        sort($methodNames);
        $this->assertSame(
            ['__construct', 'process', 'skipOnIssues'],
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
        $method = new \ReflectionMethod(ArrayUniqueItemsConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['error'], $paramNames);
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(ArrayUniqueItemsConstraint::class, 'process');
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
        $unit = new ArrayUniqueItemsConstraint();

        $input = ['a', 'b', 'c'];

        $actualResult = $unit->process(
            data: $input,
            context: new ValidationContext(),
        );

        $this->assertSame($input, $actualResult);
    }

    /**
     * Empty arrays have no duplicates — the vacuous case
     * must pass.
     */
    #[TestDox('->process() records no issue on an empty array')]
    public function test_process_passes_on_empty_array(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayUniqueItemsConstraint();

        $unit->process(
            data: [],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * A single-element array trivially has no duplicates.
     */
    #[TestDox('->process() records no issue on a single-element array')]
    public function test_process_passes_on_single_element(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayUniqueItemsConstraint();

        $unit->process(
            data: ['only'],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * All distinct scalar values must pass.
     */
    #[TestDox('->process() records no issue when every element is distinct')]
    public function test_process_passes_on_distinct_values(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayUniqueItemsConstraint();

        $unit->process(
            data: ['a', 'b', 'c', 'd'],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Duplicate scalar strings trigger a failure.
     */
    #[TestDox('->process() records an issue when two string elements are identical')]
    public function test_process_fails_on_duplicate_strings(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayUniqueItemsConstraint();

        $unit->process(
            data: ['a', 'b', 'a'],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Strict comparison is load-bearing: `1` (int) and
     * `'1'` (string) must be treated as distinct, because
     * the implementation uses `===`. Loose comparison here
     * would silently accept mixed-type duplicates.
     */
    #[TestDox('->process() records no issue when values differ by type only (strict comparison)')]
    public function test_process_uses_strict_comparison(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayUniqueItemsConstraint();

        $unit->process(
            data: [1, '1', 1.0],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The custom error callback replaces the default
     * issue generator; its output must land on the context
     * when duplicates are detected.
     */
    #[TestDox('->process() uses the custom error callback when validation fails')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new ArrayUniqueItemsConstraint(
            error: fn(mixed $data) => new ValidationIssue(
                type: 'https://example.com/errors/not-unique',
                input: $data,
                path: [],
                message: 'Custom unique-items failure',
            ),
        );

        $unit->process(
            data: ['x', 'x'],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }
}
