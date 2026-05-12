# ListOfUuids::mergeSelf()

> `public function mergeSelf(AccessibleCollection $input): static`

Copies the contents of `$input` into this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use StusDevKit\CollectionsKit\AccessibleCollection;

class ListOfUuids extends CollectionAsList
{
    /**
     * @param AccessibleCollection<int, UuidInterface> $input
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
> The narrowed `AccessibleCollection<int, UuidInterface>` parameter type shown
> above is bound by `@extends CollectionAsList<UuidInterface>` on
> [`ListOfUuids`](README.md).

## Description

Copies the UUIDs stored in `$input` into this list. `$input` must be an instance of `ListOfUuids` (or any subclass of it); siblings and unrelated `AccessibleCollection` subclasses are rejected with an [`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php).

`$input` is not modified — only the receiving list is mutated. The UUIDs are appended by reference (no cloning).

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, UuidInterface>`)

The list whose UUIDs are to be appended to this list. Must be a `ListOfUuids` or a subclass of it.

The PHP signature accepts `AccessibleCollection`, but the class's `@extends CollectionAsList<UuidInterface>` binding narrows the value type to `UuidInterface`.

## Return Values

Returns `$this` — the same `ListOfUuids` instance, with `$input`'s UUIDs appended at the next sequential integer keys.

## Errors/Exceptions

- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfUuids` (e.g. a sibling or unrelated subclass).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
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

- [`ListOfUuids`](README.md) — where the `<UuidInterface>` template parameter is bound
- [`AccessibleCollection::mergeSelf()`](../../AccessibleCollection/mergeSelf.md) — the generic implementation this page specialises
- [`ListOfUuids::merge()`](merge.md) — dispatcher for array or collection input
- [`ListOfUuids::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfUuids::add()`](add.md) — append a single UUID

## Issues

- [Open issues mentioning `ListOfUuids::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%3A%3AmergeSelf()%22)
- [Closed issues mentioning `ListOfUuids::mergeSelf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%3A%3AmergeSelf()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
