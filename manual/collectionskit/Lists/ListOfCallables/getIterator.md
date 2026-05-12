# ListOfCallables::getIterator()

> `public function getIterator(): ArrayIterator`

Return an iterator over the list's stored callables.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfCallables`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;

class ListOfCallables extends CollectionAsList
{
    /**
     * @return ArrayIterator<int, callable>
     */
    public function getIterator(): ArrayIterator
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function getIterator(): ArrayIterator`,
> inherited from [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md).
> The narrowed `ArrayIterator<int, callable>` return type shown above is bound
> by `@extends CollectionAsList<callable>` on [`ListOfCallables`](README.md).

## Description

Returns an iterator over the list's stored callables. This is the [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) hook that PHP calls when a `ListOfCallables` is used in a `foreach` loop, so callers rarely invoke it directly.

The iterator yields elements in insertion order — the same order the caller supplied to the constructor or appended via [`add()`](add.md). Keys are sequential integers starting at `0`; values are the stored callables.

Iterating over an empty list produces no iterations.

## Parameters

_None._

## Return Values

An [`ArrayIterator`](https://www.php.net/manual/en/class.arrayiterator.php) over the list's stored callables. The PHP signature returns `ArrayIterator`, but the class's `@extends CollectionAsList<callable>` binding narrows the iterator's key/value types to `int` and `callable`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfCallables
 ✔ ->getIterator() returns an ArrayIterator
 ✔ List can be iterated with foreach
 ✔ Iterating empty list produces no iterations
 ✔ Iteration produces sequential integer keys
 ✔ Iteration includes items added via add()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:226`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L226)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfCallables`](README.md) — where the `<callable>` template parameter is bound
- [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md) — the generic implementation this page specialises
- [`ListOfCallables::toArray()`](toArray.md) — return the list's contents as a plain PHP array

## Issues

- [Open issues mentioning `ListOfCallables::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfCallables%3A%3AgetIterator()%22)
- [Closed issues mentioning `ListOfCallables::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfCallables%3A%3AgetIterator()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfCallables%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
