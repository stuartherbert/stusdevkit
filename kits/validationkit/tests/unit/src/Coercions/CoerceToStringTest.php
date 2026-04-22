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

namespace StusDevKit\ValidationKit\Tests\Unit\Coercions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Coercions\CoerceToString;

#[TestDox('CoerceToString')]
class CoerceToStringTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Coercions namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(CoerceToString::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Coercions',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        $reflection = new \ReflectionClass(CoerceToString::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    #[TestDox('implements ValueCoercion')]
    public function test_implements_ValueCoercion(): void
    {
        $reflection = new \ReflectionClass(CoerceToString::class);
        $this->assertContains(
            \StusDevKit\ValidationKit\Contracts\ValueCoercion::class,
            $reflection->getInterfaceNames(),
        );
    }

    #[TestDox('declares only coerce as its own public method')]
    public function test_declares_own_method_set(): void
    {
        $reflection = new \ReflectionClass(CoerceToString::class);
        $ownMethods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === CoerceToString::class) {
                $ownMethods[] = $m->getName();
            }
        }
        sort($ownMethods);
        $this->assertSame(['coerce'], $ownMethods);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() is declared public (instance method)')]
    public function test_coerce_is_public_instance(): void
    {
        $method = new \ReflectionMethod(CoerceToString::class, 'coerce');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    #[TestDox('->coerce() parameter names in order')]
    public function test_coerce_parameter_names(): void
    {
        $method = new \ReflectionMethod(CoerceToString::class, 'coerce');
        $paramNames = array_map(fn(\ReflectionParameter $p) => $p->getName(), $method->getParameters());
        $this->assertSame(['data'], $paramNames);
    }

    #[TestDox('->coerce() declares $data as mixed')]
    public function test_coerce_parameter_types(): void
    {
        $method = new \ReflectionMethod(CoerceToString::class, 'coerce');
        $type = $method->getParameters()[0]->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    #[TestDox('->coerce() declares return type mixed')]
    public function test_coerce_return_type(): void
    {
        $method = new \ReflectionMethod(CoerceToString::class, 'coerce');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    // ================================================================
    //
    // Successful Coercions
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: mixed, 1: string}>
     */
    public static function provideCoercibleValues(): array
    {
        return [
            'int 42'        => [42, '42'],
            'int 0'         => [0, '0'],
            'int -7'        => [-7, '-7'],
            'float 3.14'    => [3.14, '3.14'],
            'float 0.0'     => [0.0, '0'],
            'bool true'     => [true, 'true'],
            'bool false'    => [false, 'false'],
        ];
    }

    #[DataProvider('provideCoercibleValues')]
    #[TestDox('->coerce() coerces to string')]
    public function test_coerces_to_string(
        mixed $inputValue,
        string $expectedResult,
    ): void {
        $unit = new CoerceToString();

        $actualResult = $unit->coerce($inputValue);

        $this->assertSame($expectedResult, $actualResult);
    }

    // ================================================================
    //
    // Non-Coercible Values
    /**
     * @return array<string, array{0: mixed}>
     */
    //
    // ----------------------------------------------------------------

    public static function provideNonCoercibleValues(): array
    {
        return [
            'null'      => [null],
            'array'     => [['a']],
            'string'    => ['already a string'],
        ];
    }

    #[DataProvider('provideNonCoercibleValues')]
    #[TestDox('->coerce() returns non-coercible value unchanged')]
    public function test_returns_non_coercible_unchanged(
        mixed $inputValue,
    ): void {
        $unit = new CoerceToString();

        $actualResult = $unit->coerce($inputValue);

        $this->assertSame($inputValue, $actualResult);
    }
}
