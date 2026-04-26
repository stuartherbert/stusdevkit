# GetBooleanTypes::__invoke()

> `public function __invoke(mixed $input): array`

Call the class as an invokable object; accepts any input and returns empty array for non-boolean values.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetBooleanTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetBooleanTypes
{
    /**
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
}
```

## Description

`__invoke()` accepts any value and returns the type hints that a PHP
boolean satisfies — but only when the input is strictly a `bool`. For
any non-boolean input, it returns an empty array.

This method deliberately does **not** apply PHP's loose boolean
coercion rules (e.g. treating `0`, `''`, or `null` as `false`). It
uses [`is_bool()`](https://www.php.net/manual/en/function.is-bool.php)
to enforce strict type checking, then delegates to
[`::from()`](from.md) for the actual type mapping.

**Siblings:**

- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings
- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats

## Parameters

**`$input`** (`mixed`)

The value to examine. If it is not strictly a PHP `bool`, the method
returns an empty array without further processing. No loose boolean
coercion is applied — values like `0`, `''`, or `null` are treated as
non-boolean and produce an empty result.

## Return Values

Returns an associative array mapping type names to their hint spelling.
When `$input` is not a `bool`, returns an empty array `[]`. When it is
a `bool`, returns:

- For `true`: `['true' => 'true', 'bool' => 'bool']`
- For `false`: `['false' => 'false', 'bool' => 'bool']`

The `'mixed'` duck-type marker is never included — it belongs to
[`GetDuckTypes`](../../GetDuckTypes/README.md).

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetBooleanTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for non-boolean input with data set "int"
 ✔ ->__invoke() returns empty array for non-boolean input with data set "positive int"
 ✔ ->__invoke() returns empty array for non-boolean input with data set "float"
 ✔ ->__invoke() returns empty array for non-boolean input with data set "string "true""
 ✔ ->__invoke() returns empty array for non-boolean input with data set "empty string"
 ✔ ->__invoke() returns empty array for non-boolean input with data set "null"
 ✔ ->__invoke() returns empty array for non-boolean input with data set "array"
 ✔ ->__invoke() returns empty array for non-boolean input with data set "object"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetBooleanTypes.php:64`](../src/TypeInspectors/GetBooleanTypes.php#L64)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts a PHP bool directly
- [`GetDuckTypes`](../../GetDuckTypes/README.md) — handles duck-typing including the `mixed` type marker
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers

## Issues

- [Open issues mentioning `GetBooleanTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetBooleanTypes%3A%3A__invoke%28%29%22)
- [Closed issues mentioning `GetBooleanTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetBooleanTypes%3A%3A__invoke%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetBooleanTypes%3A%3A__invoke%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
