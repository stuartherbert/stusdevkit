# ListOfObjects::last()

> `public function last(): object`

Returns the last object stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use RuntimeException;

class ListOfObjects extends CollectionAsList
{
    /**
     * @return object
     * @throws RuntimeException
     */
    public function last(): object
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function last(): mixed`, inherited
> from [`AccessibleCollection::last()`](../../AccessibleCollection/last.md).
> The narrowed `object` return type shown above is bound by
> `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Returns the last object stored in this list, or throws if the list is empty. This is the throwing counterpart to [`maybeLast()`](maybeLast.md). Use it when an empty list at this point in your code is a programming error rather than an expected branch — the exception names `ListOfObjects` so the failure is easy to diagnose.

The "last" object is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md). The returned value is the same handle that was stored.

## Parameters

_None._

## Return Values

The last object stored in the list. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<object>` binding narrows this to `object`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the list is empty. The message is `ListOfObjects is empty`, derived from [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ ->last() returns the last object
 ✔ ->last() throws RuntimeException for empty list
 ✔ List with one object: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`AccessibleCollection::last()`](../../AccessibleCollection/last.md) — the generic implementation this page specialises
- [`ListOfObjects::maybeLast()`](maybeLast.md) — non-throwing variant for "may be empty" call sites
- [`ListOfObjects::first()`](first.md) — first-object counterpart

## Issues

- [Open issues mentioning `ListOfObjects::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3Alast()%22)
- [Closed issues mentioning `ListOfObjects::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3Alast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
