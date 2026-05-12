# ListOfCallables

A list pinned to `callable` values — holds an ordered collection of closures, function-name strings, method arrays, and invokable objects where duplicates are allowed and items have no identity.

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

The signatures below show the PHPStan-narrowed view, with `TKey` resolved to `int` and `TValue` resolved to `callable` via the `@extends CollectionAsList<callable>` template binding. The runtime PHP signatures (which use `mixed` / `array` etc.) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use ArrayIterator;

/**
 * @extends CollectionAsList<callable>
 */
class ListOfCallables extends CollectionAsList
{
    // --- CollectionOfAnything ---

    /**
     * Create a new ListOfCallables, optionally seeded with callables.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the list's stored callables as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of callables stored in the list.
     */
    public function count(): int;

    /**
     * Return an iterator over the list's stored callables.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the list contains no callables.
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
     * Add the given array's callables to this list.
     */
    public function mergeArray(array $input): static;

    /**
     * Copies the callables from another list into this list.
     */
    public function mergeSelf(AccessibleCollection $input): static;

    /**
     * Returns the first callable stored in this list.
     */
    public function maybeFirst(): ?callable;

    /**
     * Returns the first callable stored in this list.
     */
    public function first(): callable;

    /**
     * Returns the last callable stored in this list.
     */
    public function maybeLast(): ?callable;

    /**
     * Returns the last callable stored in this list.
     */
    public function last(): callable;

    // --- CollectionAsList ---

    /**
     * Add a new callable to the end of the list.
     */
    public function add(callable $value): static;
}
```

## Description

`ListOfCallables` holds an ordered collection of PHP callables — closures, arrow functions, function-name strings, `[class, method]` and `[$object, method]` arrays, and invokable objects (any value for which [`is_callable()`](https://www.php.net/manual/en/function.is-callable.php) returns true). Duplicates are allowed, and items have no identity (no primary key).

Use this when you need to hold a sequence of callbacks — for example, hooks to run in order, validators to apply in sequence, or strategies to consult. Use [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) (or one of its child classes) instead when your callables have a key/identity.

`ListOfCallables` is a typed alias of [`CollectionAsList`](../CollectionAsList/README.md) — it adds no methods of its own. It pins the parent's `TValue` template parameter to `callable` via `@extends CollectionAsList<callable>`, so every inherited method that mentions `TValue` in its signature is narrowed to `callable` for PHPStan and for the reader.

Methods whose narrowed signatures differ from the parent — anything that references `TKey` or `TValue` in its parameters or return type — link to a `ListOfCallables`-specific page that shows the resolved-type view. Methods with no generics ([`count()`](../../CollectionOfAnything/count.md), [`empty()`](../../CollectionOfAnything/empty.md), [`getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md)) link directly to the parent page.

## Methods

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new `ListOfCallables`, optionally seeded with callables
- [`->copy()`](copy.md) — return a new `ListOfCallables` containing the same callables
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of callables stored in the list
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the list contains no callables
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the list's stored callables
- [`->toArray()`](toArray.md) — return the list's stored callables as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first callable stored in this list (throws when empty)
- [`->last()`](last.md) — returns the last callable stored in this list (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first callable stored in this list (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last callable stored in this list (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible `ListOfCallables`) to this list
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this list
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of another `ListOfCallables` into this list

**From CollectionAsList**

- [`->add()`](add.md) — add a new callable to the end of the list

## Here Be Dragons

**Callable identity is not deduplicated.** `add()` and the merge family never check whether a value already exists in the list. Two closures that look identical in source are distinct PHP objects with different identities — appending the same closure twice produces two entries, and appending the same `[$object, 'method']` array twice likewise produces two entries. If you need uniqueness, enforce it at the caller.

**Stored callables are stored, not invoked.** The list never calls anything on insertion. A value that satisfies `callable` at insertion time can later cease to be callable — for example, a `[$object, 'method']` array where `$object` is later destroyed, or a function-name string for a function that is later removed from the symbol table. Re-check with [`is_callable()`](https://www.php.net/manual/en/function.is-callable.php) at the call site if the lifetime is unclear.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfCallables
 ✔ lives in the StusDevKit\CollectionsKit\Lists namespace
 ✔ is declared as a class
 ✔ extends CollectionAsList
 ✔ declares no public methods of its own beyond inherited methods
```

## Source

[`kits/collectionskit/src/Lists/ListOfCallables.php:60`](../../../../kits/collectionskit/src/Lists/ListOfCallables.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsList`](../CollectionAsList/README.md) — the generic parent class whose `TValue` template `ListOfCallables` pins to `callable`
- [`CollectionAsDict`](../../Dictionaries/CollectionAsDict/README.md) — dictionary variant for callables that have an identity / primary key
- [`CollectionAsStack`](../../Stacks/CollectionAsStack/README.md) — stack variant for last-in-first-out access
- [`ListOfStrings`](../ListOfStrings/README.md) — list pinned to `string` values
- [`ListOfIntegers`](../ListOfIntegers/README.md) — list pinned to `int` values
- [`ListOfFloats`](../ListOfFloats/README.md) — list pinned to `float` values
- [`ListOfNumbers`](../ListOfNumbers/README.md) — list pinned to numeric values (`int` or `float`)
- [`ListOfObjects`](../ListOfObjects/README.md) — list pinned to `object` values
- [`ListOfUuids`](../ListOfUuids/README.md) — list pinned to `UuidInterface` values

## Issues

- [Open issues mentioning `ListOfCallables`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfCallables%22)
- [Closed issues mentioning `ListOfCallables`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfCallables%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfCallables%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
