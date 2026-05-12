# ListOfNumbers::maybeLast()

> `public function maybeLast(): int|float|null`

Returns the last number stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfNumbers extends CollectionAsList
{
    /**
     * @return int|float|null
     */
    public function maybeLast(): int|float|null
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed types shown above come from the
> `@template TValue of int|float = int|float` on [`ListOfNumbers`](README.md);
> subclasses can pin these further (e.g.
> [`ListOfIntegers`](../ListOfIntegers/README.md) pins `TValue` to `int`,
> [`ListOfFloats`](../ListOfFloats/README.md) pins `TValue` to `float`).

## Description

Returns the last number stored in this list, or `null` when the list is empty. This is the non-throwing counterpart to [`last()`](last.md) — use it when an empty list is an expected branch rather than a programming error.

The "last" number is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md). The returned value's PHP type (`int` or `float`) matches what was stored.

## Parameters

_None._

## Return Values

The last number stored in the list, or `null` when the list is empty. The PHP signature returns `mixed`, but the class's `@template TValue of int|float = int|float` re-bound narrows this to `int|float|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ ->maybeLast() returns the last number
 ✔ ->maybeLast() returns null for empty list
 ✔ ->maybeLast() returns the last number added via add()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](README.md) — where the `<int|float>` template parameter is re-bounded
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises
- [`ListOfNumbers::last()`](last.md) — throwing variant for "must not be empty" call sites
- [`ListOfNumbers::maybeFirst()`](maybeFirst.md) — first-number counterpart

## Issues

- [Open issues mentioning `ListOfNumbers::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%3A%3AmaybeLast()%22)
- [Closed issues mentioning `ListOfNumbers::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%3A%3AmaybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
