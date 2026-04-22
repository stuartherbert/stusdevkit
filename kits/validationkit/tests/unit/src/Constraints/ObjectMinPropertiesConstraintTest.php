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
use StusDevKit\ValidationKit\Constraints\ObjectMinPropertiesConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('ObjectMinPropertiesConstraint')]
class ObjectMinPropertiesConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ObjectMinPropertiesConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(ObjectMinPropertiesConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(ObjectMinPropertiesConstraint::class);
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
        $reflection = new \ReflectionClass(ObjectMinPropertiesConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares __construct, count and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(ObjectMinPropertiesConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ObjectMinPropertiesConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(
            ['__construct', 'count', 'process'],
            $ownMethods,
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['count', 'error'], $paramNames);
    }

    #[TestDox('::__construct() declares $count as int and $error as nullable callable')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, '__construct');
        $params = $method->getParameters();

        $countType = $params[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $countType);
        $this->assertSame('int', $countType->getName());

        $errorType = $params[1]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $errorType);
        $this->assertSame('callable', $errorType->getName());
        $this->assertTrue($errorType->allowsNull());
    }

    #[TestDox('->count() is declared public (instance method)')]
    public function test_count_is_public_instance(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'count');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->count() takes no parameters')]
    public function test_count_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'count');
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('->count() declares return type int')]
    public function test_count_return_type(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'count');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('int', $type->getName());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'process');
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
        $method = new \ReflectionMethod(ObjectMinPropertiesConstraint::class, 'process');
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
     * The configured lower bound is the one piece of state
     * this constraint carries — exposing it lets tooling and
     * JSON-Schema exporters recover the original keyword.
     */
    #[TestDox('->count() returns the limit passed to the constructor')]
    public function test_count_returns_constructor_limit(): void
    {
        $unit = new ObjectMinPropertiesConstraint(count: 2);

        $this->assertSame(2, $unit->count());
    }

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input array unchanged')]
    public function test_process_returns_input_array_unchanged(): void
    {
        $unit = new ObjectMinPropertiesConstraint(count: 1);
        $data = ['a' => 1, 'b' => 2];

        $actualResult = $unit->process(data: $data, context: new ValidationContext());

        $this->assertSame($data, $actualResult);
    }

    /**
     * More properties than the lower bound is obviously fine.
     */
    #[TestDox('->process() records no issue when property count is above the limit')]
    public function test_process_records_no_issue_when_above_limit(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(count: 2);

        $unit->process(
            data: ['a' => 1, 'b' => 2, 'c' => 3],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The lower bound is inclusive — equal to the limit is
     * allowed. Boundary test for the `<` comparison inside
     * the constraint.
     */
    #[TestDox('->process() records no issue when property count equals the limit')]
    public function test_process_records_no_issue_when_equal_to_limit(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(count: 2);

        $unit->process(
            data: ['a' => 1, 'b' => 2],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The core failure case — one fewer property than
     * required flags the data.
     */
    #[TestDox('->process() records an issue when property count is below the limit')]
    public function test_process_records_issue_when_below_limit(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(count: 3);

        $unit->process(
            data: ['a' => 1, 'b' => 2],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
        $this->assertCount(1, $context->issues()->toArray());
    }

    /**
     * An empty object with a non-zero minimum fails — the
     * classic footgun when a caller forgets to set a lower
     * bound of zero for "optional".
     */
    #[TestDox('->process() records an issue for an empty array when the limit is positive')]
    public function test_process_records_issue_when_empty_and_limit_positive(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(count: 1);

        $unit->process(
            data: [],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * A minimum of zero is satisfied by any object,
     * including the empty one.
     */
    #[TestDox('->process() records no issue for an empty array when the limit is zero')]
    public function test_process_records_no_issue_when_empty_and_limit_zero(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(count: 0);

        $unit->process(
            data: [],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Objects are treated like arrays — properties are read
     * via get_object_vars().
     */
    #[TestDox('->process() records an issue when an object is below the limit')]
    public function test_process_records_issue_on_object_below_limit(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(count: 3);

        $unit->process(
            data: (object) ['a' => 1, 'b' => 2],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * The custom error callback is the override hook for
     * callers who want a different problem-type URI or a
     * bespoke message — when supplied, it replaces the
     * default completely.
     */
    #[TestDox('->process() uses the custom error callback when supplied')]
    public function test_process_uses_custom_error_callback(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectMinPropertiesConstraint(
            count: 3,
            error: static fn(mixed $data) => new ValidationIssue(
                type: 'https://example.com/errors/too-few',
                input: $data,
                path: [],
                message: 'custom: too few props',
            ),
        );

        $unit->process(
            data: ['a' => 1],
            context: $context,
        );

        $issues = $context->issues()->toArray();
        $this->assertCount(1, $issues);
        $this->assertSame('https://example.com/errors/too-few', $issues[0]->type);
        $this->assertSame('custom: too few props', $issues[0]->message);
    }

    /**
     * Constraints always run regardless of prior issues.
     */
    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new ObjectMinPropertiesConstraint(count: 1);

        $this->assertFalse($unit->skipOnIssues());
    }
}
