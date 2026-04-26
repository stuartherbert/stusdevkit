# GetStringTypes

Returns a full list of PHP types that a string value might satisfy — including
pseudo-types like `callable`, `numeric`, and `int`/`float`.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Stringable;

class GetStringTypes
{
    /**
     * @param  mixed $input
     *         the item to be inspected
     * @return array<string,string>
     *         the list of PHP types that `$input` can satisfy
     *
     *         returns an empty list if `$input` is not a string
     *         or not Stringable
     */
    public function __invoke(mixed $input): array;

    /**
     * @param  string $item
     *         the item to examine
     * @return array<string,string>
     *         a list of type(s) that this item can be
     */
    public static function from(string $item): array;
}
```

## Description

`GetStringTypes` returns a full list of PHP types that a string value might
satisfy — including pseudo-types like `callable`, `numeric`, and `int`/`float`.
It is designed for callers that need to understand the full type surface of a
string value, not just its raw `gettype()` result.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts
  any input, coerces `Stringable` objects to strings, and returns the type list.

- [`::from()`](from.md) — Static factory; accepts a string directly and returns
  the type list.

The method dispatches through three branches:

1. **Callable strings** — if `is_callable($item)` is true, adds `'callable'`.
2. **Numeric strings** — delegates to [`GetNumericTypes::from()`](../GetNumericTypes/README.md)
   which adds `'numeric'`, and optionally `'int'` or `'float'`.
3. **Basic string** — always adds `'string'`.

The universal `'mixed'` type hint is deliberately **not** included here.
As documented in [`GetIntegerTypes::from()`](../GetIntegerTypes/README.md), `mixed`
is a duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md), not
by per-type inspectors.

**Siblings:**

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the union of all per-type inspectors plus `mixed`
- [`GetNumericTypes`](../GetNumericTypes/README.md) — returns types for values that `is_numeric()` accepts

## Methods

- [`->__invoke()`](__invoke.md) — Invokable; accepts any input, coerces `Stringable`, returns the type list
- [`::from()`](from.md) — Static factory; accepts a string, returns the type list

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetStringTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "int"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "float"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "true"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "false"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "null"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "array"
 ✔ ->__invoke() returns empty array for a non-string, non-Stringable input with data set "object without __toString"
 ✔ ->__invoke() coerces a Stringable object and returns the expected type list
 ✔ ::from() returns just string for a plain string with data set "empty"
 ✔ ::from() returns just string for a plain string with data set "single word"
 ✔ ::from() returns just string for a plain string with data set "with spaces"
 ✔ ::from() returns callable and string for a callable string
 ✔ ::from() returns numeric, int, and string for an integer-shaped numeric string
 ✔ ::from() returns numeric, float, and string for a float-shaped numeric string
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetStringTypes.php:61`](../src/TypeInspectors/GetStringTypes.php#L61)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the union of all per-type inspectors plus `mixed`
- [`GetNumericTypes`](../GetNumericTypes/README.md) — returns types for values that `is_numeric()` accepts
- [`GetPrintableType`](../GetPrintableType/README.md) — returns a human-readable descriptor string

## Issues

- [Open issues mentioning `GetStringTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetStringTypes%22)
- [Closed issues mentioning `GetStringTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetStringTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetStringTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
