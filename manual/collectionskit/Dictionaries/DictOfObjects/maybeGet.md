# DictOfObjects::maybeGet()

> `public function maybeGet(int|string $key): ?object`

Return an object from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return object|null
     */
    public function maybeGet(int|string $key): ?object
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function maybeGet($key): mixed`,
> inherited from [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md).
> The value type `object` comes from `@template TValue of object` declared on
> [`DictOfObjects`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Returns the object stored for the given `$key`.

If the dict has no value for `$key`, returns `null`. The no-null invariant — enforced by [`DictOfObjects::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null".

Use [`DictOfObjects::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose object is being requested. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

## Return Values

The object stored at `$key`, or `null` when the key is absent. The PHP return type is `mixed`; the class's template binding narrows it to `object|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->maybeGet() returns object for existing key
 ✔ ->maybeGet() returns null for missing key
 ✔ ->maybeGet() returns null for empty dict
 ✔ ->maybeGet() returns object added via set()
 ✔ ->maybeGet() returns object with integer key
 ✔ ->maybeGet() returns the overwritten object after set()
 ✔ ->get() and ->maybeGet() return same object for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:131`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L131)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::get()`](get.md) — return an object from the dict, throwing if absent
- [`DictOfObjects::set()`](set.md) — store an object in the dict
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::maybeGet()%22)
- [Closed issues mentioning `DictOfObjects::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
