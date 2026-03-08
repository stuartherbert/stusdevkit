<?php

//
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
//

declare(strict_types=1);
namespace StusDevKit\DateTimeKit\Tests\Unit;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Now;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubGroupFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleTransformer;
use StusDevKit\DateTimeKit\When;

#[TestDox('Now')]
class NowTest extends TestCase
{
    // ================================================================
    //
    // Setup / Teardown
    //
    // ----------------------------------------------------------------

    protected function setUp(): void
    {
        // ensure a clean state for every test
        Now::init();
    }

    // ================================================================
    //
    // Constructors
    //
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    // init()

    #[TestDox('::init() sets Now to the current datetime')]
    public function test_init_sets_now_to_current_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that init() sets Now to a datetime
        // close to the actual current time

        // ----------------------------------------------------------------
        // setup your test

        $before = new When('now');

        // ----------------------------------------------------------------
        // perform the change

        Now::init();

        // ----------------------------------------------------------------
        // test the results

        $after = new When('now');
        $now = Now::now();

        $this->assertGreaterThanOrEqual(
            $before->asUnixTimestamp(),
            $now->asUnixTimestamp(),
        );
        $this->assertLessThanOrEqual(
            $after->asUnixTimestamp(),
            $now->asUnixTimestamp(),
        );
    }

    // ----------------------------------------------------------------
    // reset()

    #[TestDox('::reset() updates Now to the current datetime')]
    public function test_reset_updates_now_to_current_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that reset() updates Now to the
        // current datetime, replacing whatever was set before

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2020-01-01 00:00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::reset();

        // ----------------------------------------------------------------
        // test the results

        $now = Now::now();

        // the year should no longer be 2020
        $this->assertNotSame(2020, $now->getYear());
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    // now()

    #[TestDox('::now() returns a When instance')]
    public function test_now_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that now() returns a When instance

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::now();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
    }

    #[TestDox('::now() returns the same value on repeated calls')]
    public function test_now_returns_same_value_on_repeated_calls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that repeated calls to now()
        // return the exact same When object

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result1 = Now::now();
        $result2 = Now::now();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($result1, $result2);
    }

    // ----------------------------------------------------------------
    // asFormat()

    #[TestDox('::asFormat() returns a WhenFormatter')]
    public function test_asFormat_returns_when_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asFormat() returns a WhenFormatter
        // instance that delegates to the underlying When object

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asFormat();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenFormatter::class, $result);
    }

    #[TestDox('::asFormat() uses the cached Now datetime')]
    public function test_asFormat_uses_cached_now_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asFormat() formats the cached
        // Now datetime, not a new datetime

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asFormat()->database()->postgres();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            '2025-06-15T10:30:00+00:00',
            $result,
        );
    }

    // ----------------------------------------------------------------
    // formatWith()

    #[TestDox('::formatWith() returns an instance of the given formatter class')]
    public function test_formatWith_returns_formatter_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatWith() instantiates the given
        // formatter class using the cached Now datetime

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::formatWith(StubGroupFormatter::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(StubGroupFormatter::class, $result);
    }

    #[TestDox('::formatWith() uses the cached Now datetime')]
    public function test_formatWith_uses_cached_now_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatWith() passes the cached Now
        // datetime to the formatter

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::formatWith(StubGroupFormatter::class)->date();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15', $result);
    }

    // ----------------------------------------------------------------
    // formatUsing()

    #[TestDox('::formatUsing() returns the formatted string from the given formatter')]
    public function test_formatUsing_returns_formatted_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that formatUsing() passes the cached Now
        // datetime to the formatter and returns the resulting string

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');
        $formatter = new StubSingleFormatter();

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::formatUsing($formatter);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15 10:30:00', $result);
    }

    // ----------------------------------------------------------------
    // transformUsing()

    #[TestDox('::transformUsing() returns the transformed value from the given transformer')]
    public function test_transformUsing_returns_transformed_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that transformUsing() passes the cached
        // Now datetime to the transformer and returns the result,
        // which can be any type (not just string)

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');
        $transformer = new StubSingleTransformer();

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::transformUsing($transformer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['year' => 2025, 'month' => 6, 'day' => 15],
            $result,
        );
    }

    // ----------------------------------------------------------------
    // asDateTimeImmutable()

    #[TestDox('::asDateTimeImmutable() returns a DateTimeImmutable')]
    public function test_asDateTimeImmutable_returns_datetime_immutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asDateTimeImmutable() returns a
        // DateTimeImmutable instance (not a When)

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asDateTimeImmutable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DateTimeImmutable::class, $result);
        $this->assertNotInstanceOf(When::class, $result);
    }

    #[TestDox('::asDateTimeImmutable() returns the same datetime value as Now')]
    public function test_asDateTimeImmutable_returns_same_datetime_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asDateTimeImmutable() returns a
        // DateTimeImmutable with the same datetime value as Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asDateTimeImmutable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            Now::now()->format('Y-m-d H:i:s.u'),
            $result->format('Y-m-d H:i:s.u'),
        );
    }

    #[TestDox('::asDateTimeImmutable() returns a new instance on each call')]
    public function test_asDateTimeImmutable_returns_new_instance_on_each_call(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each call to asDateTimeImmutable()
        // returns a new DateTimeImmutable instance

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result1 = Now::asDateTimeImmutable();
        $result2 = Now::asDateTimeImmutable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($result1, $result2);
        $this->assertEquals($result1, $result2);
    }

    // ----------------------------------------------------------------
    // asUnixTimestamp()

    #[TestDox('::asUnixTimestamp() returns a UNIX timestamp')]
    public function test_asUnixTimestamp_returns_unix_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that asUnixTimestamp() returns the
        // expected UNIX timestamp integer

        // ----------------------------------------------------------------
        // setup your test

        $expectedTimestamp = 1718451000;
        Now::setTestClock($expectedTimestamp);

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asUnixTimestamp();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedTimestamp, $result);
    }

    #[TestDox('::asUnixTimestamp() returns the same value on repeated calls')]
    public function test_asUnixTimestamp_returns_same_value_on_repeated_calls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that repeated calls to asUnixTimestamp()
        // return the exact same value

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock(1718451000);

        // ----------------------------------------------------------------
        // perform the change

        $result1 = Now::asUnixTimestamp();
        $result2 = Now::asUnixTimestamp();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($result1, $result2);
    }



    // ----------------------------------------------------------------
    // or()

    #[TestDox('::or() returns Now when given null')]
    public function test_or_returns_now_when_given_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that or() returns the current Now
        // value when given null

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');
        $expectedWhen = Now::now();

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::or(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedWhen, $result);
    }

    #[TestDox('::or() returns a When from a string input')]
    public function test_or_returns_when_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that or() creates a When from a
        // string input rather than returning Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::or('2024-01-01 00:00:00');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2024, $result->getYear());
        $this->assertSame(1, $result->getMonthOfYear());
        $this->assertSame(1, $result->getDayOfMonth());
    }

    #[TestDox('::or() returns a When from a UNIX timestamp input')]
    public function test_or_returns_when_from_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that or() creates a When from a
        // UNIX timestamp input rather than returning Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');
        $timestamp = 1718451000;

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::or($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame($timestamp, $result->asUnixTimestamp());
    }

    #[TestDox('::or() returns a When from a DateTimeInterface input')]
    public function test_or_returns_when_from_datetime_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that or() creates a When from a
        // DateTimeInterface input rather than returning Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');
        $input = new DateTimeImmutable('2024-03-20 14:00:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::or($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $result);
        $this->assertSame(2024, $result->getYear());
        $this->assertSame(3, $result->getMonthOfYear());
        $this->assertSame(20, $result->getDayOfMonth());
    }

    #[TestDox('::or() returns the same value on repeated calls with null')]
    public function test_or_returns_same_value_on_repeated_null_calls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that repeated calls to or() with null
        // return the exact same When object

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result1 = Now::or(null);
        $result2 = Now::or(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($result1, $result2);
    }

    // ================================================================
    //
    // Test clock support
    //
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    // setTestClock()

    #[TestDox('::setTestClock() sets Now from a string')]
    public function test_setTestClock_sets_now_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTestClock() sets the value
        // of Now from a date/time string

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        Now::setTestClock('2024-03-20 14:30:00+00:00');

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2024, $when->getYear());
        $this->assertSame(3, $when->getMonthOfYear());
        $this->assertSame(20, $when->getDayOfMonth());
        $this->assertSame(14, $when->getHour());
        $this->assertSame(30, $when->getMinutes());
        $this->assertSame(0, $when->getSeconds());
    }

    #[TestDox('::setTestClock() sets Now from a UNIX timestamp')]
    public function test_setTestClock_sets_now_from_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTestClock() sets the value
        // of Now from a UNIX timestamp

        // ----------------------------------------------------------------
        // setup your test

        $timestamp = 1718451000;

        // ----------------------------------------------------------------
        // perform the change

        Now::setTestClock($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($timestamp, Now::asUnixTimestamp());
    }

    #[TestDox('::setTestClock() sets Now from a DateTimeInterface')]
    public function test_setTestClock_sets_now_from_datetime_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTestClock() sets the value
        // of Now from a DateTimeInterface object

        // ----------------------------------------------------------------
        // setup your test

        $input = new DateTimeImmutable('2024-03-20 14:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::setTestClock($input);

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2024, $when->getYear());
        $this->assertSame(3, $when->getMonthOfYear());
        $this->assertSame(20, $when->getDayOfMonth());
    }

    #[TestDox('::setTestClock() sets Now from a When instance')]
    public function test_setTestClock_sets_now_from_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that setTestClock() sets the value
        // of Now from a When instance

        // ----------------------------------------------------------------
        // setup your test

        $input = new When('2024-03-20 14:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::setTestClock($input);

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2024, $when->getYear());
        $this->assertSame(3, $when->getMonthOfYear());
        $this->assertSame(20, $when->getDayOfMonth());
    }

    // ----------------------------------------------------------------
    // modifyTestClock()

    #[TestDox('::modifyTestClock() modifies Now using a relative modifier')]
    public function test_modifyTestClock_modifies_now(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyTestClock() applies a PHP
        // relative datetime modifier to the current value of Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::modifyTestClock('+1 day');

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2025, $when->getYear());
        $this->assertSame(6, $when->getMonthOfYear());
        $this->assertSame(16, $when->getDayOfMonth());
        $this->assertSame(10, $when->getHour());
        $this->assertSame(30, $when->getMinutes());
    }

    #[TestDox('::modifyTestClock() can subtract time')]
    public function test_modifyTestClock_can_subtract_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that modifyTestClock() can use a
        // negative modifier to move time backwards

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::modifyTestClock('-3 hours');

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2025, $when->getYear());
        $this->assertSame(6, $when->getMonthOfYear());
        $this->assertSame(15, $when->getDayOfMonth());
        $this->assertSame(7, $when->getHour());
        $this->assertSame(30, $when->getMinutes());
    }

    // ----------------------------------------------------------------
    // addToTestClock()

    #[TestDox('::addToTestClock() adds a DateInterval to Now')]
    public function test_addToTestClock_adds_dateinterval(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that addToTestClock() adds a
        // DateInterval object to the current value of Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');
        $interval = new DateInterval('P1D');

        // ----------------------------------------------------------------
        // perform the change

        Now::addToTestClock($interval);

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2025, $when->getYear());
        $this->assertSame(6, $when->getMonthOfYear());
        $this->assertSame(16, $when->getDayOfMonth());
        $this->assertSame(10, $when->getHour());
        $this->assertSame(30, $when->getMinutes());
    }

    #[TestDox('::addToTestClock() adds an interval string to Now')]
    public function test_addToTestClock_adds_interval_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that addToTestClock() accepts a string
        // in DateInterval format and adds it to Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::addToTestClock('PT2H30M');

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2025, $when->getYear());
        $this->assertSame(6, $when->getMonthOfYear());
        $this->assertSame(15, $when->getDayOfMonth());
        $this->assertSame(13, $when->getHour());
        $this->assertSame(0, $when->getMinutes());
    }

    // ----------------------------------------------------------------
    // subFromTestClock()

    #[TestDox('::subFromTestClock() subtracts a DateInterval from Now')]
    public function test_subFromTestClock_subtracts_dateinterval(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that subFromTestClock() subtracts a
        // DateInterval object from the current value of Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');
        $interval = new DateInterval('P1D');

        // ----------------------------------------------------------------
        // perform the change

        Now::subFromTestClock($interval);

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2025, $when->getYear());
        $this->assertSame(6, $when->getMonthOfYear());
        $this->assertSame(14, $when->getDayOfMonth());
        $this->assertSame(10, $when->getHour());
        $this->assertSame(30, $when->getMinutes());
    }

    #[TestDox('::subFromTestClock() subtracts an interval string from Now')]
    public function test_subFromTestClock_subtracts_interval_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that subFromTestClock() accepts a string
        // in DateInterval format and subtracts it from Now

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::subFromTestClock('PT2H30M');

        // ----------------------------------------------------------------
        // test the results

        $when = Now::now();
        $this->assertSame(2025, $when->getYear());
        $this->assertSame(6, $when->getMonthOfYear());
        $this->assertSame(15, $when->getDayOfMonth());
        $this->assertSame(8, $when->getHour());
        $this->assertSame(0, $when->getMinutes());
    }
}
