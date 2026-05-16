# NormalisesForComparison

Contract for objects that produce their own canonical form for
structural comparison.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Contracts;

interface NormalisesForComparison
{
    /**
     * return the canonical representation of this object's state
     * for use in structural comparison.
     */
    public function getNormalisedForComparison(): mixed;
}
```

## Description

`NormalisesForComparison` is the contract by which an object
declares its **own** canonical form for structural comparison.
Implementors return a fully-normalised representation of their
state. The caller treats the return value as **final** and does
NOT recurse into it.

A generic recursive normaliser cannot tell a key/value map from
a sparse list when both are stored as a PHP array — but the
implementor knows. So the implementor produces the final form.

Originally added so the
[CollectionsKit](../../../collectionskit/) `Dict*` family could
preserve their key-as-identity semantics through canonicalising
assertions. A reflection-based walk of a
[`DictOfIntegers([42 => 1, 7 => 2])`](../../../collectionskit/Dictionaries/DictOfIntegers/README.md)
sees only the backing array. From the array alone it cannot tell
whether the int keys are positions (drop them) or identities
(preserve them) — so it guesses, and a dict with identity keys
gets canonicalised the same way as a list with positional keys.
Implementing this interface lets the dict declare its own
canonical form, so the comparison stays correct.

The recommended in-tree caller is
[`NormaliseForComparison::from()`](../../NormaliseForComparison/from.md) —
it special-cases any input that `implements NormalisesForComparison`
and takes the implementor's return value verbatim without
re-normalising.

## Methods

**From `NormalisesForComparison`**

- [`->getNormalisedForComparison()`](getNormalisedForComparison.md) — return the canonical representation of this object's state for use in structural comparison.

## Here Be Dragons

- **Implementors handle nested values themselves.** If the
  implementor's state contains values that need normalising
  (other objects, nested arrays, enums), the implementor must
  normalise each one — typically by calling
  [`NormaliseForComparison::from()`](../../NormaliseForComparison/from.md)
  on it — and splice the results into its return value. The
  caller will not do this work.

- **Stability is the implementor's responsibility.** Two
  instances with the same logical state must produce the same
  return value — same shape, same keys in the same order, same
  scalars at the same positions. If your state includes
  ordering-irrelevant collections (sets, etc.), sort them before
  returning so the output order is deterministic.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Contracts\NormalisesForComparison
 ✔ is declared as an interface
 ✔ lives in the StusDevKit\MissingBitsKit\Contracts namespace
 ✔ extends no other interface
 ✔ publishes exactly the getNormalisedForComparison method
```

## Source

[`kits/missingbitskit/src/Contracts/NormalisesForComparison.php:84`](../../../../kits/missingbitskit/src/Contracts/NormalisesForComparison.php#L84)

## Changelog

_No tagged releases yet._

## See Also

- [`NormaliseForComparison`](../../NormaliseForComparison/README.md) —
  the recommended caller. Knows how to walk arbitrary PHP values
  (scalars, arrays, enums, ordinary objects) AND defers to this
  contract whenever it meets an object that implements it.
- [`CollectionAsDict`](../../../collectionskit/Dictionaries/CollectionAsDict/README.md) —
  the dict base class that implements this interface to preserve
  its key-as-identity semantics.
- [`CollectionAsList`](../../../collectionskit/Lists/CollectionAsList/README.md) —
  the list base class that implements this interface to drop its
  position-only int keys.

## Issues

- [Open issues mentioning `NormalisesForComparison`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisesForComparison%22)
- [Closed issues mentioning `NormalisesForComparison`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisesForComparison%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisesForComparison%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
