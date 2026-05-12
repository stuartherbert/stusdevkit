# DictOfNumbers::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @template TIn of int|float
     * @param AccessibleCollection<array-key, TIn> $input
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function mergeSelf(AccessibleCollection $input): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function mergeSelf(AccessibleCollection $input): static`,
> inherited from [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Copies the contents of another dict into this dict in place. The source `$input` is not modified; only this dict grows.

Before any data is moved, this method calls the protected `canMerge()` helper to verify type compatibility. `canMerge()` accepts `$input` only when it is an instance of the calling class (resolved via late-static binding) or one of its subclasses. For `DictOfNumbers`, that means any `DictOfNumbers`, `DictOfIntegers`, or `DictOfFloats`; sibling collections (e.g. `DictOfStrings`) and unrelated `AccessibleCollection` subclasses are rejected with an `InvalidArgumentException`.

When the compatibility check passes, `$input`'s data is appended via PHP's array spread operator: entries with new keys are added, entries whose keys already exist overwrite the previously-stored value.

## Parameters

**`$input`** (`AccessibleCollection<array-key, int|float>`)

The source dict whose contents should be copied into this dict. Must be a `DictOfNumbers` (or subclass — `DictOfIntegers`, `DictOfFloats`); sibling collections and unrelated `AccessibleCollection` subclasses are rejected.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents copied in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a `DictOfNumbers` (or subclass). The message is `type mismatch: cannot merge <input type> into <runtime collection type>`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->mergeSelf() merges another dict into this one
 ✔ ->mergeSelf() does not modify the source dict
 ✔ ->mergeSelf() with empty source leaves dict unchanged
 ✔ ->mergeSelf() overwrites matching keys
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:165`](../../../../kits/collectionskit/src/AccessibleCollection.php#L165)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`DictOfNumbers::mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::mergeSelf()%22)
- [Closed issues mentioning `DictOfNumbers::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::mergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
