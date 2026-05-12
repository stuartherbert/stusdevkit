# DictOfStrings

## Hierarchy

**Extends:**

- [`CollectionOfAnything`](../../CollectionOfAnything/README.md)
- [`AccessibleCollection`](../../AccessibleCollection/README.md)
- [`CollectionAsDict`](../CollectionAsDict/README.md)

**Implements:**

- [`Arrayable`](../../../missingbitskit/Arrays/Arrayable/README.md) (via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in, via [`CollectionOfAnything`](../../CollectionOfAnything/README.md))

**Uses:**

- [`StringTransformations`](../../Traits/StringTransformations/README.md)

## Synopsis

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `array-key` and `TValue` resolved to `string` via the `@extends CollectionAsDict<array-key, string>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\CollectionsKit\Traits\StringTransformations;

/**
 * @extends CollectionAsDict<array-key, string>
 */
class DictOfStrings extends CollectionAsDict
{
    use StringTransformations;

    // --- CollectionOfAnything ---

    /**
     * Create a new dict of strings, optionally seeded with data.
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
     * Returns the first string stored in this dict.
     */
    public function maybeFirst(): ?string;

    /**
     * Returns the first string stored in this dict.
     */
    public function first(): string;

    /**
     * Returns the last string of this dict.
     */
    public function maybeLast(): ?string;

    /**
     * Returns the last string of this dict.
     */
    public function last(): string;

    // --- CollectionAsDict ---

    /**
     * Store a string in the dict.
     */
    public function set(int|string $key, string $value): static;

    /**
     * Return a string from the dict.
     */
    public function maybeGet(int|string $key): ?string;

    /**
     * Return a string from the dict.
     */
    public function get(int|string $key): string;

    /**
     * Check to see if we have a string for the given `$key` in this dict.
     */
    public function has(int|string $key): bool;

    // --- StringTransformations ---

    /**
     * Trims all strings in the collection using PHP's trim() function.
     */
    public function applyTrim(
        string $characters = " \n\r\t\v\0",
    ): static;

    /**
     * Left-trims all strings in the collection using PHP's ltrim() function.
     */
    public function applyLtrim(
        string $characters = " \n\r\t\v\0",
    ): static;

    /**
     * Right-trims all strings in the collection using PHP's rtrim() function.
     */
    public function applyRtrim(
        string $characters = " \n\r\t\v\0",
    ): static;
}
```

## Description

`DictOfStrings` holds a collection of strings.

It is a specialisation of [`CollectionAsDict`](../CollectionAsDict/README.md) that pins the value template to `string`, and pulls in the [`StringTransformations`](../../Traits/StringTransformations/README.md) trait so callers can trim every stored string in place.

`DictOfStrings` declares no methods of its own — the entire surface comes from the parent chain plus the trait. That keeps the class itself a pure type binding.

## Methods

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `DictOfStrings`-specific page that shows the resolved-type view. Methods with no generics (`count()`, `empty()`, `getCollectionTypeAsString()`) link directly to the parent page.

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new dict of strings, optionally seeded with data
- [`->copy()`](copy.md) — creates a copy of this dict
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`->toArray()`](toArray.md) — return the dict's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first string stored in this dict (throws when empty)
- [`->last()`](last.md) — returns the last string of this dict (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first string stored in this dict (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last string of this dict (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict

**From CollectionAsDict**

- [`->get()`](get.md) — return a string from the dict, throwing if absent
- [`->has()`](has.md) — check whether a string exists for the given key
- [`->maybeGet()`](maybeGet.md) — return a string from the dict, or `null` if absent
- [`->set()`](set.md) — store a string in the dict

**From StringTransformations**

- [`->applyLtrim()`](../../Traits/StringTransformations/applyLtrim.md) — left-trims all strings in the collection using PHP's `ltrim()` function
- [`->applyRtrim()`](../../Traits/StringTransformations/applyRtrim.md) — right-trims all strings in the collection using PHP's `rtrim()` function
- [`->applyTrim()`](../../Traits/StringTransformations/applyTrim.md) — trims all strings in the collection using PHP's `trim()` function

**From DictOfStrings**

_No own public methods — the class exists to pin the value template to `string` and pull in the StringTransformations trait._

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends CollectionAsDict
 ✔ uses the StringTransformations trait
 ✔ declares only trait public methods as its own
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfStrings.php:51`](../../../../kits/collectionskit/src/Dictionaries/DictOfStrings.php#L51)

## Changelog

_No tagged releases yet._

## See Also

- [`StringTransformations`](../../Traits/StringTransformations/README.md) — trait providing the `applyTrim` / `applyLtrim` / `applyRtrim` methods
- [`CollectionAsDict`](../CollectionAsDict/README.md) — base class providing keyed access (`set` / `get` / `maybeGet` / `has`)

## Issues

- [Open issues mentioning `DictOfStrings`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings%22)
- [Closed issues mentioning `DictOfStrings`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
