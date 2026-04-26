# GetNumericTypes

Returns the set of PHP type hints that a numeric value satisfies, including
the original input's shape (int, float, or string).

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Stringable;

class GetNumericTypes
{
    /**
     * do we have a PHP numeric type? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * do we have a numeric type? if so, what is it?
     */
    public static function from(int|float|string $item): array;
}
```

## Description

`GetNumericTypes` maps a numeric value to the set of type hints it
satisfies. It is designed for use in type-inspection pipelines where a
caller needs to know which declared types a given numeric value could match.

Unlike the per-type inspectors (`GetIntegerTypes`, `GetFloatTypes`), this
class handles values that satisfy PHP's `is_numeric()` check — integers,
floats, and numeric strings. It also handles `Stringable` objects by
coercing them to strings before the numeric check.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not numeric. Coerces `Stringable` objects to
  strings before checking with [`is_numeric()`](https://www.php.net/manual/en/function.is-numeric.php).
  No loose-typing coercion is applied to non-stringable values.
- [`::from()`](from.md) — accepts an `int`, `float`, or `string` directly
  and returns its type map. This is the fast path when the caller already
  knows the input is numeric.

The return map always includes `'numeric'` and one of `'int'` or `'float'`,
depending on the numeric value. When the original input was a string, an
additional `'string'` entry is included to carry the fact that the input was
a string rather than a coerced int or float.

The universal `'mixed'` type hint is deliberately **not** included here;
it is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-numeric values
- [`::from()`](from.md) — Static factory; accepts an int, float, or string and returns its type map

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetNumericTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetNumericTypes.php:60`](../src/TypeInspectors/GetNumericTypes.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats
- [`GetStringTypes`](../GetStringTypes/README.md) — includes `GetNumericTypes::from()` for numeric strings

## Issues

- [Open issues mentioning `GetNumericTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNumericTypes%22)
- [Closed issues mentioning `GetNumericTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNumericTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNumericTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
