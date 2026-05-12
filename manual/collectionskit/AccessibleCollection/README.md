# AccessibleCollection

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../CollectionOfAnything/README.md)

**Implements:**

- [`Arrayable`](../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use ArrayIterator;

/**
 * @template TKey of array-key
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends CollectionOfAnything<TKey, TValue>
 * @phpstan-consistent-constructor
 */
class AccessibleCollection extends CollectionOfAnything
{
    // --- CollectionOfAnything ---

    /**
     * Create a new collection, optionally seeded with data.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the collection's stored data as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of elements stored in the collection.
     */
    public function count(): int;

    /**
     * Return an iterator over the collection's stored data.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the collection contains no elements.
     */
    public function empty(): bool;

    /**
     * Return the unqualified class name of this collection type.
     */
    public function getCollectionTypeAsString(): string;

    /**
     * Creates a copy of this collection.
     */
    public function copy(): static;

    // --- AccessibleCollection ---

    /**
     * Add the given `$input` to this collection.
     */
    public function merge(AccessibleCollection|array $input): static;

    /**
     * Add the given `$input` to this collection.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the contents of `$input` into this collection.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first value stored in this collection.
     */
    public function maybeFirst(): mixed;

    /**
     * Returns the first value stored in this collection.
     */
    public function first(): mixed;

    /**
     * Returns the last value of this collection.
     */
    public function maybeLast(): mixed;

    /**
     * Returns the last value of this collection.
     */
    public function last(): mixed;
}
```

## Description

`AccessibleCollection` extends [`CollectionOfAnything`](../CollectionOfAnything/README.md) with methods for accessing arbitrary elements, merging collections, and copying.

It is the base class for Lists, Dictionaries, and Indexes — collection types that allow unrestricted access to their contents. Stacks intentionally do not extend this class because they restrict access to the top element only.

The class adds three families of operations on top of the storage / counting / iteration behaviour inherited from `CollectionOfAnything`:

- **Element access** — `first()` / `maybeFirst()` and `last()` / `maybeLast()` return the first or last stored value, with paired throwing and nullable accessors.
- **Merging** — `merge()` dispatches to `mergeArray()` or `mergeSelf()` depending on the input type. `mergeSelf()` enforces a late-static-binding compatibility check via the protected `canMerge()` helper.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](../CollectionOfAnything/__construct.md) — create a new collection, optionally seeded with data
- [`->copy()`](../CollectionOfAnything/copy.md) — creates a copy of this collection
- [`->count()`](../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](../CollectionOfAnything/getIterator.md) — return an iterator over the collection's stored data
- [`->toArray()`](../CollectionOfAnything/toArray.md) — return the collection's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first value stored in this collection (throws when empty)
- [`->last()`](last.md) — returns the last value of this collection (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first value stored in this collection (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last value of this collection (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible collection) to this collection
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this collection
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible collection into this collection

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\AccessibleCollection
 ✔ lives in the StusDevKit\CollectionsKit namespace
 ✔ is declared as a class
 ✔ extends CollectionOfAnything
 ✔ declares the expected set of own public methods
 ✔ Collection can be iterated with foreach
 ✔ Iterating empty collection produces no iterations
 ✔ Iteration preserves string keys
 ✔ Collection can hold mixed types
 ✔ Merge methods support fluent chaining
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:65`](../../../kits/collectionskit/src/AccessibleCollection.php#L65)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything`](../CollectionOfAnything/README.md) — root base class for every collection type in CollectionsKit
- [`Arrayable`](../../missingbitskit/Arrays/Arrayable/README.md) — interface for classes that can return their internal state as a PHP array

## Issues

- [Open issues mentioning `AccessibleCollection`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22AccessibleCollection%22)
- [Closed issues mentioning `AccessibleCollection`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22AccessibleCollection%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=AccessibleCollection%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
