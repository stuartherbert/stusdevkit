# GetBooleanTypes::from()

> `public static function from(bool $item): array`

Static factory; accepts a PHP bool and returns its type list.

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
    public static function from(bool $item): array
}
```

## Description

`from()` accepts a PHP `bool` value and returns an associative array
mapping type names to their hint spelling. This is the fast path for
callers that already know the input is boolean — it skips the
[`is_bool()`](https://www.php.net/manual/en/function.is-bool.php) guard
that [`->__invoke()`](__invoke.md) performs.

The return map always includes `'bool' => 'bool'`, plus either
`'true' => 'true'` or `'false' => 'false'` depending on the runtime
value:

- `from(true)` returns `['true' => 'true', 'bool' => 'bool']`
- `from(false)` returns `['false' => 'false', 'bool' => 'bool']`

The literal types `'true'` and `'false'` are PHP 8.2+ standalone type
hints. The generic `'bool'` hint covers both values.

**Deliberate design choice:** the duck-type marker `'mixed'` is never
included in the return map. `GetDuckTypes` appends it centrally when a
caller asks the duck-type question, because `'mixed'` applies to every
PHP value — not just booleans — and belongs to the duck-type inspector,
not per-type inspectors.

## Parameters

**`$item`** (`bool`)

The boolean value to examine. Unlike [`->__invoke()`](__invoke.md), this
method does not accept arbitrary input — it requires a strict `bool`.

## Return Values

Returns an associative array mapping type names to their hint spelling:

- For `true`: `['true' => 'true', 'bool' => 'bool']`
- For `false`: `['false' => 'false', 'bool' => 'bool']`

The keys are the PHP type names; the values are the exact strings that
can be used as parameter type hints. The `'mixed'` duck-type marker is
not included — it belongs to [`GetDuckTypes`](../../GetDuckTypes/README.md).

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetBooleanTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from(true) returns true and bool
 ✔ ::from(false) returns false and bool
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetBooleanTypes.php:76`](../src/TypeInspectors/GetBooleanTypes.php#L76)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; returns empty array for non-boolean values
- [`GetDuckTypes`](../../GetDuckTypes/README.md) — handles duck-typing including the `mixed` type marker
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers

## Issues

- [Open issues mentioning `GetBooleanTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetBooleanTypes%3A%3Afrom%28%29%22)
- [Closed issues mentioning `GetBooleanTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetBooleanTypes%3A%3Afrom%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetBooleanTypes%3A%3Afrom%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
