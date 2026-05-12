# DictOfUuids

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)
- [`CollectionAsDict`](../CollectionAsDict/README.md)
- [`DictOfObjects`](../DictOfObjects/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:**

- [`UuidConversions`](../../Traits/UuidConversions/README.md)

## Synopsis

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `string` and `TValue` resolved to [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) via the `@extends DictOfObjects<string, UuidInterface>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use Ramsey\Uuid\UuidInterface;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\CollectionsKit\Traits\UuidConversions;

/**
 * @extends DictOfObjects<string, UuidInterface>
 */
class DictOfUuids extends DictOfObjects
{
    use UuidConversions;

    // --- CollectionOfAnything ---

    /**
     * Create a new dict of UUIDs, optionally seeded with data.
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
     * Returns the first UUID stored in this dict.
     */
    public function maybeFirst(): ?UuidInterface;

    /**
     * Returns the first UUID stored in this dict.
     */
    public function first(): UuidInterface;

    /**
     * Returns the last UUID of this dict.
     */
    public function maybeLast(): ?UuidInterface;

    /**
     * Returns the last UUID of this dict.
     */
    public function last(): UuidInterface;

    // --- CollectionAsDict ---

    /**
     * Store a UUID in the dict.
     */
    public function set(string $key, UuidInterface $value): static;

    /**
     * Return a UUID from the dict.
     */
    public function maybeGet(string $key): ?UuidInterface;

    /**
     * Return a UUID from the dict.
     */
    public function get(string $key): UuidInterface;

    /**
     * Check to see if we have a UUID for the given `$key` in this dict.
     */
    public function has(string $key): bool;

    // --- UuidConversions ---

    /**
     * Returns the dict's UUIDs as an array of strings, preserving the original array keys.
     */
    public function toArrayOfStrings(): array;
}
```

## Description

`DictOfUuids` holds a collection of [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) objects, using the UUID (as a string) as the identity (i.e. the array key).

It is a specialisation of [`DictOfObjects`](../DictOfObjects/README.md) that pins the value template to `UuidInterface` and pulls in the [`UuidConversions`](../../Traits/UuidConversions/README.md) trait so callers can read the stored UUIDs back as plain strings via `toArrayOfStrings()`.

`DictOfUuids` declares no methods of its own — the entire surface comes from the parent chain plus the trait. That keeps the class itself a pure type binding.

Most of the time, you probably want the more user-friendly [`IndexOfUuids`](../../Indexes/IndexOfUuids/README.md) instead — it indexes UUIDs by their own string representation, so callers don't have to invent a key.

## Methods

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `DictOfUuids`-specific page that shows the resolved-type view. Methods with no generics (`count()`, `empty()`, `getCollectionTypeAsString()`) link directly to the parent page.

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new dict of UUIDs, optionally seeded with data
- [`->copy()`](copy.md) — creates a copy of this dict
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`->toArray()`](toArray.md) — return the dict's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first UUID stored in this dict (throws when empty)
- [`->last()`](last.md) — returns the last UUID of this dict (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first UUID stored in this dict (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last UUID of this dict (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict

**From CollectionAsDict**

- [`->get()`](get.md) — return a UUID from the dict, throwing if absent
- [`->has()`](has.md) — check whether a UUID exists for the given key
- [`->maybeGet()`](maybeGet.md) — return a UUID from the dict, or `null` if absent
- [`->set()`](set.md) — store a UUID in the dict

**From DictOfObjects**

_No own public methods — `DictOfObjects` exists to narrow the value template to `object`._

**From UuidConversions**

- [`->toArrayOfStrings()`](../../Traits/UuidConversions/toArrayOfStrings.md) — returns the dict's UUIDs as an array of strings, preserving the original array keys

**From DictOfUuids**

_No own public methods — the class exists to pin the value template to `UuidInterface` and pull in the UuidConversions trait._

## Here Be Dragons

**Object-identity hazards inherited from [`DictOfObjects`](../DictOfObjects/README.md).** UUIDs are stored by reference, so `copy()` is shallow and `mergeSelf()` / `mergeArray()` transfer references rather than clones. This is rarely a problem in practice — `UuidInterface` implementations are conventionally immutable — but if you implement your own mutable `UuidInterface`, the parent's hazards apply.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends DictOfObjects
 ✔ uses the UuidConversions trait
 ✔ declares only trait public methods as its own
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
 ✔ Dict with one UUID: ->first() and ->last() return the same UUID
 ✔ Preserves UUID identity (same instance, not a copy)
 ✔ All stored values implement UuidInterface
 ✔ Each UUID has a unique string representation
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfUuids.php:56`](../../../../kits/collectionskit/src/Dictionaries/DictOfUuids.php#L56)

## Changelog

_No tagged releases yet._

## See Also

- [`IndexOfUuids`](../../Indexes/IndexOfUuids/README.md) — bare `UuidInterface` values keyed by string representation; usually the more ergonomic choice
- [`DictOfObjects`](../DictOfObjects/README.md) — generic parent that narrows the value template to `object`
- [`UuidConversions`](../../Traits/UuidConversions/README.md) — trait providing the `toArrayOfStrings()` method

## Issues

- [Open issues mentioning `DictOfUuids`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids%22)
- [Closed issues mentioning `DictOfUuids`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
