# UuidConversions::toArrayOfStrings()

> `public function toArrayOfStrings(): array`

Returns the collection's UUIDs as an array of strings, preserving the original array keys.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Traits\UuidConversions`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Traits;

trait UuidConversions
{
    /**
     * @return array<string,string>
     */
    public function toArrayOfStrings(): array
}
```

## Description

Returns every stored [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) value as its string representation, preserving the collection's original array keys.

The method walks the underlying `$data` array via `array_map()`, casting each `UuidInterface` to `(string)` (which delegates to the UUID object's own `__toString()`). The collection itself is not mutated.

## Parameters

_None._

## Return Values

A new `array<string,string>` mapping the collection's original keys to the string form of each stored UUID.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Traits\UuidConversions
 ✔ ::toArrayOfStrings() is declared
 ✔ ::toArrayOfStrings() is public
 ✔ ::toArrayOfStrings() is an instance method, not static
 ✔ ::toArrayOfStrings() takes no parameters
 ✔ ::toArrayOfStrings() declares an `array` return type
 ✔ ->toArrayOfStrings() returns an empty array for an empty collection
 ✔ ->toArrayOfStrings() converts each UuidInterface value to its string form
 ✔ ->toArrayOfStrings() preserves the collection's array keys
 ✔ ->toArrayOfStrings() returns a one-entry map for a single-UUID collection
```

## Source

[`kits/collectionskit/src/Traits/UuidConversions.php:68`](../../../../kits/collectionskit/src/Traits/UuidConversions.php#L68)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](../../Dictionaries/DictOfUuids/README.md) — uses this trait

## Issues

- [Open issues mentioning `UuidConversions::toArrayOfStrings()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UuidConversions::toArrayOfStrings()%22)
- [Closed issues mentioning `UuidConversions::toArrayOfStrings()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UuidConversions::toArrayOfStrings()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UuidConversions%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
