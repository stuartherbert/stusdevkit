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

namespace StusDevKit\ValidationKit\Tests\Unit\Contracts;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;

#[TestDox(ValueCoercion::class)]
class ValueCoercionTest extends TestCase
{
    // ================================================================
    //
    // Interface identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Contracts namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - every
        // implementer imports the interface by FQN, so moving it
        // is a breaking change that must go through a major
        // version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\ValidationKit\\Contracts';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            ValueCoercion::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as an interface')]
    public function test_is_declared_as_an_interface(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            ValueCoercion::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isInterface();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only coerce() as a public method')]
    public function test_exposes_only_coerce(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = ['coerce'];
        $reflection = new ReflectionClass(
            ValueCoercion::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->coerce() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() is declared')]
    public function test_coerce_is_declared(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            ValueCoercion::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('coerce');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->coerce() is public')]
    public function test_coerce_is_public(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            ValueCoercion::class,
        ))->getMethod('coerce');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->coerce() returns mixed')]
    public function test_coerce_returns_mixed(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = 'mixed';
        $method = (new ReflectionClass(
            ValueCoercion::class,
        ))->getMethod('coerce');
        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $type->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->coerce() declares $data as its only parameter')]
    public function test_coerce_declares_data_parameter(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = ['data'];
        $method = (new ReflectionClass(
            ValueCoercion::class,
        ))->getMethod('coerce');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->coerce() declares $data as mixed')]
    public function test_coerce_declares_data_as_mixed(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $expected = 'mixed';
        $param = (new ReflectionClass(
            ValueCoercion::class,
        ))->getMethod('coerce')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
