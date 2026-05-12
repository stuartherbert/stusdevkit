# CollectionAsStack::maybePop()

> `public function maybePop(): mixed`

Remove and return the top value, or `null` if empty.

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
    public function maybePop(): mixed
}
```

## Description

Removes the top value from the stack and returns it. If the stack is empty, returns `null` instead of throwing.

For a throwing variant, see [`pop()`](pop.md), which raises an [`EmptyStackException`](../../Exceptions/EmptyStackException/README.md) when the stack is empty.

## Parameters

_None._

## Return Values

The value that was on top of the stack, or `null` if the stack was empty. The PHPStan-narrowed type is `TValue|null`. Because [`CollectionOfAnything`](../../CollectionOfAnything/README.md)'s constructor rejects `null` as a stored value, a `null` return unambiguously means "the stack was empty".

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->maybePop() takes no parameters and returns mixed
 ✔ ->maybePop() returns the top value and removes it from the stack
 ✔ ->maybePop() on an empty stack returns null
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:109`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L109)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::pop()`](pop.md) — same as `maybePop()` but throws when empty
- [`CollectionAsStack::maybePeek()`](maybePeek.md) — return the top value without removing it, or `null` if empty

## Issues

- [Open issues mentioning `CollectionAsStack::maybePop()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::maybePop()%22)
- [Closed issues mentioning `CollectionAsStack::maybePop()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::maybePop()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
