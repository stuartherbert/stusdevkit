# ListOfNumbers::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;

class ListOfNumbers extends CollectionAsList
{
    /**
     * @param AccessibleCollection<int, int|float> $input
     * @return $this
     * @throws InvalidArgumentException
     */
    public function mergeSelf(AccessibleCollection $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function mergeSelf(AccessibleCollection $input): static`,
> inherited from [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md).
> The narrowed types shown above come from the
> `@template TValue of int|float = int|float` on [`ListOfNumbers`](README.md);
> subclasses can pin these further (e.g.
> [`ListOfIntegers`](../ListOfIntegers/README.md) pins `TValue` to `int`,
> [`ListOfFloats`](../ListOfFloats/README.md) pins `TValue` to `float`).

## Description

Copies the numbers stored in `$input` into this list. `$input` must be an instance of `ListOfNumbers` (or any subclass of it — [`ListOfIntegers`](../ListOfIntegers/README.md) and [`ListOfFloats`](../ListOfFloats/README.md) both qualify); siblings such as [`ListOfStrings`](../ListOfStrings/README.md) and unrelated `AccessibleCollection` subclasses are rejected with an [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php).

`$input` is not modified — only the receiving list is mutated.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, int|float>`)

The list whose numbers are to be appended to this list. Must be a `ListOfNumbers` or a subclass of it.

The PHP signature accepts `AccessibleCollection`, but the class's `@template TValue of int|float = int|float` re-bound narrows the value type to `int|float`.

## Return Values

Returns `$this` — the same `ListOfNumbers` instance, with `$input`'s numbers appended at the next sequential integer keys.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfNumbers` (e.g. a sibling or unrelated subclass).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ ->mergeSelf() merges another list into this one
 ✔ ->mergeSelf() does not modify the source list
 ✔ ->mergeSelf() with empty source leaves list unchanged
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:165`](../../../../kits/collectionskit/src/AccessibleCollection.php#L165)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](README.md) — where the `<int|float>` template parameter is re-bounded
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises
- [`ListOfNumbers::merge()`](merge.md) — dispatcher for array or collection input
- [`ListOfNumbers::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfNumbers::add()`](add.md) — append a single number

## Issues

- [Open issues mentioning `ListOfNumbers::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%3A%3AmergeSelf()%22)
- [Closed issues mentioning `ListOfNumbers::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%3A%3AmergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
