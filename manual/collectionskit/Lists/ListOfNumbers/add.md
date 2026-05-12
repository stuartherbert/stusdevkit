# ListOfNumbers::add()

> `public function add(int|float $value): static`

Add a new number to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfNumbers`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfNumbers extends CollectionAsList
{
    /**
     * @param int|float $value
     * @throws NullValueNotAllowedException
     */
    public function add(int|float $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed types shown above come from the
> `@template TValue of int|float = int|float` on [`ListOfNumbers`](README.md);
> subclasses can pin these further (e.g.
> [`ListOfIntegers`](../ListOfIntegers/README.md) pins `TValue` to `int`,
> [`ListOfFloats`](../ListOfFloats/README.md) pins `TValue` to `float`).

## Description

Appends `$value` to the end of the list. The new number receives the next sequential integer key.

Duplicate numbers are not prevented — `add()` does not check whether `$value` already exists in the list. If you need uniqueness, enforce it at the caller, or use a [Dictionary](../../Dictionaries/CollectionAsDict/README.md) variant.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfNumbers` type (or the subclass type, when called on [`ListOfIntegers`](../ListOfIntegers/README.md) or [`ListOfFloats`](../ListOfFloats/README.md)).

## Parameters

**`$value`** (`int|float`)

The number to append. PHP's `int` and `float` types are both accepted; the value's PHP type is preserved as-stored — `1` stays `int`, `1.0` stays `float`. `null` is not permitted.

The PHP signature accepts `mixed`, but the class's `@template TValue of int|float = int|float` re-bound narrows this to `int|float`.

## Return Values

Returns `$this` — the same `ListOfNumbers` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

**`int` and `float` are not interchanged.** `add(1)` and `add(1.0)` produce two distinct entries whose PHP types differ — the list never silently coerces between numeric types. Callers relying on `assertSame()` to compare round-tripped data must respect that distinction.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfNumbers
 ✔ ->add() appends a number to the list
 ✔ ->add() appends multiple numbers in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add duplicate numbers
 ✔ ->add() can add zero
 ✔ ->add() accepts various numeric formats with data set "positive integer"
 ✔ ->add() accepts various numeric formats with data set "negative integer"
 ✔ ->add() accepts various numeric formats with data set "zero integer"
 ✔ ->add() accepts various numeric formats with data set "positive float"
 ✔ ->add() accepts various numeric formats with data set "negative float"
 ✔ ->add() accepts various numeric formats with data set "zero float"
 ✔ ->add() accepts various numeric formats with data set "large integer"
 ✔ ->add() accepts various numeric formats with data set "small integer"
 ✔ ->add() accepts various numeric formats with data set "large float"
 ✔ ->add() accepts various numeric formats with data set "small positive float"
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfNumbers`](README.md) — where the `<int|float>` template parameter is re-bounded
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfNumbers::merge()`](merge.md) — append many numbers at once
- [`ListOfNumbers::mergeArray()`](mergeArray.md) — append an array of numbers

## Issues

- [Open issues mentioning `ListOfNumbers::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfNumbers%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfNumbers::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfNumbers%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfNumbers%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
