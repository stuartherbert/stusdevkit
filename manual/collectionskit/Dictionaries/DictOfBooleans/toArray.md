# DictOfBooleans::toArray()

> `public function toArray(): array`

Return the dict's stored data as a plain PHP array.

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
     * @return array<array-key, bool>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function toArray(): array`,
> inherited from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md).

## Description

Returns the dict's stored data as a plain PHP array. The returned array preserves all keys and `bool` values exactly as stored. No transformation, copy, or filtering is applied — the array is returned by value, so mutating the returned array does not affect the dict.

## Parameters

_None._

## Return Values

An `array<array-key, bool>` containing all stored data. The array preserves the original keys and flag values in their exact form. For an empty dict, returns `[]`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->toArray() returns empty array for empty dict
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via set()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::toArray()%22)
- [Closed issues mentioning `DictOfBooleans::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::toArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
