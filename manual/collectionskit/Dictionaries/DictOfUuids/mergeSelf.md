# DictOfUuids::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use StusDevKit\CollectionsKit\AccessibleCollection;

class DictOfUuids extends DictOfObjects
{
    /**
     * @template TIn of UuidInterface
     * @param AccessibleCollection<string, TIn> $input
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function mergeSelf(AccessibleCollection $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function mergeSelf(AccessibleCollection $input): static`,
> inherited from [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md).
> The narrowed `AccessibleCollection<string, UuidInterface>` parameter type shown
> above is bound by `@extends DictOfObjects<string, UuidInterface>` on
> [`DictOfUuids`](README.md).

## Description

Copies the contents of another dict into this dict in place. The source `$input` is not modified; only this dict grows.

Before any data is moved, this method calls the protected `canMerge()` helper to verify type compatibility. `canMerge()` accepts `$input` only when it is an instance of the calling class (resolved via late-static binding) or one of its subclasses. For `DictOfUuids`, that means any `DictOfUuids` or `DictOfUuids` subclass; sibling collections (e.g. `DictOfStrings`) and unrelated `AccessibleCollection` subclasses are rejected with an `InvalidArgumentException`.

When the compatibility check passes, `$input`'s data is appended via PHP's array spread operator: entries with new string keys are added, entries whose keys already exist overwrite the previously-stored UUID.

## Parameters

**`$input`** (`AccessibleCollection<string, UuidInterface>`)

The source dict whose contents should be copied into this dict. Must be a `DictOfUuids` (or subclass); sibling collections and unrelated `AccessibleCollection` subclasses are rejected.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents copied in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a `DictOfUuids` (or subclass). The message is `type mismatch: cannot merge <input type> into DictOfUuids`.

## Here Be Dragons

**UUIDs are transferred by reference**, inherited from [`DictOfObjects`](../DictOfObjects/README.md). After the merge, both `$input` and this dict hold pointers to the same `UuidInterface` instances. In practice this is rarely a concern because `UuidInterface` implementations are conventionally immutable.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
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

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`DictOfUuids::mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::mergeSelf()%22)
- [Closed issues mentioning `DictOfUuids::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::mergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
