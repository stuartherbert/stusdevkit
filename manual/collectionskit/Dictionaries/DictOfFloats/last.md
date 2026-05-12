# DictOfFloats::last()

> `public function last(): float`

Returns the last float of this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use RuntimeException;

class DictOfFloats extends DictOfNumbers
{
    /**
     * @return float
     *
     * @throws RuntimeException
     */
    public function last(): float
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function last(): mixed`, inherited from
> [`AccessibleCollection::last()`](../../AccessibleCollection/last.md). The
> narrowed `float` return type shown above is bound by
> `@template-extends DictOfNumbers<array-key, float>` on [`DictOfFloats`](README.md).

## Description

Returns the last float stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfFloats::maybeLast()`](maybeLast.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "last" float is the entry whose key is returned by PHP's `array_key_last()` over the dict's stored data — the last key in iteration order, which is the most recently added entry for a dict that has only been added to.

## Parameters

_None._

## Return Values

The last stored float. The PHP return type is `mixed`; the class's template binding narrows it to `float`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `DictOfFloats is empty`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfFloats
 ✔ ->last() returns the last float
 ✔ ->last() throws RuntimeException for empty dict
 ✔ Dict with one float: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:308`](../../../../kits/collectionskit/src/AccessibleCollection.php#L308)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfFloats`](README.md) — where the `<array-key, float>` template parameters are bound
- [`DictOfFloats::maybeLast()`](maybeLast.md) — returns the last float of this dict (returns `null` when empty)
- [`DictOfFloats::first()`](first.md) — returns the first float stored in this dict (throws when empty)
- [`DictOfFloats::maybeFirst()`](maybeFirst.md) — returns the first float stored in this dict (returns `null` when empty)
- [`DictOfNumbers::last()`](../DictOfNumbers/last.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfFloats::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfFloats::last()%22)
- [Closed issues mentioning `DictOfFloats::last()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfFloats::last()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
