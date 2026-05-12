# DictOfObjects::mergeArray()

> `public function mergeArray(array $input): static`

Add the given `$input` to this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfObjects`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfObjects extends CollectionAsDict
{
    /**
     * @template TIn of object
     * @param array<array-key, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException
     */
    public function mergeArray(array $input): static
}
```

> [!NOTE]
> This page shows the PHPStan-narrowed view at the [`DictOfObjects`](README.md)
> level. The runtime PHP signature is `public function mergeArray(array $input): static`,
> inherited from [`AccessibleCollection::mergeArray()`](../../AccessibleCollection/mergeArray.md).
> The value type `object` comes from `@template TValue of object` declared
> on [`DictOfObjects`](README.md). Subclasses such as
> [`DictOfUuids`](../DictOfUuids/README.md) pin `TValue` to a concrete
> object type.

## Description

Adds the contents of a plain PHP array to this dict in place.

The merge follows PHP's spread-operator semantics: entries with new keys are added, while entries whose keys already exist in the dict overwrite the previously-stored object. The input is validated by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md) before any data is moved across — `null` values are rejected because no collection may store `null`.

Use this method when you already know `$input` is an array. Otherwise, call [`DictOfObjects::merge()`](merge.md), which dispatches to the right specialised method.

## Parameters

**`$input`** (`array<array-key, object>`)

The array to merge. Keys are integers or strings; values must be objects. The PHP parameter type is `array`; the class's template binding narrows it to `array<array-key, object>`.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents merged in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$input` is `null`. The exception message names the runtime collection type as the offending collection.

## Here Be Dragons

**Objects are transferred by reference**, inherited from `DictOfObjects`. After the merge, both `$input` (if a caller still holds it) and this dict hold pointers to the same object instances. If you need value semantics, clone before storing.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfObjects
 ✔ ->mergeArray() adds array items to the dict
 ✔ ->mergeArray() into empty dict sets the data
 ✔ ->mergeArray() with empty array leaves dict unchanged
 ✔ ->mergeArray() overwrites matching string keys
 ✔ ->mergeArray() returns $this for method chaining
```

## Source

[`kits/collectionskit/src/AccessibleCollection.php:128`](../../../../kits/collectionskit/src/AccessibleCollection.php#L128)

## Changelog

_No tagged releases yet._

## See Also

- [`DictOfObjects`](README.md) — where `TValue` is re-bounded to `object`
- [`DictOfObjects::merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`DictOfObjects::mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict
- [`DictOfUuids`](../DictOfUuids/README.md) — narrower specialisation that pins `TValue` to `UuidInterface`
- [`AccessibleCollection::mergeArray()`](../../AccessibleCollection/mergeArray.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfObjects::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfObjects::mergeArray()%22)
- [Closed issues mentioning `DictOfObjects::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfObjects::mergeArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfObjects%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
