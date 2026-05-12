# DictOfUuids::copy()

> `public function copy(): static`

Creates a copy of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfUuids extends DictOfObjects
{
    /**
     * @return static<string, UuidInterface>
     */
    public function copy(): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function copy(): static`, inherited from
> [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md). The
> narrowed `static<string, UuidInterface>` return type shown above is bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns a new `DictOfUuids` containing the same stored data. The new dict does not share its underlying array with the original — adding, replacing, or removing entries in one leaves the other unchanged.

Late-static binding ensures the copy is always a `DictOfUuids` (or your `DictOfUuids` subclass), never the parent `DictOfObjects` or `CollectionAsDict`.

Useful when you want to hand a dict to code that may mutate it while keeping the original intact.

## Parameters

_None._

## Return Values

A new `DictOfUuids` (or matching `DictOfUuids` subclass) containing the same `string => UuidInterface` data as `$this`. The two instances do not share the underlying array — modifying one leaves the other unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

**`copy()` is a shallow copy**, inherited from [`DictOfObjects`](../DictOfObjects/README.md). The new dict has its own key set, but the values are the same `UuidInterface` instances. Mutating a UUID retrieved from the copy mutates the same object in the original. In practice this is rarely a concern because `UuidInterface` implementations are conventionally immutable, but if you implement your own mutable `UuidInterface`, the parent's hazard applies.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->copy() returns a new DictOfUuids with the same data
 ✔ ->copy() returns independent instance (adding to copy does not affect original)
 ✔ ->copy() of empty dict returns empty dict
 ✔ ->copy() shares UUID references with original
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:282`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::merge()`](merge.md) — add the given input to this dict
- [`CollectionOfAnything::copy()`](../../CollectionOfAnything/copy.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::copy()%22)
- [Closed issues mentioning `DictOfUuids::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::copy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
