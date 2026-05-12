# DictOfStrings::maybeGet()

> `public function maybeGet(int|string $key): ?string`

Return a string from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return string|null
     */
    public function maybeGet(int|string $key): ?string
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeGet($key): mixed`, inherited
> from [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md). The
> narrowed `array-key` key and `string|null` return types shown above are
> bound by `@extends CollectionAsDict<array-key, string>` on
> [`DictOfStrings`](README.md).

## Description

Returns the string stored for the given `$key`.

If the dict has no value for `$key`, returns `null`. The no-null invariant — enforced by [`DictOfStrings::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null". An empty string (`""`) is a valid stored value and is returned as-is — it does not collapse to `null`.

Use [`DictOfStrings::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose string is being requested. The PHP signature accepts `mixed`; the class's `@extends CollectionAsDict<array-key, string>` binding narrows this to `int|string`.

## Return Values

The string stored at `$key`, or `null` when the key is absent. The PHP return type is `mixed`; the class's template binding narrows it to `string|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ ->maybeGet() returns value for existing key
 ✔ ->maybeGet() returns empty string without converting to null
 ✔ ->maybeGet() returns null for missing key
 ✔ ->maybeGet() returns null for empty dict
 ✔ ->maybeGet() returns value added via set()
 ✔ ->maybeGet() returns the overwritten value after set()
 ✔ ->get() and ->maybeGet() return same value for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:131`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L131)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::get()`](get.md) — return a string from the dict, throwing if absent
- [`DictOfStrings::has()`](has.md) — check whether a string exists for the given key
- [`DictOfStrings::set()`](set.md) — store a string in the dict
- [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::maybeGet()%22)
- [Closed issues mentioning `DictOfStrings::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
