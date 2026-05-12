# DictOfUuids::maybeGet()

> `public function maybeGet(string $key): ?UuidInterface`

Return a UUID from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;

class DictOfUuids extends DictOfObjects
{
    /**
     * @param string $key
     * @return UuidInterface|null
     */
    public function maybeGet(string $key): ?UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeGet($key): mixed`, inherited
> from [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md). The
> narrowed `string` key and `UuidInterface|null` return types shown above are
> bound by `@extends DictOfObjects<string, UuidInterface>` on
> [`DictOfUuids`](README.md).

## Description

Returns the [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored for the given `$key`.

If the dict has no value for `$key`, returns `null`. The no-null invariant — enforced by [`DictOfUuids::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null".

Use [`DictOfUuids::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`string`)

The key whose UUID is being requested. The PHP signature accepts `mixed`; the class's `@extends DictOfObjects<string, UuidInterface>` binding narrows this to `string`.

## Return Values

The [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored at `$key`, or `null` when the key is absent. The PHP return type is `mixed`; the class's template binding narrows it to `UuidInterface|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->maybeGet() returns UUID for existing key
 ✔ ->maybeGet() returns null for missing key
 ✔ ->maybeGet() returns null for empty dict
 ✔ ->maybeGet() returns UUID added via set()
 ✔ ->maybeGet() returns the overwritten UUID after set()
 ✔ ->get() and ->maybeGet() return same UUID for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:131`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L131)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::get()`](get.md) — return a UUID from the dict, throwing if absent
- [`DictOfUuids::has()`](has.md) — check whether a UUID exists for the given key
- [`DictOfUuids::set()`](set.md) — store a UUID in the dict
- [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::maybeGet()%22)
- [Closed issues mentioning `DictOfUuids::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
