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
use StusDevKit\ValidationKit\Constraints\StringTimeConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('StringTimeConstraint')]
class StringTimeConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringTimeConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringTimeConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringTimeConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('implements the ValidationConstraint contract')]
    public function test_implements_ValidationConstraint(): void
    {
        $reflection = new \ReflectionClass(StringTimeConstraint::class);
        $this->assertTrue($reflection->implementsInterface(ValidationConstraint::class));
    }

    #[TestDox('declares only __construct and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringTimeConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringTimeConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringTimeConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['error'], $paramNames);
    }

    #[TestDox('::__construct() declares $error as a nullable callable with default null')]
    public function test_construct_error_is_nullable_callable(): void
    {
        $method = new \ReflectionMethod(StringTimeConstraint::class, '__construct');
        $param = $method->getParameters()[0];

        $type = $param->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('callable', $type->getName());
        $this->assertTrue($type->allowsNull());
        $this->assertTrue($param->isDefaultValueAvailable());
        $this->assertNull($param->getDefaultValue());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringTimeConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares return type mixed')]
    public function test_process_return_type(): void
    {
        $method = new \ReflectionMethod(StringTimeConstraint::class, 'process');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * An HH:MM:SS string with a Z timezone designator
     * passes.
     */
    #[TestDox('->process() records no issue for HH:MM:SS with Z timezone')]
    public function test_process_passes_on_zulu_time(): void
    {
        $context = new ValidationContext();
        $unit = new StringTimeConstraint();

        $unit->process(data: '10:30:00Z', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Fractional seconds and a numeric offset are both
     * accepted by RFC 3339 full-time.
     */
    #[TestDox('->process() records no issue for HH:MM:SS with fractional seconds and offset')]
    public function test_process_passes_with_fraction_and_offset(): void
    {
        $context = new ValidationContext();
        $unit = new StringTimeConstraint();

        $unit->process(data: '10:30:00.123+01:00', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Hours beyond 23 must be rejected.
     */
    #[TestDox('->process() records an issue when the hours component is out of range')]
    public function test_process_fails_on_out_of_range_hours(): void
    {
        $context = new ValidationContext();
        $unit = new StringTimeConstraint();

        $unit->process(data: '25:00:00Z', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Anything that isn't HH:MM:SS-shaped is rejected by
     * the regex pre-check.
     */
    #[TestDox('->process() records an issue for a non-time input')]
    public function test_process_fails_on_non_time_input(): void
    {
        $context = new ValidationContext();
        $unit = new StringTimeConstraint();

        $unit->process(data: 'not-a-time', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input value unchanged on success')]
    public function test_process_returns_input_unchanged_on_success(): void
    {
        $unit = new StringTimeConstraint();

        $actualResult = $unit->process(data: '10:30:00Z', context: new ValidationContext());

        $this->assertSame('10:30:00Z', $actualResult);
    }

    /**
     * Even when the time is invalid, the pass-through
     * contract still holds.
     */
    #[TestDox('->process() returns the input value unchanged on failure')]
    public function test_process_returns_input_unchanged_on_failure(): void
    {
        $unit = new StringTimeConstraint();

        $actualResult = $unit->process(data: '25:00:00Z', context: new ValidationContext());

        $this->assertSame('25:00:00Z', $actualResult);
    }

    /**
     * When a custom error callback is supplied, the issue
     * it returns is the one that lands in the context.
     */
    #[TestDox('->process() routes failures through the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new StringTimeConstraint(
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:bad-time',
                input: $data,
                path: [],
                message: 'custom bad-time message',
            ),
        );

        $unit->process(data: 'not-a-time', context: $context);

        $this->assertTrue($context->hasIssues());
    }
}
