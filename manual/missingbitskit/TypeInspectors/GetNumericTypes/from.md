# GetNumericTypes::from()

> `public static function from(int|float|string $item): array`

Static factory; accepts an int, float, or string and returns its type map.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetNumericTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetNumericTypes
{
    /**
     * @param  int|float|string $item
     *         the item to examine
     * @return array<string,string>
     *         a map of the types that $item satisfies
     */
    public static function from(int|float|string $item): array
}
```

## Description

`from()` accepts a PHP integer, float, or string value and returns an
associative array mapping each type hint it satisfies to its spelling. This
is the fast path for callers that already know the input is numeric — it
skips the [`is_numeric()`](https://www.php.net/manual/en/function.is-numeric.php)
guard that [`->__invoke()`](__invoke.md) performs.

The method first checks whether the input is numeric via `is_numeric()`. If
not, it returns an empty array. For valid numeric input, the return map
always includes:

- `'numeric'` — the value satisfies PHP's `numeric` pseudo-type
- `'int'` or `'float'` — the numeric type of the value

When the original input was a string, an additional `'string'` entry is
included. This carries the fact that the input was a string rather than a
coerced int or float — callers can distinguish `'123'` (string) from `123`
(int) even though both produce the same numeric result.

The universal `'mixed'` type hint is deliberately **not** included here.
As documented in [`GetIntegerTypes::from()`](../GetIntegerTypes/README.md),
`mixed` is a duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

Each entry maps the type hint name to itself (`$type => $type`), producing
an `array<string, string>` shape. The order is `'numeric'` first, then the
numeric type (`'int'` or `'float'`), and finally `'string'` if the original
input was a string.

**Siblings:**

- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats

## Parameters

**`$item`** (`int|float|string`)

The numeric value to examine. Unlike [`->__invoke()`](__invoke.md), this
method does not validate the input — it expects a valid `int`, `float`, or
`string`. If the value is not numeric, the method returns an empty array.

## Return Values

Returns an associative array mapping type hint names to their spelling:

- For a non-numeric string, returns an empty array `[]`
- For an integer (int or numeric int string), returns:
  - `'numeric'` — the value satisfies PHP's `numeric` pseudo-type
  - `'int'` — the value is an integer
  - `'string'` — only when the original input was a string (e.g. `'123'`)
- For a float (float or numeric float string), returns:
  - `'numeric'` — the value satisfies PHP's `numeric` pseudo-type
  - `'float'` — the value is a float
  - `'string'` — only when the original input was a string (e.g. `'45.6'`)

The shape is `array<string, string>`. The order is `'numeric'` first, then
the numeric type, then `'string'` if applicable.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetNumericTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns numeric and int for an integer with data set "zero"
 ✔ ::from() returns numeric and int for an integer with data set "positive"
 ✔ ::from() returns numeric and int for an integer with data set "negative"
 ✔ ::from() returns numeric and int for an integer with data set "max"
 ✔ ::from() returns numeric and float for a float with data set "zero"
 ✔ ::from() returns numeric and float for a float with data set "positive"
 ✔ ::from() returns numeric and float for a float with data set "negative"
 ✔ ::from() returns numeric, int, and string for an integer-shaped numeric string
 ✔ ::from() returns numeric, float, and string for a float-shaped numeric string
 ✔ ::from() returns empty array for a non-numeric string with data set "empty"
 ✔ ::from() returns empty array for a non-numeric string with data set "plain text"
 ✔ ::from() returns empty array for a non-numeric string with data set "alphanumeric"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetNumericTypes.php:78`](../src/TypeInspectors/GetNumericTypes.php#L78)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates and coerces Stringable before delegating to `::from()`
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetStringTypes`](../GetStringTypes/README.md) — includes `GetNumericTypes::from()` for numeric strings

## Issues

- [Open issues mentioning `GetNumericTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNumericTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetNumericTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNumericTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNumericTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
