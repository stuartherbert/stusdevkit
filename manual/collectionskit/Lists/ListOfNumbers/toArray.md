# ListOfNumbers::toArray()

> `public function toArray(): array`

Return the list's stored numbers as a plain PHP array.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfNumbers extends CollectionAsList
{
    /**
     * @return array<int, int|float>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function toArray(): array`, inherited
> from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The narrowed types shown above come from the
> `@template TValue of int|float = int|float` on [`ListOfNumbers`](README.md);
> subclasses can pin these further (e.g.
> [`ListOfIntegers`](../ListOfIntegers/README.md) pins `TValue` to `int`,
> [`ListOfFloats`](../ListOfFloats/README.md) pins `TValue` to `float`).

## Description

Returns the list's stored numbers as a plain PHP array. Keys are the sequential integer keys assigned by the list (`0`, `1`, `2`, …); values are the numbers in insertion order, each retaining its original PHP type (`int` or `float`).

The returned array is a copy — mutating it does not affect the list, and mutating the list does not affect a previously-returned array.

## Parameters

_None._

## Return Values

A plain PHP array of numbers. The keys are sequential integers starting at `0`; the values are the numbers in the order they were inserted, with each value's `int`/`float` type preserved. Returns an empty array when the list contains no numbers.

The PHP signature returns `array`, but the class's `@template TValue of int|float = int|float` re-bound narrows this to `array<int, int|float>`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ ->toArray() returns empty array for empty list
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via add()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](README.md) — where the `<int|float>` template parameter is re-bounded
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises
- [`ListOfNumbers::getIterator()`](getIterator.md) — iterator equivalent for `foreach` use

## Issues

- [Open issues mentioning `ListOfNumbers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%3A%3AtoArray()%22)
- [Closed issues mentioning `ListOfNumbers::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%3A%3AtoArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
