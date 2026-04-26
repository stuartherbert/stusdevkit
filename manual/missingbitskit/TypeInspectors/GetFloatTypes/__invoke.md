# GetFloatTypes::__invoke()

> `public function __invoke(mixed $input): array`

Call the class as an invokable object; accepts any input and returns empty
array for non-float values.

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
     * @param  mixed $input
     *         the value to examine
     *
     *   returns an empty list if `$input` is not a float
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
}
```

## Description

`__invoke()` accepts any value and returns the set of type hints that a PHP
float satisfies — but only when the input is strictly a `float`. For any
other input, it returns an empty array.

The method performs a single validation check before delegating to
[`::from()`](from.md):

1. The input must be a `float` (checked via [`is_float()`](https://www.php.net/manual/en/function.is-float.php)).

If the check fails, the method returns `[]` immediately without further
processing. No loose-typing coercion is applied — an integer, a numeric
string, or any other value will not be treated as a float.

**Siblings:**

- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings

## Parameters

**`$input`** (`mixed`)

The value to examine. Must be a PHP `float`. If it is not strictly a
`float`, the method returns an empty array without further processing.

## Return Values

Returns an associative array mapping type hint names to their spelling.
When `$input` is not a `float`, returns an empty array `[]`. When it is
a valid float, returns:

- `'numeric'` — floats satisfy PHP's `numeric` pseudo-type
- `'float'` — the value is a float

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
StusDevKit\MissingBitsKit\TypeInspectors\GetFloatTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for non-float input with data set "int"
 ✔ ->__invoke() returns empty array for non-float input with data set "numeric string"
 ✔ ->__invoke() returns empty array for non-float input with data set "plain string"
 ✔ ->__invoke() returns empty array for non-float input with data set "true"
 ✔ ->__invoke() returns empty array for non-float input with data set "false"
 ✔ ->__invoke() returns empty array for non-float input with data set "null"
 ✔ ->__invoke() returns empty array for non-float input with data set "array"
 ✔ ->__invoke() returns empty array for non-float input with data set "object"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetFloatTypes.php:54`](../src/TypeInspectors/GetFloatTypes.php#L54)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts a float directly
- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetFloatTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetFloatTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetFloatTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetFloatTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetFloatTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
