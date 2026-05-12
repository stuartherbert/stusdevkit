# CollectionOfAnything

## Hierarchy

**Extends:** _(none)_

**Implements:**

- [`Arrayable`](../../missingbitskit/Arrays/Arrayable/README.md)
- [`Countable`](https://www.php.net/manual/en/class.countable.php) (PHP built-in)
- [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php) (PHP built-in)

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;
use StusDevKit\MissingBitsKit\Arrays\Arrayable;

/**
 * @template TKey of array-key
 * @template TValue of array|bool|callable|float|int|object|string
 * @implements Arrayable<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 */
class CollectionOfAnything implements Arrayable, Countable, IteratorAggregate
{
    /**
     * Create a new collection, optionally seeded with data.
     */
    public function __construct(
        protected array $data = [],
    );

    /**
     * Return the collection's stored data as a plain PHP array.
     */
    public function toArray(): array;

    /**
     * Return the number of elements stored in the collection.
     */
    public function count(): int;

    /**
     * Return an iterator over the collection's stored data.
     */
    public function getIterator(): ArrayIterator;

    /**
     * Return true if the collection contains no elements.
     */
    public function empty(): bool;

    /**
     * Return the unqualified class name of this collection type.
     */
    public function getCollectionTypeAsString(): string;

    /**
     * Creates a copy of this collection.
     */
    public function copy(): static;
}
```

## Description

`CollectionOfAnything` is the root base class for every collection type in CollectionsKit. It provides three foundational capabilities:

- **storage** — a protected `$data` array that subclasses read and mutate directly;
- **counting** — via the `Countable` interface so callers can use PHP's built-in `count()`;
- **iteration** — via the `IteratorAggregate` interface so callers can use `foreach`.

It also provides `toArray()` (via the `Arrayable` interface), a lightweight `empty()` predicate, and `copy()` for taking an independent snapshot of any collection.

Originally added to standardise storage, counting, iteration, and array conversion for all CollectionsKit collection types.

All concrete collection types extend this class through one of two intermediate bases: `AccessibleCollection` (for collections that allow unrestricted element access — lists, dictionaries, indexes) or `CollectionAsStack` (for LIFO collections that only expose the top element). Do not extend `CollectionOfAnything` directly.

## Methods

**From CollectionOfAnything**

- [`->copy()`](copy.md) — creates a copy of this collection
- [`->count()`](count.md) — return the number of elements stored in the collection
- [`->empty()`](empty.md) — return true if the collection contains no elements
- [`->getCollectionTypeAsString()`](getCollectionTypeAsString.md) — return the unqualified class name of this collection type
- [`->getIterator()`](getIterator.md) — return an iterator over the collection's stored data
- [`->toArray()`](toArray.md) — return the collection's stored data as a plain PHP array

## Here Be Dragons

**PHPStan template inference on empty instances.** When you instantiate `CollectionOfAnything` (or any subclass) with no seed data, PHPStan resolves the template parameters as `*NEVER*` because an empty array carries no type information. This causes false-positive errors on subsequent method calls.

Workaround: annotate the variable with `@var` at the point of creation:

    // @var CollectionOfAnything<int, string> $unit
    $unit = new CollectionOfAnything();

See the linked PHPStan issues for background.

## Contract (from tests)

```
CollectionOfAnything
 ✔ lives in the StusDevKit\CollectionsKit namespace
 ✔ is declared as a class
 ✔ is not declared abstract
 ✔ implements Arrayable
 ✔ implements Countable
 ✔ implements IteratorAggregate
 ✔ declares the expected set of own public methods
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:125`](../../../kits/collectionskit/src/CollectionOfAnything.php#L125)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection`](../AccessibleCollection/README.md) — extends CollectionOfAnything with methods for accessing arbitrary elements, merging collections, and copying
- [`CollectionAsStack`](../Stacks/CollectionAsStack/README.md) — base class for LIFO collections
- [`Arrayable`](../../missingbitskit/Arrays/Arrayable/README.md) — interface for classes that can return their internal state as a PHP array

## Issues

- [Open issues mentioning `CollectionOfAnything`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything%22)
- [Closed issues mentioning `CollectionOfAnything`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
