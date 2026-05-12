# DictOfObjects::maybeFirst()

> `public function maybeFirst(): ?object`

Returns the first object stored in this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @return object|null
     */
    public function maybeFirst(): ?object
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function maybeFirst(): mixed`,
> inherited from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Returns the first object stored in this dict, or `null` if the dict is empty.

The "first" object is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfObjects::first()`](first.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The first stored object, or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `object|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->maybeFirst() returns the first object
 ✔ ->maybeFirst() returns null for empty dict
 ✔ ->maybeFirst() returns the first object added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::first()`](first.md) — returns the first object stored in this dict (throws when empty)
- [`DictOfObjects::maybeLast()`](maybeLast.md) — returns the last object of this dict (returns `null` when empty)
- [`DictOfObjects::last()`](last.md) — returns the last object of this dict (throws when empty)
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::maybeFirst()%22)
- [Closed issues mentioning `DictOfObjects::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::maybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
