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

namespace StusDevKit\DateTimeKit\Tests\Unit;

use DateInterval;
use DateMalformedStringException;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubGroupFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleTransformer;
use StusDevKit\DateTimeKit\When;

#[TestDox(When::class)]
class WhenTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\DateTimeKit namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract -
        // callers import When by FQN, so moving it is a
        // breaking change that must go through a major version
        // bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(When::class))
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

        // When is a concrete subclass of DateTimeImmutable - not
        // a trait, interface, or enum.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(When::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('extends DateTimeImmutable')]
    public function test_extends_date_time_immutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // extending DateTimeImmutable is what makes a When
        // usable anywhere a PHP DateTimeInterface is expected
        // (typehints on external libraries, date arithmetic,
        // parent formatter calls). Swapping the parent would
        // silently break every interop site.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(When::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($actual);
        $this->assertSame(DateTimeImmutable::class, $actual->getName());
    }

    #[TestDox('exposes only the expected public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared-here public method set is pinned by
        // enumeration. Methods inherited from DateTimeImmutable
        // but not overridden here are excluded - those belong
        // to the parent's contract. A new public helper on When
        // must show up here as a named diff.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            // constructors
            'maybeFrom',
            'from',
            'fromDateTimeInterface',
            'fromRealtime',
            'fromUnixTimestamp',
            // type conversion
            'asFormat',
            'formatWith',
            'formatUsing',
            'transformUsing',
            'asMicrotime',
            'asUnixTimestamp',
            // extractors
            'getYear',
            'getMonthOfYear',
            'getDayOfMonth',
            'getHour',
            'getMinutes',
            'getSeconds',
            'getMicroseconds',
            // modifiers - date
            'withDateFrom',
            'withDate',
            'withYear',
            'withMonthOfYear',
            'withDayOfMonth',
            // modifiers - time
            'withTimeFrom',
            'withTime',
            'withHour',
            'withMinutes',
            'withSeconds',
            'withMicroseconds',
            // modifier support
            'modifyDayOfMonth',
            'modifyTime',
            // parent wrappers
            'add',
            'modify',
            'setDate',
            'setISODate',
            'setTime',
            'setTimestamp',
            'setTimezone',
            'sub',
        ];
        $reflection = new ReflectionClass(When::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === When::class,
            ),
        ));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Method shape - static constructors
    //
    // Static factories that build a When from various inputs.
    // All are public, static, and return static (the When class
    // itself).
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, string, string}>
     *   method name, return-type "static" or other, comma-joined
     *   list of parameter names (comma-joined so `$TestDox`
     *   interpolates the list into the sentence cleanly)
     */
    public static function provideStaticConstructorShapes(): array
    {
        return [
            'from'                  => ['from',                  'static', 'input'],
            'fromDateTimeInterface' => ['fromDateTimeInterface', 'static', 'input'],
            'fromRealtime'          => ['fromRealtime',          'static', 'input'],
            'fromUnixTimestamp'     => ['fromUnixTimestamp',     'static', 'input'],
        ];
    }

    #[TestDox('::$methodName() is a public static method returning $expectedReturnType, taking $$expectedParamList as parameter')]
    #[DataProvider('provideStaticConstructorShapes')]
    public function test_static_constructor_shape(
        string $methodName,
        string $expectedReturnType,
        string $expectedParamList,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // every static constructor shares the shape (public,
        // static, returns static, single parameter). Pinning
        // the family once in a data provider catches drift in
        // any member.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(When::class))->getMethod($methodName);

        // ----------------------------------------------------------------
        // perform the change / test the results

        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame($expectedReturnType, $returnType->getName());

        $actualParamNames = array_map(
            static fn ($p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(
            explode(',', $expectedParamList),
            $actualParamNames,
        );
    }

    #[TestDox('::maybeFrom() is a public static method returning ?static, taking $input as parameter')]
    public function test_maybeFrom_shape(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // maybeFrom() is the nullable sibling of from() - it
        // differs in return type only (nullable), so it warrants
        // its own shape test rather than bending the shared
        // data provider.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(When::class))->getMethod('maybeFrom');

        // ----------------------------------------------------------------
        // perform the change / test the results

        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('static', $returnType->getName());
        $this->assertTrue(
            $returnType->allowsNull(),
            '::maybeFrom() returns a nullable static',
        );

        $paramNames = array_map(
            static fn ($p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['input'], $paramNames);
    }

    // ================================================================
    //
    // Method shape - extractors
    //
    // Every getX() method is public, instance, takes no parameters,
    // and returns int.
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideExtractorNames(): array
    {
        return [
            'getYear'         => ['getYear'],
            'getMonthOfYear'  => ['getMonthOfYear'],
            'getDayOfMonth'   => ['getDayOfMonth'],
            'getHour'         => ['getHour'],
            'getMinutes'      => ['getMinutes'],
            'getSeconds'      => ['getSeconds'],
            'getMicroseconds' => ['getMicroseconds'],
        ];
    }

    #[TestDox('->$extractorName() is a public instance method returning int with no parameters')]
    #[DataProvider('provideExtractorNames')]
    public function test_extractor_shape(string $extractorName): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every extractor has the identical shape: public,
        // instance, zero parameters, int return. Parametrising
        // pins that invariant once for the whole family.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(When::class))
            ->getMethod($extractorName);

        // ----------------------------------------------------------------
        // perform the change / test the results

        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
        $this->assertSame([], $method->getParameters());

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame('int', $returnType->getName());
    }

    // ================================================================
    //
    // Constructors - behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('can be instantiated directly with `new When()`')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // inheriting DateTimeImmutable's zero-arg constructor
        // means `new When()` is a valid way to build "right
        // now". Pinning this guards against someone making
        // When abstract or locking the constructor.

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new When();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $unit);
    }

    #[TestDox('::maybeFrom() returns null when given null')]
    public function test_maybeFrom_returns_null_when_given_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ::maybeFrom() is the "safe" variant designed for
        // hydrating optional fields from a database. null in,
        // null out.

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $result = When::maybeFrom(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($result);
    }

    #[TestDox('::maybeFrom() builds a When from a date/time string')]
    public function test_maybeFrom_returns_when_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // non-null string input delegates to ::from(), which
        // parses the string into a fresh When.

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

    #[TestDox('::maybeFrom() builds a When from a UNIX timestamp')]
    public function test_maybeFrom_returns_when_from_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // integer input is treated as a UNIX timestamp via
        // ::from().

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

        // passing an existing When back in must return the same
        // object - no clone. This is the no-op path for code
        // wrapping possibly-already-a-When values.

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

    #[TestDox('::from() returns the same When instance without cloning')]
    public function test_from_returns_same_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same no-op guarantee as ::maybeFrom() - a When in
        // gives the identical object back.

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

    #[TestDox('::from() builds a When from a DateTimeInterface')]
    public function test_from_creates_when_from_datetime_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // any DateTimeInterface (including DateTimeImmutable)
        // is hydrated into a new When, preserving every
        // component.

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

    #[TestDox('::from() builds a When from a mutable DateTime')]
    public function test_from_creates_when_from_mutable_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // mutable DateTime is also a DateTimeInterface, so the
        // same hydration path applies. This pins the
        // distinction from the same-instance path
        // (DateTime != When, so it must be wrapped).

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

    #[TestDox('::from() builds a When from a UNIX timestamp integer')]
    public function test_from_creates_when_from_unix_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // integer input is treated as a UNIX timestamp.

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

    #[TestDox('::from() builds a When from a date/time string')]
    public function test_from_creates_when_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // string input is parsed via DateTimeImmutable's
        // constructor.

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

    #[TestDox('::fromDateTimeInterface() builds a When from a DateTimeInterface, preserving microsecond precision')]
    public function test_fromDateTimeInterface_creates_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ::fromDateTimeInterface() is the dedicated branch for
        // DateTimeInterface inputs. It goes via a full
        // Y-m-d H:i:s.u format string so microseconds survive
        // the conversion.

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

    #[TestDox('::fromDateTimeInterface() preserves numeric timezone offsets (e.g. +05:30)')]
    public function test_fromDateTimeInterface_preserves_numeric_offset(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // databases often hand back timezones as numeric
        // offsets ("+05:30") rather than named zones
        // ("Asia/Kolkata"). The hydration must preserve the
        // offset verbatim or the reconstructed instant shifts.

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

    #[TestDox('::fromRealtime() builds a When from a microtime float with microsecond precision')]
    public function test_fromRealtime_creates_when_from_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ::fromRealtime() is the microsecond-precision entry
        // point. Feeding it a known float and re-extracting
        // via asMicrotime() must round-trip to the same
        // value (within float-precision slop).

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

        // null (or no argument) means "capture now with
        // microsecond precision". Bracketing with microtime()
        // proves the captured value is in the right window.

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

    #[TestDox('::fromUnixTimestamp() builds a When from a UNIX timestamp integer')]
    public function test_fromUnixTimestamp_creates_when(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // dedicated timestamp constructor, bypassing the
        // polymorphic ::from() dispatch.

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

    #[TestDox('->asFormat() returns a WhenFormatter router')]
    public function test_asFormat_returns_when_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // asFormat() gives callers the domain-specific
        // formatter router (filesystem / database / http).

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

    #[TestDox('->formatWith() returns an instance of the given formatter class')]
    public function test_formatWith_returns_formatter_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // formatWith() is the custom-group-formatter entry
        // point. Users pass a class-string, get back a typed
        // instance wired to this When.

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

    #[TestDox('->formatWith() wires the formatter to this When, so downstream format methods see the right time')]
    public function test_formatWith_formatter_methods_return_correct_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the returned formatter must be constructed with this
        // When (not a fresh "now"). Round-tripping two fixture
        // format methods proves the wiring on every component
        // without relying on just one format's guessable output.

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

    #[TestDox('->formatWith() throws InvalidArgumentException if the class does not implement WhenGroupFormatterInterface')]
    public function test_formatWith_throws_for_invalid_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the interface check is the runtime gate. PHP cannot
        // enforce constructor signatures through interfaces
        // (see WhenGroupFormatterInterface docs), so an
        // is_a(..., allow_string: true) check is what stops a
        // random class-string instantiating with a wrong arg
        // shape.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-06-15 10:30:45+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidArgumentException::class);
        /** @phpstan-ignore argument.type, argument.templateType */
        $_ = $unit->formatWith(\stdClass::class);
    }

    #[TestDox('->formatUsing() returns the formatted string from the given formatter')]
    public function test_formatUsing_returns_formatted_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // formatUsing() takes an already-built single-formatter
        // and passes this When to it, returning the string.

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

    #[TestDox('->transformUsing() returns the transformed value from the given transformer, with any return type')]
    public function test_transformUsing_returns_transformed_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // transformUsing() is the "any type" counterpart to
        // formatUsing() - the stub returns an array, proving
        // the mixed return type is honoured end-to-end.

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

    #[TestDox('->asMicrotime() returns a float representation of the datetime')]
    public function test_asMicrotime_returns_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // microtime() format is the "U.u" seconds-with-fraction
        // encoding. Round-tripping through ::fromRealtime()
        // proves the extraction matches the input.

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

        // thin wrapper over getTimestamp() that pins the
        // return type as int. The round-trip through
        // ::fromUnixTimestamp() proves no drift.

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
    // Extractors - behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getYear() returns the year component')]
    public function test_getYear_returns_year(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // year extraction from a known input.

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

    #[TestDox('->getMonthOfYear() returns the month component as an integer (1-12)')]
    public function test_getMonthOfYear_returns_month(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // month extraction returns a 1-based integer (PHP's
        // "m" format), not zero-padded strings.

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

    #[TestDox('->getDayOfMonth() returns the day component as an integer (1-31)')]
    public function test_getDayOfMonth_returns_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // day-of-month extraction.

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

    #[TestDox('->getHour() returns the hour component as an integer (0-23)')]
    public function test_getHour_returns_hour(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // hour extraction in 24-hour form.

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

    #[TestDox('->getMinutes() returns the minutes component as an integer (0-59)')]
    public function test_getMinutes_returns_minutes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // minutes extraction.

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

    #[TestDox('->getSeconds() returns the seconds component as an integer (0-59)')]
    public function test_getSeconds_returns_seconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // seconds extraction.

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

    #[TestDox('->getMicroseconds() returns the microseconds component as an integer (0-999999)')]
    public function test_getMicroseconds_returns_microseconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // microseconds only survive when the input had them to
        // start with, so we use a microsecond-carrying string
        // literal.

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

    #[TestDox('->get*() extractors return 0 for zero-valued components (e.g. midnight on the first of January)')]
    public function test_getters_handle_zero_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // naive format-based extraction can have footguns at
        // zero (e.g. misreading "00" as an empty string). This
        // test pins every extractor against "all zeros" input.

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

    #[TestDox('->withDateFrom() copies year/month/day from the input and preserves the original time')]
    public function test_withDateFrom_copies_date_from_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this method is half a merge: take the date from one
        // object, keep the time from another. Using two very
        // different inputs (2025 vs 2030, 06 vs 03, etc.) makes
        // it unambiguous which halves are expected.

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

    #[TestDox('->withDate() replaces year, month and day with the supplied values')]
    public function test_withDate_replaces_year_month_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withDate() is a named-parameter variant - every
        // component can be set independently.

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

        // calling withDate() with no arguments at all is
        // identical to `clone $when` - null means "keep this
        // component".

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

        // withYear() is the one-component convenience sibling
        // of withDate(year: ...).

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

        // one-component convenience sibling, month axis.

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

        // one-component convenience sibling, day axis - within
        // the month's natural range.

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

    #[TestDox('->withDayOfMonth() clamps the requested day to the last valid day of the month (February non-leap year)')]
    public function test_withDayOfMonth_clamps_to_last_day_of_month(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the key difference from DateTimeImmutable's
        // own setDate() - that would roll "February 31" forward
        // into March 3. withDayOfMonth() clamps instead, so
        // you always stay within the current month. 2025
        // February has 28 days.

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

        // same clamp logic, but the cap slides up to 29 in a
        // leap year. 2024 is leap.

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

    #[TestDox('->withTimeFrom() copies the time from the input and preserves the original date')]
    public function test_withTimeFrom_copies_time_from_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // mirror of withDateFrom() - take the time from one
        // object, keep the date from another.

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

    #[TestDox('->withTime() replaces hour, minutes, seconds and microseconds with the supplied values')]
    public function test_withTime_replaces_time_components(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTime() is the time-axis named-parameter variant.

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

        // no-arg call is a clone, matching withDate()'s shape.

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

        // one-component convenience sibling, hour axis.

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

        // one-component convenience sibling, minutes axis.

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

        // one-component convenience sibling, seconds axis.

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

        // one-component convenience sibling, microseconds axis.

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

    #[TestDox('->modifyDayOfMonth() applies a PHP relative modifier constrained to this month')]
    public function test_modifyDayOfMonth_changes_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // modifyDayOfMonth() appends " of this month" to the
        // modifier, so "first day" means "first day of the
        // current month", never rolling into a different
        // month or year.

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

    #[TestDox('->modifyDayOfMonth() can jump to the last day of the month')]
    public function test_modifyDayOfMonth_can_get_last_day(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "last day" is a typical use case: billing cycles
        // ("bill on the last day of every month") and similar.

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

    #[TestDox('->modifyDayOfMonth() preserves the time component when rewriting the day')]
    public function test_modifyDayOfMonth_preserves_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the reason the implementation re-applies the
        // original time after modify(): PHP's "first day"
        // modifier silently zeroes the time component, and
        // callers doing e.g. "last day of the month at the
        // same time" would be surprised by that.

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

    #[TestDox('->modifyDayOfMonth() throws InvalidArgumentException if the modifier would change the month or year')]
    public function test_modifyDayOfMonth_throws_if_month_changes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // February 2025 has only 4 Mondays, so "fifth monday"
        // would roll into March. The method's whole purpose is
        // to stop exactly that - the exception makes the
        // failure loud instead of silent month drift.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new When('2025-02-15 10:30:45');

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(InvalidArgumentException::class);
        $_ = $unit->modifyDayOfMonth('fifth monday');
    }

    #[TestDox('->modifyTime() applies a PHP relative modifier constrained to this date')]
    public function test_modifyTime_changes_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // mirror of modifyDayOfMonth() for the time axis -
        // "+1 hour" inside the same day is safe.

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

    #[TestDox('->modifyTime() throws InvalidArgumentException if the modifier would change the date')]
    public function test_modifyTime_throws_if_date_changes(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // "+1 day" is outside the time axis, so the method
        // must refuse rather than silently shift the day.

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

    #[TestDox('->add() returns a When instance (not a plain DateTimeImmutable)')]
    public function test_add_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's native add() returns DateTimeImmutable. The
        // override re-wraps the result as a When so callers
        // keep the extra method surface.

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

        // same re-wrap reason as add().

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

    #[TestDox('->modify() throws DateMalformedStringException on an invalid modifier string')]
    public function test_modify_throws_on_invalid_modifier(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP 8.3+ throws DateMalformedStringException for
        // unparseable modifier strings. The override
        // propagates it unchanged.

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

        // same re-wrap reason as add().

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

        // same re-wrap reason as add().

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

        // same re-wrap reason as add().

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

        // same re-wrap reason as add().

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

    #[TestDox('->setTimezone() returns a When instance with the requested timezone')]
    public function test_setTimezone_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same re-wrap reason as add(), and we also confirm
        // the new timezone sticks.

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

    #[TestDox('->sub() returns a When instance (not a plain DateTimeImmutable)')]
    public function test_sub_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // mirror of ->add() - PHP's native returns
        // DateTimeImmutable; this override re-wraps as a When.

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
