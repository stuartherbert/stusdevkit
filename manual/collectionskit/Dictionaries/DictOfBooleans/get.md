# DictOfBooleans::get()

> `public function get(int|string $key): bool`

Return a flag from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return bool
     * @throws NoValueForKeyInCollectionException
     */
    public function get(int|string $key): bool
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function get($key): mixed`,
> inherited from [`CollectionAsDict::get()`](../CollectionAsDict/get.md). The
> value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md).

## Description

Returns the flag stored for the given `$key`.

If the dict has no value for `$key`, throws an exception. This is the throwing counterpart to [`DictOfBooleans::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`DictOfBooleans::isTrue()`](isTrue.md) / [`DictOfBooleans::isFalse()`](isFalse.md) when you would rather fold "absent" into `false` than raise an exception.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The flag name being requested. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

## Return Values

The flag stored at `$key` (`true` or `false`). The PHP return type is `mixed`; the class's template binding narrows it to `bool`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dict. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `DictOfBooleans does not contain <key>`, where `<key>` is the missing key.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
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

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::maybeGet()`](maybeGet.md) — return a flag from the dict, or `null` if absent
- [`DictOfBooleans::set()`](set.md) — store a flag in the dict
- [`DictOfBooleans::isTrue()`](isTrue.md) — is the named flag set to `true`?
- [`DictOfBooleans::isFalse()`](isFalse.md) — is the named flag set to `false`?
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::get()%22)
- [Closed issues mentioning `DictOfBooleans::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
