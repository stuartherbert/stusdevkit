# ListOfIntegers::toArray()

> `public function toArray(): array`

Return the list's stored integers as a plain PHP array.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfIntegers extends ListOfNumbers
{
    /**
     * @return array<int, int>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function toArray(): array`, inherited
> from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The narrowed `array<int, int>` return type shown above is bound by
> `@template-extends ListOfNumbers<int>` on [`ListOfIntegers`](README.md).

## Description

Returns the list's stored integers as a plain PHP array. Keys are the sequential integer keys assigned by the list (`0`, `1`, `2`, …); values are the integers in insertion order.

The returned array is a copy — mutating it does not affect the list, and mutating the list does not affect a previously-returned array.

## Parameters

_None._

## Return Values

A plain PHP array of integers. The keys are sequential integers starting at `0`; the values are the integers in the order they were inserted. Returns an empty array when the list contains no integers.

The PHP signature returns `array`, but the class's `@template-extends ListOfNumbers<int>` binding narrows this to `array<int, int>`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfIntegers
 ✔ ->toArray() returns empty array for empty list
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via add()
 ✔ All stored values are integers
 ✔ Handles negative integers correctly
 ✔ Handles boundary values correctly
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfIntegers`](README.md) — where the `<int>` template parameter is bound
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises
- [`ListOfIntegers::getIterator()`](getIterator.md) — iterator equivalent for `foreach` use

## Issues

- [Open issues mentioning `ListOfIntegers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfIntegers%3A%3AtoArray()%22)
- [Closed issues mentioning `ListOfIntegers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfIntegers%3A%3AtoArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
