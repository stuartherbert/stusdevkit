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
use StusDevKit\ValidationKit\Constraints\ObjectDependentSchemasConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Validate;

#[TestDox('ObjectDependentSchemasConstraint')]
class ObjectDependentSchemasConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ObjectDependentSchemasConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(ObjectDependentSchemasConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(ObjectDependentSchemasConstraint::class);
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
        $reflection = new \ReflectionClass(ObjectDependentSchemasConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares __construct, dependencies and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(ObjectDependentSchemasConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ObjectDependentSchemasConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(
            ['__construct', 'dependencies', 'process'],
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
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['dependencies'], $paramNames);
    }

    #[TestDox('::__construct() declares $dependencies as array')]
    public function test_construct_parameter_types(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, '__construct');
        $type = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('array', $type->getName());
    }

    #[TestDox('->dependencies() is declared public (instance method)')]
    public function test_dependencies_is_public_instance(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'dependencies');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->dependencies() takes no parameters')]
    public function test_dependencies_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'dependencies');
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('->dependencies() declares return type array')]
    public function test_dependencies_return_type(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'dependencies');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('array', $type->getName());
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'process');
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
        $method = new \ReflectionMethod(ObjectDependentSchemasConstraint::class, 'process');
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
     * The dependency map given at construction time is the
     * sole configuration of this constraint — exposing it
     * lets tooling and JSON-Schema exporters reconstruct the
     * keyword.
     */
    #[TestDox('->dependencies() returns the map passed to the constructor')]
    public function test_dependencies_returns_constructor_map(): void
    {
        $schema = Validate::object(shape: [
            'billing_city' => Validate::string(),
        ]);
        $unit = new ObjectDependentSchemasConstraint(
            dependencies: ['billing_address' => $schema],
        );

        $this->assertSame(['billing_address' => $schema], $unit->dependencies());
    }

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input array unchanged')]
    public function test_process_returns_input_array_unchanged(): void
    {
        $unit = new ObjectDependentSchemasConstraint(dependencies: []);
        $data = ['name' => 'Ada'];

        $actualResult = $unit->process(data: $data, context: new ValidationContext());

        $this->assertSame($data, $actualResult);
    }

    /**
     * When the trigger property is absent, the dependent
     * schema is not consulted at all, so the context stays
     * clean even for data that would fail the schema.
     */
    #[TestDox('->process() records no issue when the trigger property is absent')]
    public function test_process_records_no_issue_when_trigger_absent(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectDependentSchemasConstraint(
            dependencies: [
                'billing_address' => Validate::object(shape: [
                    'billing_city' => Validate::string(),
                ]),
            ],
        );

        $unit->process(
            data: ['name' => 'Ada'],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Trigger present and the whole data satisfies the
     * dependent schema — no issue.
     */
    #[TestDox('->process() records no issue when the dependent schema is satisfied')]
    public function test_process_records_no_issue_when_schema_satisfied(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectDependentSchemasConstraint(
            dependencies: [
                'billing_address' => Validate::object(shape: [
                    'billing_address' => Validate::string(),
                    'billing_city' => Validate::string(),
                ]),
            ],
        );

        $input = new \stdClass();
        $input->billing_address = '123 Main St';
        $input->billing_city = 'Springfield';

        $unit->process(
            data: $input,
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The core failure case: trigger present and the data
     * fails the dependent schema — at least one issue must
     * propagate into our context.
     */
    #[TestDox('->process() records an issue when the dependent schema fails')]
    public function test_process_records_issue_when_schema_fails(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectDependentSchemasConstraint(
            dependencies: [
                'billing_address' => Validate::object(shape: [
                    'billing_address' => Validate::string(),
                    'billing_city' => Validate::string(),
                ]),
            ],
        );

        $unit->process(
            data: [
                'billing_address' => '123 Main St',
            ],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Multiple independent trigger/schema pairs — each one
     * fires in turn and issues accumulate into the same
     * context.
     */
    #[TestDox('->process() evaluates every dependent schema independently')]
    public function test_process_evaluates_every_dependent_schema(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectDependentSchemasConstraint(
            dependencies: [
                'billing_address' => Validate::object(shape: [
                    'billing_address' => Validate::string(),
                    'billing_city' => Validate::string(),
                ]),
                'shipping_address' => Validate::object(shape: [
                    'shipping_address' => Validate::string(),
                    'shipping_city' => Validate::string(),
                ]),
            ],
        );

        $unit->process(
            data: [
                'billing_address' => '123 Main St',
                'shipping_address' => '456 Elm St',
            ],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Empty dependency map — nothing to evaluate, nothing
     * to flag.
     */
    #[TestDox('->process() records no issue when the dependency map is empty')]
    public function test_process_records_no_issue_when_map_empty(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectDependentSchemasConstraint(dependencies: []);

        $unit->process(
            data: ['billing_address' => '123 Main St'],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Objects are treated like arrays — properties are read
     * via get_object_vars().
     */
    #[TestDox('->process() accepts an object as data')]
    public function test_process_accepts_object_data(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectDependentSchemasConstraint(dependencies: []);
        $data = (object) ['name' => 'Ada'];

        $actualResult = $unit->process(data: $data, context: $context);

        $this->assertSame($data, $actualResult);
        $this->assertFalse($context->hasIssues());
    }

    /**
     * Constraints always run regardless of prior issues.
     */
    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new ObjectDependentSchemasConstraint(dependencies: []);

        $this->assertFalse($unit->skipOnIssues());
    }
}
