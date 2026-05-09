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

namespace StusDevKit\CollectionsKit\Tests\Unit\Validators;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use stdClass;
use StusDevKit\CollectionsKit\Validators\RejectNullValue;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox(RejectNullValue::class)]
class RejectNullValueTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Validators namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract — every
        // caller imports the class by FQN, so moving it is a
        // breaking change that must go through a major version
        // bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Validators';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            RejectNullValue::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published kind (class vs interface vs trait) is part
        // of the contract — switching kinds breaks every consumer
        // that depends on this type.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(RejectNullValue::class);

        // ----------------------------------------------------------------
        // perform the change

        $isInterface = $reflection->isInterface();
        $isTrait = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isInterface);
        $this->assertFalse($isTrait);
    }

    #[TestDox('exposes only ::check() as a public static method')]
    public function test_exposes_only_check(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // RejectNullValue is a single-purpose validator; pin the
        // published method list so any new public method forces a
        // conscious update to this test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['check'];
        $reflection = new ReflectionClass(RejectNullValue::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === RejectNullValue::class) {
                $methodNames[] = $m->getName();
            }
        }
        sort($methodNames);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $methodNames);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::check() is declared public static')]
    public function test_check_is_public_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // check() is a stateless guard — callers invoke it
        // statically. Visibility and static-ness are part of the
        // published contract.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(RejectNullValue::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $isPublic = $method->isPublic();
        $isStatic = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($isPublic);
        $this->assertTrue($isStatic);
    }

    #[TestDox('::check() parameter names in order')]
    public function test_check_parameter_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every collection class invokes check() with named
        // arguments; renaming a parameter is a silent breaking
        // change for those callers.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['value', 'collectionType'];
        $method = new ReflectionMethod(RejectNullValue::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $paramNames = array_map(
            fn(ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $paramNames);
    }

    #[TestDox('::check() returns void')]
    public function test_check_returns_void(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // check() is a guard — it either returns nothing or
        // throws. Switching to a non-void return would change the
        // call-site contract.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(RejectNullValue::class, 'check');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('void', $returnType->getName());
    }

    // ================================================================
    //
    // check()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{mixed}>
     */
    public static function provideNonNullValues(): array
    {
        return [
            'string' => ['hello'],
            'empty string' => [''],
            'integer' => [42],
            'zero' => [0],
            'float' => [3.14],
            'true' => [true],
            'false' => [false],
            'array' => [['nested']],
            'empty array' => [[]],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('::check() accepts non-null value')]
    #[DataProvider('provideNonNullValues')]
    public function test_accepts_non_null_values(
        mixed $value,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() does not throw for
        // non-null values, including falsy values like false,
        // 0, and empty string

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        RejectNullValue::check(
            value: $value,
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // if we get here without an exception, the test passes
        $this->expectNotToPerformAssertions();
    }

    #[TestDox('::check() throws NullValueNotAllowed for null value')]
    public function test_throws_for_null_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() throws a
        // NullValueNotAllowed exception when the value is null

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        RejectNullValue::check(
            value: null,
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('::check() exception message includes the collection type')]
    public function test_exception_includes_collection_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception message includes
        // the collection type, so the caller knows which
        // collection rejected the null value

        // ----------------------------------------------------------------
        // setup your test

        $collectionType = 'ListOfStrings';

        // ----------------------------------------------------------------
        // perform the change

        try {
            RejectNullValue::check(
                value: null,
                collectionType: $collectionType,
            );
            $this->fail('Expected NullValueNotAllowed exception');
        } catch (NullValueNotAllowedException $e) {
            // ----------------------------------------------------------------
            // test the results

            $this->assertStringContainsString(
                $collectionType,
                $e->getMessage(),
            );
        }
    }
}
