# ListOfObjects::maybeLast()

> `public function maybeLast(): ?object`

Returns the last object stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfObjects extends CollectionAsList
{
    /**
     * @return object|null
     */
    public function maybeLast(): ?object
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed `object|null` return type shown above is bound by
> `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Returns the last object stored in this list, or `null` when the list is empty. This is the non-throwing counterpart to [`last()`](last.md) — use it when an empty list is an expected branch rather than a programming error.

The "last" object is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md). The returned value is the same handle that was stored.

## Parameters

_None._

## Return Values

The last object stored in the list, or `null` when the list is empty. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<object>` binding narrows this to `object|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ ->maybeLast() returns the last object
 ✔ ->maybeLast() returns null for empty list
 ✔ ->maybeLast() returns the last object added via add()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises
- [`ListOfObjects::last()`](last.md) — throwing variant for "must not be empty" call sites
- [`ListOfObjects::maybeFirst()`](maybeFirst.md) — first-object counterpart

## Issues

- [Open issues mentioning `ListOfObjects::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3AmaybeLast()%22)
- [Closed issues mentioning `ListOfObjects::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3AmaybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
