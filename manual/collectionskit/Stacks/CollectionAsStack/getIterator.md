# CollectionAsStack::getIterator()

> `public function getIterator(): ArrayIterator`

Iterate the stack in LIFO order (top to bottom).

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Stacks\CollectionAsStack`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Stacks;

use ArrayIterator;

class CollectionAsStack
{
    /**
     * @return ArrayIterator<int, TValue>
     */
    public function getIterator(): ArrayIterator
}
```

## Description

Returns an [`ArrayIterator`](https://www.php.net/manual/en/class.arrayiterator.php) that yields the stack's values in LIFO order — top of the stack first, bottom last. The stack itself is not modified by iteration.

This method **overrides** [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md), which would otherwise yield values in insertion (FIFO) order. The override exists so `foreach ($stack as $value)` walks the stack top-first, matching the expectations of stack callers.

The iterator wraps a reversed copy of the stack's internal data, so callers can iterate freely without affecting subsequent `pop()` / `peek()` operations.

## Parameters

_None._

## Return Values

An `ArrayIterator<int, TValue>` over the stack's values in LIFO order. For an empty stack, the iterator produces no iterations.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->getIterator() takes no parameters and returns ArrayIterator
 ✔ ->getIterator() yields values in LIFO order (top to bottom)
 ✔ ->getIterator() on an empty stack produces no iterations
 ✔ ->getIterator() does not modify the stack during iteration
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:182`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L182)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::toArray()`](toArray.md) — return the stack values as an array, also in LIFO order
- [`CollectionOfAnything::getIterator()`](../../CollectionOfAnything/getIterator.md) — the inherited FIFO-ordered version this method overrides

## Issues

- [Open issues mentioning `CollectionAsStack::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::getIterator()%22)
- [Closed issues mentioning `CollectionAsStack::getIterator()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::getIterator()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
