# DictOfFloats::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfFloats extends DictOfNumbers
{
    /**
     * @template TIn of float
     * @param AccessibleCollection<array-key, TIn>|array<array-key, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException
     * @throws InvalidArgumentException
     */
    public function merge(AccessibleCollection|array $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function merge(AccessibleCollection|array $input): static`,
> inherited from [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md).
> The narrowed `array-key` keys and `float` values shown above are bound
> by `@template-extends DictOfNumbers<array-key, float>` on [`DictOfFloats`](README.md).

## Description

Adds the contents of `$input` to this dict in place.

`merge()` is the public entry point that dispatches to one of two specialised methods based on the input's runtime type:

- if `$input` is a plain PHP array, the call is forwarded to [`DictOfFloats::mergeArray()`](mergeArray.md);
- if `$input` is an `AccessibleCollection`, the call is forwarded to [`DictOfFloats::mergeSelf()`](mergeSelf.md), which enforces a late-static-binding compatibility check (only `DictOfFloats` and its subclasses are accepted).

The dict itself is modified — no copy is returned. The method returns `$this` so calls can be chained.

## Parameters

**`$input`** (`AccessibleCollection<array-key, float>|array<array-key, float>`)

The data to add. Either a plain PHP array of `array-key => float`, or another `DictOfFloats` (or `DictOfFloats` subclass) containing compatible entries. When passing a collection, see [`DictOfFloats::mergeSelf()`](mergeSelf.md) for the type-compatibility rules.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents added. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a `null` value (propagated from [`DictOfFloats::mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a `DictOfFloats` or a subclass thereof (propagated from [`DictOfFloats::mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfFloats
 ✔ ->merge() can merge an array into the dict
 ✔ ->merge() can merge another DictOfFloats
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:98`](../../../../kits/collectionskit/src/AccessibleCollection.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfFloats`](README.md) — where the `<array-key, float>` template parameters are bound
- [`DictOfFloats::mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`DictOfFloats::mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict
- [`DictOfFloats::copy()`](copy.md) — creates a copy of this dict
- [`DictOfNumbers::merge()`](../DictOfNumbers/merge.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfFloats::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfFloats::merge()%22)
- [Closed issues mentioning `DictOfFloats::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfFloats::merge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
