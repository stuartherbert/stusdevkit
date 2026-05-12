# ListOfFloats::add()

> `public function add(float $value): static`

Add a new float to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfFloats`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfFloats extends ListOfNumbers
{
    /**
     * @param float $value
     * @throws NullValueNotAllowedException
     */
    public function add(float $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed `float` parameter type shown above is bound by
> `@template-extends ListOfNumbers<float>` on [`ListOfFloats`](README.md).

## Description

Appends `$value` to the end of the list. The new float receives the next sequential integer key.

Duplicate floats are not prevented — `add()` does not check whether `$value` already exists in the list. If you need uniqueness, enforce it at the caller, or use a [Dictionary](../../Dictionaries/CollectionAsDict/README.md) variant.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfFloats` type.

## Parameters

**`$value`** (`float`)

The float to append. PHP widens an `int` to `float` automatically at the call site, so the type system accepts both forms — but the value is stored as `float`. `null` is not permitted.

The PHP signature accepts `mixed`, but the class's `@template-extends ListOfNumbers<float>` binding narrows this to `float`.

## Return Values

Returns `$this` — the same `ListOfFloats` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfFloats
 ✔ ->add() appends a float to the list
 ✔ ->add() appends multiple floats in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add duplicate floats
 ✔ ->add() can add zero
 ✔ ->add() accepts various float formats with data set "positive float"
 ✔ ->add() accepts various float formats with data set "negative float"
 ✔ ->add() accepts various float formats with data set "zero"
 ✔ ->add() accepts various float formats with data set "very small positive"
 ✔ ->add() accepts various float formats with data set "very large positive"
 ✔ ->add() accepts various float formats with data set "very small negative"
 ✔ ->add() accepts various float formats with data set "very large negative"
 ✔ ->add() accepts various float formats with data set "one third"
 ✔ ->add() accepts various float formats with data set "pi approximation"
 ✔ ->add() accepts various float formats with data set "euler number"
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfFloats`](README.md) — where the `<float>` template parameter is bound
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfFloats::merge()`](merge.md) — append many floats at once
- [`ListOfFloats::mergeArray()`](mergeArray.md) — append an array of floats

## Issues

- [Open issues mentioning `ListOfFloats::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfFloats%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfFloats::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfFloats%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfFloats%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
