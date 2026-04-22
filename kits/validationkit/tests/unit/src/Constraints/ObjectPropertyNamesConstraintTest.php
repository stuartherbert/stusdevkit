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
use StusDevKit\ValidationKit\Constraints\ObjectPropertyNamesConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('ObjectPropertyNamesConstraint')]
class ObjectPropertyNamesConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(ObjectPropertyNamesConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(ObjectPropertyNamesConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(ObjectPropertyNamesConstraint::class);
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
        $reflection = new \ReflectionClass(ObjectPropertyNamesConstraint::class);
        $this->assertTrue($reflection->implementsInterface(ValidationConstraint::class));
    }

    #[TestDox('declares only __construct, schema, and process as its own public methods')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(ObjectPropertyNamesConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === ObjectPropertyNamesConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['__construct', 'process', 'schema'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectPropertyNamesConstraint::class, '__construct');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['schema', 'error'], $paramNames);
    }

    #[TestDox('::__construct() declares $error as a nullable callable with default null')]
    public function test_construct_error_parameter_is_nullable_callable(): void
    {
        $method = new \ReflectionMethod(ObjectPropertyNamesConstraint::class, '__construct');
        $param = $method->getParameters()[1];

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
        $method = new \ReflectionMethod(ObjectPropertyNamesConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(ObjectPropertyNamesConstraint::class, 'process');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(ObjectPropertyNamesConstraint::class, 'process');
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
        $method = new \ReflectionMethod(ObjectPropertyNamesConstraint::class, 'process');
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
     * When every key satisfies the property-name schema, the
     * context stays clean.
     */
    #[TestDox('->process() records no issue when every property name satisfies the schema')]
    public function test_process_passes_when_all_names_valid(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectPropertyNamesConstraint(
            schema: Validate::string()->min(length: 1),
        );

        $unit->process(
            data: ['name' => 'alice', 'age' => 30],
            context: $context,
        );

        $this->assertFalse($context->hasIssues());
    }

    /**
     * A single key that fails the property-name schema is
     * enough to fail the whole object.
     */
    #[TestDox('->process() records an issue when a property name fails the schema')]
    public function test_process_fails_when_a_name_invalid(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectPropertyNamesConstraint(
            schema: Validate::string()->min(length: 3),
        );

        $unit->process(
            data: ['ok_name' => 1, 'xy' => 2],
            context: $context,
        );

        $this->assertTrue($context->hasIssues());
    }

    /**
     * Objects are treated the same as associative arrays by
     * reading public properties via get_object_vars().
     */
    #[TestDox('->process() also validates property names on a stdClass object')]
    public function test_process_handles_object_data(): void
    {
        $context = new ValidationContext();
        $unit = new ObjectPropertyNamesConstraint(
            schema: Validate::string()->min(length: 1),
        );

        $obj = new \stdClass();
        $obj->name = 'alice';

        $unit->process(data: $obj, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Constraints never mutate the value — they report
     * issues and return the data unchanged.
     */
    #[TestDox('->process() returns the input value unchanged on success')]
    public function test_process_returns_input_unchanged_on_success(): void
    {
        $unit = new ObjectPropertyNamesConstraint(
            schema: Validate::string()->min(length: 1),
        );

        $input = ['name' => 'alice'];
        $actualResult = $unit->process(data: $input, context: new ValidationContext());

        $this->assertSame($input, $actualResult);
    }

    /**
     * Even when a property name is rejected, the
     * pass-through contract still holds.
     */
    #[TestDox('->process() returns the input value unchanged on failure')]
    public function test_process_returns_input_unchanged_on_failure(): void
    {
        $unit = new ObjectPropertyNamesConstraint(
            schema: Validate::string()->min(length: 5),
        );

        $input = ['xy' => 1];
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
        $unit = new ObjectPropertyNamesConstraint(
            schema: Validate::string()->min(length: 5),
            error: fn(mixed $data): ValidationIssue => new ValidationIssue(
                type: 'urn:custom:bad-name',
                input: $data,
                path: [],
                message: 'custom bad-name message',
            ),
        );

        $unit->process(data: ['xy' => 1], context: $context);

        $this->assertTrue($context->hasIssues());
    }

    /**
     * The introspector returns the schema supplied to the
     * constructor verbatim.
     */
    #[TestDox('->schema() returns the schema supplied at construction')]
    public function test_schema_returns_schema(): void
    {
        $schema = Validate::string();
        $unit = new ObjectPropertyNamesConstraint(schema: $schema);

        $this->assertSame($schema, $unit->schema());
    }
}
