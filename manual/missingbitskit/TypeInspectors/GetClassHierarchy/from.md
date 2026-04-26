# GetClassHierarchy::from()

> `public static function from(class-string $className): array`

Static factory; accepts a class-string and returns its full parent chain including itself.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetClassHierarchy
{
    /**
     * @param class-string $className
     *   the item to examine
     *
     * @return array<class-string,class-string>
     *         the class's inheritance hierarchy
     */
    public static function from(string $className): array
}
```

## Description

`from()` accepts a PHP class or interface name and returns an associative array mapping each entry in its parent chain to itself. This is the fast path for callers that already know the input is a valid class-string — it skips the [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php) / [`interface_exists()`](https://www.php.net/manual/en/function.interface-exists.php) guard that [`->__invoke()`](__invoke.md) performs.

The return map includes:

- The class itself as the first entry
- Every parent class in order from immediate parent to root ancestor (e.g. for a 3-deep hierarchy `Child → Parent → Grandparent`, all three appear)

Each entry maps the FQCN to itself (`$name => $name`), producing an `array<class-string, class-string>` shape. The order matches what PHP's [`class_parents()`](https://www.php.net/manual/en/function.class-parents.php) reports.

**Siblings:**

- [`GetClassInterfaces`](../GetClassInterfaces/README.md) — returns implemented interfaces instead of parent classes
- [`GetClassTraits`](../GetClassTraits/README.md) — returns traits used by a class
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level type inspection

## Parameters

**`$className`** (`class-string`)

The fully qualified name of the class or interface to examine. Unlike [`->__invoke()`](__invoke.md), this method does not validate the input — it expects a valid class-string and delegates directly to `class_parents()`.

## Return Values

Returns an associative array mapping type names to their hint spelling:

- For any class, returns entries for the class itself plus every parent in its inheritance chain
- The first entry is always the class itself (keyed and valued by its FQCN)
- Subsequent entries are parent classes from immediate parent up to root ancestor

The shape is `array<class-string, class-string>`. The order matches what PHP's [`class_parents()`](https://www.php.net/manual/en/function.class-parents.php) reports.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns only the class itself when it has no parents
 ✔ ::from() returns class plus its parent for a 2-deep hierarchy
 ✔ ::from() returns the full chain for a 3-deep hierarchy
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassHierarchy.php:75`](../src/TypeInspectors/GetClassHierarchy.php#L75)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetClassInterfaces`](../GetClassInterfaces/README.md) — returns implemented interfaces instead of parent classes
- [`GetClassTraits`](../GetClassTraits/README.md) — returns traits used by a class

## Issues

- [Open issues mentioning `GetClassHierarchy::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassHierarchy%3A%3Afrom()%22)
- [Closed issues mentioning `GetClassHierarchy::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassHierarchy%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassHierarchy%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
