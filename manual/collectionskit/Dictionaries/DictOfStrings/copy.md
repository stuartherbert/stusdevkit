# DictOfStrings::copy()

> `public function copy(): static`

Creates a copy of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @return static<array-key, string>
     */
    public function copy(): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function copy(): static`, inherited from
> [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md). The
> narrowed `static<array-key, string>` return type shown above is bound by
> `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Returns a new `DictOfStrings` containing the same stored data. The new dict does not share its underlying array with the original — adding, replacing, or removing entries in one leaves the other unchanged.

Late-static binding ensures the copy is always a `DictOfStrings` (or your `DictOfStrings` subclass), never the parent `CollectionAsDict`.

Useful when you want to hand a dict to code that may mutate it while keeping the original intact.

## Parameters

_None._

## Return Values

A new `DictOfStrings` (or matching `DictOfStrings` subclass) containing the same `array-key => string` data as `$this`. The two instances do not share the underlying array — modifying one leaves the other unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ ->copy() returns a new DictOfStrings with the same data
 ✔ ->copy() returns independent instance (modifying copy does not affect original)
 ✔ ->copy() of empty dict returns empty dict
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::merge()`](merge.md) — add the given input to this dict
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::copy()%22)
- [Closed issues mentioning `DictOfStrings::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::copy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
