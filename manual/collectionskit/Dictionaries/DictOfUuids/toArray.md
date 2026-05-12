# DictOfUuids::toArray()

> `public function toArray(): array`

Return the dict's stored data as a plain PHP array.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;

class DictOfUuids extends DictOfObjects
{
    /**
     * @return array<string, UuidInterface>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function toArray(): array`, inherited
> from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The narrowed `array<string, UuidInterface>` return type shown above is bound
> by `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns the dict's stored data as a plain PHP array. The returned array preserves all string keys and [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) values exactly as stored. No transformation, copy, or filtering is applied — the array is returned by value, so mutating the returned array does not affect the dict.

If you want the UUIDs flattened to their string representations, call [`DictOfUuids::toArrayOfStrings()`](../../Traits/UuidConversions/toArrayOfStrings.md) instead.

## Parameters

_None._

## Return Values

An `array<string, UuidInterface>` containing all stored data. The array preserves the original string keys and `UuidInterface` values in their exact form. For an empty dict, returns `[]`.

## Errors/Exceptions

_None._

## Here Be Dragons

**UUIDs are stored by reference**, inherited from [`DictOfObjects`](../DictOfObjects/README.md). The returned array holds the same `UuidInterface` instances the dict holds — if you swap one out for a different instance in the returned array, the dict's copy is unaffected (the array is a value copy), but if you mutate the same `UuidInterface` object, both views see the change. In practice this is rarely a concern because `UuidInterface` implementations are conventionally immutable.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->toArray() returns empty array for empty dict
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via set()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::toArrayOfStrings()`](../../Traits/UuidConversions/toArrayOfStrings.md) — return the UUIDs as an array of strings
- [`DictOfUuids::getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::toArray()%22)
- [Closed issues mentioning `DictOfUuids::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::toArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
