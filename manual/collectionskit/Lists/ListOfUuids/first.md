# ListOfUuids::first()

> `public function first(): UuidInterface`

Returns the first UUID stored in this list.

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
    public function first(): UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function first(): mixed`, inherited
> from [`AccessibleCollection::first()`](../../AccessibleCollection/first.md).
> The narrowed `UuidInterface` return type shown above is bound by
> `@extends CollectionAsList<UuidInterface>` on [`ListOfUuids`](README.md).

## Description

Returns the first UUID stored in this list, or throws if the list is empty. This is the throwing counterpart to [`maybeFirst()`](maybeFirst.md). Use it when an empty list at this point in your code is a programming error rather than an expected branch — the exception names `ListOfUuids` so the failure is easy to diagnose.

The "first" UUID is the one at the lowest sequential integer key (`0`, by construction) — the entry that was supplied first to the constructor or appended first via [`add()`](add.md).

## Parameters

_None._

## Return Values

The first UUID stored in the list. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<UuidInterface>` binding narrows this to `UuidInterface`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the list is empty. The message is `ListOfUuids is empty`, derived from [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
 ✔ ->first() returns the first UUID
 ✔ ->first() throws RuntimeException for empty list
 ✔ List with one UUID: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:257`](../../../../kits/collectionskit/src/AccessibleCollection.php#L257)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfUuids`](README.md) — where the `<UuidInterface>` template parameter is bound
- [`AccessibleCollection::first()`](../../AccessibleCollection/first.md) — the generic implementation this page specialises
- [`ListOfUuids::maybeFirst()`](maybeFirst.md) — non-throwing variant for "may be empty" call sites
- [`ListOfUuids::last()`](last.md) — last-UUID counterpart

## Issues

- [Open issues mentioning `ListOfUuids::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%3A%3Afirst()%22)
- [Closed issues mentioning `ListOfUuids::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%3A%3Afirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
