# ListOfCallables::toArray()

> `public function toArray(): array`

Return the list's stored callables as a plain PHP array.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfCallables`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfCallables extends CollectionAsList
{
    /**
     * @return array<int, callable>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function toArray(): array`, inherited
> from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The narrowed `array<int, callable>` return type shown above is bound by
> `@extends CollectionAsList<callable>` on [`ListOfCallables`](README.md).

## Description

Returns the list's stored callables as a plain PHP array. Keys are the sequential integer keys assigned by the list (`0`, `1`, `2`, …); values are the callables in insertion order.

The returned array is a copy — mutating it does not affect the list, and mutating the list does not affect a previously-returned array.

## Parameters

_None._

## Return Values

A plain PHP array of callables. The keys are sequential integers starting at `0`; the values are the callables in the order they were inserted. Returns an empty array when the list contains no callables.

The PHP signature returns `array`, but the class's `@extends CollectionAsList<callable>` binding narrows this to `array<int, callable>`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfCallables
 ✔ ->toArray() returns empty array for empty list
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via add()
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfCallables`](README.md) — where the `<callable>` template parameter is bound
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises
- [`ListOfCallables::getIterator()`](getIterator.md) — iterator equivalent for `foreach` use

## Issues

- [Open issues mentioning `ListOfCallables::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfCallables%3A%3AtoArray()%22)
- [Closed issues mentioning `ListOfCallables::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfCallables%3A%3AtoArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfCallables%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
