# AccessibleCollection::mergeArray()

> `public function mergeArray(array $input): static`

Add the given `$input` to this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\AccessibleCollection`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use StusDevKit\CollectionsKit\Validators\RejectNullArrayValues;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class AccessibleCollection extends CollectionOfAnything
{
    /**
     * @template TIn of TValue
     * @param array<TKey, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException
     */
    public function mergeArray(array $input): static
}
```

## Description

Adds the contents of a plain PHP array to this collection in place.

The merge follows PHP's spread-operator semantics: integer-keyed entries are appended in order, while string-keyed entries overwrite any matching key already in the collection. The input is validated by [`RejectNullArrayValues`](../Validators/RejectNullArrayValues/README.md) before any data is moved across — null values are rejected because no collection may store `null`.

Use this method when you already know `$input` is an array. Otherwise, call [`AccessibleCollection::merge()`](merge.md), which dispatches to the right specialised method.

## Parameters

**`$input`** (`array<TKey, TIn>`)

The array to merge. Keys must match the collection's `TKey` template; values must be of a type bounded by the collection's `TValue` template. The array may be indexed (integer keys) or associative (string keys); both are handled per PHP spread-operator semantics.

## Return Values

Returns `$this` — the same collection instance, with `$input`'s contents merged in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$input` is null.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\AccessibleCollection
 ✔ ::mergeArray() signature: mergeArray(array $input): static
 ✔ ->mergeArray() adds array items to the collection
 ✔ ->mergeArray() into empty collection sets the data
 ✔ ->mergeArray() with empty array leaves collection unchanged
 ✔ ->mergeArray() with associative keys overwrites matching keys
 ✔ ->mergeArray() returns $this for method chaining
 ✔ ->mergeArray() rejects array containing null
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:109`](../../../kits/collectionskit/src/AccessibleCollection.php#L109)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection::merge()`](merge.md) — adds the given input (array or compatible collection) to this collection
- [`AccessibleCollection::mergeSelf()`](mergeSelf.md) — copies the contents of a compatible collection into this collection
- [`RejectNullArrayValues`](../Validators/RejectNullArrayValues/README.md) — validates that no array element is null

## Issues

- [Open issues mentioning `AccessibleCollection::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22AccessibleCollection::mergeArray()%22)
- [Closed issues mentioning `AccessibleCollection::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22AccessibleCollection::mergeArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=AccessibleCollection%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
