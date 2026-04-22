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

namespace StusDevKit\DateTimeKit\Tests\Unit\Formatters;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DateTimeKit\Formatters\WhenFilesystemFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\When;

#[TestDox(WhenFilesystemFormatter::class)]
class WhenFilesystemFormatterTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\DateTimeKit\\Formatters namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import the formatter by FQN, so moving it is a breaking
        // change.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit\\Formatters';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(WhenFilesystemFormatter::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // WhenFilesystemFormatter is a concrete class - not a trait,
        // interface or enum.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenFilesystemFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('implements WhenGroupFormatterInterface')]
    public function test_implements_when_group_formatter_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // implementing WhenGroupFormatterInterface is how the class
        // becomes eligible for When::formatWith() / Now::formatWith()
        // dispatch.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenFilesystemFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->implementsInterface(
            WhenGroupFormatterInterface::class,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only the expected public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared-here public method set is pinned by
        // enumeration. Each format method is a promise to every
        // caller that wrote e.g. `->filesystem()->date()`. Adding,
        // removing, or renaming any must show up here as a named
        // diff.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            '__construct',
            'yearMonth',
            'date',
            'dateTime',
            'dateTimeAndMilliseconds',
        ];
        $reflection = new ReflectionClass(WhenFilesystemFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === WhenFilesystemFormatter::class,
            ),
        ));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::__construct() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() is declared')]
    public function test_construct_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor is the single entry point for wiring the
        // formatter to its When instance.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenFilesystemFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('__construct');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() is public')]
    public function test_construct_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // callers and When::formatWith() both call `new
        // WhenFilesystemFormatter($when)` directly, so the
        // constructor must be public.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenFilesystemFormatter::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $when as its only parameter')]
    public function test_construct_declares_when_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the single-parameter constructor taking a When is the
        // contract WhenGroupFormatterInterface documents. Adding
        // or renaming parameters would break When::formatWith()
        // which always passes exactly one When argument.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['when'];
        $method = (new ReflectionClass(WhenFilesystemFormatter::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() declares $when as When')]
    public function test_construct_declares_when_as_When(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter type must be When so the formatter has
        // access to the extra When-specific format methods.

        // ----------------------------------------------------------------
        // setup your test

        $expected = When::class;
        $param = (new ReflectionClass(WhenFilesystemFormatter::class))
            ->getMethod('__construct')
            ->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Format method shape (applies to yearMonth / date / dateTime /
    // dateTimeAndMilliseconds)
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideFormatMethodNames(): array
    {
        return [
            'yearMonth'               => ['yearMonth'],
            'date'                    => ['date'],
            'dateTime'                => ['dateTime'],
            'dateTimeAndMilliseconds' => ['dateTimeAndMilliseconds'],
        ];
    }

    #[TestDox('->$methodName() is a public instance method returning string with no parameters')]
    #[DataProvider('provideFormatMethodNames')]
    public function test_format_method_shape(string $methodName): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every format method on this router follows the same
        // shape: public, instance, zero parameters, string return.
        // A single parametrised test pins that contract across the
        // whole family so one-off drift (turning a method static,
        // adding an optional parameter, widening the return) is
        // caught as a named diff.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenFilesystemFormatter::class))
            ->getMethod($methodName);

        // ----------------------------------------------------------------
        // perform the change / test the results

        $this->assertTrue($method->isPublic(), 'is public');
        $this->assertFalse($method->isStatic(), 'is instance method');
        $this->assertSame([], $method->getParameters(), 'takes no parameters');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
    }

    // ================================================================
    //
    // Format method behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->yearMonth() returns year-month in YYYY-MM format')]
    public function test_yearMonth_returns_YYYY_MM(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // YYYY-MM sorts naturally in directory listings and is a
        // natural bucket for monthly rollups (logs, backups, ...).

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

    #[TestDox('->yearMonth() zero-pads single-digit months')]
    public function test_yearMonth_zero_pads_month(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // sort order depends on the leading zero - "2025-01" must
        // come before "2025-10", which only works if January is
        // emitted as "01" rather than "1".

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-01-15 10:30:45');
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->yearMonth();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-01', $result);
    }

    #[TestDox('->date() returns year-month-day in YYYY-MM-DD format')]
    public function test_date_returns_YYYY_MM_DD(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // YYYY-MM-DD is ISO 8601 date notation; sorts naturally
        // and is the most widely recognised "per-day bucket"
        // shape.

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

    #[TestDox('->date() zero-pads single-digit months and days')]
    public function test_date_zero_pads(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // lexical sort order of YYYY-MM-DD requires every
        // component to be zero-padded. "2025-01-05" must sort
        // before "2025-01-15" and "2025-01-05" before "2025-10-01".

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-01-05 10:30:45');
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->date();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-01-05', $result);
    }

    #[TestDox('->dateTime() returns YYYYMMDD-HHMMSS format')]
    public function test_dateTime_returns_compact_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the compact form (no dashes between Y-M-D, no colons
        // between H:M:S) is safe for filenames on every common
        // filesystem - no "colons forbidden on Windows" footgun,
        // and no shell-unfriendly characters that require
        // quoting.

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

    #[TestDox('->dateTime() zero-pads single-digit time components')]
    public function test_dateTime_zero_pads_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // filename sort order depends on fixed-width components.
        // "20250615-010203" must sort before "20250615-110203",
        // so every time part has to be two digits.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 01:02:03');
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->dateTime();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('20250615-010203', $result);
    }

    #[TestDox('->dateTimeAndMilliseconds() returns YYYYMMDD-HHMMSS-MS format')]
    public function test_dateTimeAndMilliseconds_returns_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // adding a millisecond suffix lets callers safely create
        // many files inside the same second (e.g. rapid log
        // rolls) without collision, while still sorting naturally.

        // ----------------------------------------------------------------
        // setup your test

        // build an input with an exact millisecond value so the
        // expected output is a literal string, not something
        // derived at test time
        $when = (new When('2025-06-15 10:30:45'))->withMicroseconds(123000);
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->dateTimeAndMilliseconds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('20250615-103045-123', $result);
    }

    #[TestDox('->dateTimeAndMilliseconds() zero-pads the millisecond component to 3 digits')]
    public function test_dateTimeAndMilliseconds_zero_pads_ms(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // filename sort order depends on the millisecond component
        // being fixed-width. "20250615-103045-005" must sort
        // before "20250615-103045-050".

        // ----------------------------------------------------------------
        // setup your test

        $when = (new When('2025-06-15 10:30:45'))->withMicroseconds(5000);
        $unit = new WhenFilesystemFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->dateTimeAndMilliseconds();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('20250615-103045-005', $result);
    }
}
