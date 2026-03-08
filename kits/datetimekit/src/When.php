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
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use NoDiscard;
use Override;
use StusDevKit\DateTimeKit\Formatters\WhenFormatter;
use StusDevKit\DateTimeKit\Formatters\WhenGroupFormatterInterface;
use StusDevKit\DateTimeKit\Formatters\WhenSingleFormatterInterface;
use StusDevKit\DateTimeKit\Formatters\WhenSingleTransformerInterface;

/**
 * A `DateTimeImmutable` with added convenience.
 *
 * @phpstan-consistent-constructor
 */
class When extends DateTimeImmutable
{
    // ================================================================
    //
    // Constructors
    //
    // ----------------------------------------------------------------

    /**
     * Attempt to build a new instance from the given input.
     *
     * Supports passing in `null` - handy for hydrating fields from a
     * database ;)
     *
     * `$input` can be:
     *
     * - an instance of `When`
     * - any DateTime-compatible object
     * - any date/time string supported by PHP's DateTimeImmutable constructor
     * - a UNIX timestamp
     *
     * NOTES:
     *
     * - does not clone any instance of `When` that's passed in; you
     *   get the same object returned (handy if you're using this to
     *   wrap a DateTimeInterface that _might_ already be a `When` object)
     * - does not support microsecond (aka realtime) precision. Use the
     *   `::fromRealtime()` static constructor for that.
     */
    #[NoDiscard]
    public static function maybeFrom(DateTimeInterface|string|int|null $input): ?static
    {
        if ($input === null) {
            return null;
        }

        return static::from($input);
    }

    /**
     * Builds a new instance from the given input.
     *
     * If you pass in an instance of `When`, we return the same instance back
     * (ie, we don't return a new object).
     *
     * `$input` can be:
     *
     * - an instance of `When`
     * - any DateTime-compatible object
     * - any date/time string supported by PHP's DateTimeImmutable constructor
     * - a UNIX timestamp
     *
     * NOTES:
     *
     * - does not clone any instance of `When` that's passed in; you
     *   get the same object returned (handy if you're using this to
     *   wrap a DateTimeInterface that _might_ already be a `When` object)
     * - does not support microsecond (aka realtime) precision. Use the
     *   `::fromRealtime()` static constructor for that.
     */
    #[NoDiscard]
    public static function from(DateTimeInterface|string|int $input): static
    {
        if ($input instanceof static) {
            return $input;
        }

        if ($input instanceof DateTimeInterface) {
            return static::fromDateTimeInterface($input);
        }

        if (is_int($input)) {
            return static::fromUnixTimestamp($input);
        }

        return new static($input);
    }

    /**
     * Builds a new instance, using the given datetime information.
     *
     * Preserves microsecond precision from the input.
     */
    #[NoDiscard]
    public static function fromDateTimeInterface(DateTimeInterface $input): static
    {
        return new static($input->format('Y-m-d H:i:s.u e'));
    }

    /**
     * Builds a new instance with microsecond precision.
     *
     * When called without an argument (or with `null`), captures
     * the current time with microsecond precision.
     */
    #[NoDiscard]
    public static function fromRealtime(?float $input = null): static
    {
        $input ??= microtime(true);

        return new static("@" . sprintf('%.6f', $input));
    }

    /**
     * Builds a new instance, using the given UNIX timestamp
     */
    #[NoDiscard]
    public static function fromUnixTimestamp(int $input): static
    {
        return new static("@" . $input);
    }

    // ================================================================
    //
    // Type conversion
    //
    // ----------------------------------------------------------------

    /**
     * Returns a formatter router for domain-specific formatting.
     *
     * Usage:
     *
     *     $when->asFormat()->filesystem()->date();
     *     $when->asFormat()->database()->postgres();
     */
    #[NoDiscard]
    public function asFormat(): WhenFormatter
    {
        return new WhenFormatter($this);
    }

