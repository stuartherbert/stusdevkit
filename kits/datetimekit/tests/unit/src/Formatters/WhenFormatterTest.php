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
use StusDevKit\DateTimeKit\Formatters\WhenDatabaseFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenFilesystemFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenHttpFormatter;
use StusDevKit\DateTimeKit\When;

#[TestDox(WhenFormatter::class)]
class WhenFormatterTest extends TestCase
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
        // import the router by FQN.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit\\Formatters';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(WhenFormatter::class))
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

        // WhenFormatter is a concrete class - not a trait,
        // interface, or enum.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only __construct(), filesystem(), database() and http() as public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // WhenFormatter's job is to route to domain-specific
        // formatters. Adding a new router entry (e.g. `json()`,
        // `xml()`) would expand the surface area every caller
        // inherits, and must show up as a named diff here.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            '__construct',
            'filesystem',
            'database',
            'http',
        ];
        $reflection = new ReflectionClass(WhenFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === WhenFormatter::class,
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
        // the router to its When instance.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenFormatter::class);

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

        // When::asFormat() instantiates the router directly, so
        // the constructor must be public.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenFormatter::class))
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

        // the single-parameter When constructor mirrors the shape
        // the downstream WhenGroupFormatterInterface implementers
        // use, so the same When flows all the way through.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['when'];
        $method = (new ReflectionClass(WhenFormatter::class))
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

        // the parameter type must be When, not DateTimeImmutable,
        // so the downstream formatters have access to
        // When-specific format methods.

        // ----------------------------------------------------------------
        // setup your test

        $expected = When::class;
        $param = (new ReflectionClass(WhenFormatter::class))
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
    // Router method shape (applies to filesystem / database / http)
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, class-string}>
     */
    public static function provideRouterMethods(): array
    {
        return [
            'filesystem' => ['filesystem', WhenFilesystemFormatter::class],
            'database'   => ['database',   WhenDatabaseFormatter::class],
            'http'       => ['http',       WhenHttpFormatter::class],
        ];
    }

    #[TestDox('->$methodName() is a public instance method returning $expectedReturnType with no parameters')]
    #[DataProvider('provideRouterMethods')]
    public function test_router_method_shape(
        string $methodName,
        string $expectedReturnType,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // every router method on WhenFormatter follows the same
        // shape: public, instance, zero parameters, returns a
        // specific WhenGroupFormatterInterface implementer.
        // Parametrising pins that contract across the whole family.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenFormatter::class))
            ->getMethod($methodName);

        // ----------------------------------------------------------------
        // perform the change / test the results

        $this->assertTrue($method->isPublic(), 'is public');
        $this->assertFalse($method->isStatic(), 'is instance method');
        $this->assertSame([], $method->getParameters(), 'takes no parameters');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame($expectedReturnType, $returnType->getName());
    }

    // ================================================================
    //
    // Router method behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->filesystem() returns a WhenFilesystemFormatter')]
    public function test_filesystem_returns_filesystem_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // filesystem() is the router entry for filename-friendly
        // formats. It must hand back a WhenFilesystemFormatter
        // wired to the same When.

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

    #[TestDox('->filesystem() formats the same When the router was constructed with')]
    public function test_filesystem_uses_same_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the router must wire its downstream formatters to the
        // same When instance it holds - otherwise
        // `$when->asFormat()->filesystem()->date()` would format
        // some other time than $when. Round-tripping through one
        // downstream format method proves the wiring.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->filesystem()->date();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15', $result);
    }

    #[TestDox('->database() returns a WhenDatabaseFormatter')]
    public function test_database_returns_database_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // database() is the router entry for DB-column-friendly
        // formats. It must hand back a WhenDatabaseFormatter
        // wired to the same When.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+00:00');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->database();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenDatabaseFormatter::class, $result);
    }

    #[TestDox('->database() formats the same When the router was constructed with')]
    public function test_database_uses_same_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same wiring proof as filesystem() - the downstream
        // formatter must see the same time the router was
        // given.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+00:00');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->database()->postgres();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15T10:30:45+00:00', $result);
    }

    #[TestDox('->http() returns a WhenHttpFormatter')]
    public function test_http_returns_http_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // http() is the router entry for HTTP header formats. It
        // must hand back a WhenHttpFormatter wired to the same
        // When.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+00:00');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->http();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(WhenHttpFormatter::class, $result);
    }

    #[TestDox('->http() formats the same When the router was constructed with')]
    public function test_http_uses_same_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same wiring proof as filesystem() and database() - the
        // downstream formatter must see the same time the router
        // was given.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+00:00');
        $unit = new WhenFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->http()->rfc9110();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Sun, 15 Jun 2025 10:30:45 GMT', $result);
    }
}
