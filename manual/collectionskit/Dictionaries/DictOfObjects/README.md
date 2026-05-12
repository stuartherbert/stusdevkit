# DictOfObjects

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)
- [`CollectionAsDict`](../CollectionAsDict/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

The signatures below show the PHPStan-narrowed view at the `DictOfObjects` level, with `TValue` re-bounded to `object`. `TKey` retains the `array-key` upper bound from [`CollectionAsDict`](../CollectionAsDict/README.md). The runtime PHP signatures (which use `mixed` / `array`) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use StusDevKit\CollectionsKit\AccessibleCollection;

/**
 * @template TKey of array-key
 * @template TValue of object = object
 * @extends CollectionAsDict<TKey, TValue>
 * @phpstan-consistent-constructor
 */
class DictOfObjects extends CollectionAsDict
{
    // --- CollectionOfAnything ---

    /**
     * Create a new dict of objects, optionally seeded with data.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the dict's stored data as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of elements stored in the collection.
     */
    public function count(): int;

    /**
     * Return an iterator over the dict's stored data.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the collection contains no elements.
     */
    public function empty(): bool;

    /**
     * Creates a copy of this dict.
     */
    public function copy(): static;

    /**
     * Return the unqualified class name of this collection type.
     */
    public function getCollectionTypeAsString(): string;

    // --- AccessibleCollection ---

    /**
     * Add the given `$input` to this dict.
     */
    public function merge(AccessibleCollection|array $input): static;

    /**
     * Add the given `$input` to this dict.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the contents of `$input` into this dict.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first object stored in this dict.
     */
    public function maybeFirst(): ?object;

    /**
     * Returns the first object stored in this dict.
     */
    public function first(): object;

    /**
     * Returns the last object of this dict.
     */
    public function maybeLast(): ?object;

    /**
     * Returns the last object of this dict.
     */
    public function last(): object;

    // --- CollectionAsDict ---

    /**
     * Store an object in the dict.
     */
    public function set(int|string $key, object $value): static;

    /**
     * Return an object from the dict.
     */
    public function maybeGet(int|string $key): ?object;

    /**
     * Return an object from the dict.
     */
    public function get(int|string $key): object;

    /**
     * Check to see if we have a value for the given `$key` in this collection.
     */
    public function has($key): bool;
}
```

## Description

`DictOfObjects` holds a collection of objects that have identity (i.e. they have a primary key or equivalent of some kind).

It is a generic specialisation of [`CollectionAsDict`](../CollectionAsDict/README.md) that narrows the value template to `object`. Subclasses can narrow further to pin a specific class (for example `@extends DictOfObjects<string, MyEntity>`), giving a type-safe dictionary of that class's instances.

`DictOfObjects` declares no methods of its own — the entire surface comes from the parent chain.

## Methods

Methods whose narrowed signatures differ from the parent — anything that references `TValue` (re-bounded to `object` on `DictOfObjects`) — link to a `DictOfObjects`-specific page that shows the narrowed view. Methods that reference only `TKey` (whose `array-key` bound is set at the parent, not here) or no templates at all link directly to the parent page.

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new dict of objects, optionally seeded with data
- [`->copy()`](copy.md) — creates a copy of this dict
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`->toArray()`](toArray.md) — return the dict's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first object stored in this dict (throws when empty)
- [`->last()`](last.md) — returns the last object of this dict (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first object stored in this dict (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last object of this dict (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict

**From CollectionAsDict**

- [`->get()`](get.md) — return an object from the dict, throwing if absent
- [`->has()`](../CollectionAsDict/has.md) — check whether a value exists for the given key
- [`->maybeGet()`](maybeGet.md) — return an object from the dict, or `null` if absent
- [`->set()`](set.md) — store an object in the dict

**From DictOfObjects**

_No own public methods — the class exists to narrow the value template to `object`._

## Here Be Dragons

**Objects are stored by reference, not by value.** Three knock-on consequences bite callers who expect "collection" to mean "self-contained":

1. **Mutating a retrieved object mutates the dict's copy too.** `$dict->get('key')->name = 'new'` is not a footgun — it's working as designed — but it surprises callers who treat `get()` as if it returned a value.
2. **`copy()` is a shallow copy.** The new dict has its own key set, but the values are the same object instances. Mutating an object retrieved from the copy mutates the same object in the original.
3. **`mergeSelf()` and `mergeArray()` transfer object references, not clones.** After the merge, both dicts hold pointers to the same objects.

If you need value semantics, clone before storing or before returning.

**PHPStan template inference on empty instances.** This class has template parameters (`TKey`, `TValue`) so that subclasses can narrow the allowed types. When you create an empty instance (e.g. `new DictOfObjects()`), PHPStan resolves these templates as `*NEVER*` because the empty array `[]` has no elements to infer types from. This causes false errors on subsequent method calls like `mergeArray()` or `get()`.

To work around this, add a `@var` annotation when creating empty instances:

```php
/** @var DictOfObjects<string, stdClass> $unit */
$unit = new DictOfObjects();
```

This is a known PHPStan limitation. There is no support for template default types yet. See [phpstan/phpstan#5065](https://github.com/phpstan/phpstan/issues/5065), [phpstan/phpstan#4801](https://github.com/phpstan/phpstan/issues/4801), and [phpstan/phpstan#6731](https://github.com/phpstan/phpstan/discussions/6731).

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends CollectionAsDict
 ✔ declares no public methods of its own beyond inherited methods
 ✔ Extends CollectionAsDict
 ✔ Can hold mixed object types
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
 ✔ Iteration includes items added via set()
 ✔ Dict with one object: ->first() and ->last() return the same object
 ✔ Preserves object identity (same instance, not a copy)
 ✔ Mutations to retrieved object are visible through the dict
 ✔ All stored values are objects
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfObjects.php:102`](../../../../kits/collectionskit/src/Dictionaries/DictOfObjects.php#L102)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict`](../CollectionAsDict/README.md) — base class providing keyed access (`set` / `get` / `maybeGet` / `has`)
- [`IndexOfEntitiesWithStringIds`](../../Indexes/IndexOfEntitiesWithStringIds/README.md) — index variant where entities provide their own string IDs
- [`IndexOfEntitiesWithUuids`](../../Indexes/IndexOfEntitiesWithUuids/README.md) — index variant where entities provide their own UUID IDs

## Issues

- [Open issues mentioning `DictOfObjects`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects%22)
- [Closed issues mentioning `DictOfObjects`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
