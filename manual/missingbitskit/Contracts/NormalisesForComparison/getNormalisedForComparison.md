# NormalisesForComparison::getNormalisedForComparison()

> `public function getNormalisedForComparison(): mixed`

Return the canonical representation of this object's state for
use in structural comparison.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Contracts;

interface NormalisesForComparison
{
    /**
     * return the canonical representation of this object's state
     * for use in structural comparison.
     *
     * See the class-level docblock for the full contract.
     */
    public function getNormalisedForComparison(): mixed
}
```

## Description

`->getNormalisedForComparison()` is the sole method on
[`NormalisesForComparison`](README.md). It is called by canonicalisers
(typically [`NormaliseForComparison::from()`](../../NormaliseForComparison/from.md))
to obtain a representation of this object's state suitable for
**structural** comparison — i.e. comparison by content rather
than by object identity.

The implementor returns a fully-normalised value of its own
choosing — a scalar for a value object, a keyed array for a
dict, an ordered array for a list, a structured array for a
record type, etc. The caller takes the return value as **final**
and does not recurse into it; see the
[class-level docblock](README.md#description) for why this
direction was chosen.

For the implementor's contract — what counts as "fully
normalised", who is responsible for nested values, and how
output stability is defined — see
[Here Be Dragons on `NormalisesForComparison`](README.md#here-be-dragons).

## Parameters

_None._

## Return Values

The canonical representation of this object's state. The exact
shape is the implementor's choice; the only requirements are:

- Two instances with the same logical state must produce the
  same return value (same shape, same keys in the same order,
  same scalars at the same positions).
- The value must be self-contained — any nested objects, arrays
  or enums the implementor's state references must already be
  normalised in the return value, because the caller does not
  recurse.

See [Here Be Dragons on `NormalisesForComparison`](README.md#here-be-dragons)
for the full obligations.

## Errors/Exceptions

_None defined by the contract._ Individual implementors may
choose to throw if they cannot produce a canonical form (for
example, an implementor whose state is partially uninitialised),
but the contract itself imposes no exceptions.

## Here Be Dragons

See [Here Be Dragons on `NormalisesForComparison`](README.md#here-be-dragons) —
the **nested-value** and **stability** obligations apply to every
implementation of this method.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison
 ✔ ::getNormalisedForComparison() is public
 ✔ ::getNormalisedForComparison() takes zero parameters
 ✔ ::getNormalisedForComparison() returns mixed
```

## Source

[`kits/missingbitskit/src/Contracts/NormalisesForComparison.php:94`](../../../../kits/missingbitskit/src/Contracts/NormalisesForComparison.php#L94)

## Changelog

_No tagged releases yet._

## See Also

- [`NormaliseForComparison::from()`](../../NormaliseForComparison/from.md) —
  the recommended caller. Recognises any input that implements
  `NormalisesForComparison` and takes its return value verbatim
  without re-normalising.
- [`CollectionAsDict::getNormalisedForComparison()`](../../../collectionskit/Dictionaries/CollectionAsDict/getNormalisedForComparison.md) —
  a worked example for dicts: returns a `ksort`-ed keyed array
  with each value passed through `NormaliseForComparison::from()`.
- [`CollectionAsList::getNormalisedForComparison()`](../../../collectionskit/Lists/CollectionAsList/getNormalisedForComparison.md) —
  a worked example for lists: drops the int keys via
  `array_values()` and passes each value through
  `NormaliseForComparison::from()`.

## Issues

- [Open issues mentioning `NormalisesForComparison::getNormalisedForComparison()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisesForComparison%3A%3AgetNormalisedForComparison%28%29%22)
- [Closed issues mentioning `NormalisesForComparison::getNormalisedForComparison()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisesForComparison%3A%3AgetNormalisedForComparison%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisesForComparison%3A%3AgetNormalisedForComparison%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
