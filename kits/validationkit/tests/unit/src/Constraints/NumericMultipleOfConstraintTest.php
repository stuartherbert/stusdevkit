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
use StusDevKit\ValidationKit\Constraints\NumericMultipleOfConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('NumericMultipleOfConstraint')]
class NumericMultipleOfConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(NumericMultipleOfConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(NumericMultipleOfConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(NumericMultipleOfConstraint::class);
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
        $reflection = new \ReflectionClass(NumericMultipleOfConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === NumericMultipleOfConstraint::class) {
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
        $method = new \ReflectionMethod(NumericMultipleOfConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['value', 'error'], $paramNames);
    }

    #[TestDox('->value() returns the divisor stored at construction time')]
    public function test_value_returns_divisor(): void
    {
        $unit = new NumericMultipleOfConstraint(value: 5);

        $this->assertSame(5, $unit->value());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(NumericMultipleOfConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(NumericMultipleOfConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * Integer operands divide exactly — zero remainder
     * passes.
     */
    #[TestDox('->process() records no issue when integer value is an exact multiple of integer divisor')]
    public function test_process_passes_with_integer_multiple(): void
    {
        $context = new ValidationContext();
        $unit = new NumericMultipleOfConstraint(value: 5);

        $unit->process(data: 15, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Non-zero remainder on integer operands fails.
     */
    #[TestDox('->process() records an issue when integer value has a non-zero integer remainder')]
    public function test_process_fails_with_integer_remainder(): void
    {
        $context = new ValidationContext();
        $unit = new NumericMultipleOfConstraint(value: 5);

        $unit->process(data: 7, context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Zero is divisible by every non-zero integer — this
     * is the always-passes boundary.
     */
    #[TestDox('->process() records no issue when value is zero')]
    public function test_process_passes_with_zero(): void
    {
        $context = new ValidationContext();
        $unit = new NumericMultipleOfConstraint(value: 5);

        $unit->process(data: 0, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Float divisor with float-multiple value passes —
     * uses fmod() with epsilon tolerance.
     */
    #[TestDox('->process() records no issue when float value is an exact multiple of float divisor')]
    public function test_process_passes_with_float_multiple(): void
    {
        $context = new ValidationContext();
        // 0.5 and 1.5 are exactly representable in IEEE 754, so
        // fmod(1.5, 0.5) returns exactly 0.0 — no epsilon drift
        $unit = new NumericMultipleOfConstraint(value: 0.5);

        $unit->process(data: 1.5, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Float divisor with non-multiple value fails.
     */
    #[TestDox('->process() records an issue when float value is not an exact multiple of float divisor')]
    public function test_process_fails_with_float_remainder(): void
    {
        $context = new ValidationContext();
        $unit = new NumericMultipleOfConstraint(value: 0.1);

        $unit->process(data: 0.25, context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Integer value against float divisor takes the
     * fmod() path with epsilon tolerance.
     */
    #[TestDox('->process() records no issue when integer value divides exactly by float divisor')]
    public function test_process_passes_with_mixed_int_value_float_divisor(): void
    {
        $context = new ValidationContext();
        $unit = new NumericMultipleOfConstraint(value: 0.5);

        $unit->process(data: 2, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The input value is returned unchanged — constraints
     * never mutate the data.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new NumericMultipleOfConstraint(value: 5);

        $actualResult = $unit->process(data: 15, context: new ValidationContext());

        $this->assertSame(15, $actualResult);
    }

    /**
     * When a custom error callback is supplied, the issue
     * it returns is the one that lands in the context.
     */
    #[TestDox('->process() routes failures through the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new NumericMultipleOfConstraint(
            value: 5,
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:not-multiple',
                input: $data,
                path: [],
                message: 'custom not-multiple message',
            ),
        );

        $unit->process(data: 7, context: $context);

        $this->assertTrue($context->hasIssues());
    }
}
