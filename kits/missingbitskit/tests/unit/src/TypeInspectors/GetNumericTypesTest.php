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
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleToString;
use StusDevKit\MissingBitsKit\TypeInspectors\GetNumericTypes;

#[TestDox(GetNumericTypes::class)]
class GetNumericTypesTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\TypeInspectors namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new ReflectionClass(GetNumericTypes::class);
        $this->assertSame(
            'StusDevKit\\MissingBitsKit\\TypeInspectors',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(GetNumericTypes::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('exposes __invoke() and ::from() as its public methods')]
    public function test_exposes_expected_public_methods(): void
    {
        $reflection = new ReflectionClass(GetNumericTypes::class);
        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === GetNumericTypes::class) {
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
        $method = new ReflectionMethod(GetNumericTypes::class, '__invoke');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->__invoke() parameter names in order')]
    public function test_invoke_parameter_names(): void
    {
        $method = new ReflectionMethod(GetNumericTypes::class, '__invoke');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['input'], $paramNames);
    }

    #[TestDox('->__invoke() returns array')]
    public function test_invoke_return_type(): void
    {
        $method = new ReflectionMethod(GetNumericTypes::class, '__invoke');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('array', $returnType->getName());
    }

    #[TestDox('::from() is declared public static')]
    public function test_from_is_public_static(): void
    {
        $method = new ReflectionMethod(GetNumericTypes::class, 'from');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    #[TestDox('::from() parameter names in order')]
    public function test_from_parameter_names(): void
    {
        $method = new ReflectionMethod(GetNumericTypes::class, 'from');
        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['item'], $paramNames);
    }

    #[TestDox('::from() returns array')]
    public function test_from_return_type(): void
    {
        $method = new ReflectionMethod(GetNumericTypes::class, 'from');
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
         * the GetNumericTypes class can be instantiated as an invokable object
         */
        $unit = new GetNumericTypes();

        $this->assertInstanceOf(GetNumericTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects non-numeric input
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonNumericProvider(): array
    {
        return [
            'plain string' => ['hello'],
            'empty string' => [''],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('->__invoke() returns empty array for non-numeric input')]
    #[DataProvider('nonNumericProvider')]
    public function test_invoke_rejects_non_numeric_input(mixed $input): void
    {
        /**
         * any input which is not numeric is rejected by the __invoke() guard
         * and produces an empty type list
         */
        $unit = new GetNumericTypes();
        $expected = [];

        $actual = $unit($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->__invoke() returns empty array for Stringable whose string is non-numeric')]
    public function test_invoke_rejects_stringable_with_non_numeric_value(): void
    {
        /**
         * __invoke coerces a Stringable to a string (SampleToString produces
         * 'some text') and then rejects it because the resulting string is not
         * numeric
         */
        $unit = new GetNumericTypes();
        $input = new SampleToString();
        $expected = [];

        $actual = $unit($input);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - integer input
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{int}>
     */
    public static function integerProvider(): array
    {
        return [
            'zero' => [0],
            'positive' => [42],
            'negative' => [-7],
            'max' => [PHP_INT_MAX],
        ];
    }

    #[TestDox('::from() returns numeric and int for an integer')]
    #[DataProvider('integerProvider')]
    public function test_from_returns_expected_types_for_integer(int $input): void
    {
        /**
         * passing an integer produces the numeric family list - no 'string' is
         * added because the input was never a string
         */
        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
        ];

        $actual = GetNumericTypes::from($input);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - float input
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
        ];
    }

    #[TestDox('::from() returns numeric and float for a float')]
    #[DataProvider('floatProvider')]
    public function test_from_returns_expected_types_for_float(float $input): void
    {
        /**
         * passing a float produces the numeric family list - no 'string' is
         * added because the input was never a string
         */
        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
        ];

        $actual = GetNumericTypes::from($input);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - string input
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns numeric, int, and string for an integer-shaped numeric string')]
    public function test_from_returns_expected_types_for_numeric_int_string(): void
    {
        /**
         * a string containing an integer value produces the numeric family list
         * plus 'string' - the 'string' marker carries the fact that the
         * original input was a string, not a coerced int
         */
        $input = '123';
        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
            'string' => 'string',
        ];

        $actual = GetNumericTypes::from($input);

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns numeric, float, and string for a float-shaped numeric string')]
    public function test_from_returns_expected_types_for_numeric_float_string(): void
    {
        /**
         * a string containing a decimal value produces the numeric family list
         * plus 'string' - the 'string' marker carries the fact that the
         * original input was a string, not a coerced float
         */
        $input = '45.6';
        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
            'string' => 'string',
        ];

        $actual = GetNumericTypes::from($input);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array<string,array{string}>
     */
    public static function nonNumericStringProvider(): array
    {
        return [
            'empty' => [''],
            'plain text' => ['hello'],
            'alphanumeric' => ['abc123'],
        ];
    }

    #[TestDox('::from() returns empty array for a non-numeric string')]
    #[DataProvider('nonNumericStringProvider')]
    public function test_from_returns_empty_for_non_numeric_string(string $input): void
    {
        /**
         * strings whose content is not numeric produce an empty type list - the
         * `is_numeric()` check guards the body of from()
         */
        $expected = [];

        $actual = GetNumericTypes::from($input);

        $this->assertSame($expected, $actual);
    }
}
