# ListOfUuids::maybeLast()

> `public function maybeLast(): ?UuidInterface`

Returns the last UUID stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use Ramsey\Uuid\UuidInterface;

class ListOfUuids extends CollectionAsList
{
    /**
     * @return UuidInterface|null
     */
    public function maybeLast(): ?UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed `UuidInterface|null` return type shown above is bound by
> `@extends CollectionAsList<UuidInterface>` on [`ListOfUuids`](README.md).

## Description

Returns the last UUID stored in this list, or `null` when the list is empty. This is the non-throwing counterpart to [`last()`](last.md) — use it when an empty list is an expected branch rather than a programming error.

The "last" UUID is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md).

## Parameters

_None._

## Return Values

The last UUID stored in the list, or `null` when the list is empty. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<UuidInterface>` binding narrows this to `UuidInterface|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
 ✔ ->maybeLast() returns the last UUID
 ✔ ->maybeLast() returns null for empty list
 ✔ ->maybeLast() returns the last UUID added via add()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfUuids`](README.md) — where the `<UuidInterface>` template parameter is bound
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises
- [`ListOfUuids::last()`](last.md) — throwing variant for "must not be empty" call sites
- [`ListOfUuids::maybeFirst()`](maybeFirst.md) — first-UUID counterpart

## Issues

- [Open issues mentioning `ListOfUuids::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%3A%3AmaybeLast()%22)
- [Closed issues mentioning `ListOfUuids::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%3A%3AmaybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
