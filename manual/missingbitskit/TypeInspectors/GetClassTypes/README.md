# GetClassTypes

Standalone class.

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassTraits;

class GetClassTypes
{
    /**
     * do we have a PHP class / interface / trait name?
     */
    public function __invoke(mixed $input): array;

    /**
     * get a full list of types that a class can satisfy
     */
    public static function from(string $className): array;
}
```

## Description

Returns the complete set of type hints that a named PHP class, interface, or trait satisfies. The return value includes:

- The original name itself (the class/interface/trait is always an instance of itself)
- All parent classes in the inheritance hierarchy
- All implemented interfaces
- All used traits
- The universal `object` type hint (every class resolves to `object`)
- Optionally, a `callable` entry if the class has a public `__invoke()` method

**Since:** _Upcoming Release_

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for unknown names
- [`::from()`](from.md) — Static factory; accepts a fully qualified class/interface/trait name string

## Here Be Dragons

None yet. (See TODO below.)

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassTypes.php:47`](../src/TypeInspectors/GetClassTypes.php#L47)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassHierarchy::from()`](../GetClassHierarchy/README.md) — returns all parent classes
- [`GetClassInterfaces::from()`](../GetClassInterfaces/README.md) — returns all implemented interfaces
- [`GetClassTraits::from()`](../GetClassTraits/README.md) — returns all used traits
- `GetObjectTypes` — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetClassTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassTypes%22)
- [Closed issues mentioning `GetClassTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
