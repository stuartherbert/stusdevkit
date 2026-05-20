# NormalisationContext::visitIndexOf()

> `public visitIndexOf(object $obj): int`

Return the visit index assigned to the given object.

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
     *      the object that we want to get the index of
     * @return int
     *      the zero-based visit index assigned at first sight
     * @throws UnseenObjectException
     */
    public function visitIndexOf(object $obj): int
}
```

## Description

`->visitIndexOf()` returns the **visit index** that this context
assigned to `$obj` when it was first recorded via
[`markSeen()`](markSeen.md).

Visit indexes are assigned in order of first encounter (`0`, `1`,
`2`, ...) and never change once assigned, so the returned value is
stable for the lifetime of the context.

The method is designed to be paired with [`hasSeen()`](hasSeen.md):
call `hasSeen()` first, and only call `visitIndexOf()` when it
returned `true`. Calling `visitIndexOf()` on an object that was never
recorded is a programmer error — a broken calling protocol — and
throws [`UnseenObjectException`](../UnseenObjectException/README.md).
This is the wrapper earning its keep: the underlying storage would
raise its own
[`UnexpectedValueException`](https://www.php.net/manual/en/class.unexpectedvalueexception.php)
on a missing key, and `NormalisationContext` exists so that no caller
has to know that.

## Parameters

**`$obj`** (`object`)

The object whose visit index is wanted. Lookup is by **runtime
identity**: two distinct objects with identical state get distinct
visit indexes, and a `clone` of a recorded object is a different
object that was never recorded.

## Return Values

Returns the zero-based `int` visit index that
[`markSeen()`](markSeen.md) assigned to `$obj` at first sight. The
value is non-nullable — a missing object surfaces as an
[`UnseenObjectException`](../UnseenObjectException/README.md), never
as `null`.

## Errors/Exceptions

- **[`UnseenObjectException`](../UnseenObjectException/README.md)** — when `$obj` has not been recorded via [`markSeen()`](markSeen.md) in this context.

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext
 ✔ ->visitIndexOf() takes exactly one parameter
 ✔ ->visitIndexOf()'s parameter is named $obj
 ✔ ->visitIndexOf()'s parameter is typed as object
 ✔ ->visitIndexOf()'s parameter is required, not optional
 ✔ ->visitIndexOf() returns int
 ✔ ->visitIndexOf() returns the index that ->markSeen() assigned
 ✔ ->visitIndexOf() returns the correct index for an object stored among several
 ✔ ->visitIndexOf() distinguishes objects by runtime identity, not by state
 ✔ ->visitIndexOf() throws UnseenObjectException for an unrecorded object
 ✔ ->visitIndexOf()'s exception names the offending object
 ✔ ->hasSeen() and ->visitIndexOf() agree that a recorded object is present
```

## Source

[`kits/missingbitskit/src/DataInspectors/NormalisationContext.php:120`](../../../../kits/missingbitskit/src/DataInspectors/NormalisationContext.php#L120)

## Changelog

_No tagged releases yet._

## See Also

- [`hasSeen()`](hasSeen.md) — the precondition guard; call it before `visitIndexOf()`
- [`markSeen()`](markSeen.md) — assigns the visit index this method returns
- [`UnseenObjectException`](../UnseenObjectException/README.md) — thrown when the `hasSeen()` precondition is skipped
- [`NormalisationContext`](README.md) — the visited-set wrapper this method belongs to

## Issues

- [Open issues mentioning `NormalisationContext::visitIndexOf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisationContext%3A%3AvisitIndexOf%28%29%22)
- [Closed issues mentioning `NormalisationContext::visitIndexOf()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisationContext%3A%3AvisitIndexOf%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisationContext%3A%3AvisitIndexOf%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
