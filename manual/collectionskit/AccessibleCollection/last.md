# AccessibleCollection::last()

> `public function last(): mixed`

Returns the last value of this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\AccessibleCollection`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use RuntimeException;

class AccessibleCollection extends CollectionOfAnything
{
    /**
     * @return TValue
     *
     * @throws RuntimeException
     */
    public function last(): mixed
}
```

## Description

Returns the last value stored in this collection. Throws an exception if the collection is empty.

This method is the throwing counterpart to [`AccessibleCollection::maybeLast()`](maybeLast.md). Use it when an empty collection at this point in your code is a programming error rather than an expected branch — the exception names the offending collection type so the failure is easy to diagnose.

The "last" value is the entry whose key is returned by PHP's `array_key_last()` over the collection's stored data — the last key in iteration order. This method is defined on `AccessibleCollection` and inherited by every subclass; see your collection class's docblock for the definition of "last" that applies to that subclass.

## Parameters

_None._

## Return Values

The last stored value, typed as `TValue`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the collection is empty. The message is `<CollectionType> is empty`, where `<CollectionType>` is the unqualified class name reported by [`->getCollectionTypeAsString()`](../CollectionOfAnything/getCollectionTypeAsString.md).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\AccessibleCollection
 ✔ ::last() signature: last(): mixed
 ✔ ->last() returns the last item
 ✔ ->last() throws RuntimeException for empty collection
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:279`](../../../kits/collectionskit/src/AccessibleCollection.php#L279)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection::maybeLast()`](maybeLast.md) — returns the last value of this collection (returns `null` when empty)
- [`AccessibleCollection::first()`](first.md) — returns the first value stored in this collection (throws when empty)
- [`AccessibleCollection::maybeFirst()`](maybeFirst.md) — returns the first value stored in this collection (returns `null` when empty)

## Issues

- [Open issues mentioning `AccessibleCollection::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22AccessibleCollection::last()%22)
- [Closed issues mentioning `AccessibleCollection::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22AccessibleCollection::last()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=AccessibleCollection%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
