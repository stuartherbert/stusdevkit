# ListOfFloats

A list pinned to `float` values — holds an ordered collection of floating-point numbers where duplicates are allowed and items have no identity.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)
- [`CollectionAsList`](../CollectionAsList/README.md)
- [`ListOfNumbers`](../ListOfNumbers/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:** _(none)_

## Synopsis

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `int` and `TValue` resolved to `float` via the `@template-extends ListOfNumbers<float>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;

/**
 * @template-extends ListOfNumbers<float>
 */
class ListOfFloats extends ListOfNumbers
{
    // --- CollectionOfAnything ---

    /**
     * Create a new ListOfFloats, optionally seeded with floats.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the list's stored floats as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of floats stored in the list.
     */
    public function count(): int;

    /**
     * Return an iterator over the list's stored floats.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the list contains no floats.
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
     * Add the given array's floats to this list.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the floats from another list into this list.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first float stored in this list.
     */
    public function maybeFirst(): ?float;

    /**
     * Returns the first float stored in this list.
     */
    public function first(): float;

    /**
     * Returns the last float stored in this list.
     */
    public function maybeLast(): ?float;

    /**
     * Returns the last float stored in this list.
     */
    public function last(): float;

    // --- CollectionAsList ---

    /**
     * Add a new float to the end of the list.
     */
    public function add(float $value): static;
}
```

## Description

`ListOfFloats` holds an ordered collection of `float` values. Duplicates are allowed, and items have no identity (no primary key). All stored values keep their `float` PHP type — the list never coerces a float to an integer.

Use this when you need a sequence of floating-point numbers. For mixed `int`/`float` lists, use the parent class [`ListOfNumbers`](../ListOfNumbers/README.md); for `int`-only lists, use [`ListOfIntegers`](../ListOfIntegers/README.md). Use [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) (or one of its child classes) instead when your floats have a key/identity.

`ListOfFloats` is a typed alias of [`ListOfNumbers`](../ListOfNumbers/README.md) — it adds no methods of its own. It pins its parent's `TValue` template parameter to `float` via `@template-extends ListOfNumbers<float>`, so every inherited method that mentions `TValue` in its signature is narrowed to `float` for PHPStan and for the reader.

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `ListOfFloats`-specific page that shows the resolved-type view. Methods with no generics ([`count()`](../../CollectionOfAnything/count.md), [`empty()`](../../CollectionOfAnything/empty.md), [`getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md)) link directly to the parent page.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new `ListOfFloats`, optionally seeded with floats
- [`->copy()`](copy.md) — return a new `ListOfFloats` containing the same floats
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of floats stored in the list
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the list contains no floats
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the list's stored floats
- [`->toArray()`](toArray.md) — return the list's stored floats as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first float stored in this list (throws when empty)
- [`->last()`](last.md) — returns the last float stored in this list (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first float stored in this list (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last float stored in this list (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible `ListOfFloats`) to this list
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this list
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of another `ListOfFloats` into this list

**From CollectionAsList**

- [`->add()`](add.md) — add a new float to the end of the list

## Here Be Dragons

**`ListOfFloats` and [`ListOfIntegers`](../ListOfIntegers/README.md) are siblings, not subtypes.** Both extend [`ListOfNumbers`](../ListOfNumbers/README.md), but neither is assignable to the other. `ListOfFloats::mergeSelf()` rejects a `ListOfIntegers` with [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php), and PHPStan catches the same mismatch at compile time. The parent [`ListOfNumbers`](../ListOfNumbers/README.md), in contrast, accepts both because both are subtypes of `ListOfNumbers`.

**Float precision is preserved exactly, including IEEE 754 quirks.** Stored values keep their original `float` representation — including non-finite values, denormalised numbers, and the usual floating-point rounding behaviour. Use strict comparison and tolerance-based equality when comparing retrieved floats.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfFloats
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends ListOfNumbers
 ✔ declares no public methods of its own beyond inherited methods
```

## Source

[`kits/collectionskit/src/Lists/ListOfFloats.php:58`](../../../../kits/collectionskit/src/Lists/ListOfFloats.php#L58)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](../ListOfNumbers/README.md) — the re-bounded parent class whose `TValue` `ListOfFloats` pins to `float`
- [`ListOfIntegers`](../ListOfIntegers/README.md) — sibling list pinned to `int` values
- [`CollectionAsList`](../CollectionAsList/README.md) — the generic list parent shared by all `List*` collections
- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for floats that have an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfStrings`](../ListOfStrings/README.md) — list pinned to `string` values
- [`ListOfCallables`](../ListOfCallables/README.md) — list pinned to `callable` values
- [`ListOfObjects`](../ListOfObjects/README.md) — list pinned to `object` values
- [`ListOfUuids`](../ListOfUuids/README.md) — list pinned to `UuidInterface` values

## Issues

- [Open issues mentioning `ListOfFloats`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfFloats%22)
- [Closed issues mentioning `ListOfFloats`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfFloats%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
