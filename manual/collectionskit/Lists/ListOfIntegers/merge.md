# ListOfIntegers::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfIntegers extends ListOfNumbers
{
    /**
     * @param AccessibleCollection<int, int>|array<int, int> $input
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
> The narrowed `AccessibleCollection<int, int>|array<int, int>` parameter type
> shown above is bound by `@template-extends ListOfNumbers<int>` on
> [`ListOfIntegers`](README.md).

## Description

Adds the integers in `$input` to this list. `$input` may be a plain PHP array of integers or another `ListOfIntegers`.

Internally, `merge()` is a dispatcher: it forwards array inputs to [`mergeArray()`](mergeArray.md) and collection inputs to [`mergeSelf()`](mergeSelf.md). Use the specific method directly if you already know which form your input is.

This method modifies the receiving list — the integers are appended, in order, after the existing entries. It does not return a copy.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, int>|array<int, int>`)

The integers to append. A plain PHP array is appended in its given order, and an `AccessibleCollection` argument must be a subtype of `ListOfIntegers` (the late-static-bound class) — siblings such as [`ListOfFloats`](../ListOfFloats/README.md), [`ListOfNumbers`](../ListOfNumbers/README.md) instances that are not also `ListOfIntegers`, and unrelated `AccessibleCollection` subclasses are rejected.

The PHP signature accepts `AccessibleCollection|array`, but the class's `@template-extends ListOfNumbers<int>` binding narrows the value type to `int`.

## Return Values

Returns `$this` — the same `ListOfIntegers` instance, with `$input`'s integers appended at the next sequential integer keys.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a `null` value (propagated from [`mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfIntegers` (propagated from [`mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

**`ListOfIntegers` rejects `ListOfFloats` even though both are `ListOfNumbers`.** Subtype-compatibility runs the other way: `ListOfIntegers::merge()` accepts only `ListOfIntegers` (or subclasses of `ListOfIntegers`). The parent [`ListOfNumbers::merge()`](../ListOfNumbers/merge.md) is the place to accept either-or-both — call that when you need flexibility.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfIntegers
 ✔ ->merge() can merge an array into the list
 ✔ ->merge() can merge another ListOfIntegers
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:98`](../../../../kits/collectionskit/src/AccessibleCollection.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfIntegers`](README.md) — where the `<int>` template parameter is bound
- [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md) — the generic implementation this page specialises
- [`ListOfIntegers::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfIntegers::mergeSelf()`](mergeSelf.md) — collection-specific variant
- [`ListOfIntegers::add()`](add.md) — append a single integer
- [`ListOfNumbers::merge()`](../ListOfNumbers/merge.md) — parent variant that accepts both `ListOfIntegers` and `ListOfFloats`

## Issues

- [Open issues mentioning `ListOfIntegers::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfIntegers%3A%3Amerge()%22)
- [Closed issues mentioning `ListOfIntegers::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfIntegers%3A%3Amerge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
