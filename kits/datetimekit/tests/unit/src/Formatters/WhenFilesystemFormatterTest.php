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
namespace StusDevKit\DateTimeKit\Tests\Unit\Formatters;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\DateTimeKit\When;
use StusDevKit\DateTimeKit\Formatters\WhenFilesystemFormatter;

#[TestDox('WhenFilesystemFormatter')]
class WhenFilesystemFormatterTest extends TestCase
{
    #[TestDox('->yearMonth() returns YYYY-MM format')]
    public function test_yearMonth_returns_correct_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that yearMonth() returns the datetime
        // in YYYY-MM format

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->yearMonth();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06', $result);
    }

    #[TestDox('->date() returns YYYY-MM-DD format')]
    public function test_date_returns_correct_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that date() returns the datetime in
        // YYYY-MM-DD format

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->date();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15', $result);
    }

    #[TestDox('->dateTime() returns YYYYMMDD-HHMMSS format')]
    public function test_dateTime_returns_correct_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dateTime() returns the datetime
        // in YYYYMMDD-HHMMSS format

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->dateTime();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('20250615-103045', $result);
    }

    #[TestDox('->dateTimeAndMilliseconds() returns YYYYMMDD-HHMMSS-MS format')]
    public function test_dateTimeAndMilliseconds_returns_correct_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dateTimeAndMilliseconds() returns
        // the datetime in YYYYMMDD-HHMMSS-MS format

        // ----------------------------------------------------------------
        // setup your test

        $when = When::fromRealtime(1718451045.123);
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->dateTimeAndMilliseconds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertMatchesRegularExpression('/^\d{8}-\d{6}-\d{3}$/', $result);
    }
}
