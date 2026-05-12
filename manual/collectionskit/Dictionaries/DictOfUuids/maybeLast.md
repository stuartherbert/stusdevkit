# DictOfUuids::maybeLast()

> `public function maybeLast(): ?UuidInterface`

Returns the last UUID of this dict.

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
    public function maybeLast(): ?UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function maybeLast(): mixed`, inherited
> from [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md).
> The narrowed `UuidInterface|null` return type shown above is bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns the last [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored in this dict, or `null` if the dict is empty.

The "last" UUID is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

This is the non-throwing accessor. Use [`DictOfUuids::last()`](last.md) when you would rather have an exception than a `null` when the dict is empty.

## Parameters

_None._

## Return Values

The last stored `UuidInterface`, or `null` when the dict is empty. The PHP return type is `mixed`; the class's template binding narrows it to `UuidInterface|null`.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->maybeLast() returns the last UUID
 ✔ ->maybeLast() returns null for empty dict
 ✔ ->maybeLast() returns the last UUID added via set()
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:282`](../../../../kits/collectionskit/src/AccessibleCollection.php#L282)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::last()`](last.md) — returns the last UUID of this dict (throws when empty)
- [`DictOfUuids::maybeFirst()`](maybeFirst.md) — returns the first UUID stored in this dict (returns `null` when empty)
- [`DictOfUuids::first()`](first.md) — returns the first UUID stored in this dict (throws when empty)
- [`AccessibleCollection::maybeLast()`](../../AccessibleCollection/maybeLast.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::maybeLast()%22)
- [Closed issues mentioning `DictOfUuids::maybeLast()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::maybeLast()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
