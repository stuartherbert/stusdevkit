# DictOfFloats::getIterator()

> `public function getIterator(): ArrayIterator`

Return an iterator over the dict's stored data.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;

class DictOfFloats extends DictOfNumbers
{
    /**
     * @return ArrayIterator<array-key, float>
     */
    public function getIterator(): ArrayIterator
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function getIterator(): ArrayIterator`,
> inherited from [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md).
> The narrowed `ArrayIterator<array-key, float>` return type shown above is
> bound by `@template-extends DictOfNumbers<array-key, float>` on [`DictOfFloats`](README.md).

## Description

Returns an iterator over the dict's stored data. The iterator yields float values keyed by their associated keys, in insertion order — the same order the caller supplied to the constructor or added via [`DictOfFloats::set()`](set.md).

This method satisfies the `IteratorAggregate` interface, so callers can use `foreach ($dict as $key => $float)` directly.

## Parameters

_None._

## Return Values

An `ArrayIterator<array-key, float>` that yields each stored float alongside its key, in insertion order. For an empty dict, the iterator yields no items.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfFloats
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

- [`DictOfFloats`](README.md) — where the `<array-key, float>` template parameters are bound
- [`DictOfFloats::toArray()`](toArray.md) — return the dict's stored data as a plain PHP array
- [`DictOfNumbers::getIterator()`](../DictOfNumbers/getIterator.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfFloats::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfFloats::getIterator()%22)
- [Closed issues mentioning `DictOfFloats::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfFloats::getIterator()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
