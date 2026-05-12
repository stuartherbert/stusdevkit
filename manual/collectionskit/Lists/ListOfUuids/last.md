# ListOfUuids::last()

> `public function last(): UuidInterface`

Returns the last UUID stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use Ramsey\Uuid\UuidInterface;
use RuntimeException;

class ListOfUuids extends CollectionAsList
{
    /**
     * @return UuidInterface
     * @throws RuntimeException
     */
    public function last(): UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function last(): mixed`, inherited
> from [`AccessibleCollection::last()`](../../AccessibleCollection/last.md).
> The narrowed `UuidInterface` return type shown above is bound by
> `@extends CollectionAsList<UuidInterface>` on [`ListOfUuids`](README.md).

## Description

Returns the last UUID stored in this list, or throws if the list is empty. This is the throwing counterpart to [`maybeLast()`](maybeLast.md). Use it when an empty list at this point in your code is a programming error rather than an expected branch — the exception names `ListOfUuids` so the failure is easy to diagnose.

The "last" UUID is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md).

## Parameters

_None._

## Return Values

The last UUID stored in the list. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<UuidInterface>` binding narrows this to `UuidInterface`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the list is empty. The message is `ListOfUuids is empty`, derived from [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
 ✔ ->last() returns the last UUID
 ✔ ->last() throws RuntimeException for empty list
 ✔ List with one UUID: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfUuids`](README.md) — where the `<UuidInterface>` template parameter is bound
- [`AccessibleCollection::last()`](../../AccessibleCollection/last.md) — the generic implementation this page specialises
- [`ListOfUuids::maybeLast()`](maybeLast.md) — non-throwing variant for "may be empty" call sites
- [`ListOfUuids::first()`](first.md) — first-UUID counterpart

## Issues

- [Open issues mentioning `ListOfUuids::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%3A%3Alast()%22)
- [Closed issues mentioning `ListOfUuids::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%3A%3Alast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
