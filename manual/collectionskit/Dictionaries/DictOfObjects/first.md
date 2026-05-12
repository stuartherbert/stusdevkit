# DictOfObjects::first()

> `public function first(): object`

Returns the first object stored in this dict.

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
    public function first(): object
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function first(): mixed`,
> inherited from [`AccessibleCollection::first()`](../../AccessibleCollection/first.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Returns the first object stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfObjects::maybeFirst()`](maybeFirst.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "first" object is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

## Parameters

_None._

## Return Values

The first stored object. The PHP return type is `mixed`; the class's template binding narrows it to `object`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `<CollectionType> is empty`, where `<CollectionType>` is the runtime class name (e.g. `DictOfObjects`, `DictOfUuids`).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->first() returns the first object
 ✔ ->first() throws RuntimeException for empty dict
 ✔ Dict with one object: ->first() and ->last() return the same object
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:257`](../../../../kits/collectionskit/src/AccessibleCollection.php#L257)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::maybeFirst()`](maybeFirst.md) — returns the first object stored in this dict (returns `null` when empty)
- [`DictOfObjects::last()`](last.md) — returns the last object of this dict (throws when empty)
- [`DictOfObjects::maybeLast()`](maybeLast.md) — returns the last object of this dict (returns `null` when empty)
- [`AccessibleCollection::first()`](../../AccessibleCollection/first.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::first()%22)
- [Closed issues mentioning `DictOfObjects::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::first()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
