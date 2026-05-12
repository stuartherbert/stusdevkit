# ListOfObjects

A list pinned to `object` values — holds an ordered collection of PHP objects where duplicates are allowed and items have no identity.

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

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `int` and `TValue` resolved to `object` via the `@extends CollectionAsList<object>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;

/**
 * @extends CollectionAsList<object>
 */
class ListOfObjects extends CollectionAsList
{
    // --- CollectionOfAnything ---

    /**
     * Create a new ListOfObjects, optionally seeded with objects.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the list's stored objects as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of objects stored in the list.
     */
    public function count(): int;

    /**
     * Return an iterator over the list's stored objects.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the list contains no objects.
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
     * Add the given array's objects to this list.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the objects from another list into this list.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first object stored in this list.
     */
    public function maybeFirst(): ?object;

    /**
     * Returns the first object stored in this list.
     */
    public function first(): object;

    /**
     * Returns the last object stored in this list.
     */
    public function maybeLast(): ?object;

    /**
     * Returns the last object stored in this list.
     */
    public function last(): object;

    // --- CollectionAsList ---

    /**
     * Add a new object to the end of the list.
     */
    public function add(object $value): static;
}
```

## Description

`ListOfObjects` holds an ordered collection of PHP objects — any instance of any class, including built-ins ([`stdClass`](https://www.php.net/manual/en/class.stdclass.php), [`ArrayIterator`](https://www.php.net/manual/en/class.arrayiterator.php), `DateTime`, …), user-defined classes, anonymous classes, and invokable objects. Duplicates are allowed, and items have no identity (no primary key).

Use this when you need a heterogeneous sequence of objects with no further type constraint. For object-typed lists with a specific class constraint, document a pinned subclass (as [`ListOfUuids`](../ListOfUuids/README.md) does for [`UuidInterface`](https://uuid.ramsey.dev/en/stable/)). Use [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) (or one of its child classes) instead when your objects have a key/identity.

`ListOfObjects` is a typed alias of [`CollectionAsList`](../CollectionAsList/README.md) — it adds no methods of its own. It pins the parent's `TValue` template parameter to `object` via `@extends CollectionAsList<object>`, so every inherited method that mentions `TValue` in its signature is narrowed to `object` for PHPStan and for the reader.

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `ListOfObjects`-specific page that shows the resolved-type view. Methods with no generics ([`count()`](../../CollectionOfAnything/count.md), [`empty()`](../../CollectionOfAnything/empty.md), [`getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md)) link directly to the parent page.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new `ListOfObjects`, optionally seeded with objects
- [`->copy()`](copy.md) — return a new `ListOfObjects` containing the same objects
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of objects stored in the list
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the list contains no objects
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the list's stored objects
- [`->toArray()`](toArray.md) — return the list's stored objects as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first object stored in this list (throws when empty)
- [`->last()`](last.md) — returns the last object stored in this list (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first object stored in this list (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last object stored in this list (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible `ListOfObjects`) to this list
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this list
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of another `ListOfObjects` into this list

**From CollectionAsList**

- [`->add()`](add.md) — add a new object to the end of the list

## Here Be Dragons

**Objects are stored by reference, not by value.** PHP objects are reference-type — adding an object to a `ListOfObjects` stores the same handle the caller holds. Mutating the object after storage mutates what the list reports back. If you need an isolated copy, `clone` before adding.

**Duplicate object instances are not deduplicated.** `add($obj)` twice produces two entries pointing at the same handle. If you need uniqueness by identity, enforce it at the caller or use a [Dictionary](../../Dictionaries/CollectionAsDict/README.md) variant indexed by `spl_object_id()` or similar.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends CollectionAsList
 ✔ declares no public methods of its own beyond inherited methods
```

## Source

[`kits/collectionskit/src/Lists/ListOfObjects.php:59`](../../../../kits/collectionskit/src/Lists/ListOfObjects.php#L59)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsList`](../CollectionAsList/README.md) — the generic parent class whose `TValue` template `ListOfObjects` pins to `object`
- [`ListOfUuids`](../ListOfUuids/README.md) — list pinned to the more specific `UuidInterface` object type
- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for objects that have an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfStrings`](../ListOfStrings/README.md) — list pinned to `string` values
- [`ListOfIntegers`](../ListOfIntegers/README.md) — list pinned to `int` values
- [`ListOfFloats`](../ListOfFloats/README.md) — list pinned to `float` values
- [`ListOfNumbers`](../ListOfNumbers/README.md) — list re-bounded to numeric values (`int` or `float`)
- [`ListOfCallables`](../ListOfCallables/README.md) — list pinned to `callable` values

## Issues

- [Open issues mentioning `ListOfObjects`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%22)
- [Closed issues mentioning `ListOfObjects`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
