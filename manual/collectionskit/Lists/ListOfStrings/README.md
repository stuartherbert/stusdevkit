# ListOfStrings

A list pinned to `string` values, with in-place string-transformation helpers.

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

**Uses:**

- [`StringTransformations`](../../Traits/StringTransformations/README.md)

## Synopsis

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `int` and `TValue` resolved to `string` via the `@extends CollectionAsList<string>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;
use StusDevKit\CollectionsKit\Traits\StringTransformations;

/**
 * @extends CollectionAsList<string>
 */
class ListOfStrings extends CollectionAsList
{
    use StringTransformations;

    // --- CollectionOfAnything ---

    /**
     * Create a new ListOfStrings, optionally seeded with strings.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the list's stored strings as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of strings stored in the list.
     */
    public function count(): int;

    /**
     * Return an iterator over the list's stored strings.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the list contains no strings.
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
     * Add the given array's strings to this list.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the strings from another list into this list.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first string stored in this list.
     */
    public function maybeFirst(): ?string;

    /**
     * Returns the first string stored in this list.
     */
    public function first(): string;

    /**
     * Returns the last string stored in this list.
     */
    public function maybeLast(): ?string;

    /**
     * Returns the last string stored in this list.
     */
    public function last(): string;

    // --- CollectionAsList ---

    /**
     * Add a new string to the end of the list.
     */
    public function add(string $value): static;

    // --- StringTransformations ---

    /**
     * Trims all strings in the collection using PHP's trim() function.
     */
    public function applyTrim(string $characters = " \n\r\t\v\0"): static;

    /**
     * Left-trims all strings in the collection using PHP's ltrim() function.
     */
    public function applyLtrim(string $characters = " \n\r\t\v\0"): static;

    /**
     * Right-trims all strings in the collection using PHP's rtrim() function.
     */
    public function applyRtrim(string $characters = " \n\r\t\v\0"): static;
}
```

## Description

`ListOfStrings` holds an ordered collection of `string` values. Duplicates are allowed, and items have no identity (no primary key). Empty strings are accepted; `null` is not.

In addition to the standard `List*` API, `ListOfStrings` mixes in the [`StringTransformations`](../../Traits/StringTransformations/README.md) trait, which supplies in-place trim helpers ([`applyTrim()`](../../Traits/StringTransformations/applyTrim.md), [`applyLtrim()`](../../Traits/StringTransformations/applyLtrim.md), [`applyRtrim()`](../../Traits/StringTransformations/applyRtrim.md)) for cleaning up string data in bulk.

`ListOfStrings` is a typed alias of [`CollectionAsList`](../CollectionAsList/README.md) — it adds no methods of its own beyond the trait it uses. It pins the parent's `TValue` template parameter to `string` via `@extends CollectionAsList<string>`, so every inherited method that mentions `TValue` in its signature is narrowed to `string` for PHPStan and for the reader.

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `ListOfStrings`-specific page that shows the resolved-type view. Methods with no generics ([`count()`](../../CollectionOfAnything/count.md), [`empty()`](../../CollectionOfAnything/empty.md), [`getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md)) link directly to the parent page; trait methods ([`applyTrim()`](../../Traits/StringTransformations/applyTrim.md) and siblings) link to the trait's own method pages.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new `ListOfStrings`, optionally seeded with strings
- [`->copy()`](copy.md) — return a new `ListOfStrings` containing the same strings
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of strings stored in the list
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the list contains no strings
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the list's stored strings
- [`->toArray()`](toArray.md) — return the list's stored strings as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first string stored in this list (throws when empty)
- [`->last()`](last.md) — returns the last string stored in this list (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first string stored in this list (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last string stored in this list (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible `ListOfStrings`) to this list
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this list
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of another `ListOfStrings` into this list

**From CollectionAsList**

- [`->add()`](add.md) — add a new string to the end of the list

**From StringTransformations**

- [`->applyTrim()`](../../Traits/StringTransformations/applyTrim.md) — trims all strings in the list using PHP's `trim()`
- [`->applyLtrim()`](../../Traits/StringTransformations/applyLtrim.md) — left-trims all strings in the list using PHP's `ltrim()`
- [`->applyRtrim()`](../../Traits/StringTransformations/applyRtrim.md) — right-trims all strings in the list using PHP's `rtrim()`

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfStrings
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends CollectionAsList
 ✔ uses the StringTransformations trait
 ✔ declares only the StringTransformations trait methods as its own public methods
```

## Source

[`kits/collectionskit/src/Lists/ListOfStrings.php:51`](../../../../kits/collectionskit/src/Lists/ListOfStrings.php#L51)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsList`](../CollectionAsList/README.md) — the generic parent class whose `TValue` template `ListOfStrings` pins to `string`
- [`StringTransformations`](../../Traits/StringTransformations/README.md) — the trait supplying in-place trim helpers
- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for strings that have an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfIntegers`](../ListOfIntegers/README.md) — list pinned to `int` values
- [`ListOfFloats`](../ListOfFloats/README.md) — list pinned to `float` values
- [`ListOfNumbers`](../ListOfNumbers/README.md) — list re-bounded to numeric values (`int` or `float`)
- [`ListOfCallables`](../ListOfCallables/README.md) — list pinned to `callable` values
- [`ListOfObjects`](../ListOfObjects/README.md) — list pinned to `object` values
- [`ListOfUuids`](../ListOfUuids/README.md) — list pinned to `UuidInterface` values

## Issues

- [Open issues mentioning `ListOfStrings`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfStrings%22)
- [Closed issues mentioning `ListOfStrings`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfStrings%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
