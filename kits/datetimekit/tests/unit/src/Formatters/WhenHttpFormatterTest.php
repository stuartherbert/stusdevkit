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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\Formatters\WhenHttpFormatter;
use StusDevKit\DateTimeKit\When;

#[TestDox(WhenHttpFormatter::class)]
class WhenHttpFormatterTest extends TestCase
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
        // import the formatter by FQN.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit\\Formatters';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(WhenHttpFormatter::class))
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

        // WhenHttpFormatter is a concrete class - not a trait,
        // interface, or enum.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenHttpFormatter::class);

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

        $reflection = new ReflectionClass(WhenHttpFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->implementsInterface(
            WhenGroupFormatterInterface::class,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only __construct() and rfc9110() as public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared-here public method set is pinned by
        // enumeration. New HTTP header format methods (e.g.
        // cookie dates) would show up here as a named diff.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct', 'rfc9110'];
        $reflection = new ReflectionClass(WhenHttpFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === WhenHttpFormatter::class,
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

        // the constructor is the single entry point for wiring
        // the formatter to its When instance.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenHttpFormatter::class);

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
        // WhenHttpFormatter($when)` directly.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenHttpFormatter::class))
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
        // contract WhenGroupFormatterInterface documents.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['when'];
        $method = (new ReflectionClass(WhenHttpFormatter::class))
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
        $param = (new ReflectionClass(WhenHttpFormatter::class))
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
    // ->rfc9110() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->rfc9110() is declared')]
    public function test_rfc9110_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // rfc9110() is the published format method.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenHttpFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('rfc9110');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->rfc9110() is public')]
    public function test_rfc9110_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // callers outside the class must be able to invoke it.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenHttpFormatter::class))
            ->getMethod('rfc9110');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->rfc9110() is an instance method')]
    public function test_rfc9110_is_instance_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // rfc9110() runs against the When injected into the
        // constructor, so it must be an instance method.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenHttpFormatter::class))
            ->getMethod('rfc9110');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->rfc9110() declares a string return type')]
    public function test_rfc9110_declares_string_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return value goes straight into an HTTP header, so
        // it must be a string; anything wider would force every
        // call site to re-coerce.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $method = (new ReflectionClass(WhenHttpFormatter::class))
            ->getMethod('rfc9110');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->rfc9110() declares no parameters')]
    public function test_rfc9110_declares_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // all inputs are supplied via the constructor, so the
        // format method takes no parameters.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $method = (new ReflectionClass(WhenHttpFormatter::class))
            ->getMethod('rfc9110');

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

    // ================================================================
    //
    // ->rfc9110() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->rfc9110() returns an RFC 9110 / IMF-fixdate HTTP date string')]
    public function test_rfc9110_returns_http_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // RFC 9110 specifies the IMF-fixdate form: "Day, DD Mon
        // YYYY HH:MM:SS GMT". This is the preferred format for
        // Date, Last-Modified, and Expires headers.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+00:00');
        $unit = new WhenHttpFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->rfc9110();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Sun, 15 Jun 2025 10:30:45 GMT', $result);
    }

    #[TestDox('->rfc9110() converts non-UTC times to GMT')]
    public function test_rfc9110_converts_to_gmt(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // RFC 9110 requires HTTP dates to be expressed in GMT /
        // UTC - silently keeping the original offset would emit
        // a non-compliant header. The formatter therefore
        // re-zones the When to GMT before formatting. A naive
        // implementation that skipped the conversion would make
        // this test fail with a wrong hour.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 12:30:45+02:00');
        $unit = new WhenHttpFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->rfc9110();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Sun, 15 Jun 2025 10:30:45 GMT', $result);
    }
}
