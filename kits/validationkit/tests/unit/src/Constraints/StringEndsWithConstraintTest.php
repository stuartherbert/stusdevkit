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
use StusDevKit\ValidationKit\Constraints\StringEndsWithConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('StringEndsWithConstraint')]
class StringEndsWithConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringEndsWithConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringEndsWithConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringEndsWithConstraint::class);
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
        $reflection = new \ReflectionClass(StringEndsWithConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares __construct, suffix and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringEndsWithConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringEndsWithConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'process', 'suffix'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['suffix', 'error'], $paramNames);
    }

    #[TestDox('::__construct() declares $suffix as string and $error as nullable callable')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, '__construct');
        $params = $method->getParameters();

        $suffixType = $params[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $suffixType);
        $this->assertSame('string', $suffixType->getName());

        $errorType = $params[1]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $errorType);
        $this->assertSame('callable', $errorType->getName());
        $this->assertTrue($errorType->allowsNull());
    }

    #[TestDox('->suffix() is declared public (instance method)')]
    public function test_suffix_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, 'suffix');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->suffix() declares return type string')]
    public function test_suffix_return_type(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, 'suffix');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('string', $type->getName());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, 'process');
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
        $method = new \ReflectionMethod(StringEndsWithConstraint::class, 'process');
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
     * The configured suffix must be readable via the suffix()
     * introspector.
     */
    #[TestDox('->suffix() returns the configured suffix')]
    public function test_suffix_returns_configured_value(): void
    {
        $unit = new StringEndsWithConstraint(suffix: '.php');

        $this->assertSame('.php', $unit->suffix());
    }

    /**
     * Constraints never mutate the value.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new StringEndsWithConstraint(suffix: '.php');

        $actualResult = $unit->process(
            data: 'file.php',
            context: new ValidationContext(),
        );

        $this->assertSame('file.php', $actualResult);
    }

    /**
     * Happy path — a string that ends with the required
     * suffix is accepted.
     */
    #[TestDox('->process() records no issue when the string ends with the required suffix')]
    public function test_process_accepts_suffix_match(): void
    {
        $context = new ValidationContext();
        $unit = new StringEndsWithConstraint(suffix: '.php');

        $unit->process(data: 'index.php', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Failure — a string that does not end with the required
     * suffix is rejected.
     */
    #[TestDox('->process() records an issue when the string does not end with the required suffix')]
    public function test_process_rejects_missing_suffix(): void
    {
        $context = new ValidationContext();
        $unit = new StringEndsWithConstraint(suffix: '.php');

        $unit->process(data: 'index.html', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Edge — the empty string is rejected against any
     * non-empty suffix.
     */
    #[TestDox('->process() records an issue when the empty string is given and the suffix is non-empty')]
    public function test_process_rejects_empty_string_for_nonempty_suffix(): void
    {
        $context = new ValidationContext();
        $unit = new StringEndsWithConstraint(suffix: 'bar');

        $unit->process(data: '', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Edge — an empty suffix is satisfied by every string,
     * including the empty string (str_ends_with returns true
     * for an empty needle).
     */
    #[TestDox('->process() accepts any input when the configured suffix is empty')]
    public function test_process_accepts_any_input_when_suffix_empty(): void
    {
        $context = new ValidationContext();
        $unit = new StringEndsWithConstraint(suffix: '');

        $unit->process(data: '', context: $context);
        $unit->process(data: 'anything', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Multibyte — str_ends_with is byte-based but works
     * correctly for any valid UTF-8 suffix that is itself a
     * byte suffix of the input.
     */
    #[TestDox('->process() accepts multibyte suffixes when the input ends with them')]
    public function test_process_accepts_multibyte_suffix(): void
    {
        $context = new ValidationContext();
        $unit = new StringEndsWithConstraint(suffix: 'ちは');

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
        $unit = new StringEndsWithConstraint(
            suffix: '.php',
            error: fn(mixed $data) => new ValidationIssue(
                type: 'https://example.test/not-php',
                input: $data,
                path: [],
                message: 'must be a PHP file',
            ),
        );

        $unit->process(data: 'index.html', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new StringEndsWithConstraint(suffix: 'x');

        $this->assertFalse($unit->skipOnIssues());
    }
}
