# DictOfUuids::getIterator()

> `public function getIterator(): ArrayIterator`

Return an iterator over the dict's stored data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use Ramsey\Uuid\UuidInterface;

class DictOfUuids extends DictOfObjects
{
    /**
     * @return ArrayIterator<string, UuidInterface>
     */
    public function getIterator(): ArrayIterator
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function getIterator(): ArrayIterator`,
> inherited from [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md).
> The narrowed `ArrayIterator<string, UuidInterface>` return type shown above is
> bound by `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns an iterator over the dict's stored data. The iterator yields `UuidInterface` values keyed by their associated string keys, in insertion order — the same order the caller supplied to the constructor or added via [`DictOfUuids::set()`](set.md).

This method satisfies the `IteratorAggregate` interface, so callers can use `foreach ($dict as $key => $uuid)` directly.

## Parameters

_None._

## Return Values

An `ArrayIterator<string, UuidInterface>` that yields each stored `UuidInterface` value alongside its `string` key, in insertion order. For an empty dict, the iterator yields no items.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->getIterator() returns an ArrayIterator
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
 ✔ Iteration includes items added via set()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:226`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L226)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::getIterator()%22)
- [Closed issues mentioning `DictOfUuids::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::getIterator()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
