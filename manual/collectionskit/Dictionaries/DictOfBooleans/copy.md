# DictOfBooleans::copy()

> `public function copy(): static`

Creates a copy of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @return static<array-key, bool>
     */
    public function copy(): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function copy(): static`,
> inherited from [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md).

## Description

Returns a new `DictOfBooleans` containing the same stored data. The new dict does not share its underlying array with the original — adding, replacing, or removing entries in one leaves the other unchanged.

Late-static binding ensures the copy is always an instance of the same runtime class — a `DictOfBooleans` returns a `DictOfBooleans`.

Useful when you want to hand a dict to code that may mutate it while keeping the original intact.

## Parameters

_None._

## Return Values

A new `DictOfBooleans` (or matching `DictOfBooleans` subclass) containing the same `array-key => bool` data as `$this`. The two instances do not share the underlying array — modifying one leaves the other unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->copy() returns a new DictOfBooleans with the same data
 ✔ ->copy() returns independent instance (modifying copy does not affect original)
 ✔ ->copy() of empty dict returns empty dict
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::merge()`](merge.md) — add the given input to this dict
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::copy()%22)
- [Closed issues mentioning `DictOfBooleans::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::copy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
