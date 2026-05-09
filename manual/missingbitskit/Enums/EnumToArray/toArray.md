# EnumToArray::toArray()

> `public static function toArray(): array`

Returns a `name => value` map of every enum case.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Enums\EnumToArray`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Enums;

/**
 * @phpstan-require-implements \BackedEnum
 * @template TValue of string|int
 */
trait EnumToArray
{
    /**
     * @return array<string, TValue>
     */
    public static function toArray(): array;
}
```

## Description

`toArray()` returns a map of every case's name to its backing value. It walks
the enum's cases via `self::cases()` and builds a `name => value` map.

Originally added for writing data provider-driven unit tests, this method is
intended to give callers a convenient way to enumerate all cases of a backed
enum along with their backing values.

The trait is generic over the enum's backing type: string-backed enums bind
`TValue` to `string`, int-backed enums bind it to `int`.

**Siblings:**

- [`StaticallyArrayable`](../../Arrays/StaticallyArrayable/README.md) — interface this trait implements

## Parameters

_None._ The method takes no parameters.

## Return Values

Returns an associative array mapping case names to their backing values:

- Keys are case names (as declared, e.g. `'ZEBRA'`, `'APPLE'`)
- Values are the backing values (strings for string-backed enums, ints for int-backed enums)

For a three-case string-backed enum:

```php
[
    'ZEBRA' => 'zebra-value',
    'APPLE' => 'apple-value',
    'MANGO' => 'mango-value',
]
```

For a three-case int-backed enum:

```php
[
    'TEN'   => 10,
    'TWENTY' => 20,
    'THIRTY' => 30,
]
```

For a single-case enum:

```php
[
    'ONLY' => 'only-value',
]
```

The returned map preserves case declaration order — keys are not sorted
alphabetically.

The shape is `array<string, TValue>` where `TValue` is the enum's backing type
(`string` or `int`).

## Errors/Exceptions

_None._

## Here Be Dragons

This method may only be called on **backed** enums. Attempting to call it on a
pure enum (one without `->value`) will fail at compile time.

## Examples

```php
// String-backed enum
StringBackedSampleEnum::toArray();
// Returns: ['ZEBRA' => 'zebra-value', 'APPLE' => 'apple-value', 'MANGO' => 'mango-value']

// Int-backed enum
IntBackedSampleEnum::toArray();
// Returns: ['TEN' => 10, 'TWENTY' => 20, 'THIRTY' => 30]

// Single-case enum
SingleCaseBackedEnum::toArray();
// Returns: ['ONLY' => 'only-value']
```

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Enums\EnumToArray
 ✔ ::toArray() is declared
 ✔ ::toArray() is public
 ✔ ::toArray() is static
 ✔ ::toArray() takes no parameters
 ✔ ::toArray() declares an `array` return type
 ✔ ::toArray() returns a name-to-value map for a string-backed enum
 ✔ ::toArray() returns a name-to-value map for an int-backed enum
 ✔ ::toArray() returns a one-entry map for a single-case enum
 ✔ ::toArray() preserves case declaration order
```

## Source

[`kits/missingbitskit/src/Enums/EnumToArray.php:78`](../src/Enums/EnumToArray.php#L78)

## Changelog

_No tagged releases yet._

## See Also

- [`StaticallyArrayable`](../../Arrays/StaticallyArrayable/README.md) — interface this trait implements

## Issues

- [Open issues mentioning `EnumToArray::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22EnumToArray%3A%3AtoArray()%22)
- [Closed issues mentioning `EnumToArray::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22EnumToArray%3A%3AtoArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=EnumToArray%3A%3AtoArray()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill, with hand-authored additions._
