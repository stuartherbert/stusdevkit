# DictOfUuids::first()

> `public function first(): UuidInterface`

Returns the first UUID stored in this dict.

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
    public function first(): UuidInterface
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function first(): mixed`, inherited from
> [`AccessibleCollection::first()`](../../AccessibleCollection/first.md). The
> narrowed `UuidInterface` return type shown above is bound by
> `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Returns the first [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfUuids::maybeFirst()`](maybeFirst.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "first" UUID is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

## Parameters

_None._

## Return Values

The first stored `UuidInterface`. The PHP return type is `mixed`; the class's template binding narrows it to `UuidInterface`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `DictOfUuids is empty`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
 ✔ ->first() returns the first UUID
 ✔ ->first() throws RuntimeException for empty dict
 ✔ Dict with one UUID: ->first() and ->last() return the same UUID
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:257`](../../../../kits/collectionskit/src/AccessibleCollection.php#L257)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::maybeFirst()`](maybeFirst.md) — returns the first UUID stored in this dict (returns `null` when empty)
- [`DictOfUuids::last()`](last.md) — returns the last UUID of this dict (throws when empty)
- [`DictOfUuids::maybeLast()`](maybeLast.md) — returns the last UUID of this dict (returns `null` when empty)
- [`AccessibleCollection::first()`](../../AccessibleCollection/first.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::first()%22)
- [Closed issues mentioning `DictOfUuids::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::first()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
