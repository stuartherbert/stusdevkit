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

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\DateTimeKit\When;
use StusDevKit\ValidationKit\Coercions\CoerceToWhen;

#[TestDox('CoerceToWhen')]
class CoerceToWhenTest extends TestCase
{
    // ================================================================
    //
    // ISO 8601 String Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerces ISO 8601 (ATOM) string to When')]
    public function test_coerces_atom_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen converts an
        // ISO 8601 ATOM-format string to a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $input = '2026-01-15T10:30:00+00:00';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            When::class,
            $actualResult,
        );
        $this->assertSame(
            '2026-01-15T10:30:00+00:00',
            $actualResult->format(DateTimeInterface::ATOM),
        );
    }

    // ================================================================
    //
    // Lenient String Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerces lenient date string to When')]
    public function test_coerces_lenient_date_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen falls back to
        // lenient parsing for non-ATOM date strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $input = '2026-01-15';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            When::class,
            $actualResult,
        );
        $this->assertSame(
            '2026-01-15',
            $actualResult->format('Y-m-d'),
        );
    }

    // ================================================================
    //
    // Timestamp Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerces integer timestamp to When')]
    public function test_coerces_integer_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen converts an
        // integer Unix timestamp to a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $timestamp = 1700000000;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            When::class,
            $actualResult,
        );
        $this->assertSame(
            $timestamp,
            $actualResult->getTimestamp(),
        );
    }

    // ================================================================
    //
    // DateTimeImmutable Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerces DateTimeImmutable to When')]
    public function test_coerces_datetimeimmutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen converts a
        // DateTimeImmutable instance to a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $input = new DateTimeImmutable('2026-01-15T10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            When::class,
            $actualResult,
        );
        $this->assertSame(
            '2026-01-15T10:30:00+00:00',
            $actualResult->format(DateTimeInterface::ATOM),
        );
    }

    // ================================================================
    //
    // When Pass-Through
    //
    // ----------------------------------------------------------------

    #[TestDox('returns an existing When instance')]
    public function test_returns_existing_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen passes through
        // an existing When instance, returning the same object

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $input = When::from('2026-01-15T10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            When::class,
            $actualResult,
        );
        $this->assertSame($input, $actualResult);
    }

    // ================================================================
    //
    // Non-Coercible Values
    //
    // ----------------------------------------------------------------

    #[TestDox('returns unparseable string unchanged')]
    public function test_returns_unparseable_string_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen returns a string
        // unchanged when it cannot be parsed as a date

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $input = 'not-a-date';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('not-a-date', $actualResult);
    }

    #[TestDox('returns null unchanged')]
    public function test_returns_null_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen returns null
        // unchanged

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);
    }

    #[TestDox('returns float unchanged')]
    public function test_returns_float_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen returns a float
        // unchanged (only int timestamps are coerced)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce(3.14);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14, $actualResult);
    }

    #[TestDox('returns array unchanged')]
    public function test_returns_array_unchanged(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToWhen returns an array
        // unchanged

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToWhen();
        $input = ['2026-01-15'];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($input, $actualResult);
    }
}
