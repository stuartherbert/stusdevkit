# GetStringTypes::from()

> `public static function from(string $item): array`

Returns a full list of PHP types that a string value might satisfy.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetStringTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetStringTypes
{
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

`from()` accepts a string and returns a full list of PHP types that the value
might satisfy — including pseudo-types like `callable`, `numeric`, and `int`/`float`.

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

## Parameters

**`$item`** (`string`)

The item to examine. Accepts any PHP string — plain, callable, or numeric.

## Return Values

Returns an associative array mapping type names to their spelling — the union
of callable (if applicable), numeric types (if applicable), and `'string'`. The
shape is `array<string, string>`.

For a plain string (e.g. `'hello'`):

- `'string' => 'string'`

For a callable string (e.g. `'strlen'`):

- `'callable' => 'callable'`, `'string' => 'string'`

For an integer-shaped numeric string (e.g. `'123'`):

- `'numeric' => 'numeric'`, `'int' => 'int'`, `'string' => 'string'`

For a float-shaped numeric string (e.g. `'45.6'`):

- `'numeric' => 'numeric'`, `'float' => 'float'`, `'string' => 'string'`

The universal `'mixed'` type hint is deliberately **not** included here.
It is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

```php
// Plain string
GetStringTypes::from('hello');
// Returns: ['string' => 'string']

// Callable string
GetStringTypes::from('strlen');
// Returns: ['callable' => 'callable', 'string' => 'string']

// Numeric string (int)
GetStringTypes::from('123');
// Returns: ['numeric' => 'numeric', 'int' => 'int', 'string' => 'string']

// Numeric string (float)
GetStringTypes::from('45.6');
// Returns: ['numeric' => 'numeric', 'float' => 'float', 'string' => 'string']
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetStringTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns just string for a plain string with data set "empty"
 ✔ ::from() returns just string for a plain string with data set "single word"
 ✔ ::from() returns just string for a plain string with data set "with spaces"
 ✔ ::from() returns callable and string for a callable string
 ✔ ::from() returns numeric, int, and string for an integer-shaped numeric string
 ✔ ::from() returns numeric, float, and string for a float-shaped numeric string
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetStringTypes.php:90`](../src/TypeInspectors/GetStringTypes.php#L90)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the union of all per-type inspectors plus `mixed`
- [`GetNumericTypes`](../GetNumericTypes/README.md) — returns types for values that `is_numeric()` accepts
- [`GetPrintableType`](../GetPrintableType/README.md) — returns a human-readable descriptor string

## Issues

- [Open issues mentioning `GetStringTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetStringTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetStringTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetStringTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetStringTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
