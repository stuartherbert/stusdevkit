# ListOfStrings::maybeLast()

> `public function maybeLast(): ?string`

Returns the last string stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfStrings extends CollectionAsList
{
    /**
     * @return string|null
     */
    public function maybeLast(): ?string
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed `string|null` return type shown above is bound by
> `@extends CollectionAsList<string>` on [`ListOfStrings`](README.md).

## Description

Returns the last string stored in this list, or `null` when the list is empty. This is the non-throwing counterpart to [`last()`](last.md) — use it when an empty list is an expected branch rather than a programming error.

The "last" string is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md).

## Parameters

_None._

## Return Values

The last string stored in the list, or `null` when the list is empty. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<string>` binding narrows this to `string|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

**An empty string and an empty list are not the same.** A list containing a single `""` is not empty — its [`empty()`](../../CollectionOfAnything/empty.md) returns `false` and `maybeLast()` returns `""`. Only a truly empty list (one with no entries) makes `maybeLast()` return `null`. Callers comparing with `if (!$list->maybeLast())` will trip on this; use the explicit `=== null` check instead.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfStrings
 ✔ ->maybeLast() returns the last string
 ✔ ->maybeLast() returns null for empty list
 ✔ ->maybeLast() returns the last string added via add()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfStrings`](README.md) — where the `<string>` template parameter is bound
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises
- [`ListOfStrings::last()`](last.md) — throwing variant for "must not be empty" call sites
- [`ListOfStrings::maybeFirst()`](maybeFirst.md) — first-string counterpart

## Issues

- [Open issues mentioning `ListOfStrings::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfStrings%3A%3AmaybeLast()%22)
- [Closed issues mentioning `ListOfStrings::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfStrings%3A%3AmaybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
