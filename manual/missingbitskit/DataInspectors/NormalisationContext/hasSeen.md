# NormalisationContext::hasSeen()

> `public hasSeen(object $obj): bool`

Has the given object already been visited during this walk?

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
     *      the object that we want to check for
     * @return bool
     * - `true` if we have seen this object before
     * - `false` otherwise
     */
    public function hasSeen(object $obj): bool
}
```

## Description

`->hasSeen()` reports whether `$obj` has already been recorded in
this context via [`markSeen()`](markSeen.md) during the current walk.

It is the precondition guard for [`visitIndexOf()`](visitIndexOf.md):
the two are designed to be paired. Calling `visitIndexOf()` on an
object that `hasSeen()` would report `false` for is a programmer
error and throws
[`UnseenObjectException`](../UnseenObjectException/README.md). When
`hasSeen()` returns `true`, `visitIndexOf()` recovers the index of
the earlier visit.

## Parameters

**`$obj`** (`object`)

The object to look up in the visited-set. Lookup is by **runtime
identity**, not by state — a `clone` of a recorded object is a
distinct object and reads as unseen.

## Return Values

Returns `true` when `$obj` has already been recorded via
[`markSeen()`](markSeen.md) in this context, and `false` otherwise.
A freshly constructed context has recorded nothing, so `hasSeen()`
returns `false` for every object until the first
[`markSeen()`](markSeen.md) call.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext
 ✔ ->hasSeen() takes exactly one parameter
 ✔ ->hasSeen()'s parameter is named $obj
 ✔ ->hasSeen()'s parameter is typed as object
 ✔ ->hasSeen()'s parameter is required, not optional
 ✔ ->hasSeen() returns bool
 ✔ ->hasSeen() returns true for an object recorded via ->markSeen()
 ✔ ->hasSeen() reports false for a clone of a recorded object
 ✔ ->hasSeen() and ->visitIndexOf() agree that a recorded object is present
```

## Source

[`kits/missingbitskit/src/DataInspectors/NormalisationContext.php:97`](../../../../kits/missingbitskit/src/DataInspectors/NormalisationContext.php#L97)

## Changelog

_No tagged releases yet._

## See Also

- [`markSeen()`](markSeen.md) — record an object as visited so a later `hasSeen()` returns `true`
- [`visitIndexOf()`](visitIndexOf.md) — recover the visit index once `hasSeen()` returns `true`
- [`NormalisationContext`](README.md) — the visited-set wrapper this method belongs to

## Issues

- [Open issues mentioning `NormalisationContext::hasSeen()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisationContext%3A%3AhasSeen%28%29%22)
- [Closed issues mentioning `NormalisationContext::hasSeen()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisationContext%3A%3AhasSeen%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisationContext%3A%3AhasSeen%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
