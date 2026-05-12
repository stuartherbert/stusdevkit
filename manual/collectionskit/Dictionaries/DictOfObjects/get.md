# DictOfObjects::get()

> `public function get(int|string $key): object`

Return an object from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return object
     * @throws NoValueForKeyInCollectionException
     */
    public function get(int|string $key): object
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function get($key): mixed`,
> inherited from [`CollectionAsDict::get()`](../CollectionAsDict/get.md). The
> value type `object` comes from `@template TValue of object` declared on
> [`DictOfObjects`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Returns the object stored for the given `$key`.

If the dict has no value for `$key`, throws an exception. This is the throwing counterpart to [`DictOfObjects::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`DictOfObjects::maybeGet()`](maybeGet.md) when you want a `null` rather than an exception for absent keys.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose object is being requested. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

## Return Values

The object stored at `$key`. The PHP return type is `mixed`; the class's template binding narrows it to `object`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dict. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `<CollectionType> does not contain <key>`, where `<CollectionType>` is the runtime class name (e.g. `DictOfObjects`, `DictOfUuids`).

## Here Be Dragons

**Objects are returned by reference**, inherited from `DictOfObjects`. Mutating the returned object mutates the dict's copy too. If you need value semantics, clone the result before mutating.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->get() returns object for existing key
 ✔ ->get() throws NoValueForKeyInCollectionException for missing key
 ✔ ->get() throws NoValueForKeyInCollectionException for empty dict
 ✔ ->get() returns object added via set()
 ✔ ->get() returns object with integer key
 ✔ ->get() exception message includes the missing key
 ✔ ->get() and ->maybeGet() return same object for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:150`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::maybeGet()`](maybeGet.md) — return an object from the dict, or `null` if absent
- [`DictOfObjects::set()`](set.md) — store an object in the dict
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::get()%22)
- [Closed issues mentioning `DictOfObjects::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
