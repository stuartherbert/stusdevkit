# DictOfNumbers::maybeGet()

> `public function maybeGet(int|string $key): int|float|null`

Return a number from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return int|float|null
     */
    public function maybeGet(int|string $key): int|float|null
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function maybeGet($key): mixed`,
> inherited from [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns the number stored for the given `$key`.

If the dict has no value for `$key`, returns `null`. The no-null invariant — enforced by [`DictOfNumbers::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null".

Use [`DictOfNumbers::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose number is being requested. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

## Return Values

The number stored at `$key`, or `null` when the key is absent. The PHP return type is `mixed`; the class's template binding narrows it to `int|float|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
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

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::get()`](get.md) — return a number from the dict, throwing if absent
- [`DictOfNumbers::set()`](set.md) — store a number in the dict
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::maybeGet()%22)
- [Closed issues mentioning `DictOfNumbers::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
