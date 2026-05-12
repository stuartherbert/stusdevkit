# DictOfBooleans::last()

> `public function last(): bool`

Returns the last flag of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use RuntimeException;

class DictOfBooleans extends CollectionAsDict
{
    /**
     * @return bool
     *
     * @throws RuntimeException
     */
    public function last(): bool
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function last(): mixed`,
> inherited from [`AccessibleCollection::last()`](../../AccessibleCollection/last.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md).

## Description

Returns the last flag stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfBooleans::maybeLast()`](maybeLast.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "last" flag is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

## Parameters

_None._

## Return Values

The last stored flag (`true` or `false`). The PHP return type is `mixed`; the class's template binding narrows it to `bool`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `DictOfBooleans is empty`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->last() returns the last flag
 ✔ ->last() throws RuntimeException for empty dict
 ✔ Dict with one flag: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::maybeLast()`](maybeLast.md) — returns the last flag of this dict (returns `null` when empty)
- [`DictOfBooleans::first()`](first.md) — returns the first flag stored in this dict (throws when empty)
- [`DictOfBooleans::maybeFirst()`](maybeFirst.md) — returns the first flag stored in this dict (returns `null` when empty)
- [`AccessibleCollection::last()`](../../AccessibleCollection/last.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::last()%22)
- [Closed issues mentioning `DictOfBooleans::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::last()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
