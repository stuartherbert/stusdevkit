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
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Now;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubGroupFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleFormatter;
use StusDevKit\DateTimeKit\Tests\Unit\Fixtures\StubSingleTransformer;
use StusDevKit\DateTimeKit\When;

#[TestDox(Now::class)]
class NowTest extends TestCase
{
    // ================================================================
    //
    // Setup / Teardown
    //
    // ----------------------------------------------------------------

    protected function setUp(): void
    {
        // every test starts from a freshly-initialised Now, so
        // one test's setTestClock() leak cannot poison the next.
        Now::init();
    }

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

        // the published namespace is part of the contract - app
        // bootstraps import Now by FQN, so moving it is a
        // breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DateTimeKit';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(Now::class))
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

        // Now is a class (exposing only static methods) - not an
        // interface, trait, or enum.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(Now::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only the expected public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Now's published surface is its set of static methods.
        // Adding a new method is a surface-area expansion every
        // caller inherits - it must show up here as a named diff.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'init',
            'reset',
            'now',
            'asFormat',
            'formatWith',
            'formatUsing',
            'transformUsing',
            'asDateTimeImmutable',
            'asUnixTimestamp',
            'or',
            'setTestClock',
            'modifyTestClock',
            'addToTestClock',
            'subFromTestClock',
        ];
        $reflection = new ReflectionClass(Now::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === Now::class,
            ),
        ));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Static method shape (applies to every public method on Now)
    //
    // Now has no instance methods - every entry point is static.
    // This parametrised pair pins that contract once, rather than
    // repeating "is static / is public" for every method.
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string, string, list<string>}>
     *   method name, return type, ordered list of parameter names
     */
    public static function provideStaticMethodShapes(): array
    {
        return [
            'init'                => ['init',                'void',              []],
            'reset'               => ['reset',               'void',              []],
            'now'                 => ['now',                 When::class,         []],
            'asFormat'            => ['asFormat',            WhenFormatter::class, []],
            'formatWith'          => ['formatWith',          'object',            ['formatterClass']],
            'formatUsing'         => ['formatUsing',         'string',            ['formatter']],
            'transformUsing'      => ['transformUsing',      'mixed',             ['transformer']],
            'asDateTimeImmutable' => ['asDateTimeImmutable', DateTimeImmutable::class, []],
            'asUnixTimestamp'     => ['asUnixTimestamp',     'int',               []],
            'or'                  => ['or',                  When::class,         ['input']],
            'setTestClock'        => ['setTestClock',        'void',              ['input']],
            'modifyTestClock'     => ['modifyTestClock',     'void',              ['modifier']],
            'addToTestClock'      => ['addToTestClock',      'void',              ['input']],
            'subFromTestClock'    => ['subFromTestClock',    'void',              ['input']],
        ];
    }

    /**
     * @param list<string> $expectedParamNames
     */
    #[TestDox('::$methodName() is a public static method returning $expectedReturnType')]
    #[DataProvider('provideStaticMethodShapes')]
    public function test_static_method_shape(
        string $methodName,
        string $expectedReturnType,
        array $expectedParamNames,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // every Now method is static (Now is a stateless facade
        // over a cached When), public, and has a declared return
        // type. Parametrising the shape check gives us the same
        // lockdown across the whole class with one test body
        // rather than 42 near-identical ones.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(Now::class))->getMethod($methodName);

        // ----------------------------------------------------------------
        // perform the change / test the results

        $this->assertTrue($method->isPublic(), 'is public');
        $this->assertTrue($method->isStatic(), 'is static');

        // return type
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);
        $this->assertSame($expectedReturnType, $returnType->getName());

        // parameter order and names
        $actualParamNames = array_map(
            static fn ($p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame($expectedParamNames, $actualParamNames);
    }

    // ================================================================
    //
    // Constructors
    //
    // ----------------------------------------------------------------

    #[TestDox('::init() sets Now to the current datetime')]
    public function test_init_sets_now_to_current_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // init() is the app-bootstrap entry point. It sets the
        // cached When to "now" so subsequent code (HTTP handlers,
        // request scopes) sees a single fixed-point time for the
        // whole request.

        // ----------------------------------------------------------------
        // setup your test

        // bracket the call - the cached time must land inside
        // this window
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

    #[TestDox('::reset() updates Now to the current datetime, overwriting any prior value')]
    public function test_reset_updates_now_to_current_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // reset() is the long-running-process entry point -
        // event queue consumers call it between jobs to flush
        // the fixed-time cache. Putting the clock somewhere
        // clearly not-now first proves the overwrite happens.

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2020-01-01 00:00:00');

        // ----------------------------------------------------------------
        // perform the change

        Now::reset();

        // ----------------------------------------------------------------
        // test the results

        $now = Now::now();
        $this->assertNotSame(2020, $now->getYear());
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    #[TestDox('::now() returns a When instance')]
    public function test_now_returns_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the cached value is a When (not a plain
        // DateTimeImmutable) so callers have access to the
        // extra When-specific methods.

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

    #[TestDox('::now() returns the exact same When on repeated calls')]
    public function test_now_returns_same_value_on_repeated_calls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the whole point of Now is to freeze time across a
        // request, so repeated calls must hand back the
        // identical object (same-reference, not just equals).

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

    #[TestDox('::asFormat() returns a WhenFormatter')]
    public function test_asFormat_returns_when_formatter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // asFormat() gives callers the domain-specific
        // formatter router
        // (Now::asFormat()->filesystem()->date() and friends).

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

    #[TestDox('::asFormat() formats the cached Now datetime')]
    public function test_asFormat_uses_cached_now_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // asFormat() must wire the router to the cached When,
        // not to a fresh "now" - otherwise the fixed-point
        // guarantee breaks the moment a formatter is used.
        // Round-tripping a known time through a downstream
        // formatter proves the wiring.

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00+00:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asFormat()->database()->postgres();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2025-06-15T10:30:00+00:00', $result);
    }

    #[TestDox('::formatWith() returns an instance of the given formatter class')]
    public function test_formatWith_returns_formatter_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // formatWith() is the custom-group-formatter entry
        // point. The IDE-visible return type comes from the
        // @template generic - at runtime we just confirm the
        // returned object is an instance of the requested
        // class.

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

    #[TestDox('::formatWith() wires the formatter to the cached Now datetime')]
    public function test_formatWith_uses_cached_now_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same fixed-point guarantee as asFormat(): the
        // formatter must see the cached When, not a fresh
        // "now".

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

    #[TestDox('::formatUsing() returns the formatted string from the given formatter')]
    public function test_formatUsing_returns_formatted_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // formatUsing() takes an already-built formatter object
        // and hands it the cached When, returning whatever
        // string the formatter produces.

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

    #[TestDox('::transformUsing() returns the transformed value from the given transformer, with any return type')]
    public function test_transformUsing_returns_transformed_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // transformUsing() is the "any type, not just string"
        // counterpart to formatUsing(). The stub transformer
        // returns an array, which formatUsing() could not.

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

    #[TestDox('::asDateTimeImmutable() returns a DateTimeImmutable that is not a When')]
    public function test_asDateTimeImmutable_returns_datetime_immutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // asDateTimeImmutable() exists for test scenarios where
        // you deliberately want to shed the When-specific API
        // and work against the standard PHP type. The returned
        // object must therefore be a plain DateTimeImmutable,
        // not a When subclass.

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

        // the shed-to-DateTimeImmutable step must preserve the
        // instant in time, down to microseconds. The
        // round-trip format string pins the precision.

        // ----------------------------------------------------------------
        // setup your test

        Now::setTestClock('2025-06-15 10:30:00');

        // ----------------------------------------------------------------
        // perform the change

        $result = Now::asDateTimeImmutable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            Now::now()->format('Y-m-d H:i:s.u'),
            $result->format('Y-m-d H:i:s.u'),
        );
    }

    #[TestDox('::asDateTimeImmutable() returns a new instance on each call')]
    public function test_asDateTimeImmutable_returns_new_instance_on_each_call(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // unlike ::now() (which caches), ::asDateTimeImmutable()
        // is documented to build a fresh object every time.
        // That's intentional: it lets tests that modify the
        // returned value do so without affecting Now itself.

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

    #[TestDox('::asUnixTimestamp() returns the cached datetime as a UNIX timestamp integer')]
    public function test_asUnixTimestamp_returns_unix_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // asUnixTimestamp() is the canonical "give me an int I
        // can store anywhere" accessor. Setting the clock by
        // timestamp means the expected output is the literal
        // integer we passed in.

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

        // same fixed-point guarantee as ::now() - the cached
        // time cannot drift between two calls.

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

    #[TestDox('::or() returns the cached Now when given null')]
    public function test_or_returns_now_when_given_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ::or() is the "default to Now on null" helper for
        // expanding optional datetime parameters. Passing null
        // must hand back the same object ::now() would.

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

        // non-null input is parsed into a fresh When - no
        // fallback to Now.

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

        // integer input is treated as a UNIX timestamp and
        // converted into a fresh When.

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

        // any DateTimeInterface is hydrated into a When
        // preserving year/month/day.

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

    #[TestDox('::or() returns the same When on repeated null calls')]
    public function test_or_returns_same_value_on_repeated_null_calls(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // same fixed-point guarantee as ::now() - repeated
        // null-calls hand back the identical cached object.

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

    #[TestDox('::setTestClock() sets Now from a date/time string')]
    public function test_setTestClock_sets_now_from_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // setTestClock() is the test-only clock override. The
        // string input path is the one used most often in test
        // code because it reads like a literal time.

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

        // integer input is treated as a UNIX timestamp.

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

        // DateTimeInterface (or any subclass) is hydrated into
        // a fresh cached When.

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

        // passing a When is legal (When is a DateTimeImmutable
        // subclass) and useful when a test already has one in
        // hand.

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

    #[TestDox('::modifyTestClock() applies a PHP relative modifier to Now')]
    public function test_modifyTestClock_modifies_now(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // modifyTestClock() feeds the string straight into
        // PHP's relative-format parser. "+1 day" is the least
        // surprising case to verify.

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

    #[TestDox('::modifyTestClock() moves time backwards with a negative modifier')]
    public function test_modifyTestClock_can_subtract_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // negative PHP relative modifiers ("-3 hours") are just
        // as legal as positive ones. Pinning this direction
        // separately prevents a regression that handled "+" but
        // not "-".

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

    #[TestDox('::addToTestClock() adds a DateInterval to Now')]
    public function test_addToTestClock_adds_dateinterval(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // DateInterval input is the native, strongly-typed way
        // to nudge the clock forward. One day forward is the
        // minimal proof the addition took effect.

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

    #[TestDox('::addToTestClock() adds an ISO-8601 duration string to Now')]
    public function test_addToTestClock_adds_interval_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // string input is normalised to DateInterval inside
        // addToTestClock(). "PT2H30M" (two hours thirty
        // minutes) keeps the date but moves the clock forward
        // in both hours and minutes.

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

    #[TestDox('::subFromTestClock() subtracts a DateInterval from Now')]
    public function test_subFromTestClock_subtracts_dateinterval(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // symmetric with addToTestClock(). One day back is the
        // minimal proof the subtraction took effect.

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

    #[TestDox('::subFromTestClock() subtracts an ISO-8601 duration string from Now')]
    public function test_subFromTestClock_subtracts_interval_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // symmetric with addToTestClock() - string input is
        // normalised to DateInterval.

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
