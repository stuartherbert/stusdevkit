# DictOfNumbers::copy()

> `public function copy(): static`

Creates a copy of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @return static<array-key, int|float>
     */
    public function copy(): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function copy(): static`,
> inherited from [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns a new `DictOfNumbers` containing the same stored data. The new dict does not share its underlying array with the original — adding, replacing, or removing entries in one leaves the other unchanged.

Late-static binding ensures the copy is always an instance of the same runtime class — a `DictOfNumbers` returns a `DictOfNumbers`, a `DictOfIntegers` returns a `DictOfIntegers`, and so on.

Useful when you want to hand a dict to code that may mutate it while keeping the original intact.

## Parameters

_None._

## Return Values

A new dict of the same runtime type as `$this`, containing the same `array-key => int|float` data. The two instances do not share the underlying array — modifying one leaves the other unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->copy() returns a new DictOfNumbers with the same data
 ✔ ->copy() returns independent instance (modifying copy does not affect original)
 ✔ ->copy() of empty dict returns empty dict
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::merge()`](merge.md) — add the given input to this dict
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::copy()%22)
- [Closed issues mentioning `DictOfNumbers::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::copy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
