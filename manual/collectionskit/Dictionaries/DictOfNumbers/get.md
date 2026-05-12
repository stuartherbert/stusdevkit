# DictOfNumbers::get()

> `public function get(int|string $key): int|float`

Return a number from the dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Dictionaries\CollectionAsDict`](../CollectionAsDict/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @param array-key $key
     * @return int|float
     * @throws NoValueForKeyInCollectionException
     */
    public function get(int|string $key): int|float
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function get($key): mixed`,
> inherited from [`CollectionAsDict::get()`](../CollectionAsDict/get.md). The
> value type `int|float` comes from `@template TValue of int|float` declared on
> [`DictOfNumbers`](README.md); the key type `array-key` (rendered as
> `int|string`) was already set by `@template TKey of array-key` on
> [`CollectionAsDict`](../CollectionAsDict/README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns the number stored for the given `$key`.

If the dict has no value for `$key`, throws an exception. This is the throwing counterpart to [`DictOfNumbers::maybeGet()`](maybeGet.md) — use it when an absent key at this point in your code is a programming error rather than an expected branch.

Use [`DictOfNumbers::has()`](../CollectionAsDict/has.md) to test for a key without retrieving the value, or [`DictOfNumbers::maybeGet()`](maybeGet.md) when you want a `null` rather than an exception for absent keys.

## Parameters

**`$key`** (`array-key`, i.e. `int|string`)

The key whose number is being requested. The PHP signature accepts `mixed`; the parent class's `@template TKey of array-key` binding narrows this to `int|string`.

## Return Values

The number stored at `$key`. The PHP return type is `mixed`; the class's template binding narrows it to `int|float`.

## Errors/Exceptions

- **[`NoValueForKeyInCollectionException`](../../Exceptions/NoValueForKeyInCollectionException/README.md)** — when `$key` is not set in this dict. This is an RFC 9457 problem-details exception. Its title (and `getMessage()`) is `<CollectionType> does not contain <key>`, where `<CollectionType>` is the runtime class name (e.g. `DictOfNumbers`, `DictOfIntegers`, `DictOfFloats`).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->get() returns integer value for existing key
 ✔ ->get() returns float value for existing key
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

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::maybeGet()`](maybeGet.md) — return a number from the dict, or `null` if absent
- [`DictOfNumbers::set()`](set.md) — store a number in the dict
- [`DictOfIntegers`](../DictOfIntegers/README.md) — narrower specialisation, integer values only
- [`DictOfFloats`](../DictOfFloats/README.md) — narrower specialisation, float values only
- [`CollectionAsDict::get()`](../CollectionAsDict/get.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::get()%22)
- [Closed issues mentioning `DictOfNumbers::get()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::get()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
