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
use StusDevKit\ValidationKit\Constraints\StringPasswordConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;

#[TestDox('StringPasswordConstraint')]
class StringPasswordConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(StringPasswordConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a final class')]
    public function test_is_a_final_class(): void
    {
        $reflection = new \ReflectionClass(StringPasswordConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('extends BaseConstraint')]
    public function test_extends_BaseConstraint(): void
    {
        $reflection = new \ReflectionClass(StringPasswordConstraint::class);
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
        $reflection = new \ReflectionClass(StringPasswordConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares only process as its own public method')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(StringPasswordConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === StringPasswordConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['process'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() takes no parameters (marker constraint)')]
    public function test_construct_has_no_parameters(): void
    {
        // StringPasswordConstraint is a marker — the
        // `format: password` hint has no runtime knobs,
        // so the constructor takes nothing.
        $reflection = new \ReflectionClass(StringPasswordConstraint::class);
        $constructor = $reflection->getConstructor();

        // The class inherits the implicit default constructor
        // from BaseConstraint, which PHP reports as declared on
        // the parent. Either way, the parameter list is empty.
        $this->assertTrue(
            $constructor === null || $constructor->getParameters() === [],
        );
    }

    #[TestDox('->process() is declared public (instance method)')]
    public function test_process_is_public_instance(): void
    {
        $method = new \ReflectionMethod(StringPasswordConstraint::class, 'process');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->process() parameter names in order')]
    public function test_process_parameter_names(): void
    {
        $method = new \ReflectionMethod(StringPasswordConstraint::class, 'process');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['data', 'context'], $paramNames);
    }

    #[TestDox('->process() declares $data as mixed and $context as ValidationContext')]
    public function test_process_parameter_types(): void
    {
        $method = new \ReflectionMethod(StringPasswordConstraint::class, 'process');
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
        $method = new \ReflectionMethod(StringPasswordConstraint::class, 'process');
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
     * The `password` format is a UI hint (OpenAPI
     * tells clients to obscure the input field).
     * The constraint performs no validation, so every
     * string — weak, strong, short, long — passes.
     */
    #[TestDox('->process() records no issue for any input string (marker-only, no policy)')]
    #[DataProvider('provideAssortedPasswordStrings')]
    public function test_process_records_no_issue_for_any_string(string $password): void
    {
        $context = new ValidationContext();
        $unit = new StringPasswordConstraint();

        $unit->process(data: $password, context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Constraints never mutate their input — even a
     * pure marker returns the value unchanged so the
     * pipeline can flow through.
     */
    #[TestDox('->process() returns the input value unchanged')]
    public function test_process_returns_input_unchanged(): void
    {
        $unit = new StringPasswordConstraint();

        $actualResult = $unit->process(
            data: 'hunter2',
            context: new ValidationContext(),
        );

        $this->assertSame('hunter2', $actualResult);
    }

    /**
     * The marker imposes no minimum length — an empty
     * password is still accepted at this layer. Any
     * length policy lives in a separate constraint.
     */
    #[TestDox('->process() accepts the empty string (no minimum-length policy)')]
    public function test_process_accepts_empty_string(): void
    {
        $context = new ValidationContext();
        $unit = new StringPasswordConstraint();

        $unit->process(data: '', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * The marker imposes no character-class policy —
     * digits, symbols, mixed case are all out of scope.
     * Any policy of that kind belongs in a separate
     * constraint.
     */
    #[TestDox('->process() accepts a string with no digits, symbols, or mixed case')]
    public function test_process_accepts_weak_composition(): void
    {
        $context = new ValidationContext();
        $unit = new StringPasswordConstraint();

        $unit->process(data: 'password', context: $context);

        $this->assertFalse($context->hasIssues());
    }

    /**
     * Constraints run regardless of prior issues —
     * inherited from BaseConstraint.
     */
    #[TestDox('->skipOnIssues() returns false (inherited constraint default)')]
    public function test_skipOnIssues_returns_false(): void
    {
        $unit = new StringPasswordConstraint();

        $this->assertFalse($unit->skipOnIssues());
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function provideAssortedPasswordStrings(): array
    {
        // a spread of strings covering every dimension a
        // policy-enforcing password constraint would care
        // about — this marker ignores all of them.
        return [
            'empty string'              => [''],
            'single character'          => ['a'],
            'all lowercase letters'     => ['password'],
            'all uppercase letters'     => ['PASSWORD'],
            'mixed case letters'        => ['PassWord'],
            'digits only'               => ['12345678'],
            'symbols only'              => ['!@#$%^&*'],
            'strong mixed composition'  => ['Str0ng!Passw0rd'],
            'unicode characters'        => ['пароль'],
            'very long string'          => [str_repeat('x', 200)],
        ];
    }
}
