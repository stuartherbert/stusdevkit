# GetNumericTypes::__invoke()

> `public function __invoke(mixed $input): array`

Call the class as an invokable object; accepts any input and returns empty
array for non-numeric values.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetNumericTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Stringable;

class GetNumericTypes
{
    /**
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
}
```

## Description

`__invoke()` accepts any value and returns the set of type hints that a PHP
numeric value satisfies — but only when the input is numeric. For any other
input, it returns an empty array.

The method performs two steps before delegating to [`::from()`](from.md):

1. If the input is a `Stringable` object, it is coerced to a string via
   `(string)` casting. This allows `Stringable` objects whose string
   representation is numeric to be inspected.
2. The (possibly coerced) value is checked with [`is_numeric()`](https://www.php.net/manual/en/function.is-numeric.php).

If the check fails, the method returns `[]` immediately without further
processing. No loose-typing coercion is applied to non-stringable values —
an integer, a float, or any other value will not be treated as numeric unless
it already satisfies `is_numeric()`.

**Siblings:**

- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats

## Parameters

_None._

## Return Values

Returns an associative array mapping type hint names to their spelling.
When `$input` is not numeric, returns an empty array `[]`. When it is a
valid numeric value, returns:

- `'numeric'` — the value satisfies PHP's `numeric` pseudo-type
- `'int'` or `'float'` — the numeric type of the value (after any
  `Stringable` coercion)

The shape is `array<string, string>`. The order matches the canonical
type hint listing.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetNumericTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for non-numeric input with data set "plain string"
 ✔ ->__invoke() returns empty array for non-numeric input with data set "empty string"
 ✔ ->__invoke() returns empty array for non-numeric input with data set "true"
 ✔ ->__invoke() returns empty array for non-numeric input with data set "false"
 ✔ ->__invoke() returns empty array for non-numeric input with data set "null"
 ✔ ->__invoke() returns empty array for non-numeric input with data set "array"
 ✔ ->__invoke() returns empty array for non-numeric input with data set "object"
 ✔ ->__invoke() returns empty array for Stringable whose string is non-numeric
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetNumericTypes.php:60`](../src/TypeInspectors/GetNumericTypes.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts an int, float, or string directly
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetNumericTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNumericTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetNumericTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNumericTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNumericTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
