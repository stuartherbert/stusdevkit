# ListOfStrings::first()

> `public function first(): string`

Returns the first string stored in this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use RuntimeException;

class ListOfStrings extends CollectionAsList
{
    /**
     * @return string
     * @throws RuntimeException
     */
    public function first(): string
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function first(): mixed`, inherited
> from [`AccessibleCollection::first()`](../../AccessibleCollection/first.md).
> The narrowed `string` return type shown above is bound by
> `@extends CollectionAsList<string>` on [`ListOfStrings`](README.md).

## Description

Returns the first string stored in this list, or throws if the list is empty. This is the throwing counterpart to [`maybeFirst()`](maybeFirst.md). Use it when an empty list at this point in your code is a programming error rather than an expected branch — the exception names `ListOfStrings` so the failure is easy to diagnose.

The "first" string is the one at the lowest sequential integer key (`0`, by construction) — the entry that was supplied first to the constructor or appended first via [`add()`](add.md).

## Parameters

_None._

## Return Values

The first string stored in the list. The PHP signature returns `mixed`, but the class's `@extends CollectionAsList<string>` binding narrows this to `string`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the list is empty. The message is `ListOfStrings is empty`, derived from [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfStrings
 ✔ ->first() returns the first string
 ✔ ->first() throws RuntimeException for empty list
 ✔ List with one string: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:257`](../../../../kits/collectionskit/src/AccessibleCollection.php#L257)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfStrings`](README.md) — where the `<string>` template parameter is bound
- [`AccessibleCollection::first()`](../../AccessibleCollection/first.md) — the generic implementation this page specialises
- [`ListOfStrings::maybeFirst()`](maybeFirst.md) — non-throwing variant for "may be empty" call sites
- [`ListOfStrings::last()`](last.md) — last-string counterpart

## Issues

- [Open issues mentioning `ListOfStrings::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfStrings%3A%3Afirst()%22)
- [Closed issues mentioning `ListOfStrings::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfStrings%3A%3Afirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
