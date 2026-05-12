# DictOfUuids::last()

> `public function last(): UuidInterface`

Returns the last UUID of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;
use RuntimeException;

class DictOfUuids extends DictOfObjects
{
    /**
     * @return UuidInterface
     *
     * @throws RuntimeException
     */
    public function last(): UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function last(): mixed`, inherited from
> [`AccessibleCollection::last()`](../../AccessibleCollection/last.md). The
> narrowed `UuidInterface` return type shown above is bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns the last [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfUuids::maybeLast()`](maybeLast.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "last" UUID is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

## Parameters

_None._

## Return Values

The last stored `UuidInterface`. The PHP return type is `mixed`; the class's template binding narrows it to `UuidInterface`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `DictOfUuids is empty`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->last() returns the last UUID
 ✔ ->last() throws RuntimeException for empty dict
 ✔ Dict with one UUID: ->first() and ->last() return the same UUID
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::maybeLast()`](maybeLast.md) — returns the last UUID of this dict (returns `null` when empty)
- [`DictOfUuids::first()`](first.md) — returns the first UUID stored in this dict (throws when empty)
- [`DictOfUuids::maybeFirst()`](maybeFirst.md) — returns the first UUID stored in this dict (returns `null` when empty)
- [`AccessibleCollection::last()`](../../AccessibleCollection/last.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::last()%22)
- [Closed issues mentioning `DictOfUuids::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::last()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
