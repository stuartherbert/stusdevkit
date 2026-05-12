# DictOfIntegers::maybeLast()

> `public function maybeLast(): ?int`

Returns the last integer of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfIntegers extends DictOfNumbers
{
    /**
     * @return int|null
     */
    public function maybeLast(): ?int
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed `int|null` return type shown above is bound by
> `@template-extends DictOfNumbers<array-key, int>` on [`DictOfIntegers`](README.md).

## Description

Returns the last integer stored in this dict, or `null` if the dict is empty.

The "last" integer is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfIntegers::last()`](last.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The last stored integer, or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `int|null`. A stored `0` is returned as-is — only an absent value collapses to `null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers
 ✔ ->maybeLast() returns the last integer
 ✔ ->maybeLast() returns null for empty dict
 ✔ ->maybeLast() returns the last integer added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfIntegers`](README.md) — where the `<array-key, int>` template parameters are bound
- [`DictOfIntegers::last()`](last.md) — returns the last integer of this dict (throws when empty)
- [`DictOfIntegers::maybeFirst()`](maybeFirst.md) — returns the first integer stored in this dict (returns `null` when empty)
- [`DictOfIntegers::first()`](first.md) — returns the first integer stored in this dict (throws when empty)
- [`DictOfNumbers::maybeLast()`](../DictOfNumbers/maybeLast.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfIntegers::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfIntegers::maybeLast()%22)
- [Closed issues mentioning `DictOfIntegers::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfIntegers::maybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
