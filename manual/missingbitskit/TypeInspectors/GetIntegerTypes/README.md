# GetIntegerTypes

Returns the set of PHP type hints that an integer value satisfies.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetIntegerTypes
{
    /**
     * do we have a PHP int? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * we have a PHP int. Return a map of types that it can match.
     */
    public static function from(int $item): array;
}
```

## Description

`GetIntegerTypes` maps a PHP integer value to the set of type hints it
satisfies. It is designed for use in type-inspection pipelines where a
caller needs to know which declared types a given integer value could match.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not a PHP integer. Uses [`is_int()`](https://www.php.net/manual/en/function.is-int.php)
  to validate the type before delegating to `::from()`. No loose-typing
  coercion is applied — a float, numeric string, or any other value will
  not be treated as an integer.
- [`::from()`](from.md) — accepts an `int` value directly and returns
  its type map. This is the fast path when the caller already knows the
  input is an integer.

The return map uses PHP type hint names as both keys and values, in the
order they are listed. For an integer value, the result is always:

- `'numeric'` — integers satisfy PHP's `numeric` pseudo-type
- `'int'` — the value is an integer

The universal `'mixed'` type hint is deliberately **not** included here;
it is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-integer values
- [`::from()`](from.md) — Static factory; accepts an integer and returns its type map

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetIntegerTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetIntegerTypes.php:60`](../src/TypeInspectors/GetIntegerTypes.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetFloatTypes`](../GetFloatTypes/README.md) — same pattern for PHP floats
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings

## Issues

- [Open issues mentioning `GetIntegerTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetIntegerTypes%22)
- [Closed issues mentioning `GetIntegerTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetIntegerTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetIntegerTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
