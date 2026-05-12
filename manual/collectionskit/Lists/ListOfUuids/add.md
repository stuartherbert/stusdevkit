# ListOfUuids::add()

> `public function add(UuidInterface $value): static`

Add a new UUID to the end of the list.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\Lists\CollectionAsList`](../CollectionAsList/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Lists\ListOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

use Ramsey\Uuid\UuidInterface;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class ListOfUuids extends CollectionAsList
{
    /**
     * @param UuidInterface $value
     * @throws NullValueNotAllowedException
     */
    public function add(UuidInterface $value): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function add(mixed $value): static`,
> inherited from [`CollectionAsList::add()`](../CollectionAsList/add.md). The
> narrowed `UuidInterface` parameter type shown above is bound by
> `@extends CollectionAsList<UuidInterface>` on [`ListOfUuids`](README.md).

## Description

Appends `$value` to the end of the list. The new UUID receives the next sequential integer key.

Duplicate UUIDs are not prevented — `add()` does not check whether `$value` already exists in the list. The same UUID instance (or a different instance with the same string form) can appear in the list more than once. If you need uniqueness, enforce it at the caller.

You cannot store `null` in the list — see the kit-level [no-null rule](../../README.md). Passing `null` raises a [`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md) at the call site.

The method returns `$this` so calls can be chained fluently. The return type is `static`, so chaining preserves the `ListOfUuids` type.

## Parameters

**`$value`** (`UuidInterface`)

The UUID to append. Any [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) implementation is accepted. `null` is not permitted.

The PHP signature accepts `mixed`, but the class's `@extends CollectionAsList<UuidInterface>` binding narrows this to `UuidInterface`.

## Return Values

Returns `$this` — the same `ListOfUuids` instance, with `$value` appended at the next sequential integer key.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when `$value` is `null`.

## Here Be Dragons

**Two different `UuidInterface` instances with the same string form are still treated as distinct entries.** `add()` does not consult the UUID's string value when checking for duplicates — it does not check for duplicates at all. If your design depends on string-equality dedup, do the check yourself before calling `add()`.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
 ✔ ->add() appends a UUID to the list
 ✔ ->add() appends multiple UUIDs in order
 ✔ ->add() appends to existing data
 ✔ ->add() returns $this for method chaining
 ✔ ->add() supports fluent chaining
 ✔ ->add() maintains sequential integer keys
 ✔ ->add() can add the same UUID instance twice
 ✔ ->add() and merge methods support fluent chaining together
```

## Source

[`kits/collectionskit/src/Lists/CollectionAsList.php:101`](../../../../kits/collectionskit/src/Lists/CollectionAsList.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfUuids`](README.md) — where the `<UuidInterface>` template parameter is bound
- [`CollectionAsList::add()`](../CollectionAsList/add.md) — the generic implementation this page specialises
- [`ListOfUuids::merge()`](merge.md) — append many UUIDs at once
- [`ListOfUuids::mergeArray()`](mergeArray.md) — append an array of UUIDs

## Issues

- [Open issues mentioning `ListOfUuids::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%3A%3Aadd()%22)
- [Closed issues mentioning `ListOfUuids::add()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%3A%3Aadd()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
