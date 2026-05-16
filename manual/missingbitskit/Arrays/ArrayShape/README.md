# ArrayShape

Value object describing how a PHP array is being used: as an
ordered list of values, or as a key/value map.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:**

- [`UnitEnum`](https://www.php.net/manual/en/class.unitenum.php) —
  implicit parent of every pure (unbacked) PHP enum.

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

enum ArrayShape
{
    case LIST;
    case MAP;

    /**
     * convenience predicate for callers that only care about
     * list-ness.
     */
    public function isList(): bool;

    /**
     * convenience predicate for callers that only care about
     * map-ness.
     */
    public function isMap(): bool;
}
```

## Description

`ArrayShape` discriminates between the two ways a PHP `array` is
typically used:

- as an **ordered list of values**, where the integer keys are
  positions and carry no identity;
- as a **key/value map**, where the keys themselves are the
  identity of each entry.

PHP's single `array` type serves both roles, but algorithms that
compare, hash, or serialise arrays usually want different
behaviour for each. `ArrayShape` gives that discrimination a name
the caller can pattern-match on.

`ArrayShape` is a pure (unbacked) enum on purpose: callers only
ever pattern-match on the cases, so a backing value would be
dead weight. A later release can promote to a backed enum without
breaking any existing caller if a real diagnostic / serialisation
need surfaces.

`ArrayShape` does not create itself — instances are produced by
[`GetArrayShape::from()`](../GetArrayShape/from.md), the inspector
that classifies a PHP array into a shape.

## Cases

**`LIST`**

The array is being used as an ordered list of values. Every key
is an `int`. The key values themselves carry no information the
value sequence does not already — they are positions, not
identities.

An empty array is reported as `LIST`. The PHP standard library
agrees — [`array_is_list([])`](https://www.php.net/manual/en/function.array-is-list.php)
returns `true` — and treating empty as a list means callers do
not need a third "neither" case.

**`MAP`**

The array is being used as a key/value map. At least one key is
a `string`. The keys ARE the identity of each entry, so
algorithms that canonicalise or compare must preserve them.

## Methods

**From `ArrayShape`**

- [`->isList()`](isList.md) — convenience predicate for callers that only care about list-ness.
- [`->isMap()`](isMap.md) — convenience predicate for callers that only care about map-ness.

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\ArrayShape
 ✔ is declared as an enum
 ✔ lives in the StusDevKit\MissingBitsKit\Arrays namespace
 ✔ publishes exactly the LIST and MAP cases
```

## Source

[`kits/missingbitskit/src/Arrays/ArrayShape.php:65`](../../../../kits/missingbitskit/src/Arrays/ArrayShape.php#L65)

## Changelog

_No tagged releases yet._

## See Also

- [`GetArrayShape::from()`](../GetArrayShape/from.md) — the
  inspector that classifies a PHP array and returns the matching
  `ArrayShape` case. `ArrayShape` does not create itself; every
  instance you hold came from here.

## Issues

- [Open issues mentioning `ArrayShape`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ArrayShape%22)
- [Closed issues mentioning `ArrayShape`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ArrayShape%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ArrayShape%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
