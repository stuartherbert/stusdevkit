# GetArrayTypes::__invoke()

> `public __invoke(mixed $input): array`

Do we have a PHP array? If so, what types does it match?

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\TypeInspectors\GetArrayTypes`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetArrayTypes
{
    /**
     * @param mixed $input
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
}
```

## Description

Call the class as an invokable object. Accepts any input and returns empty array for non-array values. For invocation convenience on any input type, not just arrays. Delegates to `::from()` when a valid PHP array is provided.

## Parameters

**`$input`** (`mixed`)

The value to examine. If it is a PHP array, the method delegates to `::from()` and returns the full list of types that the array satisfies (e.g. `callable`, `array`). If `$input` is not an array, an empty list of types is returned immediately.

## Return Values

Returns an associative array where keys and values are identical — each entry represents one type hint that the input value satisfies. The shape is `array<string, string>`. Keys include:
- `callable` — if the array is also a valid callable (e.g. `[ClassName, 'methodName']`)
- `array` — always present for any PHP array

If `$input` is not an array, an empty array is returned.

## Errors/Exceptions

_None._

## Here Be Dragons

None documented here. (See TODO below.)

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetArrayTypes
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() parameter names in order
 ✔ ->__invoke() returns array
 ✔ ->__invoke() returns empty array for non-array input with data set "int"
 ✔ ->__invoke() returns empty array for non-array input with data set "float"
 ✔ ->__invoke() returns empty array for non-array input with data set "string"
 ✔ ->__invoke() returns empty array for non-array input with data set "true"
 ✔ ->__invoke() returns empty array for non-array input with data set "false"
 ✔ ->__invoke() returns empty array for non-array input with data set "null"
 ✔ ->__invoke() returns empty array for non-array input with data set "object"
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetArrayTypes.php:52`](../src/GetArrayTypes.php#L52)

## Changelog

_No tagged releases yet._

## See Also

- [`::from()`](from.md) — accepts a PHP array and returns its type list
- `GetDuckTypes` — alternative approach using duck-typing
- `GetObjectTypes` — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetArrayTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetArrayTypes%3A%3A__invoke()%22)
- [Closed issues mentioning `GetArrayTypes::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetArrayTypes%3A%3A__invoke()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetArrayTypes%3A%3A__invoke()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
