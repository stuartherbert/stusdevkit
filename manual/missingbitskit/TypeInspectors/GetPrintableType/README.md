# GetPrintableType

Returns a human-readable descriptor string for any PHP value — class names,
callable shapes, scalar values — controlled by a bitmask of flag constants.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use Closure;

class GetPrintableType
{
    public const int FLAG_NONE = 0;
    public const int FLAG_CLASSNAME = 1;
    public const int FLAG_CALLABLE_DETAILS = 2;
    public const int FLAG_SCALAR_VALUE = 4;
    public const int FLAG_DEFAULTS = self::FLAG_CLASSNAME
        | self::FLAG_CALLABLE_DETAILS
        | self::FLAG_SCALAR_VALUE;

    /**
     * what PHP type is $item?
     */
    public static function from(
        mixed $item,
        int $options = self::FLAG_DEFAULTS
    ): string;
}
```

## Description

`GetPrintableType` maps any PHP value to a human-readable descriptor string.
It is designed for use in error messages, logging, and debugging output where a
caller needs a concise, readable type description — not the raw `gettype()`
output.

The output is controlled by passing a bitmask of `FLAG_*` constants as the
`$options` parameter. Combine flags with `|`:

```php
GetPrintableType::from($value, GetPrintableType::FLAG_CLASSNAME);
```

The class provides one entry point:

- [`::from()`](from.md) — accepts any value and an optional flag bitmask,
  returning a descriptor string. The default flags include classname detail,
  callable shape, and scalar values.

The descriptor format uses angle brackets for embedded details:

- `int<42>` — integer with value
- `bool<true>` / `bool<false>` — boolean with value
- `object<ClassName>` — object with class name
- `callable<Closure>` / `callable<strlen>` / `callable<Class::method>` —
  callable with shape detail

The universal `'mixed'` type hint is deliberately **not** included here;
it is the duck-type marker owned by [`GetDuckTypes`](../GetDuckTypes/README.md),
not by per-type inspectors.

## Methods

- [`::from()`](from.md) — Static factory; accepts any value and optional flag bitmask, returns a descriptor string

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetPrintableType
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes only ::from() as a public method
 ✔ publishes FLAG_NONE, FLAG_CLASSNAME, FLAG_CALLABLE_DETAILS, FLAG_SCALAR_VALUE, and FLAG_DEFAULTS as public constants
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetPrintableType.php:94`](../src/TypeInspectors/GetPrintableType.php#L94)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — returns the universal `mixed` type hint
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance
- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string

## Issues

- [Open issues mentioning `GetPrintableType`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetPrintableType%22)
- [Closed issues mentioning `GetPrintableType`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetPrintableType%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetPrintableType%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
