# CollectionAsStack::peek()

> `public function peek(): mixed`

Return the top value without removing it.

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
    public function peek(): mixed
}
```

## Description

Returns the top value of the stack without removing it. The stack is left unchanged.

For a non-throwing variant, see [`maybePeek()`](maybePeek.md), which returns `null` instead of throwing when the stack is empty.

## Parameters

_None._

## Return Values

The value currently on top of the stack. The PHPStan-narrowed type is the class's `TValue` template parameter.

## Errors/Exceptions

- **[`EmptyStackException`](../../Exceptions/EmptyStackException/README.md)** — when the stack is empty.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->peek() takes no parameters and returns mixed
 ✔ ->peek() returns the top value without removing it
 ✔ ->peek() on an empty stack throws EmptyStackException
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:131`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L131)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::maybePeek()`](maybePeek.md) — same as `peek()` but returns `null` instead of throwing when empty
- [`CollectionAsStack::pop()`](pop.md) — return the top value AND remove it
- [`CollectionAsStack::push()`](push.md) — push a value onto the top of the stack

## Issues

- [Open issues mentioning `CollectionAsStack::peek()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::peek()%22)
- [Closed issues mentioning `CollectionAsStack::peek()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::peek()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
