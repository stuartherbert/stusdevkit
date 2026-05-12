# DictOfNumbers::maybeFirst()

> `public function maybeFirst(): int|float|null`

Returns the first number stored in this dict.

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
    public function maybeFirst(): int|float|null
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function maybeFirst(): mixed`,
> inherited from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns the first number stored in this dict, or `null` if the dict is empty.

The "first" number is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfNumbers::first()`](first.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The first stored number (`int` or `float`), or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `int|float|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->maybeFirst() returns the first number
 ✔ ->maybeFirst() returns null for empty dict
 ✔ ->maybeFirst() returns the first number added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::first()`](first.md) — returns the first number stored in this dict (throws when empty)
- [`DictOfNumbers::maybeLast()`](maybeLast.md) — returns the last number of this dict (returns `null` when empty)
- [`DictOfNumbers::last()`](last.md) — returns the last number of this dict (throws when empty)
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::maybeFirst()%22)
- [Closed issues mentioning `DictOfNumbers::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::maybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
