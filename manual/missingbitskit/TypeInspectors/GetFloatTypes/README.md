# GetFloatTypes

Returns the set of PHP type hints that a float value satisfies.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetFloatTypes
{
    /**
     * do we have a PHP float? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * we have a PHP float. Return a map of types that it can match.
     */
    public static function from(float $item): array;
}
```

## Description

`GetFloatTypes` maps a PHP float value to the set of type hints it
satisfies. It is designed for use in type-inspection pipelines where a
caller needs to know which declared types a given float value could match.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not a PHP float. Uses [`is_float()`](https://www.php.net/manual/en/function.is-float.php)
  to validate the type before delegating to `::from()`. No loose-typing
  coercion is applied — an integer or numeric string will not be treated
  as a float.
- [`::from()`](from.md) — accepts a `float` value directly and returns
  its type map. This is the fast path when the caller already knows the
  input is a float.

The return map uses PHP type hint names as both keys and values, in the
order they are listed. For a float value, the result is always:

- `'numeric'` — floats satisfy PHP's `numeric` pseudo-type
- `'float'` — the value is a float

The universal `'mixed'` type hint is deliberately **not** included here;
it is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-float values
- [`::from()`](from.md) — Static factory; accepts a float and returns its type map

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetFloatTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetFloatTypes.php:54`](../src/TypeInspectors/GetFloatTypes.php#L54)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings

## Issues

- [Open issues mentioning `GetFloatTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetFloatTypes%22)
- [Closed issues mentioning `GetFloatTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetFloatTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetFloatTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
