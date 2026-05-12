# DictOfUuids::mergeArray()

> `public function mergeArray(array $input): static`

Add the given `$input` to this dict.

**Since:** _Upcoming Release_

## Defined On

Defined on [`StusDevKit\CollectionsKit\AccessibleCollection`](../../AccessibleCollection/README.md), using PHPStan template types set in [`StusDevKit\CollectionsKit\Dictionaries\DictOfUuids`](README.md).

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Dictionaries;

use Ramsey\Uuid\UuidInterface;
use StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException;

class DictOfUuids extends DictOfObjects
{
    /**
     * @template TIn of UuidInterface
     * @param array<string, TIn> $input
     * @return $this
     *
     * @throws NullValueNotAllowedException
     */
    public function mergeArray(array $input): static
}
```

> [!NOTE]
> This is an inherited method whose types are narrowed by PHPStan generics.
> The runtime PHP signature is `public function mergeArray(array $input): static`,
> inherited from [`AccessibleCollection::mergeArray()`](../../AccessibleCollection/mergeArray.md).
> The narrowed `array<string, UuidInterface>` parameter type shown above is bound
> by `@extends DictOfObjects<string, UuidInterface>` on [`DictOfUuids`](README.md).

## Description

Adds the contents of a plain PHP array to this dict in place.

The merge follows PHP's spread-operator semantics: entries with new string keys are added, while entries whose keys already exist in the dict overwrite the previously-stored UUID. The input is validated by [`RejectNullArrayValues`](../../Validators/RejectNullArrayValues/README.md) before any data is moved across — `null` values are rejected because no collection may store `null`.

Use this method when you already know `$input` is an array. Otherwise, call [`DictOfUuids::merge()`](merge.md), which dispatches to the right specialised method.

## Parameters

**`$input`** (`array<string, UuidInterface>`)

The array to merge. Keys must be strings; values must implement [`UuidInterface`](https://uuid.ramsey.dev/en/stable/). The PHP parameter type is `array`; the class's template binding narrows it to `array<string, UuidInterface>`.

## Return Values

Returns `$this` — the same dict instance, with `$input`'s contents merged in. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

- **[`NullValueNotAllowedException`](../../../exceptionskit/Exceptions/NullValueNotAllowedException/README.md)** — when any element in `$input` is `null`. The exception message names `DictOfUuids` as the offending collection type.

## Here Be Dragons

**UUIDs are transferred by reference**, inherited from [`DictOfObjects`](../DictOfObjects/README.md). After the merge, both `$input` (if a caller still holds it) and this dict hold pointers to the same `UuidInterface` instances. In practice this is rarely a concern because `UuidInterface` implementations are conventionally immutable.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Dictionaries\DictOfUuids
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

- [`DictOfUuids`](README.md) — where the `<string, UuidInterface>` template parameters are bound
- [`DictOfUuids::merge()`](merge.md) — adds the given input (array or compatible dict) to this dict
- [`DictOfUuids::mergeSelf()`](mergeSelf.md) — copies the contents of a compatible dict into this dict
- [`AccessibleCollection::mergeArray()`](../../AccessibleCollection/mergeArray.md) — the generic implementation this page specialises

## Issues

- [Open issues mentioning `DictOfUuids::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22DictOfUuids::mergeArray()%22)
- [Closed issues mentioning `DictOfUuids::mergeArray()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22DictOfUuids::mergeArray()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=DictOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
