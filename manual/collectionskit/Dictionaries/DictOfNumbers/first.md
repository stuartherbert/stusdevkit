# DictOfNumbers::first()

> `public function first(): int|float`

Returns the first number stored in this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use RuntimeException;

class DictOfNumbers extends CollectionAsDict
{
    /**
     * @return int|float
     *
     * @throws RuntimeException
     */
    public function first(): int|float
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfNumbers`](README.md)
> level. The runtime PHP signature is `public function first(): mixed`,
> inherited from [`AccessibleCollection::first()`](../../AccessibleCollection/first.md).
> The value type `int|float` comes from `@template TValue of int|float` declared
> on [`DictOfNumbers`](README.md). Subclasses such as
> [`DictOfIntegers`](../DictOfIntegers/README.md) and
> [`DictOfFloats`](../DictOfFloats/README.md) pin `TValue` to a concrete
> numeric type.

## Description

Returns the first number stored in this dict. Throws an exception if the dict is empty.

This is the throwing counterpart to [`DictOfNumbers::maybeFirst()`](maybeFirst.md). Use it when an empty dict at this point in your code is a programming error rather than an expected branch.

The "first" number is the entry whose key is returned by PHP's `array_key_first()` over the dict's stored data — the first key in iteration order, which is insertion order for a dict that has only been added to.

## Parameters

_None._

## Return Values

The first stored number, `int` or `float`. The PHP return type is `mixed`; the class's template binding narrows it to `int|float`.

## Errors/Exceptions

- **[`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)** — when the dict is empty. The message is `<CollectionType> is empty`, where `<CollectionType>` is the runtime class name (e.g. `DictOfNumbers`, `DictOfIntegers`, `DictOfFloats`).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfNumbers
 ✔ ->first() returns the first number
 ✔ ->first() throws RuntimeException for empty dict
 ✔ Dict with one number: ->first() and ->last() return the same value
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:257`](../../../../kits/collectionskit/src/AccessibleCollection.php#L257)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfNumbers`](README.md) — where `TValue` is re-bounded to `int|float`
- [`DictOfNumbers::maybeFirst()`](maybeFirst.md) — returns the first number stored in this dict (returns `null` when empty)
- [`DictOfNumbers::last()`](last.md) — returns the last number of this dict (throws when empty)
- [`DictOfNumbers::maybeLast()`](maybeLast.md) — returns the last number of this dict (returns `null` when empty)
- [`AccessibleCollection::first()`](../../AccessibleCollection/first.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfNumbers::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfNumbers::first()%22)
- [Closed issues mentioning `DictOfNumbers::first()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfNumbers::first()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
