# ListOfFloats::last()

> `public function last(): float`

Returns the last float stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use RuntimeException;

class ListOfFloats extends ListOfNumbers
{
    /**
     * @return float
     * @throws RuntimeException
     */
    public function last(): float
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function last(): mixed`, inherited
> from [`AccessibleCollection::last()`](../../AccessibleCollection/last.md).
> The narrowed `float` return type shown above is bound by
> `@template-extends ListOfNumbers<float>` on [`ListOfFloats`](README.md).

## Description

Returns the last float stored in this list, or throws if the list is empty. This is the throwing counterpart to [`maybeLast()`](maybeLast.md). Use it when an empty list at this point in your code is a programming error rather than an expected branch — the exception names `ListOfFloats` so the failure is easy to diagnose.

The "last" float is the one at the highest sequential integer key — the entry that was supplied last to the constructor or most-recently appended via [`add()`](add.md).

## Parameters

_None._

## Return Values

The last float stored in the list. The PHP signature returns `mixed`, but the class's `@template-extends ListOfNumbers<float>` binding narrows this to `float`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the list is empty. The message is `ListOfFloats is empty`, derived from [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfFloats
 ✔ ->last() returns the last float
 ✔ ->last() throws RuntimeException for empty list
 ✔ List with one float: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfFloats`](README.md) — where the `<float>` template parameter is bound
- [`AccessibleCollection::last()`](../../AccessibleCollection/last.md) — the generic implementation this page specialises
- [`ListOfFloats::maybeLast()`](maybeLast.md) — non-throwing variant for "may be empty" call sites
- [`ListOfFloats::first()`](first.md) — first-float counterpart

## Issues

- [Open issues mentioning `ListOfFloats::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfFloats%3A%3Alast()%22)
- [Closed issues mentioning `ListOfFloats::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfFloats%3A%3Alast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
