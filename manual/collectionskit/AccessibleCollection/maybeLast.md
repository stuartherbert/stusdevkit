# AccessibleCollection::maybeLast()

> `public function maybeLast(): mixed`

Returns the last value of this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\AccessibleCollection`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

class AccessibleCollection extends CollectionOfAnything
{
    /**
     * @return TValue|null
     */
    public function maybeLast(): mixed
}
```

## Description

Returns the last value stored in this collection, or `null` if the collection is empty.

The "last" value is the entry whose key is returned by PHP's `array_key_last()` over the collection's stored data — the last key in iteration order. This method is defined on `AccessibleCollection` and inherited by every subclass; see your collection class's docblock for the definition of "last" that applies to that subclass.

This is the non-throwing accessor. Use [`AccessibleCollection::last()`](last.md) when you would rather have an exception than a null when the collection is empty.

## Parameters

_None._

## Return Values

The last stored value (typed as `TValue`), or `null` when the collection is empty.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\AccessibleCollection
 ✔ ::maybeLast() signature: maybeLast(): mixed
 ✔ ->maybeLast() returns the last item
 ✔ ->maybeLast() returns null for empty collection
 ✔ ->maybeLast() returns last item from associative array
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:258`](../../../kits/collectionskit/src/AccessibleCollection.php#L258)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection::last()`](last.md) — returns the last value of this collection (throws when empty)
- [`AccessibleCollection::maybeFirst()`](maybeFirst.md) — returns the first value stored in this collection (returns `null` when empty)
- [`AccessibleCollection::first()`](first.md) — returns the first value stored in this collection (throws when empty)

## Issues

- [Open issues mentioning `AccessibleCollection::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22AccessibleCollection::maybeLast()%22)
- [Closed issues mentioning `AccessibleCollection::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22AccessibleCollection::maybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=AccessibleCollection%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
