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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\CollectionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;

#[TestDox('RejectNullArrayValues')]
class RejectNullArrayValuesTest extends TestCase
{
    // ================================================================
    //
    // check()
    //
    // ----------------------------------------------------------------

    #[TestDox('Accepts an empty array')]
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

        $this->assertTrue(true);
    }

    #[TestDox('Accepts an array with no null values')]
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

        $this->assertTrue(true);
    }

    #[TestDox('Throws NullValueNotAllowed when array contains null')]
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
    }

    #[TestDox('Throws when null is the first value')]
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
    }

    #[TestDox('Throws when null is the last value')]
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
    }

    #[TestDox('Throws when array has multiple null values')]
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
    }

    #[TestDox('Exception message includes the collection type')]
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
