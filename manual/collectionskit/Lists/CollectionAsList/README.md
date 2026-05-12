# CollectionAsList

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
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;
use StusDevKit\CollectionsKit\AccessibleCollection;

/**
 * @template TValue of array|bool|callable|float|int|object|string
 * @extends AccessibleCollection<int, TValue>
 */
class CollectionAsList extends AccessibleCollection
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

    // --- CollectionAsList ---

    /**
     * Add a new value to the end of the list.
     */
    public function add(mixed $value): static;
}
```

## Description

`CollectionAsList` holds a collection of data as an array with sequential integer keys.

Use this (or one of its child classes) to hold data that has no identity (i.e. no primary key). Use [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) (or one of its child classes) if your data has an identity (i.e. it has a primary key).

`CollectionAsList` is a generic specialisation of [`AccessibleCollection`](../../AccessibleCollection/README.md) that pins the key template to `int`. Subclasses can pin the value template to a specific type (e.g. `@extends CollectionAsList<string>`), giving a type-safe list of that value type.

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

**From CollectionAsList**

- [`->add()`](add.md) — add a new value to the end of the list

## Here Be Dragons

**PHPStan template inference on empty instances.** This class has a template parameter (`TValue`). When you create an empty instance (e.g. `new CollectionAsList()`), PHPStan resolves this template as `*NEVER*` because the empty array `[]` has no elements to infer types from. This causes false errors on subsequent method calls like `mergeArray()` or `mergeSelf()`.

To work around this, add a `@var` annotation when creating empty instances:

```php
/** @var CollectionAsList<string> $unit */
$unit = new CollectionAsList();
```

This is a known PHPStan limitation. There is no support for template default types yet. See [phpstan/phpstan#5065](https://github.com/phpstan/phpstan/issues/5065), [phpstan/phpstan#4801](https://github.com/phpstan/phpstan/issues/4801), and [phpstan/phpstan#6731](https://github.com/phpstan/phpstan/discussions/6731).

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\CollectionAsList
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends AccessibleCollection
 ✔ declares only the add() method of its own
 ✔ ::__construct() creates an empty list
 ✔ ::__construct() accepts initial data
 ✔ ::__construct() preserves sequential integer keys
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:83`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L83)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for data that has an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfStrings`](../ListOfStrings/README.md) — list pinned to `string` values
- [`ListOfIntegers`](../ListOfIntegers/README.md) — list pinned to `int` values
- [`ListOfFloats`](../ListOfFloats/README.md) — list pinned to `float` values
- [`ListOfNumbers`](../ListOfNumbers/README.md) — list pinned to numeric values (`int` or `float`)
- [`ListOfObjects`](../ListOfObjects/README.md) — list pinned to `object` values
- [`ListOfCallables`](../ListOfCallables/README.md) — list pinned to callable values
- [`ListOfUuids`](../ListOfUuids/README.md) — list pinned to `UuidInterface` values

## Issues

- [Open issues mentioning `CollectionAsList`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionAsList%22)
- [Closed issues mentioning `CollectionAsList`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionAsList%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionAsList%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
