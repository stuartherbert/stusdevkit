# ListOfIntegers::maybeFirst()

> `public function maybeFirst(): ?int`

Returns the first integer stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfIntegers extends ListOfNumbers
{
    /**
     * @return int|null
     */
    public function maybeFirst(): ?int
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeFirst(): mixed`, inherited
> from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The narrowed `int|null` return type shown above is bound by
> `@template-extends ListOfNumbers<int>` on [`ListOfIntegers`](README.md).

## Description

Returns the first integer stored in this list, or `null` when the list is empty. This is the non-throwing counterpart to [`first()`](first.md) — use it when an empty list is an expected branch rather than a programming error.

The "first" integer is the one at the lowest sequential integer key (`0`, by construction) — the entry that was supplied first to the constructor or appended first via [`add()`](add.md).

## Parameters

_None._

## Return Values

The first integer stored in the list, or `null` when the list is empty. The PHP signature returns `mixed`, but the class's `@template-extends ListOfNumbers<int>` binding narrows this to `int|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfIntegers
 ✔ ->maybeFirst() returns the first integer
 ✔ ->maybeFirst() returns null for empty list
 ✔ ->maybeFirst() returns the first integer added via add()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfIntegers`](README.md) — where the `<int>` template parameter is bound
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises
- [`ListOfIntegers::first()`](first.md) — throwing variant for "must not be empty" call sites
- [`ListOfIntegers::maybeLast()`](maybeLast.md) — last-integer counterpart

## Issues

- [Open issues mentioning `ListOfIntegers::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfIntegers%3A%3AmaybeFirst()%22)
- [Closed issues mentioning `ListOfIntegers::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfIntegers%3A%3AmaybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
