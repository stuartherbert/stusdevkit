# GetObjectTypes::__invoke()

> `public function __invoke(mixed $item): array`

Call the class as an invokable object; accepts any input and returns empty
array for non-object values.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetObjectTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetObjectTypes
{
    /**
     * @return array<string,string>
     */
    public function __invoke(mixed $item): array
}
```

## Description

`__invoke()` accepts any value and returns the set of types that a PHP object
satisfies — but only when the input is strictly an object. For any other
input, it returns an empty array.

The method performs a single validation check before delegating to
[`::from()`](from.md):

1. The input must be an object (checked via [`is_object()`](https://www.php.net/manual/en/function.is-object.php)).

If the check fails, the method returns `[]` immediately without further
processing. No type coercion is applied — an integer, string, or any other
value will not be treated as an object.

**Siblings:**

- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetDuckTypes`](../GetDuckTypes/README.md) — instance-level dispatch to all per-type inspectors

## Parameters

**`$item`** (`mixed`)

The value to examine. Must be a PHP object. If it is not an object, the
method returns an empty array without further processing.

## Return Values

Returns an associative array mapping type names to their spelling. When
`$item` is not an object, returns an empty array `[]`. When it is a valid
object, returns:

- An entry for the object's class name (FQCN)
- Entries for all parent classes in the inheritance hierarchy
- Entries for all implemented interfaces
- Entries for all used traits
- `'object'` — the universal object type hint

The shape is `array<string, string>`. The order matches what
[`GetClassTypes::from()`](../GetClassTypes/README.md) reports.

## Errors/Exceptions

_None._

## Here Be Dragons

None yet.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetObjectTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for non-object input with data set "int"
 ✔ ->__invoke() returns empty array for non-object input with data set "float"
 ✔ ->__invoke() returns empty array for non-object input with data set "string"
 ✔ ->__invoke() returns empty array for non-object input with data set "true"
 ✔ ->__invoke() returns empty array for non-object input with data set "false"
 ✔ ->__invoke() returns empty array for non-object input with data set "null"
 ✔ ->__invoke() returns empty array for non-object input with data set "array"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetObjectTypes.php:55`](../src/TypeInspectors/GetObjectTypes.php#L55)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — static factory; accepts an object directly
- [`GetClassTypes`](../GetClassTypes/README.md) — class-string-based type inspection
- [`GetObjectTypes::from()`](from.md) — instance-level callable detection

## Issues

- [Open issues mentioning `GetObjectTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetObjectTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetObjectTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetObjectTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetObjectTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
