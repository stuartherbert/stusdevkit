# GetDuckTypes

Returns a practical list of PHP types for any value — the union of all
per-type inspectors plus the universal `mixed` marker.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetDuckTypes
{
    /**
     * @param  mixed $item
     *         the item to examine
     *
     * @return array<string,string>
     *         a list of type(s) that this item can be
     */
    public function __invoke(mixed $item): array;

    /**
     * @param  mixed $item
     *         the item to examine
     * @return array<string,string>
     *         the list of type(s) that this item can be
     */
    public static function from(mixed $item): array;
}
```

## Description

`GetDuckTypes` returns a practical list of PHP types for any value — the union
of all per-type inspectors plus the universal `mixed` marker. It is designed for
callers that need a single, comprehensive type list rather than inspecting one
type at a time.

The class dispatches via `is_*()` checks rather than `gettype()` + match for two
reasons:

1. `is_*()` avoids allocating the string that `gettype()` would have returned.
2. PHPStan narrows `mixed` on `is_*()` but not on a match-on-gettype(), so the
   concrete `from()` calls type-check without casts.

Each per-type inspector returns only the types the value literally satisfies.
`GetDuckTypes::from()` appends `'mixed'` centrally because `mixed` is the
duck-type marker — "any value at all" — and every PHP value satisfies it.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts
  any input and returns the full type list.

- [`::from()`](from.md) — Static factory; accepts any value and returns the full
  type list.

**Siblings:**

- [`GetStringTypes`](../GetStringTypes/README.md) — string-based type inspection
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — integer-based type inspection
- [`GetFloatTypes`](../GetFloatTypes/README.md) — float-based type inspection
- [`GetObjectTypes`](../GetObjectTypes/README.md) — object-based type inspection

## Methods

- [`->__invoke()`](__invoke.md) — Invokable; accepts any input, returns the full type list
- [`::from()`](from.md) — Static factory; accepts any value, returns the full type list

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetDuckTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ->__invoke() and ::from() return the same result
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetDuckTypes.php:52`](../src/TypeInspectors/GetDuckTypes.php#L52)

## Changelog

_No tagged releases yet._

## See Also

- [`GetPrintableType`](../GetPrintableType/README.md) — returns a human-readable descriptor string
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance
- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string

## Issues

- [Open issues mentioning `GetDuckTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetDuckTypes%22)
- [Closed issues mentioning `GetDuckTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetDuckTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetDuckTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
