# Str::trim()

> `public static trim(string $input, string $trimChars = self::TRIM_DEFAULT_CHARS, StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END): string`

Strips any of the individual UTF-8 characters in `$trimChars` from one or both ends of `$input`, stopping at each end as soon as it meets a character that is not in `$trimChars`.

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
     * @param string $input
     *      the string to trim
     * @param string $trimChars
     *      the set of UTF-8 codepoints to remove from `$input`.
     *      Defaults to `self::TRIM_DEFAULT_CHARS` (Unicode
     *      whitespace plus NUL).
     * @param StrTrimLocation $trimLocation
     *      where do you want to trim the string?
     * @return string
     * - `$input` unchanged if `$trimLocation` is
     *   `StrTrimLocation::TRIM_NONE`.
     * - `$input` unchanged if `$trimChars` is the empty string.
     * - `$input` unchanged if it contains no leading or trailing
     *   characters drawn from `$trimChars`.
     * - otherwise, a new string with the requested side(s) of
     *   `$input` stripped of any contiguous run of characters
     *   drawn from `$trimChars`.
     */
    public static function trim(
        string $input,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END,
    ): string
}
```

## Description

`trim()` removes a contiguous run of trim characters from one or both
ends of `$input`. It walks inwards from each end and stops the moment
it meets a character that is not listed in `$trimChars`, so characters
in the middle of the string are never touched.

Which end(s) are trimmed is controlled by
[`StrTrimLocation`](../StrTrimLocation/README.md): the start only, the
end only, both ends, or neither.

`trim()` is UTF-8 safe. It delegates to PHP's multibyte trim family
([`mb_ltrim()`](https://www.php.net/manual/en/function.mb-ltrim.php),
[`mb_rtrim()`](https://www.php.net/manual/en/function.mb-rtrim.php),
and [`mb_trim()`](https://www.php.net/manual/en/function.mb-trim.php)),
with the `UTF-8` encoding hard-coded so behaviour does not drift with
`mb_internal_encoding()` across deployments. Each entry in `$trimChars`
is matched as a whole codepoint, not byte by byte.

It is the building block behind [`Str::isEmpty()`](isEmpty.md), which
trims a string before measuring its length.

## Parameters

**`$input`** (`string`)

The string to trim. It is treated as ASCII or UTF-8 text, not as a raw
byte stream.

**`$trimChars`** (`string`, optional, default: `self::TRIM_DEFAULT_CHARS`)

The set of UTF-8 codepoints to remove from `$input`. Defaults to
[`Str::TRIM_DEFAULT_CHARS`](README.md) — the Unicode `White_Space`
property plus NUL. Each codepoint in the string is an independent
member of the set; there is no range syntax.

**`$trimLocation`** ([`StrTrimLocation`](../StrTrimLocation/README.md), optional, default: `StrTrimLocation::TRIM_START_AND_END`)

Where the trimming happens: `TRIM_START` (leading run only),
`TRIM_END` (trailing run only), `TRIM_START_AND_END` (both), or
`TRIM_NONE` (return `$input` untouched).

## Return Values

Returns a `string`:

- `$input` unchanged if `$trimLocation` is `StrTrimLocation::TRIM_NONE`.
- `$input` unchanged if `$trimChars` is the empty string.
- `$input` unchanged if it contains no leading or trailing characters
  drawn from `$trimChars`.
- otherwise, a new string with the requested side(s) of `$input`
  stripped of any contiguous run of characters drawn from `$trimChars`.

## Errors/Exceptions

_None._

## Here Be Dragons

- Reminder: this handles ASCII and UTF-8 strings, not raw bytes. See
  the [`Str`](README.md) class docblock for details.
- **Invalid UTF-8 is undefined behaviour.** This method trusts what
  you hand it. Validate at your system boundary before calling.
- **No range syntax.** `Str::trim("hello", "a..z")` strips the literal
  characters `a`, `.`, and `z` — NOT the range a-z. (PHP's byte-level
  [`trim()`](https://www.php.net/manual/en/function.trim.php) family
  supports `..` ranges; we use
  [`mb_trim()`](https://www.php.net/manual/en/function.mb-trim.php) et
  al, which do not.)
- **Empty `$trimChars` is a silent no-op.** If you pass an empty
  string, `$input` is returned unchanged. No characters means nothing
  to trim.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::trim() as a public method
 ✔ ::trim() is declared public static
 ✔ ::trim() takes parameters [input, trimChars, trimLocation]
 ✔ ::trim() returns string
 ✔ ::trim() $trimChars defaults to Str::TRIM_DEFAULT_CHARS
 ✔ ::trim() $trimLocation defaults to StrTrimLocation::TRIM_START_AND_END
 ✔ strips ASCII whitespace from both ends by default
 ✔ strips NUL from both ends by default
 ✔ strips VERTICAL TAB from both ends by default
 ✔ strips FORM FEED from both ends by default
 ✔ strips NEL from both ends by default
 ✔ strips NBSP from both ends by default
 ✔ strips OGHAM SPACE MARK from both ends by default
 ✔ strips EN QUAD from both ends by default
 ✔ strips EM SPACE from both ends by default
 ✔ strips HAIR SPACE from both ends by default
 ✔ strips LINE SEPARATOR from both ends by default
 ✔ strips PARAGRAPH SEPARATOR from both ends by default
 ✔ strips NARROW NBSP from both ends by default
 ✔ strips MEDIUM MATH SPACE from both ends by default
 ✔ strips IDEOGRAPHIC SPACE from both ends by default
 ✔ returns input unchanged when no default chars sit at either end
 ✔ returns the empty string when input is entirely trim chars
 ✔ preserves internal whitespace and only strips from the ends
 ✔ TRIM_START strips leading trim chars
 ✔ TRIM_START leaves trailing trim chars untouched
 ✔ TRIM_END strips trailing trim chars
 ✔ TRIM_END leaves leading trim chars untouched
 ✔ TRIM_NONE returns the input unchanged
 ✔ strips only the characters listed in a caller-supplied $trimChars
 ✔ treats a multibyte character in $trimChars as a single codepoint
 ✔ returns input unchanged when $trimChars is the empty string
 ✔ treats ".." in $trimChars as the literal characters dot-dot, NOT as a range
 ✔ does NOT strip lowercase letters when $trimChars is "a..z"
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:235`](../../../../kits/missingbitskit/src/Strings/Str.php#L235)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::isEmpty()`](isEmpty.md) — trims a string, then reports whether anything is left
- [`Str::contains()`](contains.md) — trims a needle before searching the haystack for it
- [`StrTrimLocation`](../StrTrimLocation/README.md) — selects which end(s) `trim()` strips

## Issues

- [Open issues mentioning `Str::trim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3Atrim()%22)
- [Closed issues mentioning `Str::trim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3Atrim()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3Atrim()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
