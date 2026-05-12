# DictOfIntegers::toArray()

> `public function toArray(): array`

Return the dict's stored data as a plain PHP array.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfIntegers extends DictOfNumbers
{
    /**
     * @return array<array-key, int>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function toArray(): array`, inherited
> from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The narrowed `array<array-key, int>` return type shown above is bound by
> `@template-extends DictOfNumbers<array-key, int>` on [`DictOfIntegers`](README.md).

## Description

Returns the dict's stored data as a plain PHP array. The returned array preserves all keys and integer values exactly as stored. No transformation, copy, or filtering is applied — the array is returned by value, so mutating the returned array does not affect the dict.

## Parameters

_None._

## Return Values

An `array<array-key, int>` containing all stored data. The array preserves the original keys and integer values in their exact form. For an empty dict, returns `[]`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers
 ✔ ->toArray() returns empty array for empty dict
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via set()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfIntegers`](README.md) — where the `<array-key, int>` template parameters are bound
- [`DictOfIntegers::getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`DictOfNumbers::toArray()`](../DictOfNumbers/toArray.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfIntegers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfIntegers::toArray()%22)
- [Closed issues mentioning `DictOfIntegers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfIntegers::toArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
