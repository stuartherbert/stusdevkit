# ListOfObjects::copy()

> `public function copy(): static`

Creates a copy of this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfObjects extends CollectionAsList
{
    /**
     * @return static<int, object>
     */
    public function copy(): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function copy(): static`, inherited
> from [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md).
> The narrowed `static<int, object>` return type shown above is bound by
> `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Returns a new `ListOfObjects` containing the same objects as this list. The two instances do not share storage — appending to or merging into the copy leaves the original unchanged, and vice versa.

Useful if you want to work with immutable lists: take a copy, hand it to code that may mutate it, and keep the original intact.

Late-static binding ensures the copy is always an instance of `ListOfObjects`, not of an ancestor.

## Parameters

_None._

## Return Values

A new `ListOfObjects` instance containing the same objects in the same order. The PHP signature returns `static`, but the class's `@extends CollectionAsList<object>` binding narrows the value type to `object`. Copying an empty list returns an empty `ListOfObjects`.

## Errors/Exceptions

_None._

## Here Be Dragons

**The copy is shallow.** The new list holds the same object references as the original. Mutating a stored object via either list is observed by both — both point at the same handle. For an isolated snapshot, `clone` each object after copying.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ ->copy() returns a new ListOfObjects with the same data
 ✔ ->copy() returns independent instance (modifying copy does not affect original)
 ✔ ->copy() of empty list returns empty list
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `ListOfObjects::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3Acopy()%22)
- [Closed issues mentioning `ListOfObjects::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3Acopy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
