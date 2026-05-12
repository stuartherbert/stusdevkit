# DictOfObjects::copy()

> `public function copy(): static`

Creates a copy of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @return static<array-key, object>
     */
    public function copy(): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function copy(): static`,
> inherited from [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Returns a new `DictOfObjects` containing the same stored data. The new dict does not share its underlying array with the original — adding, replacing, or removing entries in one leaves the other unchanged.

Late-static binding ensures the copy is always an instance of the same runtime class — a `DictOfObjects` returns a `DictOfObjects`, a `DictOfUuids` returns a `DictOfUuids`, and so on.

Useful when you want to hand a dict to code that may mutate it while keeping the original intact.

## Parameters

_None._

## Return Values

A new dict of the same runtime type as `$this`, containing the same `array-key => object` data. The two instances do not share the underlying array — modifying one leaves the other unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

**`copy()` is a shallow copy.** The new dict has its own key set, but the values are the same object instances. Mutating an object retrieved from the copy mutates the same object in the original. If you need value semantics, clone before storing or before returning.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->copy() returns a new DictOfObjects with the same data
 ✔ ->copy() returns independent instance (adding to copy does not affect original)
 ✔ ->copy() of empty dict returns empty dict
 ✔ ->copy() shares object references with original
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::merge()`](merge.md) — add the given input to this dict
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::copy()%22)
- [Closed issues mentioning `DictOfObjects::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::copy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
