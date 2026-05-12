# DictOfStrings::maybeLast()

> `public function maybeLast(): ?string`

Returns the last string of this dict.

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
    public function maybeLast(): ?string
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed `string|null` return type shown above is bound by
> `@extends CollectionAsDict<array-key, string>` on [`DictOfStrings`](README.md).

## Description

Returns the last string stored in this dict, or `null` if the dict is empty.

The "last" string is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfStrings::last()`](last.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The last stored string, or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `string|null`. An empty string (`""`) is a valid stored value and is returned as-is — only an absent value collapses to `null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfStrings
 ✔ ->maybeLast() returns the last string
 ✔ ->maybeLast() returns null for empty dict
 ✔ ->maybeLast() returns the last string added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfStrings`](README.md) — where the `<array-key, string>` template parameters are bound
- [`DictOfStrings::last()`](last.md) — returns the last string of this dict (throws when empty)
- [`DictOfStrings::maybeFirst()`](maybeFirst.md) — returns the first string stored in this dict (returns `null` when empty)
- [`DictOfStrings::first()`](first.md) — returns the first string stored in this dict (throws when empty)
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfStrings::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfStrings::maybeLast()%22)
- [Closed issues mentioning `DictOfStrings::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfStrings::maybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
