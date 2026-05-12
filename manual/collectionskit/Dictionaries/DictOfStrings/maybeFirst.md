# DictOfStrings::maybeFirst()

> `public function maybeFirst(): ?string`

Returns the first string stored in this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfStrings extends CollectionAsDict
{
    /**
     * @return string|null
     */
    public function maybeFirst(): ?string
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeFirst(): mixed`, inherited
> from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The narrowed `string|null` return type shown above is bound by
> `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Returns the first string stored in this dict, or `null` if the dict is empty.

The "first" string is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfStrings::first()`](first.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The first stored string, or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `string|null`. An empty string (`""`) is a valid stored value and is returned as-is — only an absent value collapses to `null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ ->maybeFirst() returns the first string
 ✔ ->maybeFirst() returns null for empty dict
 ✔ ->maybeFirst() returns the first string added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::first()`](first.md) — returns the first string stored in this dict (throws when empty)
- [`DictOfStrings::maybeLast()`](maybeLast.md) — returns the last string of this dict (returns `null` when empty)
- [`DictOfStrings::last()`](last.md) — returns the last string of this dict (throws when empty)
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::maybeFirst()%22)
- [Closed issues mentioning `DictOfStrings::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::maybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
