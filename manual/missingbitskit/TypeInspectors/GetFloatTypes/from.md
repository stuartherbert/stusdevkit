# GetFloatTypes::from()

> `public static function from(float $item): array`

Static factory; accepts a float and returns its type map.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetFloatTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetFloatTypes
{
    /**
     * @param  float $item
     *         the item to examine
     * @return array<string,string>
     *         a map of matching PHP pseudo-types
     */
    public static function from(float $item): array
}
```

## Description

`from()` accepts a PHP float value and returns an associative array mapping
each type hint it satisfies to its spelling. This is the fast path for callers
that already know the input is a float — it skips the [`is_float()`](https://www.php.net/manual/en/function.is-float.php)
guard that [`->__invoke()`](__invoke.md) performs.

The return map is constant regardless of the float value — every float
satisfies exactly two type hints:

- `'numeric'` — floats satisfy PHP's `numeric` pseudo-type
- `'float'` — the value is a float

The universal `'mixed'` type hint is deliberately **not** included here.
As documented in [`GetIntegerTypes::from()`](../GetIntegerTypes/README.md),
`mixed` is a duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

Each entry maps the type hint name to itself (`$type => $type`), producing
an `array<string, string>` shape. The order matches the canonical type hint
listing: `'numeric'` first, then `'float'`.

**Siblings:**

- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings

## Parameters

**`$item`** (`float`)

The float value to examine. Unlike [`->__invoke()`](__invoke.md), this
method does not validate the input — it expects a valid `float` and returns
the constant type map.

## Return Values

Returns an associative array mapping type hint names to their spelling:

- Always returns `['numeric' => 'numeric', 'float' => 'float']` regardless
  of the specific float value (zero, positive, negative, very small, or very
  large)

The shape is `array<string, string>`. The order is `'numeric'` first, then
`'float'`.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetFloatTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns numeric and float for any float with data set "zero"
 ✔ ::from() returns numeric and float for any float with data set "positive"
 ✔ ::from() returns numeric and float for any float with data set "negative"
 ✔ ::from() returns numeric and float for any float with data set "very small"
 ✔ ::from() returns numeric and float for any float with data set "very large"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetFloatTypes.php:71`](../src/TypeInspectors/GetFloatTypes.php#L71)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers

## Issues

- [Open issues mentioning `GetFloatTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetFloatTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetFloatTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetFloatTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetFloatTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
