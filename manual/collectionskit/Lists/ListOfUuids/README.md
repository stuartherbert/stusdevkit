# ListOfUuids

A list pinned to `UuidInterface` values — holds an ordered collection of UUIDs where duplicates are allowed and items have no identity.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)
- [`CollectionAsList`](../CollectionAsList/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `int` and `TValue` resolved to `UuidInterface` via the `@extends CollectionAsList<UuidInterface>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends CollectionAsList<UuidInterface>
 */
class ListOfUuids extends CollectionAsList
{
    // --- CollectionOfAnything ---

    /**
     * Create a new ListOfUuids, optionally seeded with UUIDs.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the list's stored UUIDs as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of UUIDs stored in the list.
     */
    public function count(): int;

    /**
     * Return an iterator over the list's stored UUIDs.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the list contains no UUIDs.
     */
    public function empty(): bool;

    /**
     * Creates a copy of this list.
     */
    public function copy(): static;

    /**
     * Return the unqualified class name of this collection type.
     */
    public function getCollectionTypeAsString(): string;

    // --- AccessibleCollection ---

    /**
     * Add the given input (array or compatible list) to this list.
     */
    public function merge(AccessibleCollection|array $input): static;

    /**
     * Add the given array's UUIDs to this list.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the UUIDs from another list into this list.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first UUID stored in this list.
     */
    public function maybeFirst(): ?UuidInterface;

    /**
     * Returns the first UUID stored in this list.
     */
    public function first(): UuidInterface;

    /**
     * Returns the last UUID stored in this list.
     */
    public function maybeLast(): ?UuidInterface;

    /**
     * Returns the last UUID stored in this list.
     */
    public function last(): UuidInterface;

    // --- CollectionAsList ---

    /**
     * Add a new UUID to the end of the list.
     */
    public function add(UuidInterface $value): static;

    // --- ListOfUuids ---

    /**
     * Return the contents of this collection as an array of strings.
     */
    public function toArrayOfStrings(): array;
}
```

## Description

`ListOfUuids` holds an ordered collection of [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) objects from the [`ramsey/uuid`](https://packagist.org/packages/ramsey/uuid) library. Duplicates are allowed, and items have no identity (no primary key).

Use this when you need a sequence of UUIDs and want PHPStan to enforce that every stored value is a UUID. Use [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) (or one of its child classes) instead when your UUIDs serve as keys/identities rather than payloads.

`ListOfUuids` pins the parent's `TValue` template parameter to `UuidInterface` via `@extends CollectionAsList<UuidInterface>`, so every inherited method that mentions `TValue` in its signature is narrowed to `UuidInterface` for PHPStan and for the reader. On top of the standard `List*` API, it adds [`toArrayOfStrings()`](toArrayOfStrings.md), which returns the contents of the list as an array of strings — the type-conversion is done in the method so callers do not have to repeat the same `(string)` cast throughout their code.

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `ListOfUuids`-specific page that shows the resolved-type view. Methods with no generics ([`count()`](../../CollectionOfAnything/count.md), [`empty()`](../../CollectionOfAnything/empty.md), [`getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md)) link directly to the parent page.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new `ListOfUuids`, optionally seeded with UUIDs
- [`->copy()`](copy.md) — return a new `ListOfUuids` containing the same UUIDs
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of UUIDs stored in the list
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the list contains no UUIDs
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the list's stored UUIDs
- [`->toArray()`](toArray.md) — return the list's stored UUIDs as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first UUID stored in this list (throws when empty)
- [`->last()`](last.md) — returns the last UUID stored in this list (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first UUID stored in this list (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last UUID stored in this list (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible `ListOfUuids`) to this list
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this list
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of another `ListOfUuids` into this list

**From CollectionAsList**

- [`->add()`](add.md) — add a new UUID to the end of the list

**From ListOfUuids**

- [`->toArrayOfStrings()`](toArrayOfStrings.md) — return the contents of this collection as an array of strings

## Here Be Dragons

**Duplicate UUID instances are not deduplicated.** `add($uuid)` twice produces two entries pointing at the same handle. If you need uniqueness, enforce it at the caller — for example by indexing UUIDs in a dictionary keyed by their string form, or by checking [`toArrayOfStrings()`](toArrayOfStrings.md) before insertion.

**Stored UUIDs are stored by reference.** Like all PHP objects, the list holds the same handle the caller passed in. This rarely matters for UUIDs in practice (they tend to be treated as immutable value objects), but it is the same shape as every other [`ListOfObjects`](../ListOfObjects/README.md) subclass.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends CollectionAsList
 ✔ declares toArrayOfStrings() as its only own public method
```

## Source

[`kits/collectionskit/src/Lists/ListOfUuids.php:61`](../../../../kits/collectionskit/src/Lists/ListOfUuids.php#L61)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsList`](../CollectionAsList/README.md) — the generic parent class whose `TValue` template `ListOfUuids` pins to `UuidInterface`
- [`ListOfObjects`](../ListOfObjects/README.md) — broader list pinned to `object` values (parent in spirit, though not a direct subclass relationship)
- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for UUIDs that have an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfStrings`](../ListOfStrings/README.md) — list pinned to `string` values
- [`ListOfIntegers`](../ListOfIntegers/README.md) — list pinned to `int` values
- [`ListOfFloats`](../ListOfFloats/README.md) — list pinned to `float` values
- [`ListOfNumbers`](../ListOfNumbers/README.md) — list re-bounded to numeric values (`int` or `float`)
- [`ListOfCallables`](../ListOfCallables/README.md) — list pinned to `callable` values

## Issues

- [Open issues mentioning `ListOfUuids`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%22)
- [Closed issues mentioning `ListOfUuids`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