    /**
     * Returns a custom group formatter for this datetime.
     *
     * Pass a class-string to get a fully-typed formatter
     * instance with IDE autocomplete on all its methods.
     *
     * Usage:
     *
     *     $when->formatWith(MyFormatter::class)->myMethod();
     *
     * @template T of WhenGroupFormatterInterface
     * @param class-string<T> $formatterClass
     * @return T
     */
    #[NoDiscard]
    public function formatWith(string $formatterClass): object
    {
        if (! is_a($formatterClass, WhenGroupFormatterInterface::class, allow_string: true)) {
            throw new InvalidArgumentException(
                $formatterClass . ' does not implement WhenGroupFormatterInterface'
            );
        }

        return new $formatterClass($this);
    }

    /**
     * Formats this datetime using an existing formatter
     * instance.
     *
     * Usage:
     *
     *     $when->formatUsing($myFormatter);
     */
    #[NoDiscard]
    public function formatUsing(
        WhenSingleFormatterInterface $formatter,
    ): string {
        return $formatter->formatWhen($this);
    }

    /**
     * Transforms this datetime using an existing transformer
     * instance.
     *
     * Unlike `formatUsing()` (which returns `string`), this
     * method can return any type.
     *
     * Usage:
     *
     *     $when->transformUsing($myTransformer);
     */
    #[NoDiscard]
    public function transformUsing(
        WhenSingleTransformerInterface $transformer,
    ): mixed {
        return $transformer->transformWhen($this);
    }

    /**
     * Converts to PHP's microtime float format.
     *
     * If this object wasn't constructed by calling `::fromRealtime()`,
     * the microsecond part will probably be `0`.
     */
    public function asMicrotime(): float
    {
        return (float) $this->format('U.u');
    }

    /**
     * Converts to a UNIX timestamp.
     */
    public function asUnixTimestamp(): int
    {
        return $this->getTimestamp();
    }

    // ================================================================
    //
    // Extractors
    //
    // ----------------------------------------------------------------

    /**
     * get the 'year' component of this datetime
     */
    public function getYear(): int
    {
        return (int) $this->format('Y');
    }

    /**
     * get the 'month' component of this datetime
     */
    public function getMonthOfYear(): int
    {
        return (int) $this->format('m');
    }

    /**
     * get the 'day' component of this datetime
     */
    public function getDayOfMonth(): int
    {
        return (int) $this->format('d');
    }

    /**
     * get the 'hour' component of this datetime
     */
    public function getHour(): int
    {
        return (int) $this->format('H');
    }

    /**
     * get the 'minutes' component of this datetime
     */
    public function getMinutes(): int
    {
        return (int) $this->format('i');
    }

    /**
     * get the 'seconds' component of this datetime
     */
    public function getSeconds(): int
    {
        return (int) $this->format('s');
    }

    /**
     * get the 'microseconds' component of this datetime
     */
    public function getMicroseconds(): int
    {
        return (int) $this->format('u');
    }

    // ================================================================
    //
    // Modifiers
    //
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    // Date manipulation

