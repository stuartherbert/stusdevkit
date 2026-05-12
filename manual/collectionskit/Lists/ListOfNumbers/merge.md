# ListOfNumbers::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfNumbers extends CollectionAsList
{
    /**
     * @param AccessibleCollection<int, int|float>|array<int, int|float> $input
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
> The narrowed types shown above come from the
> `@template TValue of int|float = int|float` on [`ListOfNumbers`](README.md);
> subclasses can pin these further (e.g.
> [`ListOfIntegers`](../ListOfIntegers/README.md) pins `TValue` to `int`,
> [`ListOfFloats`](../ListOfFloats/README.md) pins `TValue` to `float`).

## Description

Adds the numbers in `$input` to this list. `$input` may be a plain PHP array of numbers or any [`AccessibleCollection`](../../AccessibleCollection/README.md) subtype of `ListOfNumbers` — which includes [`ListOfIntegers`](../ListOfIntegers/README.md) and [`ListOfFloats`](../ListOfFloats/README.md).

Internally, `merge()` is a dispatcher: it forwards array inputs to [`mergeArray()`](mergeArray.md) and collection inputs to [`mergeSelf()`](mergeSelf.md). Use the specific method directly if you already know which form your input is.

This method modifies the receiving list — the numbers are appended, in order, after the existing entries. It does not return a copy.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, int|float>|array<int, int|float>`)

The numbers to append. A plain PHP array is appended in its given order, and an `AccessibleCollection` argument must be a subtype of `ListOfNumbers` (the late-static-bound class) — `ListOfNumbers`, `ListOfIntegers`, and `ListOfFloats` all qualify; sibling lists such as [`ListOfStrings`](../ListOfStrings/README.md) and unrelated `AccessibleCollection` subclasses are rejected.

The PHP signature accepts `AccessibleCollection|array`, but the class's `@template TValue of int|float = int|float` re-bound narrows the value type to `int|float`.

## Return Values

Returns `$this` — the same `ListOfNumbers` instance, with `$input`'s numbers appended at the next sequential integer keys.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a `null` value (propagated from [`mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfNumbers` (propagated from [`mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

**Subtype-compatibility is decided by late-static binding.** `ListOfNumbers::merge()` accepts [`ListOfIntegers`](../ListOfIntegers/README.md) and [`ListOfFloats`](../ListOfFloats/README.md) because both satisfy `instanceof ListOfNumbers`. The reverse is not true: `ListOfIntegers::merge()` rejects `ListOfFloats`, and vice versa — they are siblings, not subtypes of each other. Trying to merge a sibling raises [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php) at runtime; PHPStan catches the same type mismatch at compile time when used correctly.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ ->merge() can merge an array into the list
 ✔ ->merge() can merge another ListOfNumbers
 ✔ ->merge() can merge ListOfFloats
 ✔ ->merge() can merge ListOfIntegers
 ✔ ->merge() cannot merge ListOfStrings
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:98`](../../../../kits/collectionskit/src/AccessibleCollection.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](README.md) — where the `<int|float>` template parameter is re-bounded
- [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md) — the generic implementation this page specialises
- [`ListOfNumbers::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfNumbers::mergeSelf()`](mergeSelf.md) — collection-specific variant
- [`ListOfNumbers::add()`](add.md) — append a single number

## Issues

- [Open issues mentioning `ListOfNumbers::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%3A%3Amerge()%22)
- [Closed issues mentioning `ListOfNumbers::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%3A%3Amerge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
