# CollectionAsStack::push()

> `public function push(mixed $value): static`

Push a value onto the top of the stack.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Stacks\CollectionAsStack`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Stacks;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class CollectionAsStack
{
    /**
     * @param TValue $value
     * @throws NullValueNotAllowedException
     */
    public function push(mixed $value): static
}
```

## Description

Adds `$value` to the top of the stack. The value becomes the new top — the next `pop()` or `peek()` will return it.

You cannot push `null` onto the stack: the constructor rejects null on construction, and [`pop()`](pop.md) / [`peek()`](peek.md) use `null` as their empty-stack sentinel, so accepting null here would break those guarantees.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so subclasses preserve their own type when chaining.

## Parameters

**`$value`** (`mixed`)

The value to push onto the stack. The PHPStan-narrowed type is the class's `TValue` template parameter (`array|bool|callable|float|int|object|string`); subclasses can pin it further. `null` is not permitted.

## Return Values

Returns `$this` — the same stack instance, with `$value` now at the top.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ ->push() accepts a mixed value and returns static
 ✔ ->push() adds a value to the top of the stack
 ✔ ->push() returns the same instance for fluent chaining
 ✔ ->push() appends successive values so the last pushed is on top
 ✔ ->push() rejects null values
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:86`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L86)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsStack::pop()`](pop.md) — remove and return the top value
- [`CollectionAsStack::peek()`](peek.md) — return the top value without removing it

## Issues

- [Open issues mentioning `CollectionAsStack::push()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack::push()%22)
- [Closed issues mentioning `CollectionAsStack::push()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack::push()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
