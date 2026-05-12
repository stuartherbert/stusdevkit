# AccessibleCollection::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this collection.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\AccessibleCollection`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit;

use InvalidArgumentException;

class AccessibleCollection extends CollectionOfAnything
{
    /**
     * @template TIn of TValue
     * @param AccessibleCollection<TKey, TIn> $input
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function mergeSelf(AccessibleCollection $input): static
}
```

## Description

Copies the contents of another `AccessibleCollection` into this collection in place. The source `$input` is not modified; only this collection grows.

Before any data is moved, this method calls the protected `canMerge()` helper to verify type compatibility. `canMerge()` accepts `$input` only when it is an instance of the late-static-bound class or one of its subclasses. For example, calling `mergeSelf()` on a `ListOfNumbers` accepts any `ListOfNumbers`, `ListOfIntegers`, or `ListOfFloats`, but rejects a `ListOfStrings` or any unrelated `AccessibleCollection` subclass with an `InvalidArgumentException`.

When the compatibility check passes, `$input`'s data is appended via PHP's array spread operator: integer-keyed entries appended in order, string-keyed entries overwrite any matching key already in the collection.

## Parameters

**`$input`** (`AccessibleCollection<TKey, TIn>`)

The source collection whose contents should be copied into this collection. Must be an instance of the calling class (resolved via late-static binding) or any of its subclasses; siblings and unrelated subclasses are rejected.

## Return Values

Returns `$this` — the same collection instance, with `$input`'s contents copied in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `static` (e.g. a sibling or unrelated subclass).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\AccessibleCollection
 ✔ ::mergeSelf() signature: mergeSelf(AccessibleCollection $input): static
 ✔ ->mergeSelf() merges another collection into this one
 ✔ ->mergeSelf() does not modify the source collection
 ✔ ->mergeSelf() with empty source leaves collection unchanged
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:146`](../../../kits/collectionskit/src/AccessibleCollection.php#L146)

## Changelog

_No tagged releases yet._

## See Also

- [`AccessibleCollection::merge()`](merge.md) — adds the given input (array or compatible collection) to this collection
- [`AccessibleCollection::mergeArray()`](mergeArray.md) — adds the contents of the given array to this collection

## Issues

- [Open issues mentioning `AccessibleCollection::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22AccessibleCollection::mergeSelf()%22)
- [Closed issues mentioning `AccessibleCollection::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22AccessibleCollection::mergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=AccessibleCollection%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
