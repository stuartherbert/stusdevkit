# DictOfIntegers::get()

> `public function get(int|string $key): int`

Return an integer from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class DictOfIntegers extends DictOfNumbers
{
    /**
     * @param array-key $key
     * @return int
     * @throws NoValueForKeyInCollectionException
     */
    public function get(int|string $key): int
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function get($key): mixed`, inherited from
> [`CollectionAsDict::get()`](../CollectionAsDict/get.md). The narrowed
> `array-key` key and `int` return types shown above are bound by
> `@template-extends DictOfNumbers<array-key, int>` on [`DictOfIntegers`](README.md).

## Description

Returns the integer stored for the given `$key`.

If the dict has no value for `$key`, throws an exception. This is the throwing counterpart to [`DictOfIntegers::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`DictOfIntegers::has()`](has.md) to test for a key without retrieving the value, or [`DictOfIntegers::maybeGet()`](maybeGet.md) when you want a `null` rather than an exception for absent keys.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose integer is being requested. The PHP signature accepts `mixed`; the class's `@template-extends DictOfNumbers<array-key, int>` binding narrows this to `int|string`.

## Return Values

The integer stored at `$key`. The PHP return type is `mixed`; the class's template binding narrows it to `int`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dict. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `DictOfIntegers does not contain <key>`, where `<key>` is the missing key.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers
 ✔ ->get() returns value for existing key
 ✔ ->get() throws NoValueForKeyInCollectionException for missing key
 ✔ ->get() throws NoValueForKeyInCollectionException for empty dict
 ✔ ->get() returns value added via set()
 ✔ ->get() returns value with integer key
 ✔ ->get() exception message includes the missing key
 ✔ ->get() and ->maybeGet() return same value for existing key
```

## Source

[`kits/collectionskit/src/Dictionaries/CollectionAsDict.php:150`](../../../../kits/collectionskit/src/Dictionaries/CollectionAsDict.php#L150)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfIntegers`](README.md) — where the `<array-key, int>` template parameters are bound
- [`DictOfIntegers::maybeGet()`](maybeGet.md) — return an integer from the dict, or `null` if absent
- [`DictOfIntegers::has()`](has.md) — check whether an integer exists for the given key
- [`DictOfIntegers::set()`](set.md) — store an integer in the dict
- [`DictOfNumbers::get()`](../DictOfNumbers/get.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfIntegers::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfIntegers::get()%22)
- [Closed issues mentioning `DictOfIntegers::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfIntegers::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
