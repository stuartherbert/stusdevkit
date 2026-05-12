# DictOfBooleans::maybeLast()

> `public function maybeLast(): ?bool`

Returns the last flag of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @return bool|null
     */
    public function maybeLast(): ?bool
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function maybeLast(): mixed`,
> inherited from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md).

## Description

Returns the last flag stored in this dict, or `null` if the dict is empty.

The "last" flag is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfBooleans::last()`](last.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The last stored flag (`true` or `false`), or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `bool|null`. A stored `false` value is returned as-is — only an absent value collapses to `null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->maybeLast() returns the last flag
 ✔ ->maybeLast() returns null for empty dict
 ✔ ->maybeLast() returns the last flag added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::last()`](last.md) — returns the last flag of this dict (throws when empty)
- [`DictOfBooleans::maybeFirst()`](maybeFirst.md) — returns the first flag stored in this dict (returns `null` when empty)
- [`DictOfBooleans::first()`](first.md) — returns the first flag stored in this dict (throws when empty)
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::maybeLast()%22)
- [Closed issues mentioning `DictOfBooleans::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::maybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
