# ListOfCallables::merge()

> `public function merge(AccessibleCollection|array $input): static`

Add the given `$input` to this list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfCallables`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use InvalidArgumentException;
use StusDevKit\CollectionsKit\AccessibleCollection;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfCallables extends CollectionAsList
{
    /**
     * @param AccessibleCollection<int, callable>|array<int, callable> $input
     * @return $this
     * @throws NullValueNotAllowedException
     * @throws InvalidArgumentException
     */
    public function merge(AccessibleCollection|array $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function merge(AccessibleCollection|array $input): static`,
> inherited from [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md).
> The narrowed `AccessibleCollection<int, callable>|array<int, callable>`
> parameter type shown above is bound by
> `@extends CollectionAsList<callable>` on [`ListOfCallables`](README.md).

## Description

Adds the callables in `$input` to this list. `$input` may be a plain PHP array of callables or another `ListOfCallables` (or any [`AccessibleCollection`](../../AccessibleCollection/README.md) subtype of `ListOfCallables`).

Internally, `merge()` is a dispatcher: it forwards array inputs to [`mergeArray()`](mergeArray.md) and collection inputs to [`mergeSelf()`](mergeSelf.md). Use the specific method directly if you already know which form your input is.

This method modifies the receiving list — the callables are appended, in order, after the existing entries. It does not return a copy.

The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$input`** (`AccessibleCollection<int, callable>|array<int, callable>`)

The callables to append. A plain PHP array is appended in its given order, and an `AccessibleCollection` argument must be a subtype of `ListOfCallables` (the late-static-bound class) — siblings or unrelated subclasses are rejected.

The PHP signature accepts `AccessibleCollection|array`, but the class's `@extends CollectionAsList<callable>` binding narrows the value type to `callable`.

## Return Values

Returns `$this` — the same `ListOfCallables` instance, with `$input`'s callables appended at the next sequential integer keys.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$input` is an array containing a `null` value (propagated from [`mergeArray()`](mergeArray.md)).
- **[`InvalidArgumentException`](https://www.php.net/manual/en/class.invalidargumentexception.php)** — when `$input` is an `AccessibleCollection` that is not a subtype of `ListOfCallables` (propagated from [`mergeSelf()`](mergeSelf.md)).

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfCallables
 ✔ ->merge() can merge an array into the list
 ✔ ->merge() can merge another ListOfCallables
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:98`](../../../../kits/collectionskit/src/AccessibleCollection.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfCallables`](README.md) — where the `<callable>` template parameter is bound
- [`AccessibleCollection::merge()`](../../AccessibleCollection/merge.md) — the generic implementation this page specialises
- [`ListOfCallables::mergeArray()`](mergeArray.md) — array-specific variant
- [`ListOfCallables::mergeSelf()`](mergeSelf.md) — collection-specific variant
- [`ListOfCallables::add()`](add.md) — append a single callable

## Issues

- [Open issues mentioning `ListOfCallables::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfCallables%3A%3Amerge()%22)
- [Closed issues mentioning `ListOfCallables::merge()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfCallables%3A%3Amerge()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfCallables%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
