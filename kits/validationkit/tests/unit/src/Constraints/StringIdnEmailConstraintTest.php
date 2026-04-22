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
use StusDevKit\ValidationKit\Constraints\StringIdnEmailConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('StringIdnEmailConstraint')]
class StringIdnEmailConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringIdnEmailConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringIdnEmailConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringIdnEmailConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('declares only __construct and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringIdnEmailConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringIdnEmailConstraint::class) {
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
        $method = new \ReflectionMethod(StringIdnEmailConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['error'], $paramNames);
    }

    #[TestDox('::__construct() declares $error as a nullable callable with default null')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringIdnEmailConstraint::class, '__construct');
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
        $method = new \ReflectionMethod(StringIdnEmailConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringIdnEmailConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringIdnEmailConstraint::class, 'process');
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
        $method = new \ReflectionMethod(StringIdnEmailConstraint::class, 'process');
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
     * A plain ASCII email is still a valid IDN email — the
     * ASCII case is the lower bound of what IDN accepts.
     */
    #[TestDox('->process() records no issue for a plain ASCII email')]
    public function test_process_passes_on_ascii_email(): void
    {
        $context = new ValidationContext();
        $unit = new StringIdnEmailConstraint();

        $unit->process(data: 'foo@bar.com', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Unicode local parts are the raison d'etre of the IDN
     * email format — RFC 6531 / FILTER_FLAG_EMAIL_UNICODE
     * must accept them.
     */
    #[TestDox('->process() records no issue for a unicode local part')]
    public function test_process_passes_on_unicode_local_part(): void
    {
        $context = new ValidationContext();
        $unit = new StringIdnEmailConstraint();

        $unit->process(data: 'användare@example.com', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Nonsense input must still be rejected — unicode
     * permissiveness doesn't mean anything goes.
     */
    #[TestDox('->process() records an issue for a string that is not an email address')]
    public function test_process_fails_on_invalid_email(): void
    {
        $context = new ValidationContext();
        $unit = new StringIdnEmailConstraint();

        $unit->process(data: 'not-an-email', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new StringIdnEmailConstraint();

        $actualResult = $unit->process(data: 'foo@bar.com', context: new ValidationContext());

        $this->assertSame('foo@bar.com', $actualResult);
    }

    /**
     * When a custom error callback is supplied, the issue
     * it returns is the one that lands in the context.
     */
    #[TestDox('->process() routes failures through the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new StringIdnEmailConstraint(
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:not-idn-email',
                input: $data,
                path: [],
                message: 'custom not-idn-email message',
            ),
        );

        $unit->process(data: 'not-an-email', context: $context);

        $this->assertTrue($context->hasIssues());
    }
}
