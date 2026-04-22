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
use StusDevKit\ValidationKit\Constraints\StringStartsWithConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('StringStartsWithConstraint')]
class StringStartsWithConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringStartsWithConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringStartsWithConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringStartsWithConstraint::class);
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
        $reflection = new \ReflectionClass(StringStartsWithConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares __construct, prefix and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringStartsWithConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringStartsWithConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'prefix', 'process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['prefix', 'error'], $paramNames);
    }

    #[TestDox('::__construct() declares $prefix as string and $error as nullable callable')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, '__construct');
        $params = $method->getParameters();

        $prefixType = $params[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $prefixType);
        $this->assertSame('string', $prefixType->getName());

        $errorType = $params[1]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $errorType);
        $this->assertSame('callable', $errorType->getName());
        $this->assertTrue($errorType->allowsNull());
    }

    #[TestDox('->prefix() is declared public (instance method)')]
    public function test_prefix_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, 'prefix');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->prefix() declares return type string')]
    public function test_prefix_return_type(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, 'prefix');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('string', $type->getName());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, 'process');
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
        $method = new \ReflectionMethod(StringStartsWithConstraint::class, 'process');
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
     * The configured prefix must be readable via the prefix()
     * introspector.
     */
    #[TestDox('->prefix() returns the configured prefix')]
    public function test_prefix_returns_configured_value(): void
    {
        $unit = new StringStartsWithConstraint(prefix: 'https://');

        $this->assertSame('https://', $unit->prefix());
    }

    /**
     * Constraints never mutate the value.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new StringStartsWithConstraint(prefix: 'https://');

        $actualResult = $unit->process(
            data: 'https://example.test',
            context: new ValidationContext(),
        );

        $this->assertSame('https://example.test', $actualResult);
    }

    /**
     * Happy path — a string that begins with the required
     * prefix is accepted.
     */
    #[TestDox('->process() records no issue when the string starts with the required prefix')]
    public function test_process_accepts_prefix_match(): void
    {
        $context = new ValidationContext();
        $unit = new StringStartsWithConstraint(prefix: 'https://');

        $unit->process(data: 'https://example.test', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Failure — a string that does not begin with the
     * required prefix is rejected.
     */
    #[TestDox('->process() records an issue when the string does not start with the required prefix')]
    public function test_process_rejects_missing_prefix(): void
    {
        $context = new ValidationContext();
        $unit = new StringStartsWithConstraint(prefix: 'https://');

        $unit->process(data: 'http://example.test', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Edge — the empty string is rejected against any
     * non-empty prefix.
     */
    #[TestDox('->process() records an issue when the empty string is given and the prefix is non-empty')]
    public function test_process_rejects_empty_string_for_nonempty_prefix(): void
    {
        $context = new ValidationContext();
        $unit = new StringStartsWithConstraint(prefix: 'foo');

        $unit->process(data: '', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Edge — an empty prefix is satisfied by every string,
     * including the empty string (str_starts_with returns
     * true for an empty needle).
     */
    #[TestDox('->process() accepts any input when the configured prefix is empty')]
    public function test_process_accepts_any_input_when_prefix_empty(): void
    {
        $context = new ValidationContext();
        $unit = new StringStartsWithConstraint(prefix: '');

        $unit->process(data: '', context: $context);
        $unit->process(data: 'anything', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Multibyte — str_starts_with is byte-based but works
     * correctly for any valid UTF-8 prefix that is itself a
     * byte prefix of the input.
     */
    #[TestDox('->process() accepts multibyte prefixes when the input starts with them')]
    public function test_process_accepts_multibyte_prefix(): void
    {
        $context = new ValidationContext();
        $unit = new StringStartsWithConstraint(prefix: 'こん');

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
        $unit = new StringStartsWithConstraint(
            prefix: 'https://',
            error: fn(mixed $data) => new ValidationIssue(
                type: 'https://example.test/no-https',
                input: $data,
                path: [],
                message: 'HTTPS required',
            ),
        );

        $unit->process(data: 'ftp://example.test', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new StringStartsWithConstraint(prefix: 'x');

        $this->assertFalse($unit->skipOnIssues());
    }
}
