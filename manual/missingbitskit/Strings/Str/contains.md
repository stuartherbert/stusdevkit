# Str::contains()

> `public static contains(string|array $haystack, string $needle, string $trimChars = self::TRIM_DEFAULT_CHARS, StrTrimLocation $trimLocation = StrTrimLocation::TRIM_NONE): bool`

Searches one or more strings to see whether any of them contains the given needle.

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
     * @param string|string[] $haystack
     *      the string(s) that we want to search
     * @param string $needle
     *      the string we want to look for inside `$haystack`
     * @param string $trimChars
     *      set of characters to `Str::trim()` from `$needle` before
     *      we search inside `$haystack`
     * @param StrTrimLocation $trimLocation
     *      where (if anywhere) do you want `Str::trim()` to be applied to
     *      your `$needle` string?
     * @return bool
     * - `true` if $haystack contains `$needle`
     * - `false` if `$needle` is an empty string (after trimming)
     * - `false` otherwise
     */
    public static function contains(
        string|array $haystack,
        string $needle,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_NONE,
    ): bool
}
```

## Description

`contains()` searches a haystack for a needle and reports whether the
needle appears. The haystack may be a single string or an array of
strings; an array is searched element by element, and `contains()`
returns `true` as soon as any element contains the needle.

It can optionally [`Str::trim()`](trim.md) the needle for you before
searching, so you don't have to do that first. This is controlled by
`$trimLocation`, which defaults to
[`StrTrimLocation::TRIM_NONE`](../StrTrimLocation/README.md) — so by
default the needle is searched for verbatim, surrounding whitespace and
all. A needle that is empty (or trims down to empty) can never be
"contained", so `contains()` reports `false` for it via
[`Str::isEmpty()`](isEmpty.md).

The search itself delegates to PHP's
[`str_contains()`](https://www.php.net/manual/en/function.str-contains.php).
Although that is a byte-level function, it is correct on UTF-8 data:
UTF-8 is self-synchronising, so a byte match can only ever land on a
character boundary.

## Parameters

**`$haystack`** (`string|string[]`)

The string, or array of strings, that we want to search. An array is
searched element by element; an empty string or empty array contains
nothing, so `contains()` returns `false`.

**`$needle`** (`string`)

The string we want to look for inside `$haystack`. An empty needle
(or one that trims down to empty) always yields `false`.

**`$trimChars`** (`string`, optional, default: `self::TRIM_DEFAULT_CHARS`)

The set of characters to [`Str::trim()`](trim.md) from `$needle`
before we search inside `$haystack`. Only takes effect when
`$trimLocation` requests trimming. Defaults to
[`Str::TRIM_DEFAULT_CHARS`](README.md) — the Unicode `White_Space`
property plus NUL.

**`$trimLocation`** ([`StrTrimLocation`](../StrTrimLocation/README.md), optional, default: `StrTrimLocation::TRIM_NONE`)

Where (if anywhere) you want [`Str::trim()`](trim.md) applied to
`$needle` before searching. Defaults to
[`StrTrimLocation::TRIM_NONE`](../StrTrimLocation/README.md), so the
needle is searched for verbatim unless you opt in to trimming.

## Return Values

Returns a `bool`:

- `true` if `$haystack` contains `$needle`.
- `false` if `$needle` is an empty string (after trimming).
- `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

- **Inherits every [`Str::trim()`](trim.md) hazard.** Whenever
  trimming happens, the dragons listed on [`Str::trim()`](trim.md)
  apply here too - most notably, **invalid UTF-8 is undefined
  behaviour**. Validate at your system boundary before calling.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::contains() as a public method
 ✔ ::contains() is declared public static
 ✔ ::contains() takes parameters [haystack, needle, trimChars, trimLocation]
 ✔ ::contains() returns bool
 ✔ ::contains() $trimChars defaults to Str::TRIM_DEFAULT_CHARS
 ✔ ::contains() $trimLocation defaults to StrTrimLocation::TRIM_NONE
 ✔ returns true when the string haystack contains the needle
 ✔ returns false when the string haystack does not contain the needle
 ✔ is case-sensitive
 ✔ returns true when only a later element of the array haystack contains the needle
 ✔ returns false when no element of the array haystack contains the needle
 ✔ returns false when the haystack is an empty string
 ✔ returns false when the haystack is an empty array
 ✔ returns true when searching the string "0" for the needle "0"
 ✔ finds a multibyte needle inside the haystack
 ✔ does not match an ASCII needle against the bytes of a multibyte character
 ✔ returns false when the needle is an empty string
 ✔ does not trim the needle when trimLocation defaults to TRIM_NONE
 ✔ trims the needle before searching when trimLocation is TRIM_START_AND_END
 ✔ returns false when a whitespace-only needle is trimmed away
 ✔ searches for a whitespace-only needle when trimming is off
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:161`](../../../../kits/missingbitskit/src/Strings/Str.php#L161)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::trim()`](trim.md) — the trimming applied to the needle when you opt in
- [`Str::isEmpty()`](isEmpty.md) — rejects an empty (or trimmed-to-empty) needle before searching
- [`StrTrimLocation`](../StrTrimLocation/README.md) — selects whether and where the needle is trimmed

## Issues

- [Open issues mentioning `Str::contains()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3Acontains()%22)
- [Closed issues mentioning `Str::contains()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3Acontains()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3Acontains()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
