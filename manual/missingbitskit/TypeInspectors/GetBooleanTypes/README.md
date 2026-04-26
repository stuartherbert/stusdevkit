# GetBooleanTypes

Returns the complete set of type hints that a PHP boolean satisfies.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetBooleanTypes
{
    /**
     * do we have a PHP bool? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * we have a PHP bool. Return a map of types that it can match.
     */
    public static function from(bool $item): array;
}
```

## Description

`GetBooleanTypes` maps a PHP boolean value to the set of type hints it
satisfies. It is designed for use in type-inspection pipelines where a
caller needs to know which declared parameter types a given runtime
value would pass.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not strictly a PHP `bool`.
- [`::from()`](from.md) — accepts a `bool` directly and returns its
  type list. This is the fast path when the caller already knows the
  value is boolean.

The return map always includes `'bool' => 'bool'`, plus either
`'true' => 'true'` or `'false' => 'false'` depending on the runtime
value. The duck-type marker `'mixed'` is deliberately excluded — it
is owned by [`GetDuckTypes`](../GetDuckTypes/README.md), not by
per-type inspectors.

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-boolean values
- [`::from()`](from.md) — Static factory; accepts a PHP bool and returns its type list

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetBooleanTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetBooleanTypes.php:47`](../src/TypeInspectors/GetBooleanTypes.php#L47)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — handles duck-typing including the `mixed` type marker
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — maps PHP integers to their type hints
- [`GetStringTypes`](../GetStringTypes/README.md) — maps PHP strings to their type hints

## Issues

- [Open issues mentioning `GetBooleanTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetBooleanTypes%22)
- [Closed issues mentioning `GetBooleanTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetBooleanTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetBooleanTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
