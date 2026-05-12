# DictOfNumbers

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

The signatures below show the PHPStan-narrowed view at the `DictOfNumbers` level, with `TValue` re-bounded to `int|float`. `TKey` retains the `array-key` upper bound from [`CollectionAsDict`](../CollectionAsDict/README.md). The runtime PHP signatures (which use `mixed` / `array`) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use StusDevKit\CollectionsKit\AccessibleCollection;

/**
 * @template TKey of array-key
 * @template TValue of int|float
 * @template-extends CollectionAsDict<TKey, TValue>
 */
class DictOfNumbers extends CollectionAsDict
{
    // --- CollectionOfAnything ---

    /**
     * Create a new dict of numbers, optionally seeded with data.
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
     * Returns the first number stored in this dict.
     */
    public function maybeFirst(): int|float|null;

    /**
     * Returns the first number stored in this dict.
     */
    public function first(): int|float;

    /**
     * Returns the last number of this dict.
     */
    public function maybeLast(): int|float|null;

    /**
     * Returns the last number of this dict.
     */
    public function last(): int|float;

    // --- CollectionAsDict ---

    /**
     * Store a number in the dict.
     */
    public function set(int|string $key, int|float $value): static;

    /**
     * Return a number from the dict.
     */
    public function maybeGet(int|string $key): int|float|null;

    /**
     * Return a number from the dict.
     */
    public function get(int|string $key): int|float;

    /**
     * Check to see if we have a value for the given `$key` in this collection.
     */
    public function has($key): bool;
}
```

## Description

`DictOfNumbers` holds a collection of numbers.

It is a thin specialisation of [`CollectionAsDict`](../CollectionAsDict/README.md) that narrows the value template to `int|float`. The class adds no public methods of its own — the entire surface is inherited from the parents in the chain. Use it (or one of its subclasses, [`DictOfIntegers`](../DictOfIntegers/README.md) or [`DictOfFloats`](../DictOfFloats/README.md)) when you want a dictionary whose values are statically guaranteed to be numeric.

## Methods

Methods whose narrowed signatures differ from the parent — anything that references `TValue` (re-bounded to `int|float` on `DictOfNumbers`) — link to a `DictOfNumbers`-specific page that shows the narrowed view. Methods that reference only `TKey` (whose `array-key` bound is set at the parent, not here) or no templates at all link directly to the parent page.

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new dict of numbers, optionally seeded with data
- [`->copy()`](copy.md) — creates a copy of this dict
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`->toArray()`](toArray.md) — return the dict's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first number stored in this dict (throws when empty)
- [`->last()`](last.md) — returns the last number of this dict (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first number stored in this dict (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last number of this dict (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict

**From CollectionAsDict**

- [`->get()`](get.md) — return a number from the dict, throwing if absent
- [`->has()`](../CollectionAsDict/has.md) — check whether a value exists for the given key
- [`->maybeGet()`](maybeGet.md) — return a number from the dict, or `null` if absent
- [`->set()`](set.md) — store a number in the dict

**From DictOfNumbers**

_No own public methods — the class exists to bind the value template to `int|float`._

## Here Be Dragons

**PHPStan template inference on empty instances.** When you instantiate `DictOfNumbers` with no seed data, PHPStan resolves the `TKey` and `TValue` template parameters as `*NEVER*` because an empty array carries no type information. This causes false-positive errors on subsequent method calls such as `mergeArray()` or `get()`.

Workaround: annotate the variable with `@var` at the point of creation:

    // @var DictOfNumbers<string, int|float> $unit
    $unit = new DictOfNumbers();

This is a known PHPStan limitation; there is no support for template default types yet. See the linked PHPStan issues for background.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends CollectionAsDict
 ✔ declares no public methods of its own beyond inherited methods
 ✔ Extends CollectionAsDict
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
 ✔ Preserves integer and float types in same dict
 ✔ Handles negative numbers of both types
 ✔ Handles zero values of both types
 ✔ Handles boundary values
 ✔ Iteration preserves numeric types
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfNumbers.php:74`](../../../../kits/collectionskit/src/Dictionaries/DictOfNumbers.php#L74)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict`](../CollectionAsDict/README.md) — base class providing keyed access (`set` / `get` / `maybeGet` / `has`)
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only

## Issues

- [Open issues mentioning `DictOfNumbers`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers%22)
- [Closed issues mentioning `DictOfNumbers`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
