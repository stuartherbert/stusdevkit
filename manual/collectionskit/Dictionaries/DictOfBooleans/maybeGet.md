# DictOfBooleans::maybeGet()

> `public function maybeGet(int|string $key): ?bool`

Return a flag from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return bool|null
     */
    public function maybeGet(int|string $key): ?bool
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function maybeGet($key): mixed`,
> inherited from [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md).

## Description

Returns the flag stored for the given `$key`.

If the dict has no value for `$key`, returns `null`. The no-null invariant — enforced by [`DictOfBooleans::set()`](set.md) and the constructor — means a `null` result unambiguously signals "key absent" rather than "key present, stored value is null". A stored `false` value is returned as-is — it does not collapse to `null`. Use [`DictOfBooleans::isTrue()`](isTrue.md) / [`DictOfBooleans::isFalse()`](isFalse.md) when you would rather fold "absent" into `false` answers.

Use [`DictOfBooleans::get()`](get.md) when an absent key should raise an exception instead.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The flag name being requested. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

## Return Values

The flag stored at `$key`, or `null` when the key is absent. The PHP return type is `mixed`; the class's template binding narrows it to `bool|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->maybeGet() returns value for existing key
 ✔ ->maybeGet() returns false value without converting to null
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

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::get()`](get.md) — return a flag from the dict, throwing if absent
- [`DictOfBooleans::set()`](set.md) — store a flag in the dict
- [`DictOfBooleans::isTrue()`](isTrue.md) — is the named flag set to `true`?
- [`DictOfBooleans::isFalse()`](isFalse.md) — is the named flag set to `false`?
- [`CollectionAsDict::maybeGet()`](../CollectionAsDict/maybeGet.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::maybeGet()%22)
- [Closed issues mentioning `DictOfBooleans::maybeGet()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::maybeGet()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
