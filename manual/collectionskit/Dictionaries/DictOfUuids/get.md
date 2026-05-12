# DictOfUuids::get()

> `public function get(string $key): UuidInterface`

Return a UUID from the collection.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;
use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class DictOfUuids extends DictOfObjects
{
    /**
     * @param string $key
     * @return UuidInterface
     * @throws NoValueForKeyInCollectionException
     */
    public function get(string $key): UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function get($key): mixed`, inherited from
> [`CollectionAsDict::get()`](../CollectionAsDict/get.md). The narrowed `string`
> key and `UuidInterface` return types shown above are bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns the [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored for the given `$key`.

If the collection has no value for `$key`, throws an exception. This is the throwing counterpart to [`DictOfUuids::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`DictOfUuids::has()`](has.md) to test for a key without retrieving the value, or [`DictOfUuids::maybeGet()`](maybeGet.md) when you want a `null` rather than an exception for absent keys.

## Parameters

**`$key`** (`string`)

The key whose UUID is being requested. The PHP signature accepts `mixed`, but the class's `@extends DictOfObjects<string, UuidInterface>` binding narrows this to `string`.

## Return Values

The [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored at `$key`. The PHP return type is `mixed`, but the class's template binding narrows it to `UuidInterface`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dictionary. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `DictOfUuids does not contain <key>`, where `<key>` is the missing key.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->get() returns UUID for existing key
 ✔ ->get() throws NoValueForKeyInCollectionException for missing key
 ✔ ->get() throws NoValueForKeyInCollectionException for empty dict
 ✔ ->get() returns UUID added via set()
 ✔ ->get() exception message includes the missing key
 ✔ ->get() and ->maybeGet() return same UUID for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:150`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::maybeGet()`](maybeGet.md) — return a UUID from the collection, or `null` if absent
- [`DictOfUuids::has()`](has.md) — check whether a UUID exists for the given key
- [`DictOfUuids::set()`](set.md) — store a UUID in the collection
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::get()%22)
- [Closed issues mentioning `DictOfUuids::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
