# ArrayShape::isList()

> `public function isList(): bool`

Convenience predicate for callers that only care about list-ness.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Arrays\ArrayShape`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

enum ArrayShape
{
    /**
     * @return bool
     * - `true` if this ArrayShape is a LIST
     * - `false` otherwise
     */
    public function isList(): bool
}
```

## Description

`->isList()` is the dedicated shorthand for callers that only
need a yes/no answer to "is this array being used as a list?".

It is equivalent to comparing the case directly:

```php
$shape === ArrayShape::LIST
```

— but the named method reads more naturally at call sites that
do not otherwise need to import or reference the `LIST` case.

The companion predicate is [`->isMap()`](isMap.md). For a full
pattern-match against every case (recommended when the code's
behaviour differs per case rather than splitting on a single
boolean), `match ($shape)` over the bare cases is the better
fit — see [`ArrayShape`](README.md) for the case set.

## Parameters

_None._

## Return Values

Returns `true` when this case is `ArrayShape::LIST`, and `false`
otherwise (i.e. when this case is `ArrayShape::MAP`).

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\ArrayShape
 ✔ ->isList() returns true for the LIST case
 ✔ ->isList() returns false for the MAP case
```

## Source

[`kits/missingbitskit/src/Arrays/ArrayShape.php:95`](../../../../kits/missingbitskit/src/Arrays/ArrayShape.php#L95)

## Changelog

_No tagged releases yet._

## See Also

- [`ArrayShape::isMap()`](isMap.md) — companion predicate for
  callers that only care about map-ness.
- [`GetArrayShape::from()`](../GetArrayShape/from.md) — the
  inspector that produces the `ArrayShape` instance this method
  is called on.

## Issues

- [Open issues mentioning `ArrayShape::isList()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22ArrayShape%3A%3AisList%28%29%22)
- [Closed issues mentioning `ArrayShape::isList()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22ArrayShape%3A%3AisList%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=ArrayShape%3A%3AisList%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
