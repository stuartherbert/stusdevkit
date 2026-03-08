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

namespace StusDevKit\CollectionsKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\CollectionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

#[TestDox('NullValueNotAllowed')]
class NullValueNotAllowedTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate with a collection type')]
    public function test_can_instantiate_with_collection_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create a
        // NullValueNotAllowed exception with a collection
        // type string

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NullValueNotAllowedException(
            collectionType: 'ListOfStrings',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(NullValueNotAllowedException::class, $unit);
    }

    #[TestDox('Extends Rfc9457ProblemDetailsException')]
    public function test_extends_rfc9457(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that NullValueNotAllowed is an
        // Rfc9457ProblemDetailsException

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NullValueNotAllowedException(
            collectionType: 'ListOfStrings',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            Rfc9457ProblemDetailsException::class,
            $unit,
        );
    }

    // ================================================================
    //
    // RFC 9457 fields
    //
    // ----------------------------------------------------------------

    #[TestDox('Has correct type URI')]
    public function test_has_correct_type_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception has the expected
        // type URI for documentation linking

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NullValueNotAllowedException(
            collectionType: 'ListOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/errors/null-value-not-allowed',
            $actualResult,
        );
    }

    #[TestDox('Has status 422')]
    public function test_has_status_422(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception uses HTTP status
        // 422 (Unprocessable Content)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NullValueNotAllowedException(
            collectionType: 'ListOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(422, $actualResult);
    }

    #[TestDox('Title includes the collection type')]
    public function test_title_includes_collection_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception title includes
        // the collection type so the caller knows which
        // collection rejected the value

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NullValueNotAllowedException(
            collectionType: 'DictOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'DictOfStrings does not accept null values',
            $actualResult,
        );
    }

    #[TestDox('Detail includes the collection type')]
    public function test_detail_includes_collection_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception detail includes
        // the collection type

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NullValueNotAllowedException(
            collectionType: 'DictOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'DictOfStrings received a null value',
            $actualResult,
        );
    }

    #[TestDox('Exception message matches the title')]
    public function test_exception_message_matches_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the standard exception message
        // matches the RFC 9457 title field

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NullValueNotAllowedException(
            collectionType: 'ListOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'ListOfStrings received a null value',
            $actualResult,
        );
    }
}
