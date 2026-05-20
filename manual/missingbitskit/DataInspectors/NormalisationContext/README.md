# NormalisationContext

Shared state passed through a single top-level normalisation walk by [`GetNormalisedForComparison`](../GetNormalisedForComparison/README.md).

**Since:** _Upcoming Release_

⚠️ Internal — not part of the public API.

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\DataInspectors;

final class NormalisationContext
{
    /**
     * create a normalisation context with an empty visited-set.
     */
    public function __construct();

    /**
     * has the given object already been visited during this walk?
     */
    public function hasSeen(object $obj): bool;

    /**
     * return the visit index assigned to the given object.
     */
    public function visitIndexOf(object $obj): int;

    /**
     * record an object as visited and return the visit index it
     * carries.
     */
    public function markSeen(object $obj): int;
}
```

## Description

`NormalisationContext` is the shared state passed through a single
top-level normalisation walk performed by
[`GetNormalisedForComparison`](../GetNormalisedForComparison/README.md).
It records which objects the walk has already visited, and the
zero-based **visit index** assigned to each, so that a cyclic object
graph can emit a back-reference instead of recursing forever.

It was originally added so that implementors of
[`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md)
can recurse back into the normaliser via
[`GetNormalisedForComparison::fromNested()`](../GetNormalisedForComparison/fromNested.md)
while preserving the parent walk's cycle-detection state. Without a
shared context, an implementor whose canonical form references itself
— directly or transitively — infinite-loops, because every recursive
call to the top-level
[`GetNormalisedForComparison::from()`](../GetNormalisedForComparison/from.md)
starts a fresh visited-set.

The class is owned by
[`GetNormalisedForComparison`](../GetNormalisedForComparison/README.md).
Implementors only ever forward the context they were handed — they
should not construct their own, query its internals, or store it past
the duration of a single
[`getNormalisedForComparison()`](../../Contracts/NormalisesForComparison/getNormalisedForComparison.md)
call. It exists as a dedicated class, rather than a bare
[`SplObjectStorage`](https://www.php.net/manual/en/class.splobjectstorage.php),
so that the implementation can remain independent of the PHP runtime
version and so that misuse surfaces as a typed
[`UnseenObjectException`](../UnseenObjectException/README.md) rather
than the storage's own
[`UnexpectedValueException`](https://www.php.net/manual/en/class.unexpectedvalueexception.php).

## Methods

**From `NormalisationContext`**

- [`->__construct()`](__construct.md) — create a normalisation context with an empty visited-set.
- [`->hasSeen()`](hasSeen.md) — has the given object already been visited during this walk?
- [`->visitIndexOf()`](visitIndexOf.md) — return the visit index assigned to the given object.
- [`->markSeen()`](markSeen.md) — record an object as visited and return the visit index it carries.

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext
 ✔ lives in the StusDevKit\MissingBitsKit\DataInspectors namespace
 ✔ is declared as a class
 ✔ is declared as final
 ✔ extends no other class
 ✔ implements no interfaces
 ✔ uses no traits
 ✔ publishes exactly __construct/hasSeen/markSeen/visitIndexOf as its public method set
```

## Source

[`kits/missingbitskit/src/DataInspectors/NormalisationContext.php:69`](../../../../kits/missingbitskit/src/DataInspectors/NormalisationContext.php#L69)

## Changelog

_No tagged releases yet._

## See Also

- [`GetNormalisedForComparison`](../GetNormalisedForComparison/README.md) — the normaliser that owns and creates this context
- [`GetNormalisedForComparison::fromNested()`](../GetNormalisedForComparison/fromNested.md) — the entry point implementors pass the context into
- [`NormalisesForComparison`](../../Contracts/NormalisesForComparison/README.md) — the contract whose implementors receive a context
- [`UnseenObjectException`](../UnseenObjectException/README.md) — thrown by [`visitIndexOf()`](visitIndexOf.md) when its `hasSeen()` precondition is skipped

## Issues

- [Open issues mentioning `NormalisationContext`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisationContext%22)
- [Closed issues mentioning `NormalisationContext`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisationContext%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisationContext%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
