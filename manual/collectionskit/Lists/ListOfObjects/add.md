# ListOfObjects::add()

> `public function add(object $value): static`

Add a new object to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfObjects extends CollectionAsList
{
    /**
     * @param object $value
     * @throws NullValueNotAllowedException
     */
    public function add(object $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed `object` parameter type shown above is bound by
> `@extends CollectionAsList<object>` on [`ListOfObjects`](README.md).

## Description

Appends `$value` to the end of the list. The new object receives the next sequential integer key.

Duplicate objects are not prevented — `add()` does not check whether `$value` already exists in the list. The same object instance can appear in the list more than once. If you need uniqueness by identity, enforce it at the caller.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfObjects` type.

## Parameters

**`$value`** (`object`)

The object to append. Any PHP object is accepted — built-in classes ([`stdClass`](https://www.php.net/manual/en/class.stdclass.php), [`ArrayIterator`](https://www.php.net/manual/en/class.arrayiterator.php), `DateTime`, …), user-defined classes, anonymous classes, and invokable objects. `null` is not permitted.

The PHP signature accepts `mixed`, but the class's `@extends CollectionAsList<object>` binding narrows this to `object`.

## Return Values

Returns `$this` — the same `ListOfObjects` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

**Stored objects are references, not copies.** `add($obj)` stores the same handle the caller holds; mutating `$obj` afterwards changes what the list reports back. If you need an isolated snapshot, `clone $obj` before adding it.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfObjects
 ✔ ->add() appends an object to the list
 ✔ ->add() appends multiple objects in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add the same object instance twice
 ✔ ->add() accepts a stdClass
 ✔ ->add() accepts a DateTime
 ✔ ->add() accepts an anonymous class instance
 ✔ ->add() accepts an ArrayObject
 ✔ ->add() accepts various object types with data set "stdClass"
 ✔ ->add() accepts various object types with data set "DateTime"
 ✔ ->add() accepts various object types with data set "ArrayObject"
 ✔ ->add() accepts various object types with data set "anonymous class"
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfObjects`](README.md) — where the `<object>` template parameter is bound
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfObjects::merge()`](merge.md) — append many objects at once
- [`ListOfObjects::mergeArray()`](mergeArray.md) — append an array of objects

## Issues

- [Open issues mentioning `ListOfObjects::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfObjects%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfObjects::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfObjects%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
