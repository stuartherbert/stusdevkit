# DictOfBooleans::maybeFirst()

> `public function maybeFirst(): ?bool`

Returns the first flag stored in this dict.

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
    public function maybeFirst(): ?bool
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function maybeFirst(): mixed`,
> inherited from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md).

## Description

Returns the first flag stored in this dict, or `null` if the dict is empty.

The "first" flag is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfBooleans::first()`](first.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The first stored flag (`true` or `false`), or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `bool|null`. A stored `false` value is returned as-is — only an absent value collapses to `null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->maybeFirst() returns the first flag
 ✔ ->maybeFirst() returns null for empty dict
 ✔ ->maybeFirst() returns the first flag added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::first()`](first.md) — returns the first flag stored in this dict (throws when empty)
- [`DictOfBooleans::maybeLast()`](maybeLast.md) — returns the last flag of this dict (returns `null` when empty)
- [`DictOfBooleans::last()`](last.md) — returns the last flag of this dict (throws when empty)
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::maybeFirst()%22)
- [Closed issues mentioning `DictOfBooleans::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::maybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
