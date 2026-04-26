# GetNamespace

Extracts the namespace portion from a fully-qualified class name or object.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetNamespace
{
    /**
     * what namespace does a class live within?
     */
    public static function from(string|object $item): string;
}
```

## Description

`GetNamespace` extracts the namespace portion from a fully-qualified class name
or object instance. It returns everything up to (but not including) the final
backslash separator in a class-string, or an empty string if the class lives in
the global namespace.

The class provides one entry point:

- [`::from()`](from.md) — accepts a class-string or object instance, returns the
  namespace string (empty for global-namespace classes).

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance

## Methods

- [`::from()`](from.md) — Static factory; accepts a class-string or object, returns the namespace string

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetNamespace
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes only ::from() as a public method
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns string
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetNamespace.php:60`](../src/TypeInspectors/GetNamespace.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassTypes`](../GetClassTypes/README.md) — returns the full type surface for a class-string
- [`GetObjectTypes`](../GetObjectTypes/README.md) — returns the full type surface for an object instance

## Issues

- [Open issues mentioning `GetNamespace`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNamespace%22)
- [Closed issues mentioning `GetNamespace`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNamespace%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNamespace%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
