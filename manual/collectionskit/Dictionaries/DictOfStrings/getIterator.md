# DictOfStrings::getIterator()

> `public function getIterator(): ArrayIterator`

Return an iterator over the dict's stored data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @return ArrayIterator<array-key, string>
     */
    public function getIterator(): ArrayIterator
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function getIterator(): ArrayIterator`,
> inherited from [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md).
> The narrowed `ArrayIterator<array-key, string>` return type shown above is
> bound by `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Returns an iterator over the dict's stored data. The iterator yields string values keyed by their associated keys, in insertion order — the same order the caller supplied to the constructor or added via [`DictOfStrings::set()`](set.md).

This method satisfies the `IteratorAggregate` interface, so callers can use `foreach ($dict as $key => $string)` directly.

## Parameters

_None._

## Return Values

An `ArrayIterator<array-key, string>` that yields each stored string alongside its key, in insertion order. For an empty dict, the iterator yields no items.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
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

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::getIterator()%22)
- [Closed issues mentioning `DictOfStrings::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::getIterator()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
