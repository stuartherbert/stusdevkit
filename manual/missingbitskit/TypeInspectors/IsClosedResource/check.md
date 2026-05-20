# IsClosedResource::check()

> `public static function check(mixed $value): bool`

Predicate. Is the given `$value` a closed resource?

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\IsClosedResource`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class IsClosedResource
{
    /**
     * @param mixed $value
     *      the value to check
     * @return bool
     * - `true` when `$value` is a closed resource
     * - `false` otherwise, including for an open resource
     */
    public static function check(mixed $value): bool
}
```

## Description

`::check()` is the static entry point for the
[`IsClosedResource`](README.md) predicate. Use it inline whenever
the call site already has a value in hand and does not need to
store the predicate as a callable — the static form avoids
allocating an `IsClosedResource` instance.

`::check()` returns `true` only when `$value` is a resource that
has been closed (for example, a file handle after
[`fclose()`](https://www.php.net/manual/en/function.fclose.php)).
An open resource returns `false` — reach for PHP's
[`is_resource()`](https://www.php.net/manual/en/function.is-resource.php)
when you want to know whether a value is an *open* resource — and
every non-resource value returns `false` too.

When the same predicate must travel as a value (stored in a
`callable` slot, passed to a higher-order helper), use
[`->__invoke()`](__invoke.md) instead.

## Parameters

**`$value`** (`mixed`)

The value to check. Any PHP value is accepted.

## Return Values

Returns `true` when `$value` is a resource that has been closed,
and `false` for everything else — both an open resource and every
non-resource value.

## Errors/Exceptions

_None._

## Here Be Dragons

See [Here Be Dragons on `IsClosedResource`](README.md#here-be-dragons) —
the closed-vs-open-vs-non-resource rule applies identically to
both entry points.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\IsClosedResource
 ✔ ::check() is declared public, static
 ✔ ::check() declares $value as its only parameter
 ✔ ::check() returns bool
 ✔ ::check() returns true for a closed resource
 ✔ ::check() returns false for an open resource
 ✔ ::check() returns false for a zero integer
 ✔ ::check() returns false for a positive integer
 ✔ ::check() returns false for a float
 ✔ ::check() returns false for an empty string
 ✔ ::check() returns false for a non-empty string
 ✔ ::check() returns false for the string "resource (closed)"
 ✔ ::check() returns false for a true boolean
 ✔ ::check() returns false for a false boolean
 ✔ ::check() returns false for null
 ✔ ::check() returns false for an empty array
 ✔ ::check() returns false for a populated array
 ✔ ::check() returns false for a stdClass instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/IsClosedResource.php:108`](../../../../kits/missingbitskit/src/TypeInspectors/IsClosedResource.php#L108)

## Changelog

_No tagged releases yet._

## See Also

- [`IsClosedResource::__invoke()`](__invoke.md) — the invokable
  entry point with identical behaviour; use it when the predicate
  needs to travel as a callable.
- [`IsComparablePair`](../IsComparablePair/README.md) — predicate
  sibling in the same directory with the same static-plus-invokable
  shape.

## Issues

- [Open issues mentioning `IsClosedResource::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsClosedResource%3A%3Acheck%28%29%22)
- [Closed issues mentioning `IsClosedResource::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsClosedResource%3A%3Acheck%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsClosedResource%3A%3Acheck%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
