# GetArrayTypes

Standalone class.

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\TypeInspectors;

class GetArrayTypes
{
    /**
     * Do we have a PHP array? If so, what types does it match?
     */
    public function __invoke(mixed $input): array;

    /**
     * get a full list of strict types that an array can satisfy
     */
    public static function from(array $item): array;
}
```

## Description

Returns the complete set of type hints that a PHP array satisfies. The return value includes:

- `callable` — if the array is also a valid callable (e.g. `[ClassName, 'methodName']`)
- `array` — always present for any PHP array

**Since:** _Upcoming Release_

## Methods

- [`->__invoke()`](__invoke.md) — Call the class as an invokable object; accepts any input and returns empty array for non-array values
- [`::from()`](from.md) — Static factory; accepts a PHP array and returns its type list

## Here Be Dragons

None yet. (See TODO below.)

## Contract (from tests)

```
StusDevKit\MissingBitsKit\TypeInspectors\GetArrayTypes
 ✔ lives in the StusDevKit\MissingBitsKit\TypeInspectors namespace
 ✔ is declared as a class
 ✔ exposes __invoke() and ::from() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/TypeInspectors/GetArrayTypes.php:47`](../src/GetArrayTypes.php#L47)

## Changelog

_No tagged releases yet._

## See Also

- [`GetDuckTypes`](../GetDuckTypes/README.md) — handles duck-typing including the `mixed` type marker
- [`GetObjectTypes`](../GetObjectTypes/README.md) — handles instance-level callable detection (Closures, `__invoke`)

## Issues

- [Open issues mentioning `GetArrayTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetArrayTypes%22)
- [Closed issues mentioning `GetArrayTypes`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetArrayTypes%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetArrayTypes%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
