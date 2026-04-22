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
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Constraints\NumericGtConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('NumericGtConstraint')]
class NumericGtConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(NumericGtConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(NumericGtConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(NumericGtConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('declares __construct, value and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(NumericGtConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === NumericGtConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'process', 'value'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(NumericGtConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['value', 'error'], $paramNames);
    }

    #[TestDox('->value() returns the threshold stored at construction time')]
    public function test_value_returns_threshold(): void
    {
        $unit = new NumericGtConstraint(value: 42);

        $this->assertSame(42, $unit->value());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(NumericGtConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(NumericGtConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * A value strictly above the threshold satisfies Gt, so
     * the context stays clean.
     */
    #[TestDox('->process() records no issue when value is strictly greater than threshold')]
    public function test_process_passes_when_value_above_threshold(): void
    {
        $context = new ValidationContext();
        $unit = new NumericGtConstraint(value: 10);

        $unit->process(data: 11, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Gt is strict — equality must fail. This is the
     * boundary that distinguishes Gt from Gte.
     */
    #[TestDox('->process() records an issue when value equals threshold (strict bound)')]
    public function test_process_fails_at_boundary(): void
    {
        $context = new ValidationContext();
        $unit = new NumericGtConstraint(value: 10);

        $unit->process(data: 10, context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * A value below the threshold violates Gt.
     */
    #[TestDox('->process() records an issue when value is below threshold')]
    public function test_process_fails_when_below_threshold(): void
    {
        $context = new ValidationContext();
        $unit = new NumericGtConstraint(value: 10);

        $unit->process(data: 9, context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Gt works with float operands.
     */
    #[TestDox('->process() accepts float values above the threshold')]
    public function test_process_passes_with_float(): void
    {
        $context = new ValidationContext();
        $unit = new NumericGtConstraint(value: 1.5);

        $unit->process(data: 1.6, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The input value is returned unchanged — constraints
     * never mutate the data.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new NumericGtConstraint(value: 10);

        $actualResult = $unit->process(data: 42, context: new ValidationContext());

        $this->assertSame(42, $actualResult);
    }

    /**
     * When a custom error callback is supplied, the issue
     * it returns is the one that lands in the context.
     */
    #[TestDox('->process() routes failures through the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new NumericGtConstraint(
            value: 10,
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:too-small',
                input: $data,
                path: [],
                message: 'custom too-small message',
            ),
        );

        $unit->process(data: 5, context: $context);

        $this->assertTrue($context->hasIssues());
    }
}
