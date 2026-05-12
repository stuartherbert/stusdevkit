# DictOfFloats

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)
- [`CollectionAsDict`](../CollectionAsDict/README.md)
- [`DictOfNumbers`](../DictOfNumbers/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `array-key` and `TValue` resolved to `float` via the `@template-extends DictOfNumbers<array-key, float>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use StusDevKit\CollectionsKit\AccessibleCollection;

/**
 * @template-extends DictOfNumbers<array-key, float>
 */
class DictOfFloats extends DictOfNumbers
{
    // --- CollectionOfAnything ---

    /**
     * Create a new dict of floats, optionally seeded with data.
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
     * Returns the first float stored in this dict.
     */
    public function maybeFirst(): ?float;

    /**
     * Returns the first float stored in this dict.
     */
    public function first(): float;

    /**
     * Returns the last float of this dict.
     */
    public function maybeLast(): ?float;

    /**
     * Returns the last float of this dict.
     */
    public function last(): float;

    // --- CollectionAsDict ---

    /**
     * Store a float in the dict.
     */
    public function set(int|string $key, float $value): static;

    /**
     * Return a float from the dict.
     */
    public function maybeGet(int|string $key): ?float;

    /**
     * Return a float from the dict.
     */
    public function get(int|string $key): float;

    /**
     * Check to see if we have a float for the given `$key` in this dict.
     */
    public function has(int|string $key): bool;
}
```

## Description

`DictOfFloats` holds a collection of floats.

It is a thin specialisation of [`DictOfNumbers`](../DictOfNumbers/README.md) that pins the value template to `float`. The class adds no public methods of its own — the entire surface is inherited from the parents in the chain.

## Methods

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `DictOfFloats`-specific page that shows the resolved-type view. Methods with no generics (`count()`, `empty()`, `getCollectionTypeAsString()`) link directly to the parent page.

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new dict of floats, optionally seeded with data
- [`->copy()`](copy.md) — creates a copy of this dict
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`->toArray()`](toArray.md) — return the dict's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first float stored in this dict (throws when empty)
- [`->last()`](last.md) — returns the last float of this dict (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first float stored in this dict (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last float of this dict (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict

**From CollectionAsDict**

- [`->get()`](get.md) — return a float from the dict, throwing if absent
- [`->has()`](has.md) — check whether a float exists for the given key
- [`->maybeGet()`](maybeGet.md) — return a float from the dict, or `null` if absent
- [`->set()`](set.md) — store a float in the dict

**From DictOfNumbers**

_No own public methods — DictOfNumbers exists to bind the value template to `int|float`._

**From DictOfFloats**

_No own public methods — the class exists to pin the value template to `float`._

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfFloats
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends DictOfNumbers
 ✔ declares no public methods of its own beyond inherited methods
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
 ✔ All stored values are floats
 ✔ Handles negative floats correctly
 ✔ Preserves float precision
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfFloats.php:49`](../../../../kits/collectionskit/src/Dictionaries/DictOfFloats.php#L49)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](../DictOfNumbers/README.md) — parent class, accepts any `int|float` value
- [`DictOfIntegers`](../DictOfIntegers/README.md) — sibling specialisation, integer values only
- [`CollectionAsDict`](../CollectionAsDict/README.md) — base class providing keyed access (`set` / `get` / `maybeGet` / `has`)

## Issues

- [Open issues mentioning `DictOfFloats`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfFloats%22)
- [Closed issues mentioning `DictOfFloats`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfFloats%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
