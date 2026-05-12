# CollectionAsStack::pop()

> `public function pop(): mixed`

Remove and return the top value from the stack.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Stacks\CollectionAsStack`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Stacks;

use StusDevKit\CollectionsKit\Exceptions\EmptyStackException;

class CollectionAsStack
{
    /**
     * @return TValue
     * @throws EmptyStackException
     */
    public function pop(): mixed
}
```

## Description

Removes the top value from the stack and returns it. The next-most-recently-pushed value becomes the new top.

For a non-throwing variant, see [`maybePop()`](maybePop.md), which returns `null` instead of throwing when the stack is empty.

## Parameters

_None._

## Return Values

The value that was on top of the stack. The PHPStan-narrowed type is the class's `TValue` template parameter.

## Errors/Exceptions

- **[`EmptyStackException`](../../Exceptions/EmptyStackException/README.md)** — when the stack is empty.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->pop() takes no parameters and returns mixed
 ✔ ->pop() returns the top value and removes it from the stack
 ✔ ->pop() on an empty stack throws EmptyStackException
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:91`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L91)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::maybePop()`](maybePop.md) — same as `pop()` but returns `null` instead of throwing when empty
- [`CollectionAsStack::peek()`](peek.md) — return the top value without removing it
- [`CollectionAsStack::push()`](push.md) — push a value onto the top of the stack

## Issues

- [Open issues mentioning `CollectionAsStack::pop()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::pop()%22)
- [Closed issues mentioning `CollectionAsStack::pop()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::pop()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
