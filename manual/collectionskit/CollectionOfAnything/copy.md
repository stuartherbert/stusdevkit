# CollectionOfAnything::copy()

> `public function copy(): static`

Creates a copy of this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\CollectionOfAnything`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

class CollectionOfAnything implements Arrayable, Countable, IteratorAggregate
{
    /**
     * @return static<TKey,TValue>
     */
    public function copy(): static
}
```

## Description

Returns a new instance of the same runtime class containing the same stored data. The new instance does not share its underlying array with the original — mutating one leaves the other unchanged.

The return type is `static`, so late-static binding ensures the copy is always an instance of the calling class, not of `CollectionOfAnything`. Copying a [`ListOfNumbers`](../Lists/ListOfNumbers/README.md) returns a `ListOfNumbers`; copying a [`StackOfStrings`](../Stacks/StackOfStrings/README.md) returns a `StackOfStrings`.

Useful when working with immutable collections — take a copy, hand it to code that may mutate it, and keep the original intact.

## Parameters

_None._

## Return Values

A new collection of the same runtime type as `$this`, containing the same data. The two instances do not share the underlying array — modifying one leaves the other unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\CollectionOfAnything
 ✔ ::copy() signature: copy(): static
 ✔ ->copy() returns a new instance with the same data
 ✔ ->copy() returns an independent instance
 ✔ ->copy() of an empty collection returns an empty collection
 ✔ ->copy() preserves the runtime class via late-static binding
```

## Source

[`kits/collectionskit/src/CollectionOfAnything.php:280`](../../../kits/collectionskit/src/CollectionOfAnything.php#L280)

## Changelog

_No tagged releases yet._

## See Also

- [`CollectionOfAnything::toArray()`](toArray.md) — return the collection's stored data as a plain PHP array
- [`AccessibleCollection::merge()`](../AccessibleCollection/merge.md) — adds the given input (array or compatible collection) to this collection

## Issues

- [Open issues mentioning `CollectionOfAnything::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22CollectionOfAnything::copy()%22)
- [Closed issues mentioning `CollectionOfAnything::copy()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22CollectionOfAnything::copy()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=CollectionOfAnything%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
