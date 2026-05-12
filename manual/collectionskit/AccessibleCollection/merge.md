# AccessibleCollection::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\AccessibleCollection`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use InvalidArgumentException;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class AccessibleCollection extends CollectionOfAnything
{
    /**
     * @template TIn of TValue
     * @param AccessibleCollection<TKey, TIn>|array<TKey, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException
     * @throws InvalidArgumentException
     */
    public function merge(AccessibleCollection|array $input): static
}
```

## Description

Adds the contents of `$input` to this collection in place.

`merge()` is the public entry point that dispatches to one of two specialised methods based on the input's runtime type:

- if `$input` is a plain PHP array, the call is forwarded to [`AccessibleCollection::mergeArray()`](mergeArray.md);
- if `$input` is an `AccessibleCollection`, the call is forwarded to [`AccessibleCollection::mergeSelf()`](mergeSelf.md), which enforces a late-static-binding compatibility check.

The collection itself is modified — no copy is returned. The method returns `$this` so calls can be chained.

## Parameters

**`$input`** (`AccessibleCollection<TKey, TIn>|array<TKey, TIn>`)

The data to add. Either a plain PHP array indexed by `TKey` with values of type `TIn` (a subtype of `TValue`), or an `AccessibleCollection` parametrised over the same key and a compatible value type. When passing a collection, see [`AccessibleCollection::mergeSelf()`](mergeSelf.md) for the type-compatibility rules.

## Return Values

Returns `$this` — the same collection instance, with `$input`'s contents added. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a null value (propagated from [`AccessibleCollection::mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `static` (propagated from [`AccessibleCollection::mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\AccessibleCollection
 ✔ ::merge() signature: merge(self|array $input): static
 ✔ ->merge() can merge an array into the collection
 ✔ ->merge() can merge another collection
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:84`](../../../kits/collectionskit/src/AccessibleCollection.php#L84)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection::mergeArray()`](mergeArray.md) — adds the contents of the given array to this collection
- [`AccessibleCollection::mergeSelf()`](mergeSelf.md) — copies the contents of a compatible collection into this collection
- [`CollectionOfAnything::copy()`](../CollectionOfAnything/copy.md) — creates a copy of this collection

## Issues

- [Open issues mentioning `AccessibleCollection::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22AccessibleCollection::merge()%22)
- [Closed issues mentioning `AccessibleCollection::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22AccessibleCollection::merge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=AccessibleCollection%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
