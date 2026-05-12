# CollectionOfAnything::toArray()

> `public function toArray(): array`

Return the collection's stored data as a plain PHP array.

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
     * @return array<TKey,TValue>
     */
    public function toArray(): array
}
```

## Description

Return the collection's stored data as a plain PHP array. The returned array preserves all keys (integer or string) and all values exactly as stored. No transformation, copy, or filtering is applied — the array is returned by value (i.e., a copy), so mutating the return value does not affect the collection.

## Parameters

_None._

## Return Values

An `array<TKey, TValue>` containing all stored data. The array preserves the original keys and values in their exact form. For an empty collection, returns `[]`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
CollectionOfAnything
 ✔ ->toArray() returns an empty array for an empty collection
 ✔ ->toArray() returns the stored data for an indexed collection
 ✔ ->toArray() preserves string keys for an associative collection
 ✔ ->toArray() preserves mixed value types
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:175`](../../../kits/collectionskit/src/CollectionOfAnything.php#L175)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::__construct()`](__construct.md) — creates a collection, optionally seeded with data
- [`CollectionOfAnything::getIterator()`](getIterator.md) — returns an iterator over the stored data
- [`CollectionOfAnything::count()`](count.md) — returns the number of elements stored in the collection

## Issues

- [Open issues mentioning `CollectionOfAnything::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::toArray()%22)
- [Closed issues mentioning `CollectionOfAnything::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::toArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
