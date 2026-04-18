<?php

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
//

declare(strict_types=1);

namespace StusDevKit\CollectionsKit\Tests\Unit\Validators;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\CollectionsKit\Validators\RejectNullValue;
use stdClass;

#[TestDox('RejectNullValue')]
class RejectNullValueTest extends TestCase
{
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
