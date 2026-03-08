<?php

//
// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
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
namespace StusDevKit\DateTimeKit\Tests\Unit;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use DateMalformedStringException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\DateTimeKit\When;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubGroupFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleTransformer;

#[TestDox('When')]
class WhenTest extends TestCase
{
    // ================================================================
    //
    // Constructors
    //
    // ----------------------------------------------------------------

    #[TestDox('can be instantiated')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create an instance of the When
        // class

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new When();

        // ----------------------------------------------------------------
        // test the results

        // we need to assert *something* in this test
        $this->assertInstanceOf(When::class, $unit);
    }

    // ----------------------------------------------------------------
    // maybeFrom()

    #[TestDox('::maybeFrom() returns null when given null')]
    public function test_maybeFrom_returns_null_when_given_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFrom() returns null when given null

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $result = When::maybeFrom(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    #[TestDox('::maybeFrom() creates a When from a string')]
    public function test_maybeFrom_returns_when_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFrom() creates a When from a string

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $result = When::maybeFrom('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
    }

    #[TestDox('::maybeFrom() creates a When from a UNIX timestamp')]
    public function test_maybeFrom_returns_when_from_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFrom() creates a When from a
        // UNIX timestamp

        // ----------------------------------------------------------------
        // setup your test

        $timestamp = 1718451000;

        // ----------------------------------------------------------------
        // perform the change

        $result = When::maybeFrom($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame($timestamp, $result->getTimestamp());
    }

    #[TestDox('::maybeFrom() returns the same When instance without cloning')]
    public function test_maybeFrom_returns_same_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeFrom() returns the same When object
        // if given a When instance (no clone)

        // ----------------------------------------------------------------
        // setup your test

        $original = new When('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = When::maybeFrom($original);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($original, $result);
    }

    // ----------------------------------------------------------------
    // from()

    #[TestDox('::from() returns the same When instance without cloning')]
    public function test_from_returns_same_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that from() returns the same When object
        // when passed a When instance

        // ----------------------------------------------------------------
        // setup your test

        $original = new When('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = When::from($original);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($original, $result);
    }

    #[TestDox('::from() creates a When from a DateTimeInterface')]
    public function test_from_creates_when_from_datetime_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that from() creates a When from a
        // DateTimeInterface object

        // ----------------------------------------------------------------
        // setup your test

        $input = new DateTimeImmutable('2025-06-15 10:30:05+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = When::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(5, $result->getSeconds());
    }

    #[TestDox('::from() creates a When from a mutable DateTime')]
    public function test_from_creates_when_from_mutable_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that from() creates a When from a mutable
        // DateTime object

        // ----------------------------------------------------------------
        // setup your test

        $input = new DateTime('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = When::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(0, $result->getSeconds());
    }

    #[TestDox('::from() creates a When from a UNIX timestamp')]
    public function test_from_creates_when_from_unix_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that from() creates a When from a UNIX
        // timestamp (integer)

        // ----------------------------------------------------------------
        // setup your test

        $timestamp = 1718451000;

        // ----------------------------------------------------------------
        // perform the change

        $result = When::from($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame($timestamp, $result->getTimestamp());
    }

    #[TestDox('::from() creates a When from a date/time string')]
    public function test_from_creates_when_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that from() creates a When from a date/time
        // string

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $result = When::from('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
    }

    // ----------------------------------------------------------------
    // fromDateTimeInterface()

    #[TestDox('::fromDateTimeInterface() creates a When from a DateTimeInterface')]
    public function test_fromDateTimeInterface_creates_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromDateTimeInterface() creates a When
        // from a DateTimeInterface

        // ----------------------------------------------------------------
        // setup your test

        $input = new DateTimeImmutable('2025-06-15 10:30:45+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = When::fromDateTimeInterface($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('::fromDateTimeInterface() preserves numeric timezone offsets')]
    public function test_fromDateTimeInterface_preserves_numeric_offset(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromDateTimeInterface()
        // correctly handles numeric timezone offsets such
        // as those commonly found in database fields
        // (e.g. '2025-06-15 10:30:45+05:30')

        // ----------------------------------------------------------------
        // setup your test

        $input = new DateTimeImmutable('2025-06-15 10:30:45+05:30');

        // ----------------------------------------------------------------
        // perform the change

        $result = When::fromDateTimeInterface($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
        $this->assertSame('+05:30', $result->getTimezone()->getName());
    }

    // ----------------------------------------------------------------
    // fromRealtime()

    #[TestDox('::fromRealtime() creates a When from a microtime float')]
    public function test_fromRealtime_creates_when_from_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromRealtime() creates a When from a
        // microtime float

        // ----------------------------------------------------------------
        // setup your test

        $input = 1718451000.123456;

        // ----------------------------------------------------------------
        // perform the change

        $result = When::fromRealtime($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertEqualsWithDelta($input, $result->asMicrotime(), 0.001);
    }

    #[TestDox('::fromRealtime() uses the current time when given null')]
    public function test_fromRealtime_uses_current_time_when_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromRealtime() uses the current time
        // when given null

        // ----------------------------------------------------------------
        // setup your test

        $before = microtime(true);

        // ----------------------------------------------------------------
        // perform the change

        $result = When::fromRealtime();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertEqualsWithDelta(
            $before,
            $result->asMicrotime(),
            1.0,
        );
    }

    // ----------------------------------------------------------------
    // fromUnixTimestamp()

    #[TestDox('::fromUnixTimestamp() creates a When from a UNIX timestamp')]
    public function test_fromUnixTimestamp_creates_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that fromUnixTimestamp() creates a When from
        // a UNIX timestamp

        // ----------------------------------------------------------------
        // setup your test

        $timestamp = 1718451000;

        // ----------------------------------------------------------------
        // perform the change

        $result = When::fromUnixTimestamp($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame($timestamp, $result->getTimestamp());
    }

    // ================================================================
    //
    // Type conversion
    //
    // ----------------------------------------------------------------

    #[TestDox('->asFormat() returns a WhenFormatter')]
    public function test_asFormat_returns_when_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asFormat() returns a WhenFormatter
        // instance for domain-specific formatting

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->asFormat();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenFormatter::class, $result);
    }

    // ----------------------------------------------------------------
    // formatWith()

    #[TestDox('->formatWith() returns an instance of the given formatter class')]
    public function test_formatWith_returns_formatter_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatWith() instantiates the given
        // formatter class and returns the typed instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->formatWith(StubGroupFormatter::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(StubGroupFormatter::class, $result);
    }

    #[TestDox('->formatWith() formatter methods return correct values')]
    public function test_formatWith_formatter_methods_return_correct_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the formatter returned by formatWith()
        // has access to the When instance and produces correct output

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $date = $unit->formatWith(StubGroupFormatter::class)->date();
        $time = $unit->formatWith(StubGroupFormatter::class)->time();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15', $date);
        $this->assertSame('10:30:45', $time);
    }

    #[TestDox('->formatWith() throws if the class does not implement WhenGroupFormatterInterface')]
    public function test_formatWith_throws_for_invalid_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatWith() throws an
        // InvalidArgumentException when given a class that does
        // not implement WhenGroupFormatterInterface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidArgumentException::class);
        /** @phpstan-ignore argument.type, argument.templateType */
        $_ = $unit->formatWith(\stdClass::class);
    }

    // ----------------------------------------------------------------
    // formatUsing()

    #[TestDox('->formatUsing() returns the formatted string from the given formatter')]
    public function test_formatUsing_returns_formatted_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatUsing() passes the When instance
        // to the formatter and returns the resulting string

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');
        $formatter = new StubSingleFormatter();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->formatUsing($formatter);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15 10:30:45', $result);
    }

    // ----------------------------------------------------------------
    // transformUsing()

    #[TestDox('->transformUsing() returns the transformed value from the given transformer')]
    public function test_transformUsing_returns_transformed_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that transformUsing() passes the When
        // instance to the transformer and returns the result,
        // which can be any type (not just string)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');
        $transformer = new StubSingleTransformer();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->transformUsing($transformer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['year' => 2025, 'month' => 6, 'day' => 15],
            $result,
        );
    }

    #[TestDox('->asMicrotime() returns a float representation')]
    public function test_asMicrotime_returns_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asMicrotime() returns a float
        // representation of the datetime

        // ----------------------------------------------------------------
        // setup your test

        $input = 1718451000.123456;
        $unit = When::fromRealtime($input);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->asMicrotime();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEqualsWithDelta($input, $result, 0.001);
    }

    #[TestDox('->asUnixTimestamp() returns the UNIX timestamp as an integer')]
    public function test_asUnixTimestamp_returns_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asUnixTimestamp() returns the UNIX
        // timestamp as an integer

        // ----------------------------------------------------------------
        // setup your test

        $timestamp = 1718451000;
        $unit = When::fromUnixTimestamp($timestamp);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->asUnixTimestamp();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($timestamp, $result);
    }


    // ================================================================
    //
    // Extractors
    //
    // ----------------------------------------------------------------

    #[TestDox('->getYear() returns the year component')]
    public function test_getYear_returns_year(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getYear() returns the year component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getYear();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2025, $result);
    }

    #[TestDox('->getMonthOfYear() returns the month component')]
    public function test_getMonthOfYear_returns_month(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getMonthOfYear() returns the month
        // component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getMonthOfYear();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(6, $result);
    }

    #[TestDox('->getDayOfMonth() returns the day component')]
    public function test_getDayOfMonth_returns_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getDayOfMonth() returns the day
        // component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getDayOfMonth();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(15, $result);
    }

    #[TestDox('->getHour() returns the hour component')]
    public function test_getHour_returns_hour(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getHour() returns the hour component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getHour();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $result);
    }

    #[TestDox('->getMinutes() returns the minutes component')]
    public function test_getMinutes_returns_minutes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getMinutes() returns the minutes
        // component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getMinutes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(30, $result);
    }

    #[TestDox('->getSeconds() returns the seconds component')]
    public function test_getSeconds_returns_seconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getSeconds() returns the seconds
        // component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getSeconds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(45, $result);
    }

    #[TestDox('->getMicroseconds() returns the microseconds component')]
    public function test_getMicroseconds_returns_microseconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getMicroseconds() returns the
        // microseconds component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45.123456');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->getMicroseconds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(123456, $result);
    }

    #[TestDox('->get*() extractors handle zero values correctly')]
    public function test_getters_handle_zero_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the extractors handle zero values
        // correctly (e.g. midnight)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-01-01 00:00:00');

        // ----------------------------------------------------------------
        // perform the change



        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2025, $unit->getYear());
        $this->assertSame(1, $unit->getMonthOfYear());
        $this->assertSame(1, $unit->getDayOfMonth());
        $this->assertSame(0, $unit->getHour());
        $this->assertSame(0, $unit->getMinutes());
        $this->assertSame(0, $unit->getSeconds());
        $this->assertSame(0, $unit->getMicroseconds());
    }

    // ================================================================
    //
    // Modifiers - Date manipulation
    //
    // ----------------------------------------------------------------

    #[TestDox('->withDateFrom() copies the date from the input and preserves the time')]
    public function test_withDateFrom_copies_date_from_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDateFrom() copies the year, month
        // and day from the given DateTimeInterface, while preserving
        // the time from the original object

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');
        $source = new DateTimeImmutable('2030-03-20 08:00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withDateFrom($source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2030, $result->getYear());
        $this->assertSame(3, $result->getMonthOfYear());
        $this->assertSame(20, $result->getDayOfMonth());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('->withDate() replaces the specified date components')]
    public function test_withDate_replaces_year_month_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDate() replaces only the specified
        // date components

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withDate(year: 2030, month: 3, day: 20);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2030, $result->getYear());
        $this->assertSame(3, $result->getMonthOfYear());
        $this->assertSame(20, $result->getDayOfMonth());
        $this->assertSame(10, $result->getHour());
    }

    #[TestDox('->withDate() keeps original values when parameters are null')]
    public function test_withDate_keeps_original_when_params_are_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDate() keeps the original values
        // when parameters are null

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withDate();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
    }

    #[TestDox('->withYear() replaces only the year')]
    public function test_withYear_replaces_only_year(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withYear() replaces only the year

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withYear(2030);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2030, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
    }

    #[TestDox('->withMonthOfYear() replaces only the month')]
    public function test_withMonthOfYear_replaces_only_month(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withMonthOfYear() replaces only the month

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withMonthOfYear(12);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2025, $result->getYear());
        $this->assertSame(12, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
    }

    #[TestDox('->withDayOfMonth() replaces only the day')]
    public function test_withDayOfMonth_replaces_only_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDayOfMonth() replaces only the day

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withDayOfMonth(28);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(28, $result->getDayOfMonth());
    }

    #[TestDox('->withDayOfMonth() clamps to the last day of the month')]
    public function test_withDayOfMonth_clamps_to_last_day_of_month(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDayOfMonth() clamps the day to the
        // last day of the month (e.g. Feb 31 becomes Feb 28)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-02-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withDayOfMonth(31);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(28, $result->getDayOfMonth());
    }

    #[TestDox('->withDayOfMonth() clamps to 29 in February of a leap year')]
    public function test_withDayOfMonth_clamps_to_29_in_leap_year(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDayOfMonth() clamps the day to 29
        // in February of a leap year

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2024-02-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withDayOfMonth(31);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(29, $result->getDayOfMonth());
    }

    // ================================================================
    //
    // Modifiers - Time manipulation
    //
    // ----------------------------------------------------------------

    #[TestDox('->withTimeFrom() copies the time from the input and preserves the date')]
    public function test_withTimeFrom_copies_time_from_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withTimeFrom() copies the time from
        // the given DateTimeInterface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');
        $source = new DateTimeImmutable('2030-03-20 08:15:30');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withTimeFrom($source);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(15, $result->getDayOfMonth());
        $this->assertSame(8, $result->getHour());
        $this->assertSame(15, $result->getMinutes());
        $this->assertSame(30, $result->getSeconds());
    }

    #[TestDox('->withTime() replaces the specified time components')]
    public function test_withTime_replaces_time_components(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withTime() replaces only the specified
        // time components

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withTime(hour: 8, minutes: 15, seconds: 30, microseconds: 500000);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(8, $result->getHour());
        $this->assertSame(15, $result->getMinutes());
        $this->assertSame(30, $result->getSeconds());
        $this->assertSame(500000, $result->getMicroseconds());
        $this->assertSame(2025, $result->getYear());
    }

    #[TestDox('->withTime() keeps original values when parameters are null')]
    public function test_withTime_keeps_original_when_params_are_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withTime() keeps the original values
        // when parameters are null

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withTime();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('->withHour() replaces only the hour')]
    public function test_withHour_replaces_only_hour(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withHour() replaces only the hour

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withHour(23);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(23, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('->withMinutes() replaces only the minutes')]
    public function test_withMinutes_replaces_only_minutes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withMinutes() replaces only the minutes

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withMinutes(59);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $result->getHour());
        $this->assertSame(59, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('->withSeconds() replaces only the seconds')]
    public function test_withSeconds_replaces_only_seconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withSeconds() replaces only the seconds

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withSeconds(59);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(59, $result->getSeconds());
    }

    #[TestDox('->withMicroseconds() replaces only the microseconds')]
    public function test_withMicroseconds_replaces_only_microseconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withMicroseconds() replaces the
        // microseconds component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->withMicroseconds(500000);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(500000, $result->getMicroseconds());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    // ================================================================
    //
    // Modifier support
    //
    // ----------------------------------------------------------------

    #[TestDox('->modifyDayOfMonth() changes the day using a relative modifier')]
    public function test_modifyDayOfMonth_changes_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyDayOfMonth() can change the day
        // using a relative modifier

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->modifyDayOfMonth('first day');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $result->getDayOfMonth());
        $this->assertSame(6, $result->getMonthOfYear());
        $this->assertSame(2025, $result->getYear());
        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('->modifyDayOfMonth() can get the last day of the month')]
    public function test_modifyDayOfMonth_can_get_last_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyDayOfMonth() can get the last day
        // of the month

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->modifyDayOfMonth('last day');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(30, $result->getDayOfMonth());
        $this->assertSame(6, $result->getMonthOfYear());
    }

    #[TestDox('->modifyDayOfMonth() preserves the time component')]
    public function test_modifyDayOfMonth_preserves_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyDayOfMonth() preserves the time
        // component

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->modifyDayOfMonth('last day');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(45, $result->getSeconds());
    }

    #[TestDox('->modifyDayOfMonth() throws if the modifier changes the month or year')]
    public function test_modifyDayOfMonth_throws_if_month_changes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyDayOfMonth() throws an exception
        // if the modifier changes the month or year

        // ----------------------------------------------------------------
        // setup your test

        // February 2025 only has 4 Mondays, so "fifth monday" will push
        // into March
        $unit = new When('2025-02-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidArgumentException::class);
        $_ = $unit->modifyDayOfMonth('fifth monday');
    }

    #[TestDox('->modifyTime() changes the time using a relative modifier')]
    public function test_modifyTime_changes_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyTime() can change the time using
        // a relative modifier

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->modifyTime('+1 hour');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(11, $result->getHour());
        $this->assertSame(30, $result->getMinutes());
        $this->assertSame(15, $result->getDayOfMonth());
    }

    #[TestDox('->modifyTime() throws if the modifier changes the date')]
    public function test_modifyTime_throws_if_date_changes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyTime() throws an exception if
        // the modifier changes the date

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidArgumentException::class);
        $_ = $unit->modifyTime('+1 day');
    }

    // ================================================================
    //
    // Wrappers around parent class methods
    //
    // ----------------------------------------------------------------

    #[TestDox('->add() returns a When instance')]
    public function test_add_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that add() returns a When instance (not a
        // plain DateTimeImmutable)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');
        $interval = new DateInterval('P1D');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->add($interval);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(16, $result->getDayOfMonth());
    }

    #[TestDox('->modify() returns a When instance')]
    public function test_modify_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modify() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->modify('+1 day');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(16, $result->getDayOfMonth());
    }

    #[TestDox('->modify() throws on an invalid modifier string')]
    public function test_modify_throws_on_invalid_modifier(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modify() throws an exception when given
        // an invalid modifier string

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(DateMalformedStringException::class);
        $_ = $unit->modify('not a valid modifier');
    }

    #[TestDox('->setDate() returns a When instance')]
    public function test_setDate_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setDate() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->setDate(2030, 3, 20);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2030, $result->getYear());
        $this->assertSame(3, $result->getMonthOfYear());
        $this->assertSame(20, $result->getDayOfMonth());
    }

    #[TestDox('->setISODate() returns a When instance')]
    public function test_setISODate_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setISODate() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->setISODate(2025, 1, 1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
    }

    #[TestDox('->setTime() returns a When instance')]
    public function test_setTime_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTime() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->setTime(8, 15, 30);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(8, $result->getHour());
        $this->assertSame(15, $result->getMinutes());
        $this->assertSame(30, $result->getSeconds());
    }

    #[TestDox('->setTimestamp() returns a When instance')]
    public function test_setTimestamp_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTimestamp() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');
        $timestamp = 1718451000;

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->setTimestamp($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame($timestamp, $result->getTimestamp());
    }

    #[TestDox('->setTimezone() returns a When instance')]
    public function test_setTimezone_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTimezone() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');
        $tz = new DateTimeZone('America/New_York');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->setTimezone($tz);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame('America/New_York', $result->getTimezone()->getName());
    }

    #[TestDox('->sub() returns a When instance')]
    public function test_sub_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that sub() returns a When instance (not a
        // plain DateTimeImmutable)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45');
        $interval = new DateInterval('P1D');

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->sub($interval);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(14, $result->getDayOfMonth());
    }
}
