# GetNormalisedForComparison

Produce a normalised version of a value, designed for use in
structural comparisons.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\DataInspectors;

class GetNormalisedForComparison
{
    /**
     * sentinel substituted for a typed property that was never
     * initialised.
     */
    public const string UNINITIALISED_PROPERTY = '__uninitialised__';

    /**
     * normalise a value so it can be used in structural comparison.
     */
    public static function from(
        mixed $input,
    ): mixed;

    /**
     * normalise a value while continuing an existing walk's
     * cycle-detection state.
     */
    public static function fromNested(
        mixed $input,
        NormalisationContext $context,
    ): mixed;
}
```

## Description

`GetNormalisedForComparison` is a helper tool for producing a
**normalised** version of a given value. These normalised values are
designed specifically for **structural comparison** — comparison by
content rather than by identity or layout — where:

- array key order does not matter;
- object property order does not matter.

It was originally added to support the AssertApi's "Canonicalization"
methods.

Normalisation walks `$input` recursively and rewrites it:

- **list-shaped arrays** (every key an int) drop their keys and
  re-index from zero, preserving value iteration order;
- **map-shaped arrays** (any string key present) preserve all keys
  and are sorted lexicographically by key;
- **objects implementing
  [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)**
  return their class name plus the canonical form they publish — used
  verbatim, with no further normalisation;
- **other objects** return their class name plus an array of all
  properties (public, protected and private, including those
  inherited from parent classes);
- **enums** return their class plus the case name — and, for backed
  enums, the backing value;
- **cyclic object graphs** emit a back-reference marker rather than
  recursing forever.

The list-vs-map decision is made by
[`GetArrayShape`](../../Arrays/GetArrayShape/README.md), so other code
can share the same intuition.

The class offers two entry points:

- [`from()`](from.md) is the casual top-level entry point. Each call
  starts a fresh cycle-detection walk.
- [`fromNested()`](fromNested.md) is the protocol-internal entry
  point. It requires a
  [`NormalisationContext`](../NormalisationContext/README.md) and is
  the method that implementors of
  [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
  must call to recurse safely.

The public constant `UNINITIALISED_PROPERTY` is the fixed sentinel
substituted for a typed property that was declared but never
initialised — see [Here Be Dragons](#here-be-dragons).

## Methods

**From `GetNormalisedForComparison`**

- [`::from()`](from.md) — normalise a value so it can be used in structural comparison.
- [`::fromNested()`](fromNested.md) — normalise a value while continuing an existing walk's cycle-detection state.

## Here Be Dragons

The class-level constant `UNINITIALISED_PROPERTY` carries its own
hazard:

**Collision with real string values is possible.** The sentinel is
the plain string `'__uninitialised__'`. If a real string property
happens to hold that exact value, the canonical form silently wins
both ways: an uninitialised property and a `'__uninitialised__'`-valued
property canonicalise identically and compare equal. Realistic
collision rate is near zero, but if your domain ever uses that literal
as a meaningful value, implement
[`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
on the enclosing value object so its canonical form bypasses the
reflection walk entirely.

The per-walk hazards — return type differing from input type,
closures collapsing together, `resource` values, and raw `DateTime`
internals — are documented on [`from()`](from.md#here-be-dragons) and
[`fromNested()`](fromNested.md#here-be-dragons).

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\GetNormalisedForComparison
 ✔ returns the int 42 unchanged
 ✔ returns the float 3.14 unchanged
 ✔ returns the string "hello" unchanged
 ✔ returns the empty string unchanged
 ✔ returns the boolean true unchanged
 ✔ returns the boolean false unchanged
 ✔ returns null unchanged
 ✔ represents an uninitialised typed property with the UNINITIALISED_PROPERTY sentinel
 ✔ does not throw when an uninitialised typed property is encountered
 ✔ represents a self-referencing object with the __cycle_ref marker
 ✔ represents a two-node cycle with sequential visit indexes
 ✔ detects a cycle through a NormalisesForComparison implementor that passes the context
 ✔ a shared (non-cyclic) child is still normalised in full at each occurrence
 ✔ represents a pure enum case as an enum/case pair
 ✔ represents a backed enum case as an enum/case/value triple
 ✔ distinct pure enum cases normalise to distinct values
 ✔ includes private properties declared on a parent class, qualified by their declaring class
 ✔ excludes static properties from the normalised output
 ✔ normalises an empty array to an empty list
 ✔ drops integer keys from an all-int-keyed array and re-indexes from zero
 ✔ preserves the value order of an int-keyed array (values are never sorted)
 ✔ two int-keyed arrays with the same value sequence normalise identically regardless of key gaps
 ✔ preserves all keys when at least one key is a string, even mixed int+string
 ✔ sorts map keys lexicographically (SORT_STRING) so numeric-looking strings stay in lex order
 ✔ recurses into a map nested inside a list
 ✔ recurses into a list nested inside a map
 ✔ recurses into an array held in an object property
 ✔ recurses through several alternating layers of object and array
 ✔ uses the canonical form verbatim when the object implements NormalisesForComparison
 ✔ a CollectionsKit DictOfIntegers preserves its int keys through the full pipeline
```

## Source

[`kits/missingbitskit/src/DataInspectors/GetNormalisedForComparison.php:63`](../../../../kits/missingbitskit/src/DataInspectors/GetNormalisedForComparison.php#L63)

## Changelog

_No tagged releases yet._

## See Also

- [`from()`](from.md) — the casual top-level entry point
- [`fromNested()`](fromNested.md) — the protocol-internal entry point that continues an existing walk
- [`NormalisationContext`](../NormalisationContext/README.md) — the per-walk cycle-detection state
- [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md) — the contract an object implements to publish its own canonical form
- [`GetArrayShape`](../../Arrays/GetArrayShape/README.md) — the list-vs-map discriminator this normaliser relies on

## Issues

- [Open issues mentioning `GetNormalisedForComparison`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22GetNormalisedForComparison%22)
- [Closed issues mentioning `GetNormalisedForComparison`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22GetNormalisedForComparison%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=GetNormalisedForComparison%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
