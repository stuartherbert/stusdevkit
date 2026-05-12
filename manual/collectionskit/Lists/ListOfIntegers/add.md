# ListOfIntegers::add()

> `public function add(int $value): static`

Add a new integer to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfIntegers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfIntegers extends ListOfNumbers
{
    /**
     * @param int $value
     * @throws NullValueNotAllowedException
     */
    public function add(int $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed `int` parameter type shown above is bound by
> `@template-extends ListOfNumbers<int>` on [`ListOfIntegers`](README.md).

## Description

Appends `$value` to the end of the list. The new integer receives the next sequential integer key.

Duplicate integers are not prevented — `add()` does not check whether `$value` already exists in the list. If you need uniqueness, enforce it at the caller, or use a [Dictionary](../../Dictionaries/CollectionAsDict/README.md) variant.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfIntegers` type.

## Parameters

**`$value`** (`int`)

The integer to append. `null` is not permitted; floats are rejected by the type system at the call site.

The PHP signature accepts `mixed`, but the class's `@template-extends ListOfNumbers<int>` binding narrows this to `int`.

## Return Values

Returns `$this` — the same `ListOfIntegers` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfIntegers
 ✔ ->add() appends an integer to the list
 ✔ ->add() appends multiple integers in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add duplicate integers
 ✔ ->add() can add zero
 ✔ ->add() accepts various integer formats with data set "positive integer"
 ✔ ->add() accepts various integer formats with data set "negative integer"
 ✔ ->add() accepts various integer formats with data set "zero"
 ✔ ->add() accepts various integer formats with data set "one"
 ✔ ->add() accepts various integer formats with data set "negative one"
 ✔ ->add() accepts various integer formats with data set "large positive"
 ✔ ->add() accepts various integer formats with data set "large negative"
 ✔ ->add() accepts various integer formats with data set "power of two"
 ✔ ->add() accepts various integer formats with data set "hex-friendly value"
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfIntegers`](README.md) — where the `<int>` template parameter is bound
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfIntegers::merge()`](merge.md) — append many integers at once
- [`ListOfIntegers::mergeArray()`](mergeArray.md) — append an array of integers

## Issues

- [Open issues mentioning `ListOfIntegers::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfIntegers%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfIntegers::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfIntegers%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfIntegers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
