# ListOfNumbers

A list re-bounded to numeric values — holds an ordered collection of `int` and `float` values where duplicates are allowed and items have no identity.

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

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `int` and `TValue` re-bounded to `int|float` via the `@template TValue of int|float = int|float` declaration. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section. Concrete subclasses ([`ListOfIntegers`](../ListOfIntegers/README.md), [`ListOfFloats`](../ListOfFloats/README.md)) pin `TValue` further.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;

/**
 * @template TValue of int|float = int|float
 * @template-extends CollectionAsList<TValue>
 */
class ListOfNumbers extends CollectionAsList
{
    // --- CollectionOfAnything ---

    /**
     * Create a new ListOfNumbers, optionally seeded with numbers.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the list's stored numbers as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of numbers stored in the list.
     */
    public function count(): int;

    /**
     * Return an iterator over the list's stored numbers.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the list contains no numbers.
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
     * Add the given array's numbers to this list.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the numbers from another list into this list.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first number stored in this list.
     */
    public function maybeFirst(): int|float|null;

    /**
     * Returns the first number stored in this list.
     */
    public function first(): int|float;

    /**
     * Returns the last number stored in this list.
     */
    public function maybeLast(): int|float|null;

    /**
     * Returns the last number stored in this list.
     */
    public function last(): int|float;

    // --- CollectionAsList ---

    /**
     * Add a new number to the end of the list.
     */
    public function add(int|float $value): static;
}
```

## Description

`ListOfNumbers` holds an ordered collection of numeric values — `int` and `float`. Duplicates are allowed, and items have no identity (no primary key). Mixed-type lists are supported: a single `ListOfNumbers` can hold both `int` and `float` entries, and each value's PHP type is preserved on the way in and out.

Use this when you need a numeric sequence and either type is acceptable. When you need to pin the list to one type, use the concrete subclasses [`ListOfIntegers`](../ListOfIntegers/README.md) or [`ListOfFloats`](../ListOfFloats/README.md). Use [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) (or one of its child classes) instead when your numbers have a key/identity.

`ListOfNumbers` is a typed alias of [`CollectionAsList`](../CollectionAsList/README.md) — it adds no methods of its own. It re-bounds the parent's `TValue` template parameter from `array|bool|callable|float|int|object|string` to `int|float` via `@template TValue of int|float = int|float`, so every inherited method that mentions `TValue` in its signature is narrowed to `int|float` for PHPStan and for the reader. The `= int|float` default lets bare uses (`new ListOfNumbers()`) resolve cleanly without an explicit `<int|float>` annotation.

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `ListOfNumbers`-specific page that shows the resolved-type view. Methods with no generics ([`count()`](../../CollectionOfAnything/count.md), [`empty()`](../../CollectionOfAnything/empty.md), [`getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md)) link directly to the parent page.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new `ListOfNumbers`, optionally seeded with numbers
- [`->copy()`](copy.md) — return a new `ListOfNumbers` containing the same numbers
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of numbers stored in the list
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the list contains no numbers
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the list's stored numbers
- [`->toArray()`](toArray.md) — return the list's stored numbers as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first number stored in this list (throws when empty)
- [`->last()`](last.md) — returns the last number stored in this list (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first number stored in this list (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last number stored in this list (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible list) to this list
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this list
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a `ListOfNumbers` (or subclass) into this list

**From CollectionAsList**

- [`->add()`](add.md) — add a new number to the end of the list

## Here Be Dragons

**`int` and `float` retain distinct PHP types.** A `ListOfNumbers` does not coerce `int` to `float` or vice versa — `1` stays `int`, `1.0` stays `float`. Strict comparisons (`===`) and PHPUnit `assertSame()` are sensitive to that distinction, so callers that round-trip values through this list should not assume `int|float` lets them treat the two types interchangeably.

**Subclasses widen `mergeSelf()`'s accepted types via late-static binding.** A `ListOfNumbers` accepts merges from `ListOfNumbers`, [`ListOfIntegers`](../ListOfIntegers/README.md), and [`ListOfFloats`](../ListOfFloats/README.md) — because both pinned subclasses satisfy `instanceof ListOfNumbers`. The reverse is not true: a `ListOfIntegers` rejects a `ListOfFloats` (sibling types). [`merge()`](merge.md), [`mergeArray()`](mergeArray.md), and [`mergeSelf()`](mergeSelf.md) all enforce this; siblings raise [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php).

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends CollectionAsList
 ✔ declares no public methods of its own beyond inherited methods
```

## Source

[`kits/collectionskit/src/Lists/ListOfNumbers.php:83`](../../../../kits/collectionskit/src/Lists/ListOfNumbers.php#L83)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsList`](../CollectionAsList/README.md) — the generic parent class whose `TValue` template `ListOfNumbers` re-bounds to `int|float`
- [`ListOfIntegers`](../ListOfIntegers/README.md) — `ListOfNumbers` pinned to `int`
- [`ListOfFloats`](../ListOfFloats/README.md) — `ListOfNumbers` pinned to `float`
- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for numbers that have an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfStrings`](../ListOfStrings/README.md) — list pinned to `string` values
- [`ListOfCallables`](../ListOfCallables/README.md) — list pinned to `callable` values
- [`ListOfObjects`](../ListOfObjects/README.md) — list pinned to `object` values
- [`ListOfUuids`](../ListOfUuids/README.md) — list pinned to `UuidInterface` values

## Issues

- [Open issues mentioning `ListOfNumbers`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%22)
- [Closed issues mentioning `ListOfNumbers`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
