# CollectionAsDict

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;

/**
 * @template TKey of array-key
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends AccessibleCollection<TKey,TValue>
 */
class CollectionAsDict extends AccessibleCollection
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
     * Creates a copy of this collection.
     */
    public function copy(): static;

    /**
     * Return the unqualified class name of this collection type.
     */
    public function getCollectionTypeAsString(): string;

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

    // --- CollectionAsDict ---

    /**
     * Store a value in the collection.
     */
    public function set(mixed $key, mixed $value): static;

    /**
     * Return a value from the collection.
     */
    public function maybeGet($key): mixed;

    /**
     * Return a value from the collection.
     */
    public function get($key): mixed;

    /**
     * Check to see if we have a value for the given `$key` in this collection.
     */
    public function has($key): bool;
}
```

## Description

`CollectionAsDict` holds a collection of data that has identity — entries with a primary key of some kind. Extend it to create stronger-typed dictionaries for your specific classes.

Use [`CollectionAsList`](../../Lists/CollectionAsList/README.md) (or one of its child classes) instead when the data has no identity (no primary key).

The class adds keyed access on top of the storage / iteration / merging behaviour inherited from [`AccessibleCollection`](../../AccessibleCollection/README.md):

- **Mutation** — `set()` stores a value at a given key. Null values are rejected by [`RejectNullValue`](../../Validators/RejectNullValue/README.md) so the no-null invariant of every CollectionsKit collection holds.
- **Retrieval** — `get()` returns the value at a key (throwing if absent); `maybeGet()` is the non-throwing counterpart and returns `null` instead.
- **Existence test** — `has()` reports whether a value exists for the given key.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](../../CollectionOfAnything/__construct.md) — create a new collection, optionally seeded with data
- [`->copy()`](../../CollectionOfAnything/copy.md) — creates a copy of this collection
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](../../CollectionOfAnything/getIterator.md) — return an iterator over the collection's stored data
- [`->toArray()`](../../CollectionOfAnything/toArray.md) — return the collection's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](../../AccessibleCollection/first.md) — returns the first value stored in this collection (throws when empty)
- [`->last()`](../../AccessibleCollection/last.md) — returns the last value of this collection (throws when empty)
- [`->maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — returns the first value stored in this collection (returns `null` when empty)
- [`->maybeLast()`](../../AccessibleCollection/maybeLast.md) — returns the last value of this collection (returns `null` when empty)
- [`->merge()`](../../AccessibleCollection/merge.md) — adds the given input (array or compatible collection) to this collection
- [`->mergeArray()`](../../AccessibleCollection/mergeArray.md) — adds the contents of the given array to this collection
- [`->mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — copies the contents of a compatible collection into this collection

**From CollectionAsDict**

- [`->set()`](set.md) — store a value in the collection
- [`->get()`](get.md) — return a value from the collection, throwing if absent
- [`->maybeGet()`](maybeGet.md) — return a value from the collection, or `null` if absent
- [`->has()`](has.md) — check whether a value exists for the given key

## Here Be Dragons

**PHPStan template inference on empty instances.** When you instantiate `CollectionAsDict` with no seed data, PHPStan resolves the `TKey` and `TValue` template parameters as `*NEVER*` because an empty array carries no type information. This causes false-positive errors on subsequent method calls such as `mergeArray()` or `get()`.

Workaround: annotate the variable with `@var` at the point of creation:

    // @var CollectionAsDict<string, string> $unit
    $unit = new CollectionAsDict();

This is a known PHPStan limitation; there is no support for template default types yet. See the linked PHPStan issues for background.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends AccessibleCollection
 ✔ declares only get/has/maybeGet/set as its own public methods
 ✔ dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:85`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L85)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection`](../../AccessibleCollection/README.md) — base class providing first/last accessors and the merge family
- [`CollectionOfAnything`](../../CollectionOfAnything/README.md) — root base class providing storage, counting, iteration, and copying
- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) — interface for classes that can return their internal state as a PHP array

## Issues

- [Open issues mentioning `CollectionAsDict`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsDict%22)
- [Closed issues mentioning `CollectionAsDict`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsDict%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsDict%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
