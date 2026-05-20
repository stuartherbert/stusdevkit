# IsComparablePair::check()

> `public static function check(mixed $first, mixed $second): bool`

Predicate. Can `$first` and `$second` be meaningfully ordered
against each other using PHP's relational operators?

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\IsComparablePair`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class IsComparablePair
{
    /**
     * @param mixed $first
     *      one of the two values to check
     * @param mixed $second
     *      the other value to check
     * @return bool
     * - `true` when both values are scalar, or both values are
     *   `DateTimeInterface` instances
     * - `false` otherwise - most importantly when a scalar is
     *   paired with a `DateTimeInterface`
     */
    public static function check(mixed $first, mixed $second): bool
}
```

## Description

`::check()` is the static entry point for the
[`IsComparablePair`](README.md) predicate. Use it inline whenever
the call site already has both values in hand and does not need to
store the predicate as a callable — the static form avoids
allocating an `IsComparablePair` instance.

A pair is comparable when both values are scalar, or when both are
[`DateTimeInterface`](https://www.php.net/manual/en/class.datetimeinterface.php)
instances. Every other pairing is reported as not comparable. The
argument order does not matter: `::check()` asks only whether the
two values are the same kind of thing, so swapping `$first` and
`$second` never changes the result.

When the same predicate must travel as a value (stored in a
`callable` slot, passed to a higher-order helper), use
[`->__invoke()`](__invoke.md) instead.

## Parameters

**`$first`** (`mixed`)

One of the two values to check. Any PHP value is accepted.

**`$second`** (`mixed`)

The other value to check. Any PHP value is accepted.

## Return Values

Returns `true` when both values are scalar, or when both are
[`DateTimeInterface`](https://www.php.net/manual/en/class.datetimeinterface.php)
instances. Returns `false` for every other pairing — most
importantly when a scalar is paired with a `DateTimeInterface`.

## Errors/Exceptions

_None._

## Here Be Dragons

See [Here Be Dragons on `IsComparablePair`](README.md#here-be-dragons) —
the scalar-vs-DateTime rule applies identically to both entry
points.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\IsComparablePair
 ✔ ::check() is declared public, static
 ✔ ::check() declares $first and $second as its parameters
 ✔ ::check() returns bool
 ✔ ::check() returns true for two integers
 ✔ ::check() returns true for an integer and a float
 ✔ ::check() returns true for two floats
 ✔ ::check() returns true for an integer and a string
 ✔ ::check() returns true for two strings
 ✔ ::check() returns true for an integer and a boolean
 ✔ ::check() returns true for two booleans
 ✔ ::check() returns true for a string and a boolean
 ✔ ::check() returns true for two DateTimeImmutable instances
 ✔ ::check() returns true for a DateTime and a DateTimeImmutable
 ✔ ::check() returns false for an integer and a DateTimeImmutable
 ✔ ::check() returns false for a DateTimeImmutable and an integer
 ✔ ::check() returns false for a string and a DateTimeImmutable
 ✔ ::check() returns false for a float and a DateTimeImmutable
 ✔ ::check() returns false for a boolean and a DateTimeImmutable
 ✔ ::check() returns false for an integer and null
 ✔ ::check() returns false for null and an integer
 ✔ ::check() returns false for two nulls
 ✔ ::check() returns false for an integer and an array
 ✔ ::check() returns false for two arrays
 ✔ ::check() returns false for an integer and a stdClass
 ✔ ::check() returns false for two stdClass instances
 ✔ ::check() returns false for a DateTimeImmutable and a stdClass
```

## Source

[`kits/missingbitskit/src/TypeInspectors/IsComparablePair.php:113`](../../../../kits/missingbitskit/src/TypeInspectors/IsComparablePair.php#L113)

## Changelog

_No tagged releases yet._

## See Also

- [`IsComparablePair::__invoke()`](__invoke.md) — the invokable
  entry point with identical behaviour; use it when the predicate
  needs to travel as a callable.
- [`IsArrayKey`](../../Arrays/IsArrayKey/README.md) — sibling
  predicate in MissingBitsKit with the same static-plus-invokable
  shape.

## Issues

- [Open issues mentioning `IsComparablePair::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsComparablePair%3A%3Acheck%28%29%22)
- [Closed issues mentioning `IsComparablePair::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsComparablePair%3A%3Acheck%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsComparablePair%3A%3Acheck%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
