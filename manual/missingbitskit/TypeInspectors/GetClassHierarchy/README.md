# GetClassHierarchy

Returns the complete set of parent classes in a PHP class's inheritance chain.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone class._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetClassHierarchy
{
    /**
     * do we have a PHP class name? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * get a full list of a class / interface and its parent classes
     */
    public static function from(string $className): array;
}
```

## Description

`GetClassHierarchy` maps a PHP class or interface name to the set of
parent classes in its inheritance chain. It is designed for use in
type-inspection pipelines where a caller needs to know which declared
class types a given value could satisfy through inheritance.

The class provides two entry points:

- [`->__invoke()`](__invoke.md) — accepts any input; returns an empty
  array when the value is not a string naming a known class or
  interface. Uses [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php)
  and [`interface_exists()`](https://www.php.net/manual/en/function.interface-exists.php)
  to validate the name before delegating to `::from()`.
- [`::from()`](from.md) — accepts a class-string directly and returns
  its full parent chain. This is the fast path when the caller already
  knows the input is a valid class or interface name.

The return map includes the class itself as the first entry, followed
by every ancestor in order from immediate parent to root (e.g. for a
3-deep hierarchy `Child → Parent → Grandparent`, all three appear).
Each entry maps the FQCN to itself (`$name => $name`), producing an
`array<class-string, class-string>` shape. The order matches what PHP's
[`class_parents()`](https://www.php.net/manual/en/function.class-parents.php) reports.

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-class-string values
- [`::from()`](from.md) — Static factory; accepts a class-string and returns its full parent chain including itself

## Here Be Dragons

None yet.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassHierarchy.php:47`](../src/TypeInspectors/GetClassHierarchy.php#L47)

## Changelog

_No tagged releases yet._

## See Also

- [`GetClassInterfaces`](../GetClassInterfaces/README.md) — returns implemented interfaces instead of parent classes
- [`GetClassTraits`](../GetClassTraits/README.md) — returns traits used by a class
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level type inspection

## Issues

- [Open issues mentioning `GetClassHierarchy`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassHierarchy%22)
- [Closed issues mentioning `GetClassHierarchy`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassHierarchy%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassHierarchy%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
