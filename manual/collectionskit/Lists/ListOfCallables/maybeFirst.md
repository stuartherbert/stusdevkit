# ListOfCallables::maybeFirst()

> `public function maybeFirst(): ?callable`

Returns the first callable stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfCallables`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfCallables extends CollectionAsList
{
    /**
     * @return callable|null
     */
    public function maybeFirst(): ?callable
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeFirst(): mixed`, inherited
> from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The narrowed `callable|null` return type shown above is bound by
> `@extends CollectionAsList<callable>` on [`ListOfCallables`](README.md).

## Description

Returns the first callable stored in this list, or `null` when the list is empty. This is the non-throwing counterpart to [`first()`](first.md) — use it when an empty list is an expected branch rather than a programming error.

The "first" callable is the one at the lowest sequential integer key (`0`, by construction) — the entry that was supplied first to the constructor or appended first via [`add()`](add.md).

## Parameters

_None._

## Return Values

The first callable stored in the list, or `null` when the list is empty. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<callable>` binding narrows this to `callable|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfCallables
 ✔ ->maybeFirst() returns the first callable
 ✔ ->maybeFirst() returns null for empty list
 ✔ ->maybeFirst() returns the first callable added via add()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfCallables`](README.md) — where the `<callable>` template parameter is bound
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises
- [`ListOfCallables::first()`](first.md) — throwing variant for "must not be empty" call sites
- [`ListOfCallables::maybeLast()`](maybeLast.md) — last-callable counterpart

## Issues

- [Open issues mentioning `ListOfCallables::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfCallables%3A%3AmaybeFirst()%22)
- [Closed issues mentioning `ListOfCallables::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfCallables%3A%3AmaybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfCallables%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
