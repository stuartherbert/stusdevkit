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

namespace StusDevKit\MissingBitsKit\Tests\Unit\TypeInspectors;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\TypeInspectors\GetNamespace;

#[TestDox(GetNamespace::class)]
class GetNamespaceTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\TypeInspectors namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new ReflectionClass(GetNamespace::class);
        $this->assertSame(
            'StusDevKit\\MissingBitsKit\\TypeInspectors',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(GetNamespace::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('exposes only ::from() as a public method')]
    public function test_exposes_only_from(): void
    {
        $reflection = new ReflectionClass(GetNamespace::class);
        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === GetNamespace::class) {
                $methodNames[] = $m->getName();
            }
        }
        sort($methodNames);
        $this->assertSame(['from'], $methodNames);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() is declared public static')]
    public function test_from_is_public_static(): void
    {
        $method = new ReflectionMethod(GetNamespace::class, 'from');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    #[TestDox('::from() parameter names in order')]
    public function test_from_parameter_names(): void
    {
        $method = new ReflectionMethod(GetNamespace::class, 'from');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['item'], $paramNames);
    }

    #[TestDox('::from() returns string')]
    public function test_from_return_type(): void
    {
        $method = new ReflectionMethod(GetNamespace::class, 'from');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // from() - class name input
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns the namespace part of a fully-qualified class name')]
    public function test_from_returns_namespace_for_namespaced_class_name(): void
    {
        /**
         * when given a namespaced class-string, from() returns everything up to
         * but not including the final backslash separator
         */
        $input = SampleClass::class;
        $expected = 'StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors';

        $actual = GetNamespace::from($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns empty string for a class name without a namespace')]
    public function test_from_returns_empty_for_global_class_name(): void
    {
        /**
         * a class-string with no backslash separator (i.e. a class in the
         * global namespace) produces an empty namespace string
         */
        $input = stdClass::class;
        $expected = '';

        $actual = GetNamespace::from($input);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - object instance input
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns the namespace for a namespaced object instance')]
    public function test_from_returns_namespace_for_namespaced_object(): void
    {
        /**
         * when given an object, from() resolves to the class name and returns
         * the namespace part
         */
        $input = new SampleClass();
        $expected = 'StusDevKit\\MissingBitsKit\\Tests\\Fixtures\\TypeInspectors';

        $actual = GetNamespace::from($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns empty string for an object of a global-namespace class')]
    public function test_from_returns_empty_for_global_object(): void
    {
        /**
         * an object whose class lives in the global namespace (e.g. stdClass)
         * produces an empty namespace string
         */
        $input = new stdClass();
        $expected = '';

        $actual = GetNamespace::from($input);

        $this->assertSame($expected, $actual);
    }
}
