# DictOfStrings::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @template TIn of string
     * @param AccessibleCollection<array-key, TIn> $input
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
> The narrowed `AccessibleCollection<array-key, string>` parameter type shown
> above is bound by `@extends CollectionAsDict<array-key, string>` on
> [`DictOfStrings`](README.md).

## Description

Copies the contents of another dict into this dict in place. The source `$input` is not modified; only this dict grows.

Before any data is moved, this method calls the protected `canMerge()` helper to verify type compatibility. `canMerge()` accepts `$input` only when it is an instance of the calling class (resolved via late-static binding) or one of its subclasses. For `DictOfStrings`, that means any `DictOfStrings` or `DictOfStrings` subclass; sibling collections (e.g. `DictOfNumbers`) and unrelated `AccessibleCollection` subclasses are rejected with an `InvalidArgumentException`.

When the compatibility check passes, `$input`'s data is appended via PHP's array spread operator: entries with new keys are added, entries whose keys already exist overwrite the previously-stored string.

## Parameters

**`$input`** (`AccessibleCollection<array-key, string>`)

The source dict whose contents should be copied into this dict. Must be a `DictOfStrings` (or subclass); sibling collections and unrelated `AccessibleCollection` subclasses are rejected.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents copied in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a `DictOfStrings` (or subclass). The message is `type mismatch: cannot merge <input type> into DictOfStrings`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
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

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`DictOfStrings::mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::mergeSelf()%22)
- [Closed issues mentioning `DictOfStrings::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::mergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
