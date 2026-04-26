# GetArrayTypes::from()

> `public static from(array $item): array`

get a full list of strict types that an array can satisfy

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
     * @param  array<array-key,mixed> $item
     *         the array to examine
     * @return array<string,string>
     *         the list of PHP types that the array can match
     */
    public static function from(array $item): array
}
```

## Description

Returns the complete set of type hints that a PHP array satisfies. The return value is ordered from most specific to least specific:

- `callable` — if the array is also a valid callable (e.g. `[ClassName, 'methodName']`)
- `array` — always present for any PHP array

## Parameters

**`$item`** (`array<array-key,mixed>`)

The array to examine. May be an indexed list, an associative array, or a callable array (e.g. `[ClassName::class, 'methodName']`). The method inspects whether the array is also a valid PHP `callable`.

## Return Values

Returns an associative array where keys and values are identical — each entry represents one type hint that the input value satisfies. The shape is `array<string, string>`. Keys include:
- `callable` — if the array is also a valid callable (e.g. `[ClassName, 'methodName']`)
- `array` — always present for any PHP array

The return value is ordered from most specific to least specific.

## Errors/Exceptions

_None._

## Here Be Dragons

None documented here. (See TODO below.)

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetArrayTypes
 ✔ ::from() is declared public static
 ✔ ::from() parameter names in order
 ✔ ::from() returns array
 ✔ ::from() returns just array for a non-callable array with data set "empty"
 ✔ ::from() returns just array for a non-callable array with data set "list of ints"
 ✔ ::from() returns just array for a non-callable array with data set "associative"
 ✔ ::from() returns just array for a non-callable array with data set "mixed content"
 ✔ ::from() returns callable and array for a callable array
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetArrayTypes.php:62`](../src/GetArrayTypes.php#L62)

## Changelog

_No tagged releases yet._

## See Also

- [`->__invoke()`](__invoke.md) — accepts any input and delegates to `::from()` for arrays
- `GetDuckTypes` — alternative approach using duck-typing
- `GetObjectTypes` — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetArrayTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetArrayTypes%3A%3Afrom()%22)
- [Closed issues mentioning `GetArrayTypes::from()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetArrayTypes%3A%3Afrom()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetArrayTypes%3A%3Afrom()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
