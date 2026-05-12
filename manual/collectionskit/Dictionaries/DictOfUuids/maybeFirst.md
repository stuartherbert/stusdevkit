# DictOfUuids::maybeFirst()

> `public function maybeFirst(): ?UuidInterface`

Returns the first UUID stored in this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;

class DictOfUuids extends DictOfObjects
{
    /**
     * @return UuidInterface|null
     */
    public function maybeFirst(): ?UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeFirst(): mixed`, inherited
> from [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md).
> The narrowed `UuidInterface|null` return type shown above is bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns the first [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored in this dict, or `null` if the dict is empty.

The "first" UUID is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfUuids::first()`](first.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The first stored `UuidInterface`, or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `UuidInterface|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->maybeFirst() returns the first UUID
 ✔ ->maybeFirst() returns null for empty dict
 ✔ ->maybeFirst() returns the first UUID added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:231`](../../../../kits/collectionskit/src/AccessibleCollection.php#L231)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::first()`](first.md) — returns the first UUID stored in this dict (throws when empty)
- [`DictOfUuids::maybeLast()`](maybeLast.md) — returns the last UUID of this dict (returns `null` when empty)
- [`DictOfUuids::last()`](last.md) — returns the last UUID of this dict (throws when empty)
- [`AccessibleCollection::maybeFirst()`](../../AccessibleCollection/maybeFirst.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::maybeFirst()%22)
- [Closed issues mentioning `DictOfUuids::maybeFirst()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::maybeFirst()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
