# ListOfCallables::add()

> `public function add(callable $value): static`

Add a new callable to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfCallables`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfCallables extends CollectionAsList
{
    /**
     * @param callable $value
     * @throws NullValueNotAllowedException
     */
    public function add(callable $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed `callable` parameter type shown above is bound by
> `@extends CollectionAsList<callable>` on [`ListOfCallables`](README.md).

## Description

Appends `$value` to the end of the list. The new callable receives the next sequential integer key.

Duplicate callables are not prevented — `add()` does not check whether `$value` already exists in the list. If you need uniqueness, enforce it at the caller, or use a [Dictionary](../../Dictionaries/CollectionAsDict/README.md) variant.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfCallables` type.

## Parameters

**`$value`** (`callable`)

The callable to append. Any PHP callable is accepted: closure, arrow function, function-name string, `[class, method]` static-method array, `[$object, method]` instance-method array, or an invokable object (an object with [`__invoke`](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke)). `null` is not permitted.

The PHP signature accepts `mixed`, but the class's `@extends CollectionAsList<callable>` binding narrows this to `callable`.

## Return Values

Returns `$this` — the same `ListOfCallables` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

**The same callable can appear more than once.** `add()` does not deduplicate; calling `add($fn)` three times stores three entries that all reference the same closure. This is the documented behaviour — see [`->add() can add the same callable instance twice`](#contract-from-tests) below — but it is a footgun when callbacks are added in a loop that may run more than once for the same source.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfCallables
 ✔ ->add() appends a callable to the list
 ✔ ->add() appends multiple callables in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add the same callable instance twice
 ✔ ->add() accepts a closure
 ✔ ->add() accepts a named function string
 ✔ ->add() accepts a static method array
 ✔ ->add() accepts an instance method array
 ✔ ->add() accepts an invokable object
 ✔ ->add() accepts various callable formats with data set "closure"
 ✔ ->add() accepts various callable formats with data set "arrow function"
 ✔ ->add() accepts various callable formats with data set "named function"
 ✔ ->add() accepts various callable formats with data set "static method array"
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfCallables`](README.md) — where the `<callable>` template parameter is bound
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfCallables::merge()`](merge.md) — append many callables at once
- [`ListOfCallables::mergeArray()`](mergeArray.md) — append an array of callables

## Issues

- [Open issues mentioning `ListOfCallables::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfCallables%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfCallables::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfCallables%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfCallables%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
