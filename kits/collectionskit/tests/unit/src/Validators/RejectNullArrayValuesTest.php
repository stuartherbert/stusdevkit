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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

#[TestDox(RejectNullArrayValues::class)]
class RejectNullArrayValuesTest extends TestCase
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
            RejectNullArrayValues::class,
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

        $reflection = new ReflectionClass(RejectNullArrayValues::class);

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

        // RejectNullArrayValues is a single-purpose validator;
        // pin the published method list so any new public method
        // forces a conscious update to this test.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['check'];
        $reflection = new ReflectionClass(RejectNullArrayValues::class);

        // ----------------------------------------------------------------
        // perform the change

        $methodNames = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->getDeclaringClass()->getName() === RejectNullArrayValues::class) {
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

        $method = new ReflectionMethod(RejectNullArrayValues::class, 'check');

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

        $expected = ['data', 'collectionType'];
        $method = new ReflectionMethod(RejectNullArrayValues::class, 'check');

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

        $method = new ReflectionMethod(RejectNullArrayValues::class, 'check');

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

    #[TestDox('::check() accepts an empty array')]
    public function test_accepts_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() does not throw for an
        // empty array

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        RejectNullArrayValues::check(
            data: [],
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // if we get here without an exception, the test passes
        $this->expectNotToPerformAssertions();
    }

    #[TestDox('::check() accepts an array with no null values')]
    public function test_accepts_array_without_nulls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() does not throw for an
        // array containing only non-null values, including
        // falsy values like false, 0, and empty string

        // ----------------------------------------------------------------
        // setup your test

        $data = [
            'a string',
            42,
            3.14,
            true,
            false,
            0,
            '',
            ['nested'],
        ];

        // ----------------------------------------------------------------
        // perform the change

        RejectNullArrayValues::check(
            data: $data,
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // if we get here without an exception, the test passes
        $this->expectNotToPerformAssertions();
    }

    #[TestDox('::check() throws NullValueNotAllowed when array contains null')]
    public function test_throws_when_array_contains_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() throws a
        // NullValueNotAllowed exception when the array
        // contains a null value

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        RejectNullArrayValues::check(
            data: ['alpha', null, 'bravo'],
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('::check() throws when null is the first value')]
    public function test_throws_when_null_is_first(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() detects null at the
        // start of the array

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        RejectNullArrayValues::check(
            data: [null, 'alpha', 'bravo'],
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('::check() throws when null is the last value')]
    public function test_throws_when_null_is_last(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() detects null at the
        // end of the array

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        RejectNullArrayValues::check(
            data: ['alpha', 'bravo', null],
            collectionType: 'TestCollection',
        );

        // ----------------------------------------------------------------
        // test the results

        // assertion handled by expectException() above
    }

    #[TestDox('::check() throws when array has multiple null values')]
    public function test_throws_when_multiple_nulls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that check() detects arrays with
        // more than one null value

        // ----------------------------------------------------------------
        // setup your test

        $this->expectException(NullValueNotAllowedException::class);

        // ----------------------------------------------------------------
        // perform the change

        RejectNullArrayValues::check(
            data: [null, 'alpha', null],
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

        $collectionType = 'DictOfStrings';

        // ----------------------------------------------------------------
        // perform the change

        try {
            RejectNullArrayValues::check(
                data: [null],
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
