# NormalisationContext::markSeen()

> `public markSeen(object $obj): int`

Record an object as visited and return the visit index it carries.

**Since:** _Upcoming Release_

⚠️ Internal — not part of the public API.

## Defined On

[`StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\DataInspectors;

final class NormalisationContext
{
    /**
     * @param object $obj
     *      the object that we want to record as visited
     * @return int
     * - the freshly-assigned visit index, on first sight
     * - the existing first-visit index, on subsequent sights
     */
    public function markSeen(object $obj): int
}
```

## Description

`->markSeen()` records `$obj` in this context's visited-set and
returns the **visit index** it carries.

Visit indexes are assigned in order of first encounter — `0` to the
first object recorded, `1` to the next, and so on. Because the
indexes depend on traversal order rather than on runtime object
identity, two structurally-equivalent cyclic graphs normalise to the
same back-references run after run.

The method is **idempotent**: recording an object that has already
been seen returns its existing first-visit index and changes nothing
else. The visit counter is not advanced on a repeat call, so a later
`markSeen()` of a genuinely new object still lands at the correct
index. Duplicate calls are therefore safe — the "order of first
encounter" invariant survives any call pattern.

## Parameters

**`$obj`** (`object`)

The object to record as visited. It is tracked by **runtime
identity**, so two distinct objects with identical state are recorded
separately and a `clone` is recorded independently of its original.

## Return Values

Returns an `int` visit index:

- on **first sight** of `$obj`, the freshly-assigned index — the next
  value in encounter order;
- on **subsequent sights**, the existing first-visit index, unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext
 ✔ ->markSeen() takes exactly one parameter
 ✔ ->markSeen()'s parameter is named $obj
 ✔ ->markSeen()'s parameter is typed as object
 ✔ ->markSeen()'s parameter is required, not optional
 ✔ ->markSeen() returns int
 ✔ ->hasSeen() returns true for an object recorded via ->markSeen()
 ✔ ->markSeen() assigns visit index zero to the first object recorded
 ✔ ->markSeen() assigns sequential indexes in order of first encounter
 ✔ ->markSeen() is idempotent: a second call returns the first-visit index
 ✔ ->markSeen() does not increment the visit counter on a repeat call
 ✔ ->visitIndexOf() returns the index that ->markSeen() assigned
```

## Source

[`kits/missingbitskit/src/DataInspectors/NormalisationContext.php:157`](../../../../kits/missingbitskit/src/DataInspectors/NormalisationContext.php#L157)

## Changelog

_No tagged releases yet._

## See Also

- [`hasSeen()`](hasSeen.md) — query whether an object has already been recorded
- [`visitIndexOf()`](visitIndexOf.md) — retrieve the visit index assigned here
- [`NormalisationContext`](README.md) — the visited-set wrapper this method belongs to

## Issues

- [Open issues mentioning `NormalisationContext::markSeen()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisationContext%3A%3AmarkSeen%28%29%22)
- [Closed issues mentioning `NormalisationContext::markSeen()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisationContext%3A%3AmarkSeen%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisationContext%3A%3AmarkSeen%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