    /**
     * Return a new object, with the year,month,date copied from the
     * given DateTimeInterface.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withDateFrom(DateTimeInterface $input): static
    {
        $when = static::from($input);

        return $this->setDate(
            $when->getYear(),
            $when->getMonthOfYear(),
            $when->getDayOfMonth(),
        );
    }

    /**
     * Return a new object, with any of the year,month,date replaced
     * by the given inputs.
     *
     * Designed to be used with named parameters, so that you only
     * replace the parts you want to.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withDate(
        ?int $year = null,
        ?int $month = null,
        ?int $day = null,
    ): static {
        return $this->setDate(
            $year ?? $this->getYear(),
            $month ?? $this->getMonthOfYear(),
            $day ?? $this->getDayOfMonth(),
        );
    }

    /**
     * Return a new object, with the year replaced by the given input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withYear(int $year): static
    {
        return $this->withDate(year: $year);
    }

    /**
     * Return a new object, with the month replaced by the given input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withMonthOfYear(int $month): static
    {
        return $this->withDate(month: $month);
    }

    /**
     * Return a new object, with the day replaced by the given input.
     *
     * Will ensure you don't go beyond the end of this object's month (ie,
     * if this object's month is February, and you call this method with
     * an input of `31`, the returned object will have the day of month
     * set to either `28` or `29` as appropriate).
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withDayOfMonth(int $day): static
    {
        $lastDayOfMonth = (int) $this->format('t');

        return $this->withDate(day: min($day, $lastDayOfMonth));
    }

    // ----------------------------------------------------------------
    // Time manipulation

    /**
     * Return a new object, with the time component replaced by the time
     * component of the given input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withTimeFrom(DateTimeInterface $input): static
    {
        $when = static::from($input);

        return $this->setTime(
            $when->getHour(),
            $when->getMinutes(),
            $when->getSeconds(),
            $when->getMicroseconds(),
        );
    }

    /**
     * Return a new object, with any of the hour, minute, seconds and
     * microseconds replaced by the given inputs.
     *
     * Designed to be used with named parameters, so that you only
     * replace the parts you want to.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withTime(
        ?int $hour = null,
        ?int $minutes = null,
        ?int $seconds = null,
        ?int $microseconds = null,
    ): static {
        return $this->setTime(
            $hour ?? $this->getHour(),
            $minutes ?? $this->getMinutes(),
            $seconds ?? $this->getSeconds(),
            $microseconds ?? $this->getMicroseconds(),
        );
    }

    /**
     * Returns a new object, with the hour replaced by the given input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withHour(int $hour): static
    {
        return $this->withTime(hour: $hour);
    }

    /**
     * Returns a new object, with the minutes replaced by the given input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withMinutes(int $minutes): static
    {
        return $this->withTime(minutes: $minutes);
    }

    /**
     * Returns a new object, with the seconds replaced by the given input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withSeconds(int $seconds): static
    {
        return $this->withTime(seconds: $seconds);
    }

    /**
     * Returns a new object, with the microseconds replaced by the given
     * input.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     */
    #[NoDiscard]
    public function withMicroseconds(int $microseconds): static
    {
        return $this->withTime(microseconds: $microseconds);
    }

    // ----------------------------------------------------------------
    //
    // Modifier support
    //
    // ----------------------------------------------------------------

    /**
     * Returns a new object, with the given PHP relative modifier string
     * applied.
     *
     * We append " of this month" to your input; ie
     *
     * - `first day` becomes `first day of this month`
     * - `last day` becomes `last day of this month`
     *
     * If the modifier changes the current year or month, we throw an
     * `InvalidArgumentException`.
     *
     * All other parts of the datetime are copied from this object's
     * values.
     *
     * This is very handy for building datetimes for scheduled tasks,
     * such as billing dates.
     *
     * @see https://www.php.net/manual/en/datetime.formats.php#datetime.formats.relative
     *
     * @throws InvalidArgumentException
     */
    #[NoDiscard]
    public function modifyDayOfMonth(string $modifier): static
    {
        $fullModifier = $modifier . " of this month";

        // no need to catch any exception thrown by modify() here
        $retval = $this->modify($fullModifier);

        // has the modifier changed the month / year?
        if ($retval->asFormat()->filesystem()->yearMonth() !== $this->asFormat()->filesystem()->yearMonth()) {
            throw new InvalidArgumentException("{$modifier} changed the month or year; only allowed to change the day of the month");
        }

        // all good
        //
        // we copy the time component back in, just in case the modifier
        // has messed with that!
        return $retval->withTimeFrom($this);
    }

