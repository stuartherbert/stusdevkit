# CollectionOfAnything::getIterator()

> `public function getIterator(): ArrayIterator`

Return an iterator over the collection's stored data.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\CollectionOfAnything`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Arrays\Arrayable;

class CollectionOfAnything implements Arrayable, Countable, IteratorAggregate
{
    /**
     * @return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): ArrayIterator
}
```

## Description

Return an iterator over the collection's stored data. The iterator yields elements in insertion order — the same order the caller supplied to the constructor or that a subclass appended via `add()`. Keys are yielded alongside values when the caller uses `foreach ($col as $key => $value)`.

## Parameters

_None._

## Return Values

An `ArrayIterator<TKey, TValue>` that yields elements in insertion order. For an empty collection, the iterator yields no items.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
CollectionOfAnything
 ✔ ->getIterator() returns an ArrayIterator
 ✔ ->getIterator() yields values in insertion order
 ✔ ->getIterator() yields no items for an empty collection
 ✔ ->getIterator() preserves string keys during iteration
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:222`](../../../kits/collectionskit/src/CollectionOfAnything.php#L222)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::toArray()`](toArray.md) — returns the stored data as a plain PHP array
- [`CollectionOfAnything::count()`](count.md) — returns the number of elements stored in the collection
- [`CollectionOfAnything::empty()`](empty.md) — returns true if the collection contains no elements

## Issues

- [Open issues mentioning `CollectionOfAnything::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::getIterator()%22)
- [Closed issues mentioning `CollectionOfAnything::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::getIterator()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
