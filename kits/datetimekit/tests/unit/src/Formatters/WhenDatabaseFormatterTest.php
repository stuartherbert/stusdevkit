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
use StusDevKit\DateTimeKit\Formatters\WhenDatabaseFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\When;

#[TestDox(WhenDatabaseFormatter::class)]
class WhenDatabaseFormatterTest extends TestCase
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
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit\\Formatters';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(WhenDatabaseFormatter::class))
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

        // WhenDatabaseFormatter is a concrete class - not a trait,
        // not an interface, not an enum. Pinning this prevents a
        // silent reshape from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenDatabaseFormatter::class);

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
        // dispatch. Dropping the interface would make callers
        // instantiate the class directly instead of going through the
        // When formatter router.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenDatabaseFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->implementsInterface(
            WhenGroupFormatterInterface::class,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only __construct() and postgres() as public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared-here public method set is pinned by enumeration.
        // Adding a new database dialect method (mysql, sqlite, ...) is
        // a surface-area expansion every caller inherits, so it shows
        // up here as a named diff rather than a cryptic count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct', 'postgres'];
        $reflection = new ReflectionClass(WhenDatabaseFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === WhenDatabaseFormatter::class,
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
        // formatter to its When instance. Losing the declaration
        // would leave the $when slot unset.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenDatabaseFormatter::class);

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

        // the constructor must be public so callers (and
        // When::formatWith()) can instantiate the class.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenDatabaseFormatter::class))
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
        // contract WhenGroupFormatterInterface documents. Adding or
        // renaming parameters would break When::formatWith() which
        // always passes exactly one When argument.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['when'];
        $method = (new ReflectionClass(WhenDatabaseFormatter::class))
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
        // access to the extra When-specific format methods, not
        // just DateTimeImmutable's API.

        // ----------------------------------------------------------------
        // setup your test

        $expected = When::class;
        $param = (new ReflectionClass(WhenDatabaseFormatter::class))
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
    // ->postgres() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->postgres() is declared')]
    public function test_postgres_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // postgres() is the published format method. Renaming it is
        // a breaking change for every caller that wrote
        // $when->asFormat()->database()->postgres().

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(WhenDatabaseFormatter::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('postgres');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->postgres() is public')]
    public function test_postgres_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public so callers outside the class
        // can invoke it. Downgrading visibility would break every
        // call site.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenDatabaseFormatter::class))
            ->getMethod('postgres');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->postgres() is an instance method')]
    public function test_postgres_is_instance_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // postgres() runs against the When injected into the
        // constructor, so it must be an instance method. Turning
        // it static would break the formatter router pattern
        // ($when->asFormat()->database()->postgres()).

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(WhenDatabaseFormatter::class))
            ->getMethod('postgres');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->postgres() declares a string return type')]
    public function test_postgres_declares_string_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // postgres() hands the caller a string ready to be written
        // into a datetime column. A wider return type would force
        // every call site to re-coerce.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $method = (new ReflectionClass(WhenDatabaseFormatter::class))
            ->getMethod('postgres');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->postgres() declares no parameters')]
    public function test_postgres_declares_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // all inputs are supplied via the constructor, so the
        // format method takes no parameters. Adding one would
        // break every call site that wrote `->postgres()`.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $method = (new ReflectionClass(WhenDatabaseFormatter::class))
            ->getMethod('postgres');

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
    // ->postgres() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->postgres() returns the datetime in ATOM format (ISO 8601 with timezone offset)')]
    public function test_postgres_returns_atom_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Postgres' timestamptz column accepts ISO 8601 / RFC 3339
        // style strings. DateTimeInterface::ATOM emits exactly
        // that shape (YYYY-MM-DDTHH:MM:SS+HH:MM), so the formatter
        // can be piped straight into prepared statements.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+00:00');
        $unit = new WhenDatabaseFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->postgres();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15T10:30:45+00:00', $result);
    }

    #[TestDox('->postgres() preserves non-UTC timezone offsets')]
    public function test_postgres_preserves_non_utc_offset(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Postgres needs to know the timezone the value was
        // produced in so it can store a timestamptz correctly.
        // The formatter therefore emits the original timezone
        // offset verbatim rather than coercing everything to
        // UTC the way the HTTP formatter does.

        // ----------------------------------------------------------------
        // setup your test

        $when = new When('2025-06-15 10:30:45+05:30');
        $unit = new WhenDatabaseFormatter($when);

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->postgres();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15T10:30:45+05:30', $result);
    }
}
