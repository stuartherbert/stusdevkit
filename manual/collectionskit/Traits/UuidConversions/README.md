# UuidConversions

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Traits;

trait UuidConversions
{
    /**
     * Returns the collection's UUIDs as an array of strings,
     * preserving the original array keys.
     */
    public function toArrayOfStrings(): array;
}
```

## Description

`UuidConversions` provides UUID-to-string conversion methods for collections that hold [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) values.

Use this trait in any collection class that extends [`CollectionOfAnything`](../../CollectionOfAnything/README.md) and stores `UuidInterface` objects keyed by their string representation — for example [`DictOfUuids`](../../Dictionaries/DictOfUuids/README.md).

## Methods

**From UuidConversions**

- [`->toArrayOfStrings()`](toArrayOfStrings.md) — returns the collection's UUIDs as an array of strings, preserving the original array keys

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Traits\UuidConversions
 ✔ is declared as a trait
 ✔ lives in the StusDevKit\CollectionsKit\Traits namespace
 ✔ exposes only a toArrayOfStrings() method
```

## Source

[`kits/collectionskit/src/Traits/UuidConversions.php:54`](../../../../kits/collectionskit/src/Traits/UuidConversions.php#L54)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](../../Dictionaries/DictOfUuids/README.md) — uses this trait
- [`CollectionOfAnything`](../../CollectionOfAnything/README.md) — root base class for every collection type

## Issues

- [Open issues mentioning `UuidConversions`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UuidConversions%22)
- [Closed issues mentioning `UuidConversions`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UuidConversions%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UuidConversions%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
