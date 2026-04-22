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
use StusDevKit\ValidationKit\Constraints\StringMinLengthConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('StringMinLengthConstraint')]
class StringMinLengthConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringMinLengthConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringMinLengthConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringMinLengthConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('implements ValidationConstraint')]
    public function test_implements_ValidationConstraint(): void
    {
        $reflection = new \ReflectionClass(StringMinLengthConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares __construct, length and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringMinLengthConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringMinLengthConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'length', 'process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['length', 'error'], $paramNames);
    }

    #[TestDox('::__construct() declares $length as int and $error as nullable callable')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, '__construct');
        $params = $method->getParameters();

        $lengthType = $params[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $lengthType);
        $this->assertSame('int', $lengthType->getName());

        $errorType = $params[1]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $errorType);
        $this->assertSame('callable', $errorType->getName());
        $this->assertTrue($errorType->allowsNull());
    }

    #[TestDox('->length() is declared public (instance method)')]
    public function test_length_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, 'length');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->length() declares return type int')]
    public function test_length_return_type(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, 'length');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, 'process');
        $params = $method->getParameters();

        $dataType = $params[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $dataType);
        $this->assertSame('mixed', $dataType->getName());

        $contextType = $params[1]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $contextType);
        $this->assertSame(ValidationContext::class, $contextType->getName());
    }

    #[TestDox('->process() declares return type mixed')]
    public function test_process_return_type(): void
    {
        $method = new \ReflectionMethod(StringMinLengthConstraint::class, 'process');
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
     * The configured minimum length must be readable via the
     * length() introspector.
     */
    #[TestDox('->length() returns the configured minimum length')]
    public function test_length_returns_configured_value(): void
    {
        $unit = new StringMinLengthConstraint(length: 3);

        $this->assertSame(3, $unit->length());
    }

    /**
     * Constraints never mutate the value.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new StringMinLengthConstraint(length: 3);

        $actualResult = $unit->process(data: 'hello', context: new ValidationContext());

        $this->assertSame('hello', $actualResult);
    }

    /**
     * Happy path — a string longer than the minimum is
     * accepted.
     */
    #[TestDox('->process() records no issue when the string is longer than the minimum')]
    public function test_process_accepts_longer_string(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(length: 3);

        $unit->process(data: 'hello world', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Boundary — a string exactly at the minimum length is
     * accepted (the check uses `<`, not `<=`).
     */
    #[TestDox('->process() records no issue when the string is exactly at the minimum length')]
    public function test_process_accepts_boundary_length(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(length: 5);

        $unit->process(data: 'hello', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Failure — a string shorter than the minimum is rejected.
     */
    #[TestDox('->process() records an issue when the string is shorter than the minimum')]
    public function test_process_rejects_shorter_string(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(length: 5);

        $unit->process(data: 'hi', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Edge — the empty string is rejected against any
     * positive minimum.
     */
    #[TestDox('->process() records an issue when the empty string is given and the minimum is positive')]
    public function test_process_rejects_empty_string_when_minimum_positive(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(length: 1);

        $unit->process(data: '', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Edge — the empty string is accepted when the minimum
     * is zero.
     */
    #[TestDox('->process() accepts the empty string when the minimum is zero')]
    public function test_process_accepts_empty_string_at_zero_minimum(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(length: 0);

        $unit->process(data: '', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Multibyte — mb_strlen counts characters, not bytes, so
     * a 5-character unicode string passes a length-5 minimum.
     */
    #[TestDox('->process() counts characters (not bytes) for multibyte input')]
    public function test_process_counts_multibyte_characters(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(length: 5);

        // five hiragana characters — 15 bytes in UTF-8
        $unit->process(data: 'こんにちは', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Custom error callback is invoked on failure.
     */
    #[TestDox('->process() uses the custom error callback when the constraint fails')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new StringMinLengthConstraint(
            length: 5,
            error: fn(mixed $data) => new ValidationIssue(
                type: 'https://example.test/too-small',
                input: $data,
                path: [],
                message: 'too short',
            ),
        );

        $unit->process(data: 'hi', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new StringMinLengthConstraint(length: 3);

        $this->assertFalse($unit->skipOnIssues());
    }
}
