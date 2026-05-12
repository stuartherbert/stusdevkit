# ListOfObjects::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;

class ListOfObjects extends CollectionAsList
{
    /**
     * @param AccessibleCollection<int, object> $input
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
> The narrowed `AccessibleCollection<int, object>` parameter type shown above
> is bound by `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Copies the objects stored in `$input` into this list. `$input` must be an instance of `ListOfObjects` (or any subclass of it, such as [`ListOfUuids`](../ListOfUuids/README.md)); siblings such as [`ListOfStrings`](../ListOfStrings/README.md) and unrelated `AccessibleCollection` subclasses are rejected with an [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php).

`$input` is not modified — only the receiving list is mutated. The objects are appended by reference (no cloning).

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, object>`)

The list whose objects are to be appended to this list. Must be a `ListOfObjects` or a subclass of it.

The PHP signature accepts `AccessibleCollection`, but the class's `@extends CollectionAsList<object>` binding narrows the value type to `object`.

## Return Values

Returns `$this` — the same `ListOfObjects` instance, with `$input`'s objects appended at the next sequential integer keys.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfObjects` (e.g. a sibling or unrelated subclass).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
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

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises
- [`ListOfObjects::merge()`](merge.md) — dispatcher for array or collection input
- [`ListOfObjects::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfObjects::add()`](add.md) — append a single object

## Issues

- [Open issues mentioning `ListOfObjects::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3AmergeSelf()%22)
- [Closed issues mentioning `ListOfObjects::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3AmergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
