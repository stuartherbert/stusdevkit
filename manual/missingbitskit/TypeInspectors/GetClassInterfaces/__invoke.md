# GetClassInterfaces::__invoke()

> `public function __invoke(mixed $input): array`

Call the class as an invokable object; accepts any input and returns empty array for non-class-string values.

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
     * @param  mixed $input
     *         the value to examine
     *
     *   returns an empty list if `$input` is not a valid class-string
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
}
```

## Description

`__invoke()` accepts any value and returns the list of interfaces that a PHP class or interface implements — but only when the input is a string naming a known class or interface. For any other input, it returns an empty array.

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

- An entry for each interface the class implements, keyed and valued by its fully qualified class name (FQCN)
- Interfaces inherited from parent classes are included
- Interfaces transitively extended by other interfaces are included (e.g. if `ExtendedInterface extends BaseInterface`, both appear in the result)

The order matches what PHP's [`class_implements()`](https://www.php.net/manual/en/function.class-implements.php) reports.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces
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

[`kits/missingbitskit/src/TypeInspectors/GetClassInterfaces.php:64`](../src/TypeInspectors/GetClassInterfaces.php#L64)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts a class-string directly
- [`GetClassTypes`](../GetClassTypes/README.md) — broader type inspection for class/interface/trait names
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetClassInterfaces::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetClassInterfaces%3A%3A__invoke()%22)
- [Closed issues mentioning `GetClassInterfaces::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetClassInterfaces%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetClassInterfaces%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
