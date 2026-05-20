# NormalisationContext::__construct()

> `public __construct()`

Create a normalisation context with an empty visited-set.

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
    public function __construct()
}
```

## Description

Constructs a new `NormalisationContext`. The new context starts with
an **empty visited-set**: it has seen no objects, so
[`hasSeen()`](hasSeen.md) returns `false` for every object, and the
first object handed to [`markSeen()`](markSeen.md) is assigned visit
index `0`.

The constructor takes no parameters — a context cannot be pre-seeded
with a visited-set. Each top-level
[`GetNormalisedForComparison::from()`](../GetNormalisedForComparison/from.md)
call builds its own fresh context, which is what scopes cycle
detection to a single walk.

## Parameters

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\NormalisationContext
 ✔ ::__construct() takes zero parameters
 ✔ a fresh context reports no object as seen
```

## Source

[`kits/missingbitskit/src/DataInspectors/NormalisationContext.php:80`](../../../../kits/missingbitskit/src/DataInspectors/NormalisationContext.php#L80)

## Changelog

_No tagged releases yet._

## See Also

- [`NormalisationContext`](README.md) — the visited-set wrapper this constructs
- [`hasSeen()`](hasSeen.md) — query whether an object has been visited
- [`markSeen()`](markSeen.md) — record an object as visited
- [`visitIndexOf()`](visitIndexOf.md) — retrieve a recorded object's visit index

## Issues

- [Open issues mentioning `NormalisationContext::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NormalisationContext%3A%3A__construct%28%29%22)
- [Closed issues mentioning `NormalisationContext::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NormalisationContext%3A%3A__construct%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NormalisationContext%3A%3A__construct%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
