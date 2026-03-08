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

namespace StusDevKit\DateTimeKit;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use NoDiscard;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\Formatters\WhenSingleFormatterInterface;
use StusDevKit\DateTimeKit\Formatters\WhenSingleTransformerInterface;

/**
 * Represents the current datetime in your app.
 *
 * Set this in your app's bootstrap file (by calling `Now::init()`).
 *
 * Code such as HTTP and API request handlers benefit greatly from having
 * a single fixed value for 'now' throughout their execution.
 */
class Now
{
    protected static When $cachedDateTime;

    // ================================================================
    //
    // Constructors
    //
    // ----------------------------------------------------------------

    /**
     * Set Now to the current datetime.
     */
    public static function init(): void
    {
        static::reset();
    }

    /**
     * Update Now to the current datetime.
     *
     * ONLY call this inside long-running processes (such as async event
     * queue handlers).
     *
     * NEVER call this inside HTTP or API request handlers.
     */
    public static function reset(): void
    {
        self::$cachedDateTime = new When('now');
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    /**
     * Returns the underlying `When` object.
     *
     * Repeated calls to this method will return the exact same value.
     */
    public static function now(): When
    {
        return static::$cachedDateTime;
    }

    /**
     * Returns a formatter router for domain-specific formatting.
     *
     * Usage:
     *
     *     Now::asFormat()->filesystem()->date();
     *     Now::asFormat()->database()->postgres();
     *     Now::asFormat()->http()->rfc9110();
     *
     * Repeated calls to this method will return the exact same
     * underlying datetime value.
     */
    public static function asFormat(): WhenFormatter
    {
        return static::$cachedDateTime->asFormat();
    }

    /**
     * Returns a custom group formatter for the current
     * datetime.
     *
     * Usage:
     *
     *     Now::formatWith(MyFormatter::class)->myMethod();
     *
     * @template T of WhenGroupFormatterInterface
     * @param class-string<T> $formatterClass
     * @return T
     */
    #[NoDiscard]
    public static function formatWith(string $formatterClass): object
    {
        return static::$cachedDateTime->formatWith($formatterClass);
    }

    /**
     * Formats the current datetime using an existing
     * formatter instance.
     *
     * Usage:
     *
     *     Now::formatUsing($myFormatter);
     */
    #[NoDiscard]
    public static function formatUsing(
        WhenSingleFormatterInterface $formatter,
    ): string {
        return static::$cachedDateTime->formatUsing($formatter);
    }

    /**
     * Transforms the current datetime using an existing
     * transformer instance.
     *
     * Unlike `formatUsing()` (which returns `string`), this
     * method can return any type.
     *
     * Usage:
     *
     *     Now::transformUsing($myTransformer);
     */
    #[NoDiscard]
    public static function transformUsing(
        WhenSingleTransformerInterface $transformer,
    ): mixed {
        return static::$cachedDateTime->transformUsing($transformer);
    }

    /**
     * Converts to a `DateTimeImmutable`.
     *
     * Useful in testing scenarios where you deliberately want
     * to shed the extra methods that `When` adds.
     *
     * Each call returns a new `DateTimeImmutable` instance.
     */
    public static function asDateTimeImmutable(): DateTimeImmutable
    {
        return new DateTimeImmutable(
            datetime: static::$cachedDateTime->format('Y-m-d H:i:s.u'),
            timezone: static::$cachedDateTime->getTimezone(),
        );
    }

    /**
     * Converts to a UNIX timestamp.
     *
     * Repeated calls to this method will return the exact same value.
     */
    public static function asUnixTimestamp(): int
    {
        return static::$cachedDateTime->asUnixTimestamp();
    }

    /**
     * If `$input` is `null`, return the current value of `Now`.
     *
     * Otherwise, return `$input` as a `When` type.
     *
     * Handy for expanding optional values to methods!
     *
     * Repeated calls to this method with `null` parameter will return the
     * exact same value.
     */
    public static function or(DateTimeInterface|string|int|null $input): When
    {
        if ($input === null) {
            return static::$cachedDateTime;
        }

        return When::from($input);
    }

    // ================================================================
    //
    // Test clock support
    //
    // The only thing more awesome than a fixed time reference inside
    // a HTTP handler are test clocks!
    //
    // ----------------------------------------------------------------

    /**
     * Changes the value of `Now` to be the given `$input`.
     *
     * `$input` can be:
     *
     * - an instance of `When`
     * - any DateTime-compatible object
     * - any date/time string supported by PHP's DateTimeImmutable constructor
     * - a UNIX timestamp
     *
     * ONLY call this from code inside your `tests/` folder.
     */
    public static function setTestClock(DateTimeInterface|string|int $input): void
    {
        static::$cachedDateTime = When::from($input);
    }

    /**
     * Use a PHP relative-format modifier to change the value of `Now`.
     *
     * ONLY call this from code inside your `tests/` folder.
     *
     * @see https://www.php.net/manual/en/datetime.formats.php#datetime.formats.relative
     */
    public static function modifyTestClock(string $modifier): void
    {
        static::$cachedDateTime = static::$cachedDateTime->modify($modifier);
    }

    /**
     * Add the given DateInterval to the value of `Now`
     *
     * ONLY call this from code inside your `tests/` folder.
     */
    public static function addToTestClock(DateInterval|string $input): void
    {
        // normalise
        if (is_string($input)) {
            $input = new DateInterval($input);
        }

        static::$cachedDateTime = static::$cachedDateTime->add($input);
    }

    /**
     * Subtract the given DateInterval from the current value of `Now`.
     *
     * ONLY call this from code inside your `tests/` folder.
     */
    public static function subFromTestClock(DateInterval|string $input): void
    {
        if (is_string($input)) {
            $input = new DateInterval($input);
        }

        static::$cachedDateTime = static::$cachedDateTime->sub($input);
    }
}
