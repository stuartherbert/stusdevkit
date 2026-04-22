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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelChild;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelGrandparent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelParent;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy;

#[TestDox(GetClassHierarchy::class)]
class GetClassHierarchyTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\TypeInspectors namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new ReflectionClass(GetClassHierarchy::class);
        $this->assertSame(
            'StusDevKit\\MissingBitsKit\\TypeInspectors',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(GetClassHierarchy::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('exposes __invoke() and ::from() as its public methods')]
    public function test_exposes_expected_public_methods(): void
    {
        $reflection = new ReflectionClass(GetClassHierarchy::class);
        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === GetClassHierarchy::class) {
                $methodNames[] = $m->getName();
            }
        }
        sort($methodNames);
        $this->assertSame(['__invoke', 'from'], $methodNames);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->__invoke() is declared public, non-static')]
    public function test_invoke_is_public_non_static(): void
    {
        $method = new ReflectionMethod(GetClassHierarchy::class, '__invoke');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->__invoke() parameter names in order')]
    public function test_invoke_parameter_names(): void
    {
        $method = new ReflectionMethod(GetClassHierarchy::class, '__invoke');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['input'], $paramNames);
    }

    #[TestDox('->__invoke() returns array')]
    public function test_invoke_return_type(): void
    {
        $method = new ReflectionMethod(GetClassHierarchy::class, '__invoke');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
    }

    #[TestDox('::from() is declared public static')]
    public function test_from_is_public_static(): void
    {
        $method = new ReflectionMethod(GetClassHierarchy::class, 'from');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    #[TestDox('::from() parameter names in order')]
    public function test_from_parameter_names(): void
    {
        $method = new ReflectionMethod(GetClassHierarchy::class, 'from');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['className'], $paramNames);
    }

    #[TestDox('::from() returns array')]
    public function test_from_return_type(): void
    {
        $method = new ReflectionMethod(GetClassHierarchy::class, 'from');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
    }

    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() returns a new instance')]
    public function test_can_instantiate(): void
    {
        /** GetClassHierarchy is instantiable as an invokable object */
        $unit = new GetClassHierarchy();

        $this->assertInstanceOf(GetClassHierarchy::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonClassStringProvider(): array
    {
        return [
            'int' => [42],
            'float' => [1.5],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
            'unknown class name' => ['ClassThatDoesNotExist'],
            'empty string' => [''],
        ];
    }

    #[TestDox('->__invoke() returns empty array for input that is not a known class/interface name')]
    #[DataProvider('nonClassStringProvider')]
    public function test_invoke_rejects_non_class_input(mixed $input): void
    {
        /**
         * __invoke() rejects any input that is not a string naming
         * a known class or interface - so non-strings, and strings
         * that do not resolve to anything loaded in the current
         * process, both produce an empty type list
         */
        $unit = new GetClassHierarchy();
        $expected = [];

        $actual = $unit($input);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from()
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns only the class itself when it has no parents')]
    public function test_from_returns_only_class_when_no_parents(): void
    {
        /**
         * a class with no parent class produces a hierarchy list
         * containing only the class itself
         */
        $expected = [
            stdClass::class => stdClass::class,
        ];

        $actual = GetClassHierarchy::from(stdClass::class);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns class plus its parent for a 2-deep hierarchy')]
    public function test_from_returns_class_and_parent(): void
    {
        /**
         * a class extending one parent produces a hierarchy list of
         * the class followed by its parent
         */
        $expected = [
            SampleClass::class => SampleClass::class,
            SampleParent::class => SampleParent::class,
        ];

        $actual = GetClassHierarchy::from(SampleClass::class);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns the full chain for a 3-deep hierarchy')]
    public function test_from_returns_full_three_level_hierarchy(): void
    {
        /**
         * from() walks the entire parent chain - not just the
         * immediate parent - and returns every ancestor in order
         * (child, parent, grandparent)
         */
        $expected = [
            ThreeLevelChild::class => ThreeLevelChild::class,
            ThreeLevelParent::class => ThreeLevelParent::class,
            ThreeLevelGrandparent::class => ThreeLevelGrandparent::class,
        ];

        $actual = GetClassHierarchy::from(ThreeLevelChild::class);

        $this->assertSame($expected, $actual);
    }
}
