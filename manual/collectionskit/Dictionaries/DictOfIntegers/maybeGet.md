# DictOfIntegers::maybeGet()

> `public function maybeGet(int|string $key): ?int`

Return an integer from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfIntegers extends DictOfNumbers
{
    /**
     * @param array-key $key
     * @return int|null
     */
    public function maybeGet(int|string $key): ?int
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeGet($key): mixed`, inherited
> from [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md). The
> narrowed `array-key` key and `int|null` return types shown above are
> bound by `@template-extends DictOfNumbers<array-key, int>` on
> [`DictOfIntegers`](README.md).

## Description

Returns the integer stored for the given `$key`.

If the dict has no value for `$key`, returns `null`. The no-null invariant — enforced by [`DictOfIntegers::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null". A stored `0` is returned as-is.

Use [`DictOfIntegers::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose integer is being requested. The PHP signature accepts `mixed`; the class's `@template-extends DictOfNumbers<array-key, int>` binding narrows this to `int|string`.

## Return Values

The integer stored at `$key`, or `null` when the key is absent. The PHP return type is `mixed`; the class's template binding narrows it to `int|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers
 ✔ ->maybeGet() returns value for existing key
 ✔ ->maybeGet() returns null for missing key
 ✔ ->maybeGet() returns null for empty dict
 ✔ ->maybeGet() returns value added via set()
 ✔ ->maybeGet() returns value with integer key
 ✔ ->maybeGet() returns the overwritten value after set()
 ✔ ->get() and ->maybeGet() return same value for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:131`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L131)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfIntegers`](README.md) — where the `<array-key, int>` template parameters are bound
- [`DictOfIntegers::get()`](get.md) — return an integer from the dict, throwing if absent
- [`DictOfIntegers::has()`](has.md) — check whether an integer exists for the given key
- [`DictOfIntegers::set()`](set.md) — store an integer in the dict
- [`DictOfNumbers::maybeGet()`](../DictOfNumbers/maybeGet.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfIntegers::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfIntegers::maybeGet()%22)
- [Closed issues mentioning `DictOfIntegers::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfIntegers::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
