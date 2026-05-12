# ListOfStrings::add()

> `public function add(string $value): static`

Add a new string to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfStrings`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfStrings extends CollectionAsList
{
    /**
     * @param string $value
     * @throws NullValueNotAllowedException
     */
    public function add(string $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed `string` parameter type shown above is bound by
> `@extends CollectionAsList<string>` on [`ListOfStrings`](README.md).

## Description

Appends `$value` to the end of the list. The new string receives the next sequential integer key.

Duplicate strings are not prevented — `add()` does not check whether `$value` already exists in the list. If you need uniqueness, enforce it at the caller, or use a [Dictionary](../../Dictionaries/CollectionAsDict/README.md) variant.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site. Empty strings (`""`) are stored normally and counted.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfStrings` type.

## Parameters

**`$value`** (`string`)

The string to append. Any PHP `string` is accepted, including empty strings, strings with whitespace, multi-byte / Unicode strings, and strings containing null bytes. `null` is not permitted.

The PHP signature accepts `mixed`, but the class's `@extends CollectionAsList<string>` binding narrows this to `string`.

## Return Values

Returns `$this` — the same `ListOfStrings` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfStrings
 ✔ ->add() appends a string to the list
 ✔ ->add() appends multiple strings in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add duplicate strings
 ✔ ->add() can add empty strings
 ✔ ->add() accepts various string formats with data set "simple string"
 ✔ ->add() accepts various string formats with data set "empty string"
 ✔ ->add() accepts various string formats with data set "string with spaces"
 ✔ ->add() accepts various string formats with data set "string with newlines"
 ✔ ->add() accepts various string formats with data set "string with tabs"
 ✔ ->add() accepts various string formats with data set "unicode string"
 ✔ ->add() accepts various string formats with data set "string with special chars"
 ✔ ->add() accepts various string formats with data set "numeric string"
 ✔ ->add() accepts various string formats with data set "string with null bytes"
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfStrings`](README.md) — where the `<string>` template parameter is bound
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfStrings::merge()`](merge.md) — append many strings at once
- [`ListOfStrings::mergeArray()`](mergeArray.md) — append an array of strings

## Issues

- [Open issues mentioning `ListOfStrings::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfStrings%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfStrings::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfStrings%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfStrings%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
