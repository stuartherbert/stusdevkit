# ListOfFloats::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfFloats extends ListOfNumbers
{
    /**
     * @param AccessibleCollection<int, float>|array<int, float> $input
     * @return $this
     * @throws NullValueNotAllowedException
     * @throws InvalidArgumentException
     */
    public function merge(AccessibleCollection|array $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function merge(AccessibleCollection|array $input): static`,
> inherited from [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md).
> The narrowed `AccessibleCollection<int, float>|array<int, float>` parameter
> type shown above is bound by `@template-extends ListOfNumbers<float>` on
> [`ListOfFloats`](README.md).

## Description

Adds the floats in `$input` to this list. `$input` may be a plain PHP array of floats or another `ListOfFloats`.

Internally, `merge()` is a dispatcher: it forwards array inputs to [`mergeArray()`](mergeArray.md) and collection inputs to [`mergeSelf()`](mergeSelf.md). Use the specific method directly if you already know which form your input is.

This method modifies the receiving list — the floats are appended, in order, after the existing entries. It does not return a copy.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, float>|array<int, float>`)

The floats to append. A plain PHP array is appended in its given order, and an `AccessibleCollection` argument must be a subtype of `ListOfFloats` (the late-static-bound class) — siblings such as [`ListOfIntegers`](../ListOfIntegers/README.md), [`ListOfNumbers`](../ListOfNumbers/README.md) instances that are not also `ListOfFloats`, and unrelated `AccessibleCollection` subclasses are rejected.

The PHP signature accepts `AccessibleCollection|array`, but the class's `@template-extends ListOfNumbers<float>` binding narrows the value type to `float`.

## Return Values

Returns `$this` — the same `ListOfFloats` instance, with `$input`'s floats appended at the next sequential integer keys.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a `null` value (propagated from [`mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfFloats` (propagated from [`mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

**`ListOfFloats` rejects `ListOfIntegers` even though both are `ListOfNumbers`.** Subtype-compatibility runs the other way: `ListOfFloats::merge()` accepts only `ListOfFloats` (or subclasses of `ListOfFloats`). The parent [`ListOfNumbers::merge()`](../ListOfNumbers/merge.md) is the place to accept either-or-both — call that when you need flexibility.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfFloats
 ✔ ->merge() can merge an array into the list
 ✔ ->merge() can merge another ListOfFloats
 ✔ ->merge() cannot merge ListOfIntegers
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:98`](../../../../kits/collectionskit/src/AccessibleCollection.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfFloats`](README.md) — where the `<float>` template parameter is bound
- [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md) — the generic implementation this page specialises
- [`ListOfFloats::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfFloats::mergeSelf()`](mergeSelf.md) — collection-specific variant
- [`ListOfFloats::add()`](add.md) — append a single float
- [`ListOfNumbers::merge()`](../ListOfNumbers/merge.md) — parent variant that accepts both `ListOfIntegers` and `ListOfFloats`

## Issues

- [Open issues mentioning `ListOfFloats::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfFloats%3A%3Amerge()%22)
- [Closed issues mentioning `ListOfFloats::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfFloats%3A%3Amerge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
