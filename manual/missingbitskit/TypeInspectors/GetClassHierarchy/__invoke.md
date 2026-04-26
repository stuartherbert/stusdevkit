# GetClassHierarchy::__invoke()

> `public function __invoke(mixed $input): array`

Call the class as an invokable object; accepts any input and returns empty array for non-class-string values.

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
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
}
```

## Description

`__invoke()` accepts any value and returns the list of parent classes in a PHP class's inheritance chain — but only when the input is a string naming a known class or interface. For any other input, it returns an empty array.

The method performs two validation checks before delegating to [`::from()`](from.md):

1. The input must be a `string` (checked via `is_string()`).
2. The string must name an existing class or interface (checked via [`class_exists()`](https://www.php.net/manual/en/function.class-exists.php) and [`interface_exists()`](https://www.php.net/manual/en/function.interface-exists.php)).

If either check fails, the method returns `[]` immediately without further processing.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — broader type inspection for class/interface/trait names
- [`GetIntegerTypes`](../GetIntegerTypes/README.md) — same pattern for PHP integers
- [`GetStringTypes`](../GetStringTypes/README.md) — same pattern for PHP strings

## Parameters

**`$input`** (`mixed`)

The value to examine. Must be a string naming an existing class or interface. If it is not a `string`, or if the string does not name a known class or interface (including non-existent class names and empty strings), the method returns an empty array without further processing.

## Return Values

Returns an associative array mapping type names to their hint spelling.
When `$input` is not a valid class-string, returns an empty array `[]`. When it is a valid class or interface name, returns:

- The class itself as the first entry (keyed and valued by its FQCN)
- Every parent class in order from immediate parent to root ancestor

The shape is `array<string, string>`. The order matches what PHP's [`class_parents()`](https://www.php.net/manual/en/function.class-parents.php) reports.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "int"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "float"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "true"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "false"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "null"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "array"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "object"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "unknown class name"
 ✔ ->__invoke() returns empty array for input that is not a known class/interface name with data set "empty string"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetClassHierarchy.php:64`](../src/TypeInspectors/GetClassHierarchy.php#L64)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts a class-string directly
- [`GetClassTypes`](../GetClassTypes/README.md) — broader type inspection for class/interface/trait names
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetClassHierarchy::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassHierarchy%3A%3A__invoke()%22)
- [Closed issues mentioning `GetClassHierarchy::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassHierarchy%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassHierarchy%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
