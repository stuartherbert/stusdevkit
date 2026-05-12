# ListOfObjects::toArray()

> `public function toArray(): array`

Return the list's stored objects as a plain PHP array.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\CollectionOfAnything`](../../CollectionOfAnything/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfObjects extends CollectionAsList
{
    /**
     * @return array<int, object>
     */
    public function toArray(): array
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function toArray(): array`, inherited
> from [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md).
> The narrowed `array<int, object>` return type shown above is bound by
> `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Returns the list's stored objects as a plain PHP array. Keys are the sequential integer keys assigned by the list (`0`, `1`, `2`, …); values are the objects in insertion order — the same handles the caller stored.

The returned array is a copy of the internal data — mutating it does not affect the list, and mutating the list does not affect a previously-returned array. Note, however, that the objects inside the array are still references: mutating an object retrieved from `toArray()` mutates the object the list still holds.

## Parameters

_None._

## Return Values

A plain PHP array of objects. The keys are sequential integers starting at `0`; the values are the objects in the order they were inserted. Returns an empty array when the list contains no objects.

The PHP signature returns `array`, but the class's `@extends CollectionAsList<object>` binding narrows this to `array<int, object>`.

## Errors/Exceptions

_None._

## Here Be Dragons

**The array is shallow.** Object references are shared between the list and the returned array — the array is a separate container, but the objects inside it are not cloned. If you need detached copies, deep-clone after calling `toArray()`.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ ->toArray() returns empty array for empty list
 ✔ ->toArray() returns the internal data as a PHP array
 ✔ ->toArray() returns data added via add()
 ✔ All stored values are objects
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:179`](../../../../kits/collectionskit/src/CollectionOfAnything.php#L179)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the generic implementation this page specialises
- [`ListOfObjects::getIterator()`](getIterator.md) — iterator equivalent for `foreach` use

## Issues

- [Open issues mentioning `ListOfObjects::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3AtoArray()%22)
- [Closed issues mentioning `ListOfObjects::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3AtoArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
