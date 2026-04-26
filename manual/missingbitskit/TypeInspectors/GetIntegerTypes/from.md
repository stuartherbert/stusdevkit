# GetIntegerTypes::from()

> `public static function from(int $item): array`

Static factory; accepts an integer and returns its type map.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetIntegerTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetIntegerTypes
{
    /**
     * @param  int $item
     *         the item to examine
     * @return array<string,string>
     *         a map of matching PHP types
     */
    public static function from(int $item): array
}
```

## Description

`from()` accepts a PHP integer value and returns an associative array mapping
each type hint it satisfies to its spelling. This is the fast path for callers
that already know the input is an integer — it skips the [`is_int()`](https://www.php.net/manual/en/function.is-int.php)
guard that [`->__invoke()`](__invoke.md) performs.

The return map is constant regardless of the integer value — every integer
satisfies exactly two type hints:

- `'numeric'` — integers satisfy PHP's `numeric` pseudo-type
- `'int'` — the value is an integer

The universal `'mixed'` type hint is deliberately **not** included here.
Every PHP value satisfies `mixed`, so it carries no useful information in a
per-type inspector's answer. [`GetDuckTypes`](../GetDuckTypes/README.md)
appends it centrally when a caller asks the duck-type question.

Each entry maps the type hint name to itself (`$type => $type`), producing
an `array<string, string>` shape. The order matches the canonical type hint
listing: `'numeric'` first, then `'int'`.

**Siblings:**

- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings

## Parameters

**`$item`** (`int`)

The integer value to examine. Unlike [`->__invoke()`](__invoke.md), this
method does not validate the input — it expects a valid `int` and returns
the constant type map.

## Return Values

Returns an associative array mapping type hint names to their spelling:

- Always returns `['numeric' => 'numeric', 'int' => 'int']` regardless
  of the specific integer value (zero, positive, negative, `PHP_INT_MAX`,
  or `PHP_INT_MIN`)

The shape is `array<string, string>`. The order is `'numeric'` first, then
`'int'`.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetIntegerTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns numeric and int for any integer with data set "zero"
 ✔ ::from() returns numeric and int for any integer with data set "positive"
 ✔ ::from() returns numeric and int for any integer with data set "negative"
 ✔ ::from() returns numeric and int for any integer with data set "max"
 ✔ ::from() returns numeric and int for any integer with data set "min"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetIntegerTypes.php:77`](../src/TypeInspectors/GetIntegerTypes.php#L77)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats

## Issues

- [Open issues mentioning `GetIntegerTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetIntegerTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetIntegerTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetIntegerTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetIntegerTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
