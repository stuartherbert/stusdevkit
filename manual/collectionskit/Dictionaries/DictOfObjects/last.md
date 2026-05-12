# DictOfObjects::last()

> `public function last(): object`

Returns the last object of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use RuntimeException;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @return object
     *
     * @throws RuntimeException
     */
    public function last(): object
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function last(): mixed`,
> inherited from [`AccessibleCollection::last()`](../../AccessibleCollection/last.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Returns the last object stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfObjects::maybeLast()`](maybeLast.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "last" object is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

## Parameters

_None._

## Return Values

The last stored object. The PHP return type is `mixed`; the class's template binding narrows it to `object`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `<CollectionType> is empty`, where `<CollectionType>` is the runtime class name (e.g. `DictOfObjects`, `DictOfUuids`).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->last() returns the last object
 ✔ ->last() throws RuntimeException for empty dict
 ✔ Dict with one object: ->first() and ->last() return the same object
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::maybeLast()`](maybeLast.md) — returns the last object of this dict (returns `null` when empty)
- [`DictOfObjects::first()`](first.md) — returns the first object stored in this dict (throws when empty)
- [`DictOfObjects::maybeFirst()`](maybeFirst.md) — returns the first object stored in this dict (returns `null` when empty)
- [`AccessibleCollection::last()`](../../AccessibleCollection/last.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::last()%22)
- [Closed issues mentioning `DictOfObjects::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::last()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