    /**
     * Returns a new object, with the given PHP relative modifier string
     * applied.
     *
     * If the modifier changes the year, month or day, we throw an
     * `InvalidArgumentException`.
     *
     * @see https://www.php.net/manual/en/datetime.formats.php#datetime.formats.relative
     *
     * @throws InvalidArgumentException
     */
    #[NoDiscard]
    public function modifyTime(string $modifier): static
    {
        $retval = $this->modify($modifier);

        if ($retval->asFormat()->filesystem()->date() !== $this->asFormat()->filesystem()->date()) {
            throw new InvalidArgumentException("{$modifier} changed the date; only allowed to change the time");
        }

        // all good
        return $retval;
    }

    // ================================================================
    //
    // Wrappers around parent class methods
    //
    // ----------------------------------------------------------------

    /**
     * Adds an amount of days, months, years, hours, minutes
     * and seconds. Convenience wrapper around
     * `DateTimeImmutable::add()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.add.php
     */
    #[NoDiscard]
    #[Override]
    public function add(DateInterval $interval): static
    {
        return static::fromDateTimeInterface(parent::add($interval));
    }

    /**
     * Alters the timestamp. Convenience wrapper around
     * `DateTimeImmutable::modify()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.modify.php
     *
     * @param string $modifier A date/time string. Valid formats
     *     are explained in {@link https://secure.php.net/manual/en/datetime.formats.php Date and Time Formats}.
     * @return static
     *
     * @throws DateMalformedStringException if the modifier string
     *     is not a valid datetime format.
     * @throws InvalidArgumentException if the modifier is
     *     syntactically valid but produces a false result.
     */
    #[NoDiscard]
    #[Override]
    public function modify(string $modifier): static
    {
        $result = parent::modify($modifier);
        if (! $result) {
            throw new InvalidArgumentException("{$modifier} is not a valid DateTime modifier");
        }

        return static::from($result);
    }

    /**
     * Sets the date. Convenience wrapper around
     * `DateTimeImmutable::setDate()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.setdate.php
     */
    #[NoDiscard]
    #[Override]
    public function setDate(int $year, int $month, int $day): static
    {
        return static::fromDateTimeInterface(parent::setDate($year, $month, $day));
    }

    /**
     * Sets the ISO date. Convenience wrapper around
     * `DateTimeImmutable::setISODate()`.
     *
     * @link https://php.net/manual/en/class.datetimeimmutable.php
     */
    #[NoDiscard]
    #[Override]
    public function setISODate(int $year, int $week, int $dayOfWeek = 1): static
    {
        return static::fromDateTimeInterface(parent::setISODate($year, $week, $dayOfWeek));
    }

    /**
     * Sets the time. Convenience wrapper around
     * `DateTimeImmutable::setTime()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.settime.php
     */
    #[NoDiscard]
    #[Override]
    public function setTime(int $hour, int $minute, int $second = 0, int $microsecond = 0): static
    {
        return static::fromDateTimeInterface(parent::setTime($hour, $minute, $second, $microsecond));
    }

    /**
     * Sets the date and time based on a Unix timestamp.
     * Convenience wrapper around
     * `DateTimeImmutable::setTimestamp()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.settimestamp.php
     */
    #[NoDiscard]
    #[Override]
    public function setTimestamp(int $timestamp): static
    {
        return static::fromDateTimeInterface(parent::setTimestamp($timestamp));
    }

    /**
     * Sets the time zone. Convenience wrapper around
     * `DateTimeImmutable::setTimezone()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.settimezone.php
     */
    #[NoDiscard]
    #[Override]
    public function setTimezone(DateTimeZone $timezone): static
    {
        return static::fromDateTimeInterface(parent::setTimezone($timezone));
    }

    /**
     * Subtracts an amount of days, months, years, hours,
     * minutes and seconds. Convenience wrapper around
     * `DateTimeImmutable::sub()`.
     *
     * @link https://secure.php.net/manual/en/datetimeimmutable.sub.php
     */
    #[NoDiscard]
    #[Override]
    public function sub(DateInterval $interval): static
    {
        return static::fromDateTimeInterface(parent::sub($interval));
    }
}
