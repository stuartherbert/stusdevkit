# CollectionAsStack::toArray()

> `public function toArray(): array`

Return the stack values as an array in LIFO order.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Stacks\CollectionAsStack`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Stacks;

class CollectionAsStack
{
    /**
     * @return array<int, TValue>
     */
    public function toArray(): array
}
```

## Description

Returns the stack values as a plain PHP array in LIFO order — the top of the stack is at index `0`, the bottom is last. The stack itself is not modified.

This method **overrides** [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md), which would otherwise return values in insertion (FIFO) order. The override exists so callers don't have to remember to reverse the array themselves to get top-first ordering.

## Parameters

_None._

## Return Values

An `array<int, TValue>` containing every value currently on the stack, top-first. Indices are sequential integers starting from `0`. For an empty stack, returns `[]`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->toArray() takes no parameters and returns array
 ✔ ->toArray() returns values in LIFO order (top first)
 ✔ ->toArray() on an empty stack returns an empty array
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:164`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L164)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::getIterator()`](getIterator.md) — iterate the stack in LIFO order without copying
- [`CollectionOfAnything::toArray()`](../../CollectionOfAnything/toArray.md) — the inherited FIFO-ordered version this method overrides

## Issues

- [Open issues mentioning `CollectionAsStack::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::toArray()%22)
- [Closed issues mentioning `CollectionAsStack::toArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::toArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
