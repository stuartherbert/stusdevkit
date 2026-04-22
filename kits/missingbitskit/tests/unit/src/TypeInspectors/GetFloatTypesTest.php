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
use StusDevKit\MissingBitsKit\TypeInspectors\GetFloatTypes;

#[TestDox(GetFloatTypes::class)]
class GetFloatTypesTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\TypeInspectors namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new ReflectionClass(GetFloatTypes::class);
        $this->assertSame(
            'StusDevKit\\MissingBitsKit\\TypeInspectors',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(GetFloatTypes::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('exposes __invoke() and ::from() as its public methods')]
    public function test_exposes_expected_public_methods(): void
    {
        $reflection = new ReflectionClass(GetFloatTypes::class);
        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === GetFloatTypes::class) {
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
        $method = new ReflectionMethod(GetFloatTypes::class, '__invoke');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->__invoke() parameter names in order')]
    public function test_invoke_parameter_names(): void
    {
        $method = new ReflectionMethod(GetFloatTypes::class, '__invoke');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['input'], $paramNames);
    }

    #[TestDox('->__invoke() returns array')]
    public function test_invoke_return_type(): void
    {
        $method = new ReflectionMethod(GetFloatTypes::class, '__invoke');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
    }

    #[TestDox('::from() is declared public static')]
    public function test_from_is_public_static(): void
    {
        $method = new ReflectionMethod(GetFloatTypes::class, 'from');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    #[TestDox('::from() parameter names in order')]
    public function test_from_parameter_names(): void
    {
        $method = new ReflectionMethod(GetFloatTypes::class, 'from');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['item'], $paramNames);
    }

    #[TestDox('::from() returns array')]
    public function test_from_return_type(): void
    {
        $method = new ReflectionMethod(GetFloatTypes::class, 'from');
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
        /**
         * the GetFloatTypes class can be instantiated as an invokable object
         */
        $unit = new GetFloatTypes();

        $this->assertInstanceOf(GetFloatTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonFloatProvider(): array
    {
        return [
            'int' => [42],
            'numeric string' => ['45.6'],
            'plain string' => ['hello'],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('->__invoke() returns empty array for non-float input')]
    #[DataProvider('nonFloatProvider')]
    public function test_invoke_rejects_non_float_input(mixed $input): void
    {
        /**
         * any input which is not strictly a PHP float is rejected by the
         * __invoke() type-guard and produces an empty type list - no loose-
         * typing coercion (e.g. treating an int or a numeric string as a float)
         * is applied
         */
        $unit = new GetFloatTypes();
        $expected = [];

        $actual = $unit($input);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{float}>
     */
    public static function floatProvider(): array
    {
        return [
            'zero' => [0.0],
            'positive' => [1.5],
            'negative' => [-3.14],
            'very small' => [1e-10],
            'very large' => [1e20],
        ];
    }

    #[TestDox('::from() returns numeric and float for any float')]
    #[DataProvider('floatProvider')]
    public function test_from_returns_expected_types(float $input): void
    {
        /**
         * GetFloatTypes::from() returns the same 'numeric', 'float' list
         * regardless of the specific float value. 'mixed' is not emitted here:
         * it is the duck-type marker owned by GetDuckTypes, not by per-type
         * inspectors.
         */
        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
        ];

        $actual = GetFloatTypes::from($input);

        $this->assertSame($expected, $actual);
    }
}
