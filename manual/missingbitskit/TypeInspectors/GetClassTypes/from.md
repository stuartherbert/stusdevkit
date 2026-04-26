# GetClassTypes::from()

> `public static from(class-string $className): array`

Returns the complete set of types that a named PHP class, interface, or trait satisfies.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetClassTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassTraits;

class GetClassTypes
{
    /**
     * get a full list of types that a class can satisfy
     */
    public static function from(string $className): array
}
```

## Description

Returns the complete set of type hints that a named PHP class, interface, or trait satisfies. The return value includes:

- The original name itself (the class/interface/trait is always an instance of itself)
- All parent classes in the inheritance hierarchy (via `GetClassHierarchy::from()`)
- All implemented interfaces (via `GetClassInterfaces::from()`)
- All used traits (via `GetClassTraits::from()`)
- The universal `object` type hint (every class resolves to `object`)
- Optionally, a `callable` entry if the class has a public `__invoke()` method

## Parameters

**`$className`** (`class-string`)

A fully qualified class, interface, or trait name (FQCN) string. The string must refer to an existing class, interface, or trait in the PHP runtime — non-existent names result in an empty array return.

## Return Values

Returns an associative array where keys and values are identical — each entry represents one type hint that the named element satisfies. The shape is `array<string, string>`. Keys include:
- The original class/interface/trait name (as a fully qualified string)
- All parent classes in the inheritance hierarchy
- All implemented interfaces
- All used traits
- `object` — the universal object type hint for every class

If `$className` does not refer to an existing class, interface, or trait, an empty array is returned.

## Errors/Exceptions

_None._

## Here Be Dragons

None documented here. (See TODO below.)

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassTypes
 ✔ ::from() returns class and object for a simple class
 ✔ ::from() returns class, parent, interface, trait, and object for a class with all three
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassTypes.php:74`](../src/TypeInspectors/GetClassTypes.php#L74)

## Changelog

_No tagged releases yet._

## TODO

This method page shares the same source docblock gaps as the class page (see [README](README.md) → **TODO** section):
- `@param $className` description incomplete — add prose explaining it accepts a class/interface/trait FQCN string
- Return value prose missing for `array<string, string>` shape
- "Here Be Dragons" section needs content from source inline comments

## See Also

- [`->__invoke()`](README.md) — invokable wrapper; accepts any input, delegates to `::from()` when valid
- `GetClassHierarchy::from()` — returns all parent classes (manual page not yet generated)
- `GetClassInterfaces::from()` — returns all implemented interfaces (manual page not yet generated)
- `GetClassTraits::from()` — returns all used traits (manual page not yet generated)

## Issues

- [Open issues mentioning `GetClassTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetClassTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
