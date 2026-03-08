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
use StusDevKit\DateTimeKit\Formatters\WhenDatabaseFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenFilesystemFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenHttpFormatter;

#[TestDox('WhenFormatter')]
class WhenFormatterTest extends TestCase
{
    #[TestDox('->filesystem() returns a WhenFilesystemFormatter')]
    public function test_filesystem_returns_filesystem_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that filesystem() returns a
        // WhenFilesystemFormatter instance

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->filesystem();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenFilesystemFormatter::class, $result);
    }

    #[TestDox('->database() returns a WhenDatabaseFormatter')]
    public function test_database_returns_database_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that database() returns a
        // WhenDatabaseFormatter instance

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->database();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenDatabaseFormatter::class, $result);
    }

    #[TestDox('->http() returns a WhenHttpFormatter')]
    public function test_http_returns_http_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that http() returns a
        // WhenHttpFormatter instance

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->http();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenHttpFormatter::class, $result);
    }
}
