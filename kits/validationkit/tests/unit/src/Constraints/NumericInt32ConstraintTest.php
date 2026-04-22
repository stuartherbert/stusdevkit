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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Constraints\BaseConstraint;
use StusDevKit\ValidationKit\Constraints\NumericInt32Constraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('NumericInt32Constraint')]
class NumericInt32ConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(NumericInt32Constraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(NumericInt32Constraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(NumericInt32Constraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseConstraint::class, $parent->getName());
    }

    #[TestDox('implements ValidationConstraint')]
    public function test_implements_ValidationConstraint(): void
    {
        $reflection = new \ReflectionClass(NumericInt32Constraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares only __construct and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(NumericInt32Constraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === NumericInt32Constraint::class) {
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
        $method = new \ReflectionMethod(NumericInt32Constraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['error'], $paramNames);
    }

    #[TestDox('::__construct() declares $error as nullable callable with default null')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(NumericInt32Constraint::class, '__construct');
        $param = $method->getParameters()[0];
        $type = $param->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('callable', $type->getName());
        $this->assertTrue($type->allowsNull());
        $this->assertTrue($param->isDefaultValueAvailable());
        $this->assertNull($param->getDefaultValue());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(NumericInt32Constraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(NumericInt32Constraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares return type mixed')]
    public function test_process_return_type(): void
    {
        $method = new \ReflectionMethod(NumericInt32Constraint::class, 'process');
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
     * @return array<string, array{0: int}>
     */
    public static function inRangeProvider(): array
    {
        return [
            'zero' => [0],
            'positive one' => [1],
            'negative one' => [-1],
            'INT32_MAX' => [2147483647],
            'INT32_MIN' => [-2147483648],
        ];
    }

    #[TestDox('->process() records no issue when the integer $value is inside the 32-bit range')]
    #[DataProvider('inRangeProvider')]
    public function test_process_accepts_values_in_range(int $value): void
    {
        $context = new ValidationContext();
        $unit = new NumericInt32Constraint();

        $unit->process(data: $value, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new NumericInt32Constraint();

        $actualResult = $unit->process(data: 42, context: new ValidationContext());

        $this->assertSame(42, $actualResult);
    }

    /**
     * @return array<string, array{0: int}>
     */
    public static function outOfRangeProvider(): array
    {
        // PHP_INT_MAX / PHP_INT_MIN are 64-bit, so these
        // are safely outside the 32-bit range at compile time.
        return [
            'one above INT32_MAX' => [2147483648],
            'one below INT32_MIN' => [-2147483649],
            'PHP_INT_MAX' => [PHP_INT_MAX],
            'PHP_INT_MIN' => [PHP_INT_MIN],
        ];
    }

    #[TestDox('->process() records an issue when the integer $value is outside the 32-bit range')]
    #[DataProvider('outOfRangeProvider')]
    public function test_process_rejects_values_out_of_range(int $value): void
    {
        $context = new ValidationContext();
        $unit = new NumericInt32Constraint();

        $unit->process(data: $value, context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * The default error callback records an issue with the
     * out-of-range type URI and a message naming the range.
     */
    #[TestDox('->process() records the default out_of_range type URI when no callback is supplied')]
    public function test_process_uses_default_error(): void
    {
        $context = new ValidationContext();
        $unit = new NumericInt32Constraint();

        $unit->process(data: 2147483648, context: $context);

        $issues = $context->issues()->toArray();
        $this->assertCount(1, $issues);
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/out_of_range',
            $issues[0]->type,
        );
    }

    /**
     * The custom error callback receives the failing value
     * and produces the recorded issue — proves the
     * constructor wires the callback through.
     */
    #[TestDox('->process() uses a supplied error callback to build the issue')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new NumericInt32Constraint(
            error: static fn(mixed $data) => new ValidationIssue(
                type: 'https://example.com/errors/int32',
                input: $data,
                path: [],
                message: 'custom int32 failure',
            ),
        );

        $unit->process(data: 2147483648, context: $context);

        $issues = $context->issues()->toArray();
        $this->assertCount(1, $issues);
        $this->assertSame('https://example.com/errors/int32', $issues[0]->type);
        $this->assertSame('custom int32 failure', $issues[0]->message);
    }

    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new NumericInt32Constraint();

        $this->assertFalse($unit->skipOnIssues());
    }
}
