# CollectionAsStack::maybePeek()

> `public function maybePeek(): mixed`

Return the top value without removing it, or `null` if empty.

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
     * @return TValue|null
     */
    public function maybePeek(): mixed
}
```

## Description

Returns the top value of the stack without removing it. If the stack is empty, returns `null` instead of throwing. The stack is left unchanged.

For a throwing variant, see [`peek()`](peek.md), which raises an [`EmptyStackException`](../../Exceptions/EmptyStackException/README.md) when the stack is empty.

## Parameters

_None._

## Return Values

The value currently on top of the stack, or `null` if the stack is empty. The PHPStan-narrowed type is `TValue|null`. Because [`CollectionOfAnything`](../../CollectionOfAnything/README.md)'s constructor rejects `null` as a stored value, a `null` return unambiguously means "the stack is empty".

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->maybePeek() takes no parameters and returns mixed
 ✔ ->maybePeek() returns the top value without removing it
 ✔ ->maybePeek() on an empty stack returns null
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:150`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::peek()`](peek.md) — same as `maybePeek()` but throws when empty
- [`CollectionAsStack::maybePop()`](maybePop.md) — remove and return the top value, or `null` if empty

## Issues

- [Open issues mentioning `CollectionAsStack::maybePeek()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::maybePeek()%22)
- [Closed issues mentioning `CollectionAsStack::maybePeek()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::maybePeek()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
