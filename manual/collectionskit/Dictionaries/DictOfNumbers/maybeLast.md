# DictOfNumbers::maybeLast()

> `public function maybeLast(): int|float|null`

Returns the last number of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @return int|float|null
     */
    public function maybeLast(): int|float|null
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function maybeLast(): mixed`,
> inherited from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns the last number stored in this dict, or `null` if the dict is empty.

The "last" number is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfNumbers::last()`](last.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The last stored number (`int` or `float`), or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `int|float|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->maybeLast() returns the last number
 ✔ ->maybeLast() returns null for empty dict
 ✔ ->maybeLast() returns the last number added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::last()`](last.md) — returns the last number of this dict (throws when empty)
- [`DictOfNumbers::maybeFirst()`](maybeFirst.md) — returns the first number stored in this dict (returns `null` when empty)
- [`DictOfNumbers::first()`](first.md) — returns the first number stored in this dict (throws when empty)
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::maybeLast()%22)
- [Closed issues mentioning `DictOfNumbers::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::maybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
