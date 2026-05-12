# CollectionOfAnything::count()

> `public function count(): int`

Return the number of elements stored in the collection.

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
     * @return int the number of elements; zero for an empty collection.
     */
    public function count(): int
}
```

## Description

Return the number of elements stored in the collection. This method satisfies the `Countable` interface contract and is also callable directly as `$collection->count()`.

## Parameters

_None._

## Return Values

An `int` representing the number of elements. Returns `0` for an empty collection.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
CollectionOfAnything
 ✔ ->count() returns 0 for an empty collection
 ✔ ->count() returns the number of stored items
 ✔ ->count() is used by PHP's count() function
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:197`](../../../kits/collectionskit/src/CollectionOfAnything.php#L197)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::toArray()`](toArray.md) — returns the stored data as a plain PHP array
- [`CollectionOfAnything::empty()`](empty.md) — returns true if the collection contains no elements
- [`CollectionOfAnything::getIterator()`](getIterator.md) — returns an iterator over the stored data

## Issues

- [Open issues mentioning `CollectionOfAnything::count()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::count()%22)
- [Closed issues mentioning `CollectionOfAnything::count()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::count()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
