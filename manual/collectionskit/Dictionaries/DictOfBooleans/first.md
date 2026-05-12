# DictOfBooleans::first()

> `public function first(): bool`

Returns the first flag stored in this dict.

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
    public function first(): bool
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfBooleans`](README.md)
> level. The runtime PHP signature is `public function first(): mixed`,
> inherited from [`AccessibleCollection::first()`](../../AccessibleCollection/first.md).
> The value type `bool` is pinned by `@template-extends CollectionAsDict<TKey, bool>`
> on [`DictOfBooleans`](README.md).

## Description

Returns the first flag stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfBooleans::maybeFirst()`](maybeFirst.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "first" flag is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

## Parameters

_None._

## Return Values

The first stored flag (`true` or `false`). The PHP return type is `mixed`; the class's template binding narrows it to `bool`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `DictOfBooleans is empty`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfBooleans
 ✔ ->first() returns the first flag
 ✔ ->first() throws RuntimeException for empty dict
 ✔ Dict with one flag: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:257`](../../../../kits/collectionskit/src/AccessibleCollection.php#L257)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfBooleans`](README.md) — where `TValue` is pinned to `bool`
- [`DictOfBooleans::maybeFirst()`](maybeFirst.md) — returns the first flag stored in this dict (returns `null` when empty)
- [`DictOfBooleans::last()`](last.md) — returns the last flag of this dict (throws when empty)
- [`DictOfBooleans::maybeLast()`](maybeLast.md) — returns the last flag of this dict (returns `null` when empty)
- [`AccessibleCollection::first()`](../../AccessibleCollection/first.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfBooleans::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfBooleans::first()%22)
- [Closed issues mentioning `DictOfBooleans::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfBooleans::first()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfBooleans%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
