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

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Constraints\DateTimeMaxConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('DateTimeMaxConstraint')]
class DateTimeMaxConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(DateTimeMaxConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(DateTimeMaxConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(DateTimeMaxConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('declares __construct, date and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(DateTimeMaxConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === DateTimeMaxConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'date', 'process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(DateTimeMaxConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['date', 'error'], $paramNames);
    }

    #[TestDox('::__construct() declares $date as DateTimeInterface')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(DateTimeMaxConstraint::class, '__construct');
        $type = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame(DateTimeInterface::class, $type->getName());
    }

    #[TestDox('->date() returns the maximum date stored at construction time')]
    public function test_date_returns_maximum(): void
    {
        $maximum = new DateTimeImmutable('2025-12-31T00:00:00+00:00');
        $unit = new DateTimeMaxConstraint(date: $maximum);

        $this->assertSame($maximum, $unit->date());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(DateTimeMaxConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(DateTimeMaxConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * A date before the maximum satisfies Max.
     */
    #[TestDox('->process() records no issue when date is before the maximum')]
    public function test_process_passes_when_date_before_max(): void
    {
        $context = new ValidationContext();
        $unit = new DateTimeMaxConstraint(
            date: new DateTimeImmutable('2025-12-31T00:00:00+00:00'),
        );

        $unit->process(
            data: new DateTimeImmutable('2025-06-01T00:00:00+00:00'),
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Max is inclusive — equality passes. Compare with
     * DateTimeMinConstraint, which is also inclusive.
     */
    #[TestDox('->process() records no issue when date equals the maximum (inclusive bound)')]
    public function test_process_passes_at_boundary(): void
    {
        $context = new ValidationContext();
        $boundary = new DateTimeImmutable('2025-12-31T00:00:00+00:00');
        $unit = new DateTimeMaxConstraint(date: $boundary);

        $unit->process(
            data: new DateTimeImmutable('2025-12-31T00:00:00+00:00'),
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * A date after the maximum violates Max.
     */
    #[TestDox('->process() records an issue when date is after the maximum')]
    public function test_process_fails_when_date_after_max(): void
    {
        $context = new ValidationContext();
        $unit = new DateTimeMaxConstraint(
            date: new DateTimeImmutable('2025-12-31T00:00:00+00:00'),
        );

        $unit->process(
            data: new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * The input DateTime is returned unchanged — constraints
     * never mutate the data.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $input = new DateTimeImmutable('2025-06-01T00:00:00+00:00');
        $unit = new DateTimeMaxConstraint(
            date: new DateTimeImmutable('2025-12-31T00:00:00+00:00'),
        );

        $actualResult = $unit->process(data: $input, context: new ValidationContext());

        $this->assertSame($input, $actualResult);
    }

    /**
     * When a custom error callback is supplied, the issue
     * it returns is the one that lands in the context.
     */
    #[TestDox('->process() routes failures through the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new DateTimeMaxConstraint(
            date: new DateTimeImmutable('2025-12-31T00:00:00+00:00'),
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:too-big',
                input: $data,
                path: [],
                message: 'custom too-big message',
            ),
        );

        $unit->process(
            data: new DateTimeImmutable('2026-06-01T00:00:00+00:00'),
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }
}
