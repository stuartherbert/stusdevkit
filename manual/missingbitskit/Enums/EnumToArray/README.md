# EnumToArray

Provides a canonical `toArray()` implementation for **backed** PHP enums — returns
a `name => value` map of every case.

**Since:** _Upcoming Release_

## Hierarchy

_Standalone trait._

## Synopsis

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

`EnumToArray` is a trait that supplies a canonical `toArray()` implementation for
**backed** PHP enums. It walks the enum's cases and returns a `name => value` map
where keys are case names (as declared) and values are the backing values.

The trait may only be used in backed enums — pure enums have no `->value`
property and the method body will not compile-check against them. PHPStan enforces
this via the `@phpstan-require-implements \BackedEnum` tag.

The trait is generic over the enum's backing type: string-backed enums bind
`TValue` to `string`, int-backed enums bind it to `int`. Consumers tie the type
parameter at the use site with `@use`:

```php
/** @use EnumToArray<string> */
use EnumToArray;
```

That keeps `toArray()`'s return type narrow enough to satisfy the enum's
`@implements StaticallyArrayable<string, TValue>` promise.

## Methods

- [`::toArray()`](toArray.md) — Static; returns a `name => value` map of every case

## Here Be Dragons

This trait may only be used in **backed** enums. Attempting to use it with a
pure enum (one without `->value`) will fail at compile time.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Enums\EnumToArray
 ✔ is declared as a trait
 ✔ lives in the StusDevKit\MissingBitsKit\Enums namespace
 ✔ exposes only a toArray() method
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

[`kits/missingbitskit/src/Enums/EnumToArray.php:64`](../src/Enums/EnumToArray.php#L64)

## Changelog

_No tagged releases yet._

## See Also

- [`StaticallyArrayable`](../Arrays/StaticallyArrayable/README.md) — interface this trait implements
- [`EnumToArray`](./README.md) — the trait itself

## Issues

- [Open issues mentioning `EnumToArray`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22EnumToArray%22)
- [Closed issues mentioning `EnumToArray`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22EnumToArray%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=EnumToArray%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
