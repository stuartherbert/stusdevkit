# ListOfStrings::mergeArray()

> `public function mergeArray(array $input): static`

Add the given `$input` to this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfStrings extends CollectionAsList
{
    /**
     * @param array<int, string> $input
     * @return $this
     * @throws NullValueNotAllowedException
     */
    public function mergeArray(array $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function mergeArray(array $input): static`,
> inherited from [`AccessibleCollection::mergeArray()`](../../AccessibleCollection/mergeArray.md).
> The narrowed `array<int, string>` parameter type shown above is bound by
> `@extends CollectionAsList<string>` on [`ListOfStrings`](README.md).

## Description

Appends the strings in `$input` to this list. Useful when you already know that your input is a plain PHP array; otherwise call [`merge()`](merge.md), which dispatches to this method automatically for array inputs.

Internally, every element of `$input` is checked for `null` before any data is appended. If any element is `null`, a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) is raised and the list is left untouched.

This method modifies the receiving list. It does not return a copy.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`array<int, string>`)

The strings to append. The array is consumed in iteration order; keys are not preserved (the new entries receive the next sequential integer keys).

The PHP signature accepts `array`, but the class's `@extends CollectionAsList<string>` binding narrows the value type to `string`.

## Return Values

Returns `$this` — the same `ListOfStrings` instance, with `$input`'s strings appended at the next sequential integer keys.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$input` is `null`. Enforced by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md) so that the no-null invariant holds across every merge path.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfStrings
 ✔ ->mergeArray() adds array items to the list
 ✔ ->mergeArray() into empty list sets the data
 ✔ ->mergeArray() with empty array leaves list unchanged
 ✔ ->mergeArray() returns $this for method chaining
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:128`](../../../../kits/collectionskit/src/AccessibleCollection.php#L128)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfStrings`](README.md) — where the `<string>` template parameter is bound
- [`AccessibleCollection::mergeArray()`](../../AccessibleCollection/mergeArray.md) — the generic implementation this page specialises
- [`ListOfStrings::merge()`](merge.md) — dispatcher for array or collection input
- [`ListOfStrings::mergeSelf()`](mergeSelf.md) — collection-specific variant
- [`ListOfStrings::add()`](add.md) — append a single string

## Issues

- [Open issues mentioning `ListOfStrings::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfStrings%3A%3AmergeArray()%22)
- [Closed issues mentioning `ListOfStrings::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfStrings%3A%3AmergeArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
