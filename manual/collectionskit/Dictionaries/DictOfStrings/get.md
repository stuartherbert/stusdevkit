# DictOfStrings::get()

> `public function get(int|string $key): string`

Return a string from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return string
     * @throws NoValueForKeyInCollectionException
     */
    public function get(int|string $key): string
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function get($key): mixed`, inherited from
> [`CollectionAsDict::get()`](../CollectionAsDict/get.md). The narrowed
> `array-key` key and `string` return types shown above are bound by
> `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Returns the string stored for the given `$key`.

If the dict has no value for `$key`, throws an exception. This is the throwing counterpart to [`DictOfStrings::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`DictOfStrings::has()`](has.md) to test for a key without retrieving the value, or [`DictOfStrings::maybeGet()`](maybeGet.md) when you want a `null` rather than an exception for absent keys.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose string is being requested. The PHP signature accepts `mixed`; the class's `@extends CollectionAsDict<array-key, string>` binding narrows this to `int|string`.

## Return Values

The string stored at `$key`. The PHP return type is `mixed`; the class's template binding narrows it to `string`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dict. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `DictOfStrings does not contain <key>`, where `<key>` is the missing key.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ ->get() returns value for existing key
 ✔ ->get() throws NoValueForKeyInCollectionException for missing key
 ✔ ->get() throws NoValueForKeyInCollectionException for empty dict
 ✔ ->get() returns value added via set()
 ✔ ->get() exception message includes the missing key
 ✔ ->get() and ->maybeGet() return same value for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:150`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::maybeGet()`](maybeGet.md) — return a string from the dict, or `null` if absent
- [`DictOfStrings::has()`](has.md) — check whether a string exists for the given key
- [`DictOfStrings::set()`](set.md) — store a string in the dict
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::get()%22)
- [Closed issues mentioning `DictOfStrings::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
