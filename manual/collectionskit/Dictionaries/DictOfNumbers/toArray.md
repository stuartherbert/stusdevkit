# DictOfNumbers::toArray()

> `public function toArray(): array`

Return the dict's stored data as a plain PHP array.

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
     * @return array<array-key, int|float>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function toArray(): array`,
> inherited from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns the dict's stored data as a plain PHP array. The returned array preserves all keys and `int|float` values exactly as stored. No transformation, copy, or filtering is applied — the array is returned by value, so mutating the returned array does not affect the dict.

## Parameters

_None._

## Return Values

An `array<array-key, int|float>` containing all stored data. The array preserves the original keys and numeric values in their exact form. For an empty dict, returns `[]`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->toArray() returns empty array for empty dict
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via set()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::toArray()%22)
- [Closed issues mentioning `DictOfNumbers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::toArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
