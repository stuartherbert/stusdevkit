# StrTrimLocation

An option passed into [`Str::trim()`](../Str/trim.md) that controls where the trimming is attempted.

**Since:** _Upcoming Release_

## Hierarchy

**Implements:**

- [`UnitEnum`](https://www.php.net/manual/en/class.unitenum.php) (PHP built-in)

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Strings;

enum StrTrimLocation
{
    case TRIM_START;
    case TRIM_END;
    case TRIM_START_AND_END;
    case TRIM_NONE;
}
```

## Description

`StrTrimLocation` is an option passed into [`Str::trim()`](../Str/trim.md).
It controls where `Str::trim()` attempts to do the trimming — the start
of the input only, the end only, both ends, or neither.

It is a pure (unbacked) enum: the four cases carry no backing values and
exist only to name the trimming choices.

## Cases

| Case | Description |
|------|-------------|
| `TRIM_START` | only attempt to trim the front of the input string (ie, `ltrim()` it) |
| `TRIM_END` | only attempt to trim the end of the input string (ie, `rtrim()` it) |
| `TRIM_START_AND_END` | attempt to trim both ends of the input string (ie, `trim()` it) |
| `TRIM_NONE` | do not attempt to trim the input string at all |

## Methods

_None._

## Here Be Dragons

_None._

## Contract (from tests)

_No tests yet._

## Source

[`kits/missingbitskit/src/Strings/StrTrimLocation.php:48`](../../../../kits/missingbitskit/src/Strings/StrTrimLocation.php#L48)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::trim()`](../Str/trim.md) — the method this enum configures
- [`Str::isEmpty()`](../Str/isEmpty.md) — also accepts a `StrTrimLocation` to control trimming before measuring length
- [`Str::contains()`](../Str/contains.md) — also accepts a `StrTrimLocation` when trimming the needle

## Issues

- [Open issues mentioning `StrTrimLocation`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22StrTrimLocation%22)
- [Closed issues mentioning `StrTrimLocation`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22StrTrimLocation%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=StrTrimLocation%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
