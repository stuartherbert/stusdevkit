# ListOfUuids::toArrayOfStrings()

> `public function toArrayOfStrings(): array`

Return the contents of this collection as an array of strings.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Lists\ListOfUuids`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Lists;

class ListOfUuids extends CollectionAsList
{
    /**
     * @return list<string>
     */
    public function toArrayOfStrings(): array
}
```

## Description

Converts each stored [`UuidInterface`](https://uuid.ramsey.dev/en/stable/) into its canonical string form (via PHP's string-cast on the UUID object) and returns the result as a sequentially-indexed PHP array.

The type-conversion is done here so that callers do not have to repeat the same `(string)` cast throughout their own code — pull a list of UUID strings out in one call instead of mapping the list themselves every time.

The returned array is a fresh PHP array — mutating it has no effect on the list. The strings appear in insertion order; keys are sequential integers starting at `0` (the `@return list<string>` shape indicates a list-shaped array).

## Parameters

_None._

## Return Values

A PHP array of canonical UUID strings, in insertion order, indexed by sequential integers from `0`. Returns an empty array when the list contains no UUIDs.

The PHPStan `@return list<string>` shape guarantees the keys are sequential `0..N-1` integers — `array_values()` is applied internally to ensure list-shape regardless of what keys the underlying data carries.

## Errors/Exceptions

_None._

## Here Be Dragons

**Two UUIDs that share a string form become indistinguishable in the result.** `toArrayOfStrings()` collapses identity to the string value. If your input list contained two distinct `UuidInterface` instances with the same canonical string, the output array has two equal strings — but you cannot tell them apart by index alone (the position in the list still corresponds to the original entry).

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Lists\ListOfUuids
 ✔ ->toArrayOfStrings() returns empty array for empty list
 ✔ ->toArrayOfStrings() returns string representations of all UUIDs
 ✔ ->toArrayOfStrings() returns sequential integer keys
 ✔ ->toArrayOfStrings() returns valid UUID strings
 ✔ ->toArrayOfStrings() includes UUIDs added via add()
 ✔ Each UUID has a unique string representation
```

## Source

[`kits/collectionskit/src/Lists/ListOfUuids.php:78`](../../../../kits/collectionskit/src/Lists/ListOfUuids.php#L78)

## Changelog

_No tagged releases yet._

## See Also

- [`ListOfUuids::toArray()`](toArray.md) — same content, but as `UuidInterface` objects
- [`ListOfUuids::add()`](add.md) — append a single UUID
- [`ListOfUuids::getIterator()`](getIterator.md) — iterate over UUIDs as `UuidInterface` objects

## Issues

- [Open issues mentioning `ListOfUuids::toArrayOfStrings()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ListOfUuids%3A%3AtoArrayOfStrings()%22)
- [Closed issues mentioning `ListOfUuids::toArrayOfStrings()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ListOfUuids%3A%3AtoArrayOfStrings()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ListOfUuids%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
