# CollectionAsStack

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Stacks;

use ArrayIterator;
use StusDevKit\CollectionsKit\CollectionOfAnything;

/**
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends CollectionOfAnything<int, TValue>
 */
class CollectionAsStack extends CollectionOfAnything
{
    // --- CollectionOfAnything ---

    /**
     * Create a new collection, optionally seeded with data.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the number of elements stored in the collection.
     */
    public function count(): int;

    /**
     * Return true if the collection contains no elements.
     */
    public function empty(): bool;

    /**
     * Creates a copy of this collection.
     */
    public function copy(): static;

    /**
     * Return the unqualified class name of this collection type.
     */
    public function getCollectionTypeAsString(): string;

    // --- CollectionAsStack ---

    /**
     * push a value onto the top of the stack
     */
    public function push(mixed $value): static;

    /**
     * remove and return the top value from the stack
     */
    public function pop(): mixed;

    /**
     * remove and return the top value, or null if empty
     */
    public function maybePop(): mixed;

    /**
     * return the top value without removing it
     */
    public function peek(): mixed;

    /**
     * return the top value without removing it, or null if empty
     */
    public function maybePeek(): mixed;

    /**
     * return the stack values as an array in LIFO order
     */
    public function toArray(): array;

    /**
     * iterate the stack in LIFO order (top to bottom)
     */
    public function getIterator(): ArrayIterator;
}
```

## Description

`CollectionAsStack` holds a collection of data as a last-in-first-out (LIFO) stack.

Items are added with [`push()`](push.md) and removed with [`pop()`](pop.md). The most recently pushed item is always at the top of the stack.

Unlike [`CollectionAsList`](../../Lists/CollectionAsList/README.md), stacks do not support random access. Iteration via `foreach` is supported and yields values in LIFO order (top to bottom) without modifying the stack.

`CollectionAsStack` overrides two inherited methods to enforce LIFO order: `toArray()` returns the data top-first, and `getIterator()` yields values top-first.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](../../CollectionOfAnything/__construct.md) — create a new collection, optionally seeded with data
- [`->copy()`](../../CollectionOfAnything/copy.md) — creates a copy of this collection
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type

**From CollectionAsStack**

- [`->getIterator()`](getIterator.md) — iterate the stack in LIFO order (top to bottom); overrides `CollectionOfAnything::getIterator()`
- [`->maybePeek()`](maybePeek.md) — return the top value without removing it, or `null` if empty
- [`->maybePop()`](maybePop.md) — remove and return the top value, or `null` if empty
- [`->peek()`](peek.md) — return the top value without removing it (throws when empty)
- [`->pop()`](pop.md) — remove and return the top value from the stack (throws when empty)
- [`->push()`](push.md) — push a value onto the top of the stack
- [`->toArray()`](toArray.md) — return the stack values as an array in LIFO order; overrides `CollectionOfAnything::toArray()`

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Stacks\CollectionAsStack
 ✔ lives in the StusDevKit\CollectionsKit\Stacks namespace
 ✔ is declared as a class
 ✔ extends CollectionOfAnything
 ✔ ::__construct() creates an empty stack
 ✔ ::__construct() rejects arrays that contain null values
 ✔ declares exactly the expected set of own public methods
```

## Source

[`kits/collectionskit/src/Stacks/CollectionAsStack.php:66`](../../../../kits/collectionskit/src/Stacks/CollectionAsStack.php#L66)

## Changelog

_No tagged releases yet._

## See Also

- [`StackOfStrings`](../StackOfStrings/README.md) — concrete stack pinned to `string` values
- [`CollectionOfAnything`](../../CollectionOfAnything/README.md) — root base class for every collection type
- [`CollectionAsList`](../../Lists/CollectionAsList/README.md) — list variant that does support random access
- [`EmptyStackException`](../../Exceptions/EmptyStackException/README.md) — thrown by `pop()` and `peek()` when the stack is empty

## Issues

- [Open issues mentioning `CollectionAsStack`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsStack%22)
- [Closed issues mentioning `CollectionAsStack`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsStack%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsStack%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
