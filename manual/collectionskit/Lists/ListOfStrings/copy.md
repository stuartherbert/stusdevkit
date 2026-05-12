# ListOfStrings::copy()

> `public function copy(): static`

Creates a copy of this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfStrings extends CollectionAsList
{
    /**
     * @return static<int, string>
     */
    public function copy(): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function copy(): static`, inherited
> from [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md).
> The narrowed `static<int, string>` return type shown above is bound by
> `@extends CollectionAsList<string>` on [`ListOfStrings`](README.md).

## Description

Returns a new `ListOfStrings` containing the same strings as this list. The two instances do not share storage — appending to or merging into the copy leaves the original unchanged, and vice versa.

Useful if you want to work with immutable lists: take a copy, hand it to code that may mutate it, and keep the original intact.

Late-static binding ensures the copy is always an instance of `ListOfStrings`, not of an ancestor.

## Parameters

_None._

## Return Values

A new `ListOfStrings` instance containing the same strings in the same order. The PHP signature returns `static`, but the class's `@extends CollectionAsList<string>` binding narrows the value type to `string`. Copying an empty list returns an empty `ListOfStrings`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfStrings
 ✔ ->copy() returns a new ListOfStrings with the same data
 ✔ ->copy() returns independent instance (modifying copy does not affect original)
 ✔ ->copy() of empty list returns empty list
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfStrings`](README.md) — where the `<string>` template parameter is bound
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `ListOfStrings::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfStrings%3A%3Acopy()%22)
- [Closed issues mentioning `ListOfStrings::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfStrings%3A%3Acopy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
