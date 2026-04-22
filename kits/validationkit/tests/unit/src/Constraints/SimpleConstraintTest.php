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
use StusDevKit\ValidationKit\Constraints\SimpleConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;

#[TestDox('SimpleConstraint')]
class SimpleConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(SimpleConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as an abstract class')]
    public function test_is_an_abstract_class(): void
    {
        $reflection = new \ReflectionClass(SimpleConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isAbstract());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(SimpleConstraint::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseConstraint::class,
            $parent->getName(),
        );
    }

    #[TestDox('implements ValidationConstraint (via BaseConstraint)')]
    public function test_implements_ValidationConstraint(): void
    {
        $reflection = new \ReflectionClass(SimpleConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares only process as its own public method')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(SimpleConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === SimpleConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['process'], $ownMethods);
    }

    #[TestDox('declares getType as an abstract protected method')]
    public function test_getType_is_abstract_protected(): void
    {
        $method = new \ReflectionMethod(SimpleConstraint::class, 'getType');
        $this->assertTrue($method->isAbstract());
        $this->assertTrue($method->isProtected());
    }

    #[TestDox('declares check as an abstract protected method')]
    public function test_check_is_abstract_protected(): void
    {
        $method = new \ReflectionMethod(SimpleConstraint::class, 'check');
        $this->assertTrue($method->isAbstract());
        $this->assertTrue($method->isProtected());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(SimpleConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(SimpleConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(SimpleConstraint::class, 'process');
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
        $method = new \ReflectionMethod(SimpleConstraint::class, 'process');
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
     * Constraints never mutate the value — SimpleConstraint
     * wraps check() but still returns the input unchanged.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = $this->makeUnit(type: 'https://example.com/errors/x', message: null);

        $actualResult = $unit->process(data: 'hello', context: new ValidationContext());

        $this->assertSame('hello', $actualResult);
    }

    /**
     * A null return from check() is the success signal —
     * context must stay clean.
     */
    #[TestDox('->process() records no issue when check() returns null')]
    public function test_process_records_no_issue_on_null(): void
    {
        $context = new ValidationContext();
        $unit = $this->makeUnit(type: 'https://example.com/errors/x', message: null);

        $unit->process(data: 'hello', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Empty string is the footgun case (easy to return ''
     * by accident). SimpleConstraint treats empty string
     * as success — the same as null.
     */
    #[TestDox('->process() records no issue when check() returns empty string')]
    public function test_process_records_no_issue_on_empty_string(): void
    {
        $context = new ValidationContext();
        $unit = $this->makeUnit(type: 'https://example.com/errors/x', message: '');

        $unit->process(data: 'hello', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * A non-empty string from check() is the failure
     * signal — an issue is added to the context.
     */
    #[TestDox('->process() records an issue when check() returns a non-empty string')]
    public function test_process_records_issue_with_message(): void
    {
        $context = new ValidationContext();
        $unit = $this->makeUnit(
            type: 'https://example.com/errors/bad',
            message: 'value is bad',
        );

        $unit->process(data: 'hello', context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * The recorded issue carries the type URI from
     * getType() and the message from check().
     */
    #[TestDox('->process() records the issue with the URI from getType() and the message from check()')]
    public function test_process_records_issue_payload(): void
    {
        $context = new ValidationContext();
        $unit = $this->makeUnit(
            type: 'https://example.com/errors/bad',
            message: 'value is bad',
        );

        $unit->process(data: 'hello', context: $context);

        $issues = $context->issues()->toArray();
        $this->assertCount(1, $issues);
        $this->assertSame('https://example.com/errors/bad', $issues[0]->type);
        $this->assertSame('value is bad', $issues[0]->message);
    }

    /**
     * SimpleConstraint is a constraint, so it never
     * opts out on prior issues.
     */
    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = $this->makeUnit(type: 'https://example.com/errors/x', message: null);

        $this->assertFalse($unit->skipOnIssues());
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * build an anonymous SimpleConstraint whose getType()
     * and check() are fixed to the supplied values
     */
    private function makeUnit(string $type, ?string $message): SimpleConstraint
    {
        return new class ($type, $message) extends SimpleConstraint {
            public function __construct(
                private readonly string $type,
                private readonly ?string $message,
            ) {
            }

            protected function getType(): string
            {
                /** @var non-empty-string */
                return $this->type;
            }

            protected function check(mixed $data): ?string
            {
                return $this->message;
            }
        };
    }
}
