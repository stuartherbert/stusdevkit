# Str::isEmpty()

> `public static isEmpty(string|null $input, string $trimChars = self::TRIM_DEFAULT_CHARS, StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END): bool`

Checks whether the given input string is really empty or not.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Strings\Str`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Strings;

class Str
{
    public const TRIM_DEFAULT_CHARS =
        "\u{0000}"   // NUL
        . "\u{0009}" // HORIZONTAL TAB
        . "\u{000A}" // LINE FEED
        . "\u{000B}" // VERTICAL TAB
        . "\u{000C}" // FORM FEED
        . "\u{000D}" // CARRIAGE RETURN
        . "\u{0020}" // SPACE
        . "\u{0085}" // NEXT LINE (NEL)
        . "\u{00A0}" // NO-BREAK SPACE
        . "\u{1680}" // OGHAM SPACE MARK
        . "\u{2000}" // EN QUAD
        . "\u{2001}" // EM QUAD
        . "\u{2002}" // EN SPACE
        . "\u{2003}" // EM SPACE
        . "\u{2004}" // THREE-PER-EM SPACE
        . "\u{2005}" // FOUR-PER-EM SPACE
        . "\u{2006}" // SIX-PER-EM SPACE
        . "\u{2007}" // FIGURE SPACE
        . "\u{2008}" // PUNCTUATION SPACE
        . "\u{2009}" // THIN SPACE
        . "\u{200A}" // HAIR SPACE
        . "\u{2028}" // LINE SEPARATOR
        . "\u{2029}" // PARAGRAPH SEPARATOR
        . "\u{202F}" // NARROW NO-BREAK SPACE
        . "\u{205F}" // MEDIUM MATHEMATICAL SPACE
        . "\u{3000}" // IDEOGRAPHIC SPACE
    ;

    /**
     * @param string|null $input
     *      the string to check
     * @param string $trimChars
     *      set of characters to `Str::trim()` from the string before
     *      checking for emptiness
     * @param StrTrimLocation $trimLocation
     *      where (if anywhere) do you want `Str::trim()` to be applied to
     *      your `input` string?
     * @return bool
     * - `true` if `$input` is null
     * - `true` if `$input`'s length is zero before trimming
     * - `true` if `$input`'s length is zero after optional trimming
     * - `false` otherwise
     */
    public static function isEmpty(
        string|null $input,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END,
    ): bool
}
```

## Description

`isEmpty()` checks whether the given input string is really empty or
not.

By default it runs [`Str::trim()`](trim.md) on the input before
checking, so a string that contains only whitespace counts as empty.
You can change this behaviour with the `$trimLocation` parameter — pass
[`StrTrimLocation::TRIM_NONE`](../StrTrimLocation/README.md) for a
byte-exact check that does no trimming at all.

For added convenience, `isEmpty()` also accepts a `null` `$input`,
which is always reported as empty — so callers do not have to guard
their own null checks before calling.

## Parameters

**`$input`** (`string|null`)

The string to check. A `null` value is always treated as empty.

**`$trimChars`** (`string`, optional, default: `self::TRIM_DEFAULT_CHARS`)

The set of characters to [`Str::trim()`](trim.md) from the string
before checking for emptiness. Defaults to
[`Str::TRIM_DEFAULT_CHARS`](README.md) — the Unicode `White_Space`
property plus NUL.

**`$trimLocation`** ([`StrTrimLocation`](../StrTrimLocation/README.md), optional, default: `StrTrimLocation::TRIM_START_AND_END`)

Where (if anywhere) you want [`Str::trim()`](trim.md) to be applied to
the input string before the emptiness check. Pass
[`StrTrimLocation::TRIM_NONE`](../StrTrimLocation/README.md) to skip
trimming entirely.

## Return Values

Returns a `bool`:

- `true` if `$input` is null.
- `true` if `$input`'s length is zero before trimming.
- `true` if `$input`'s length is zero after optional trimming.
- `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

- **Empty `$trimChars` is a silent no-op.** Hand `isEmpty()` an empty
  `$trimChars` and [`Str::trim()`](trim.md) has nothing to strip, so a
  whitespace-only `$input` survives intact and `isEmpty()` cheerfully
  reports `false`. If you want whitespace to count as empty, leave
  `$trimChars` at its default - don't blank it out.
- **Inherits every [`Str::trim()`](trim.md) hazard.** Whenever
  trimming happens, the dragons listed on [`Str::trim()`](trim.md)
  apply here too - most notably, **invalid UTF-8 is undefined
  behaviour**. Validate at your system boundary before calling.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::isEmpty() as a public method
 ✔ ::isEmpty() is declared public static
 ✔ ::isEmpty() takes parameters [input, trimChars, trimLocation]
 ✔ ::isEmpty() returns bool
 ✔ ::isEmpty() $trimChars defaults to Str::TRIM_DEFAULT_CHARS
 ✔ ::isEmpty() $trimLocation defaults to StrTrimLocation::TRIM_START_AND_END
 ✔ returns true when input is null
 ✔ returns true when input is a zero-length string
 ✔ returns true when input is only ASCII whitespace
 ✔ returns true when input is only a Unicode whitespace character
 ✔ returns false when input contains visible content
 ✔ TRIM_NONE returns false when input is only whitespace
 ✔ TRIM_NONE returns true when input is a zero-length string
 ✔ returns true when input is only caller-supplied trim chars
 ✔ returns false when input contains a character outside caller-supplied trim chars
 ✔ returns false when $trimChars is empty and input is only whitespace
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:153`](../../../../kits/missingbitskit/src/Strings/Str.php#L153)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::trim()`](trim.md) — the trimming primitive `isEmpty()` runs before checking
- [`Str::contains()`](contains.md) — uses `isEmpty()` to reject empty needles before searching
- [`StrTrimLocation`](../StrTrimLocation/README.md) — selects which end(s) get trimmed before the check

## Issues

- [Open issues mentioning `Str::isEmpty()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AisEmpty()%22)
- [Closed issues mentioning `Str::isEmpty()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AisEmpty()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AisEmpty()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
