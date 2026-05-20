# IsComparablePair::__invoke()

> `public function __invoke(mixed $first, mixed $second): bool`

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
    public function __invoke(mixed $first, mixed $second): bool
}
```

## Description

`->__invoke()` is the invokable entry point for the
[`IsComparablePair`](README.md) predicate. Because the method is
named `__invoke()`, an `IsComparablePair` instance can be called
like a function — `(new IsComparablePair())($a, $b)` — which lets
the predicate be stored in a `callable` slot, passed to a
higher-order helper, or composed with other callables.

It is a thin shim: `->__invoke()` forwards straight to
[`::check()`](check.md) and returns the same result. A pair is
comparable when both values are scalar, or when both are
[`DateTimeInterface`](https://www.php.net/manual/en/class.datetimeinterface.php)
instances; every other pairing is reported as not comparable.

When the call site already has both values in hand and does not
need a stored callable, use [`::check()`](check.md) directly — it
avoids allocating an `IsComparablePair` instance.

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
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() declares $first and $second as its parameters
 ✔ ->__invoke() returns bool
 ✔ ->__invoke() returns true for two integers
 ✔ ->__invoke() returns true for an integer and a float
 ✔ ->__invoke() returns true for two floats
 ✔ ->__invoke() returns true for an integer and a string
 ✔ ->__invoke() returns true for two strings
 ✔ ->__invoke() returns true for an integer and a boolean
 ✔ ->__invoke() returns true for two booleans
 ✔ ->__invoke() returns true for a string and a boolean
 ✔ ->__invoke() returns true for two DateTimeImmutable instances
 ✔ ->__invoke() returns true for a DateTime and a DateTimeImmutable
 ✔ ->__invoke() returns false for an integer and a DateTimeImmutable
 ✔ ->__invoke() returns false for a DateTimeImmutable and an integer
 ✔ ->__invoke() returns false for a string and a DateTimeImmutable
 ✔ ->__invoke() returns false for a float and a DateTimeImmutable
 ✔ ->__invoke() returns false for a boolean and a DateTimeImmutable
 ✔ ->__invoke() returns false for an integer and null
 ✔ ->__invoke() returns false for null and an integer
 ✔ ->__invoke() returns false for two nulls
 ✔ ->__invoke() returns false for an integer and an array
 ✔ ->__invoke() returns false for two arrays
 ✔ ->__invoke() returns false for an integer and a stdClass
 ✔ ->__invoke() returns false for two stdClass instances
 ✔ ->__invoke() returns false for a DateTimeImmutable and a stdClass
```

## Source

[`kits/missingbitskit/src/TypeInspectors/IsComparablePair.php:94`](../../../../kits/missingbitskit/src/TypeInspectors/IsComparablePair.php#L94)

## Changelog

_No tagged releases yet._

## See Also

- [`IsComparablePair::check()`](check.md) — the static entry point
  with identical behaviour; use it when you do not need a stored
  callable.
- [`IsArrayKey`](../../Arrays/IsArrayKey/README.md) — sibling
  predicate in MissingBitsKit with the same static-plus-invokable
  shape.

## Issues

- [Open issues mentioning `IsComparablePair::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsComparablePair%3A%3A__invoke%28%29%22)
- [Closed issues mentioning `IsComparablePair::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsComparablePair%3A%3A__invoke%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsComparablePair%3A%3A__invoke%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
