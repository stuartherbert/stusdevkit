# NormalisesForComparison::getNormalisedForComparison()

> `public getNormalisedForComparison(NormalisationContext $context): mixed`

Return the canonical representation of this object's state for use in
structural comparison.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Contracts;

use StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext;

interface NormalisesForComparison
{
    /**
     * @param NormalisationContext $context
     *      the parent walk's shared state; pass through to nested
     *      `fromNested()` calls, do not construct your own.
     * @return mixed the object's state, suitable for comparison
     */
    public function getNormalisedForComparison(
        NormalisationContext $context,
    ): mixed
}
```

## Description

`->getNormalisedForComparison()` is the sole method on
[`NormalisesForComparison`](README.md). It is called by canonicalisers
— in-tree, by
[`GetNormalisedForComparison`](../../DataInspectors/GetNormalisedForComparison/README.md) —
to obtain a representation of this object's state suitable for
**structural** comparison: comparison by content rather than by
object identity.

The implementor returns a fully-normalised value of its own choosing
— a scalar for a value object, a keyed array for a dict, an ordered
array for a list, a structured array for a record type, etc. The
caller takes the return value as **final** and does not recurse into
it; see the [class-level docblock](README.md#description) for why
this direction was chosen.

The `$context` parameter carries the parent walk's cycle-detection
state. It MUST be passed through to any recursive normalisation of
nested values, via
[`GetNormalisedForComparison::fromNested()`](../../DataInspectors/GetNormalisedForComparison/fromNested.md).
The parameter is required and non-nullable precisely so that
forgetting to pass it is a compile-time error rather than a runtime
infinite loop.

For the implementor's contract — what counts as "fully normalised",
who is responsible for nested values, and how output stability is
defined — see
[Here Be Dragons on `NormalisesForComparison`](README.md#here-be-dragons).

## Parameters

**`$context`** (`NormalisationContext`)

The parent walk's shared state. Pass it through to every nested
[`GetNormalisedForComparison::fromNested()`](../../DataInspectors/GetNormalisedForComparison/fromNested.md)
call your implementation makes — do not construct your own. Passing
the same context is what lets cycles in your object's graph be
detected across the interface boundary.

## Return Values

The canonical representation of this object's state. The exact shape
is the implementor's choice; the only requirements are:

- Two instances with the same logical state must produce the same
  return value (same shape, same keys in the same order, same scalars
  at the same positions).
- The value must be self-contained — any nested objects, arrays or
  enums the implementor's state references must already be normalised
  in the return value (via
  [`fromNested()`](../../DataInspectors/GetNormalisedForComparison/fromNested.md)),
  because the caller does not recurse.

See [Here Be Dragons on `NormalisesForComparison`](README.md#here-be-dragons)
for the full obligations.

## Errors/Exceptions

_None._

## Here Be Dragons

See [Here Be Dragons on `NormalisesForComparison`](README.md#here-be-dragons) —
the **nested-value**, **context-passing**, and **stability**
obligations apply to every implementation of this method.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison
 ✔ ::getNormalisedForComparison() is public
 ✔ ::getNormalisedForComparison() takes exactly one parameter
 ✔ ::getNormalisedForComparison() names its parameter $context
 ✔ ::getNormalisedForComparison()'s parameter is typed as NormalisationContext
 ✔ ::getNormalisedForComparison()'s parameter is required, not optional
 ✔ ::getNormalisedForComparison() returns mixed
```

## Source

[`kits/missingbitskit/src/Contracts/NormalisesForComparison.php:113`](../../../../kits/missingbitskit/src/Contracts/NormalisesForComparison.php#L113)

## Changelog

_No tagged releases yet._

## See Also

- [`GetNormalisedForComparison::from()`](../../DataInspectors/GetNormalisedForComparison/from.md) — the casual top-level caller that recognises implementors of this contract
- [`GetNormalisedForComparison::fromNested()`](../../DataInspectors/GetNormalisedForComparison/fromNested.md) — the method an implementation calls to normalise its nested values
- [`NormalisationContext`](../../DataInspectors/NormalisationContext/README.md) — the cycle-detection state passed in as `$context`
- [`CollectionAsDict::getNormalisedForComparison()`](../../../collectionskit/Dictionaries/CollectionAsDict/getNormalisedForComparison.md) — a worked example for dicts: returns a `ksort`-ed keyed array
- [`CollectionAsList::getNormalisedForComparison()`](../../../collectionskit/Lists/CollectionAsList/getNormalisedForComparison.md) — a worked example for lists: drops the int keys via `array_values()`

## Issues

- [Open issues mentioning `NormalisesForComparison::getNormalisedForComparison()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisesForComparison%3A%3AgetNormalisedForComparison%28%29%22)
- [Closed issues mentioning `NormalisesForComparison::getNormalisedForComparison()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisesForComparison%3A%3AgetNormalisedForComparison%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisesForComparison%3A%3AgetNormalisedForComparison%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
