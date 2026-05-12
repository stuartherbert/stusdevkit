# DictOfObjects::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @template TIn of object
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
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function merge(AccessibleCollection|array $input): static`,
> inherited from [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Adds the contents of `$input` to this dict in place.

`merge()` is the public entry point that dispatches to one of two specialised methods based on the input's runtime type:

- if `$input` is a plain PHP array, the call is forwarded to [`DictOfObjects::mergeArray()`](mergeArray.md);
- if `$input` is an `AccessibleCollection`, the call is forwarded to [`DictOfObjects::mergeSelf()`](mergeSelf.md), which enforces a late-static-binding compatibility check (only `DictOfObjects` and its subclasses are accepted).

The dict itself is modified — no copy is returned. The method returns `$this` so calls can be chained.

## Parameters

**`$input`** (`AccessibleCollection<array-key, object>|array<array-key, object>`)

The data to add. Either a plain PHP array of `array-key => object`, or another `DictOfObjects` (or `DictOfObjects` subclass) containing compatible entries. When passing a collection, see [`DictOfObjects::mergeSelf()`](mergeSelf.md) for the type-compatibility rules.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents added. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a `null` value (propagated from [`DictOfObjects::mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a `DictOfObjects` or a subclass thereof (propagated from [`DictOfObjects::mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->merge() can merge an array into the dict
 ✔ ->merge() can merge another DictOfObjects
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:98`](../../../../kits/collectionskit/src/AccessibleCollection.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::mergeArray()`](mergeArray.md) — adds the contents of the given array to this dict
- [`DictOfObjects::mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict
- [`DictOfObjects::copy()`](copy.md) — creates a copy of this dict
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::merge()%22)
- [Closed issues mentioning `DictOfObjects::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::merge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
