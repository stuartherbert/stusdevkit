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
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;

#[TestDox('BaseConstraint')]
class BaseConstraintTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Constraints namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(BaseConstraint::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Constraints',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as an abstract class')]
    public function test_is_an_abstract_class(): void
    {
        $reflection = new \ReflectionClass(BaseConstraint::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertTrue($reflection->isAbstract());
    }

    #[TestDox('implements ValidationConstraint')]
    public function test_implements_ValidationConstraint(): void
    {
        $reflection = new \ReflectionClass(BaseConstraint::class);
        $this->assertContains(
            ValidationConstraint::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares only skipOnIssues as its own public method')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(BaseConstraint::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === BaseConstraint::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['skipOnIssues'], $ownMethods);
    }

    #[TestDox('declares process as an abstract method inherited from ValidationConstraint')]
    public function test_process_is_abstract(): void
    {
        // process() is the one job a subclass must fill in;
        // BaseConstraint never implements it directly.
        $method = new \ReflectionMethod(BaseConstraint::class, 'process');
        $this->assertTrue($method->isAbstract());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->skipOnIssues() is declared public (instance method)')]
    public function test_skipOnIssues_is_public_instance(): void
    {
        $method = new \ReflectionMethod(BaseConstraint::class, 'skipOnIssues');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->skipOnIssues() takes no parameters')]
    public function test_skipOnIssues_parameter_names(): void
    {
        $method = new \ReflectionMethod(BaseConstraint::class, 'skipOnIssues');
        $this->assertSame([], $method->getParameters());
    }

    #[TestDox('->skipOnIssues() declares return type bool')]
    public function test_skipOnIssues_return_type(): void
    {
        $method = new \ReflectionMethod(BaseConstraint::class, 'skipOnIssues');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('bool', $type->getName());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * Constraints always run, regardless of prior issues —
     * that is the defining difference from transformers
     * (which can opt out via skipOnIssues()=true).
     */
    #[TestDox('->skipOnIssues() returns false by default')]
    public function test_skipOnIssues_returns_false(): void
    {
        // anonymous subclass lets us instantiate an abstract
        // class purely to exercise its concrete default
        $unit = new class extends BaseConstraint {
            public function process(
                mixed $data,
                ValidationContext $context,
            ): mixed {
                return $data;
            }
        };

        $this->assertFalse($unit->skipOnIssues());
    }
}
