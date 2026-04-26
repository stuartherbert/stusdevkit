# GetClassInterfaces::from()

> `public static function from(string $className): array`

Static factory; accepts a class-string and returns its interface list.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetClassInterfaces
{
    /**
     * @param class-string $className
     *   the item to examine
     *
     * @return array<class-string,class-string>
     *         the list of interfaces implemented by the class
     */
    public static function from(string $className): array
}
```

## Description

`from()` accepts a PHP class or interface name and returns an associative array mapping each implemented interface to its fully qualified class name (FQCN). This is the fast path for callers that already know the input is a valid class-string — it skips the [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php) / [`interface_exists()`](https://www.php.net/manual/en/function.interface-exists.php) guard that [`->__invoke()`](__invoke.md) performs.

The return map uses `class_implements()` to enumerate all interfaces, including:

- Interfaces declared directly on the class
- Interfaces inherited from parent classes
- Interfaces transitively extended by other interfaces (e.g. if `ExtendedInterface extends BaseInterface`, both appear in the result)

Each entry maps the interface FQCN to itself (`$interface => $interface`), producing an `array<class-string, class-string>` shape. The order matches what PHP reports via `class_implements()`.

**Siblings:**

- [`GetClassHierarchy`](../GetClassHierarchy/README.md) — returns parent classes instead of interfaces
- [`GetClassTraits`](../GetClassTraits/README.md) — returns traits used by a class
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level type inspection

## Parameters

**`$className`** (`class-string`)

The fully qualified name of the class or interface to examine. Unlike [`->__invoke()`](__invoke.md), this method does not validate the input — it expects a valid class-string and delegates directly to `class_implements()`.

## Return Values

Returns an associative array mapping type names to their hint spelling:

- For a class implementing no interfaces, returns an empty array `[]`
- For a class implementing one or more interfaces, returns entries keyed by each interface's FQCN, with the FQCN as the value

The order matches what PHP's [`class_implements()`](https://www.php.net/manual/en/function.class-implements.php) reports. The shape is `array<class-string, class-string>`.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns empty array when it implements nothing
 ✔ ::from() returns a directly implemented interface
 ✔ ::from() returns an interface inherited from a parent class
 ✔ ::from() walks a 2-deep interface hierarchy
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassInterfaces.php:76`](../src/TypeInspectors/GetClassInterfaces.php#L76)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input; validates before delegating to `::from()`
- [`GetClassHierarchy`](../GetClassHierarchy/README.md) — returns parent classes instead of interfaces
- [`GetClassTraits`](../GetClassTraits/README.md) — returns traits used by a class

## Issues

- [Open issues mentioning `GetClassInterfaces::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassInterfaces%3A%3Afrom()%22)
- [Closed issues mentioning `GetClassInterfaces::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassInterfaces%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassInterfaces%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
