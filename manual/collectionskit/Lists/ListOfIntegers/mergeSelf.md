# ListOfIntegers::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;

class ListOfIntegers extends ListOfNumbers
{
    /**
     * @param AccessibleCollection<int, int> $input
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
> The narrowed `AccessibleCollection<int, int>` parameter type shown above is
> bound by `@template-extends ListOfNumbers<int>` on [`ListOfIntegers`](README.md).

## Description

Copies the integers stored in `$input` into this list. `$input` must be an instance of `ListOfIntegers` (or any subclass of it); siblings such as [`ListOfFloats`](../ListOfFloats/README.md), unrelated `ListOfNumbers` subclasses, and unrelated `AccessibleCollection` subclasses are rejected with an [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php).

`$input` is not modified — only the receiving list is mutated.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, int>`)

The list whose integers are to be appended to this list. Must be a `ListOfIntegers` or a subclass of it.

The PHP signature accepts `AccessibleCollection`, but the class's `@template-extends ListOfNumbers<int>` binding narrows the value type to `int`.

## Return Values

Returns `$this` — the same `ListOfIntegers` instance, with `$input`'s integers appended at the next sequential integer keys.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfIntegers` (e.g. a sibling or unrelated subclass).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfIntegers
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

- [`ListOfIntegers`](README.md) — where the `<int>` template parameter is bound
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises
- [`ListOfIntegers::merge()`](merge.md) — dispatcher for array or collection input
- [`ListOfIntegers::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfIntegers::add()`](add.md) — append a single integer

## Issues

- [Open issues mentioning `ListOfIntegers::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfIntegers%3A%3AmergeSelf()%22)
- [Closed issues mentioning `ListOfIntegers::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfIntegers%3A%3AmergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
