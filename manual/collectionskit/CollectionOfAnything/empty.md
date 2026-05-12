# CollectionOfAnything::empty()

> `public function empty(): bool`

Return true if the collection contains no elements.

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
     * @return bool true when the collection has zero elements; false otherwise.
     */
    public function empty(): bool
}
```

## Description

Return true if the collection contains no elements. This is a convenience predicate. It is equivalent to `$collection->count() === 0` but reads more naturally in conditional expressions.

## Parameters

_None._

## Return Values

A `bool` that is `true` when the collection has zero elements, and `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
CollectionOfAnything
 ✔ ->empty() returns true for an empty collection
 ✔ ->empty() returns false for a non-empty collection
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:247`](../../../kits/collectionskit/src/CollectionOfAnything.php#L247)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::count()`](count.md) — returns the number of elements stored in the collection
- [`CollectionOfAnything::toArray()`](toArray.md) — returns the stored data as a plain PHP array
- [`CollectionOfAnything::getIterator()`](getIterator.md) — returns an iterator over the stored data

## Issues

- [Open issues mentioning `CollectionOfAnything::empty()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::empty()%22)
- [Closed issues mentioning `CollectionOfAnything::empty()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::empty()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
