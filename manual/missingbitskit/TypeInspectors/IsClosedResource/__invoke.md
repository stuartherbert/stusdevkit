# IsClosedResource::__invoke()

> `public function __invoke(mixed $value): bool`

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
    public function __invoke(mixed $value): bool
}
```

## Description

`->__invoke()` is the invokable entry point for the
[`IsClosedResource`](README.md) predicate. Because the method is
named `__invoke()`, an `IsClosedResource` instance can be called
like a function — `(new IsClosedResource())($value)` — which lets
the predicate be stored in a `callable` slot, passed to a
higher-order helper, or composed with other callables.

It is a thin shim: `->__invoke()` forwards straight to
[`::check()`](check.md) and returns the same result — `true` only
when `$value` is a resource that has been closed (for example, a
file handle after
[`fclose()`](https://www.php.net/manual/en/function.fclose.php)),
and `false` for an open resource or any non-resource value.

When the call site already has a value in hand and does not need
a stored callable, use [`::check()`](check.md) directly — it
avoids allocating an `IsClosedResource` instance.

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
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() declares $value as its only parameter
 ✔ ->__invoke() returns bool
 ✔ ->__invoke() returns true for a closed resource
 ✔ ->__invoke() returns false for an open resource
 ✔ ->__invoke() returns false for a zero integer
 ✔ ->__invoke() returns false for a positive integer
 ✔ ->__invoke() returns false for a float
 ✔ ->__invoke() returns false for an empty string
 ✔ ->__invoke() returns false for a non-empty string
 ✔ ->__invoke() returns false for the string "resource (closed)"
 ✔ ->__invoke() returns false for a true boolean
 ✔ ->__invoke() returns false for a false boolean
 ✔ ->__invoke() returns false for null
 ✔ ->__invoke() returns false for an empty array
 ✔ ->__invoke() returns false for a populated array
 ✔ ->__invoke() returns false for a stdClass instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/IsClosedResource.php:89`](../../../../kits/missingbitskit/src/TypeInspectors/IsClosedResource.php#L89)

## Changelog

_No tagged releases yet._

## See Also

- [`IsClosedResource::check()`](check.md) — the static entry point
  with identical behaviour; use it when you do not need a stored
  callable.
- [`IsComparablePair`](../IsComparablePair/README.md) — predicate
  sibling in the same directory with the same static-plus-invokable
  shape.

## Issues

- [Open issues mentioning `IsClosedResource::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsClosedResource%3A%3A__invoke%28%29%22)
- [Closed issues mentioning `IsClosedResource::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsClosedResource%3A%3A__invoke%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsClosedResource%3A%3A__invoke%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
