# DictOfBooleans

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

The signatures below show the PHPStan-narrowed view at the `DictOfBooleans` level, with `TValue` pinned to `bool`. `TKey` retains the `array-key` upper bound from [`CollectionAsDict`](../CollectionAsDict/README.md). The runtime PHP signatures (which use `mixed` / `array`) appear on each per-method page's Signature section.

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use ArrayIterator;
use StusDevKit\CollectionsKit\AccessibleCollection;

/**
 * @template TKey of array-key
 * @template-extends CollectionAsDict<TKey, bool>
 */
class DictOfBooleans extends CollectionAsDict
{
    // --- CollectionOfAnything ---

    /**
     * Create a new dict of flags, optionally seeded with data.
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
     * Returns the first flag stored in this dict.
     */
    public function maybeFirst(): ?bool;

    /**
     * Returns the first flag stored in this dict.
     */
    public function first(): bool;

    /**
     * Returns the last flag of this dict.
     */
    public function maybeLast(): ?bool;

    /**
     * Returns the last flag of this dict.
     */
    public function last(): bool;

    // --- CollectionAsDict ---

    /**
     * Store a flag in the dict.
     */
    public function set(int|string $key, bool $value): static;

    /**
     * Return a flag from the dict.
     */
    public function maybeGet(int|string $key): ?bool;

    /**
     * Return a flag from the dict.
     */
    public function get(int|string $key): bool;

    /**
     * Check to see if we have a value for the given `$key` in this collection.
     */
    public function has($key): bool;

    // --- DictOfBooleans ---

    /**
     * is the named flag set to `true`?
     */
    public function isTrue(mixed $name): bool;

    /**
     * is the named flag set to `false`?
     */
    public function isFalse(mixed $name): bool;
}
```

## Description

`DictOfBooleans` holds a collection of named `true`/`false` flags.

It specialises [`CollectionAsDict`](../CollectionAsDict/README.md) by binding the value template to `bool`, and adds two boolean-specific predicates on top of the inherited dictionary surface:

- `isTrue()` — reports whether a named flag is currently set to `true`. Missing flags are treated as `false`.
- `isFalse()` — reports whether a named flag is currently set to `false`. Missing flags are also treated as `false`.

Both predicates are non-throwing — they fold "key absent" into the same `false` answer that "key present, value `false`" produces (for `isTrue()`) or that "key present, value `true`" produces (for `isFalse()`). Use [`->has()`](../CollectionAsDict/has.md) when you need to distinguish "absent" from "present and false".

## Methods

Methods whose narrowed signatures differ from the parent — anything that references `TValue` (pinned to `bool` on `DictOfBooleans`) — link to a `DictOfBooleans`-specific page that shows the narrowed view. Methods that reference only `TKey` (whose `array-key` bound is set at the parent, not narrowed here) or no templates at all link directly to the parent page.

**From CollectionOfAnything**

- [`->__construct()`](__construct.md) — create a new dict of flags, optionally seeded with data
- [`->copy()`](copy.md) — creates a copy of this dict
- [`->count()`](../../CollectionOfAnything/count.md) — return the number of elements stored in the collection
- [`->empty()`](../../CollectionOfAnything/empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](../../CollectionOfAnything/getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the dict's stored data
- [`->toArray()`](toArray.md) — return the dict's stored data as a plain PHP array

**From AccessibleCollection**

- [`->first()`](first.md) — returns the first flag stored in this dict (throws when empty)
- [`->last()`](last.md) — returns the last flag of this dict (throws when empty)
- [`->maybeFirst()`](maybeFirst.md) — returns the first flag stored in this dict (returns `null` when empty)
- [`->maybeLast()`](maybeLast.md) — returns the last flag of this dict (returns `null` when empty)
- [`->merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`->mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`->mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict

**From CollectionAsDict**

- [`->get()`](get.md) — return a flag from the dict, throwing if absent
- [`->has()`](../CollectionAsDict/has.md) — check whether a value exists for the given key
- [`->maybeGet()`](maybeGet.md) — return a flag from the dict, or `null` if absent
- [`->set()`](set.md) — store a flag in the dict

**From DictOfBooleans**

- [`->isFalse()`](isFalse.md) — is the named flag set to `false`?
- [`->isTrue()`](isTrue.md) — is the named flag set to `true`?

## Here Be Dragons

**PHPStan template inference on empty instances.** When you instantiate `DictOfBooleans` with no seed data, PHPStan resolves the `TKey` template parameter as `*NEVER*` because an empty array carries no type information. This causes false-positive errors on subsequent method calls such as `mergeArray()` or `mergeSelf()`.

Workaround: annotate the variable with `@var` at the point of creation:

    // @var DictOfBooleans<string> $unit
    $unit = new DictOfBooleans();

This is a known PHPStan limitation; there is no support for template default types yet. See the linked PHPStan issues for background.

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ lives in the StusDevKit\CollectionsKit\Dictionaries namespace
 ✔ is declared as a class
 ✔ extends CollectionAsDict
 ✔ declares isTrue and isFalse as its own public methods
 ✔ Dict can be iterated with foreach
 ✔ Iterating empty dict produces no iterations
 ✔ Iteration preserves string keys
 ✔ All stored values are booleans
```

## Source

[`kits/collectionskit/src/Dictionaries/DictOfBooleans.php:73`](../../../../kits/collectionskit/src/Dictionaries/DictOfBooleans.php#L73)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionAsDict`](../CollectionAsDict/README.md) — base class providing keyed access (`set` / `get` / `maybeGet` / `has`)
- [`AccessibleCollection`](../../AccessibleCollection/README.md) — supplies first/last accessors and the merge family
- [`CollectionOfAnything`](../../CollectionOfAnything/README.md) — root base class providing storage, counting, iteration, and copying

## Issues

- [Open issues mentioning `DictOfBooleans`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans%22)
- [Closed issues mentioning `DictOfBooleans`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
