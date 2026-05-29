# Str

A UTF-8 safe set of string operations, for use on all text received from external inputs.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

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
     * Search the given haystack of string(s), to see if it
     * contains the given `$needle`.
     */
    public static function contains(
        string|array $haystack,
        string $needle,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_NONE,
    ): bool;

    /**
     * Check whether the given input string is really empty or not.
     */
    public static function isEmpty(
        string|null $input,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END,
    ): bool;

    /**
     * Strip the characters in `$trimChars` from one or both ends
     * of `$input`.
     */
    public static function trim(
        string $input,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END,
    ): string;
}
```

## Description

`Str` provides a UTF-8 safe set of string operations. (If you're
unsure, they also work on regular ASCII strings.)

Use this instead of PHP's built-in string operations on all text
received from external inputs. (For the avoidance of doubt, data
stored in your database also counts as an external input!)

If you're working with strings that you *know* contain raw byte
streams, fall back to calling PHP's built-in `str_xxx()` family of
functions.

## Methods

**From Str**

- [`::contains()`](contains.md) — searches one or more strings to see whether any of them contains the given needle
- [`::isEmpty()`](isEmpty.md) — checks whether the given input string is really empty or not
- [`::trim()`](trim.md) — strips the characters in `$trimChars` from one or both ends of a string

## Here Be Dragons

- **"UTF-8 safe" means codepoint-aware, not normalisation-aware.**
  These operations compare Unicode codepoints, not *canonically
  equivalent* text. Two strings that look identical but use
  different codepoint sequences - for example "é" as the single
  codepoint U+00E9 versus the pair U+0065 U+0301 - will NOT
  match. Normalising text to a single form is firmly the
  caller's responsibility, not Str's.
- **Normalise once, at your system boundary.** As text enters
  your system (HTTP input, database reads, file reads), convert
  it to a single normalisation form - NFC is the usual choice -
  using PHP's [`Normalizer`](https://www.php.net/manual/en/class.normalizer.php) class from `ext-intl`. Once every
  string in play is in the same form, every Str operation just
  works. `ext-intl` is a hard requirement of this package for
  exactly this reason.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ lives in the StusDevKit\MissingBitsKit\Strings namespace
 ✔ is declared as a class
 ✔ publishes ::trim() as a public method
 ✔ publishes ::isEmpty() as a public method
 ✔ publishes ::contains() as a public method
 ✔ publishes ::TRIM_DEFAULT_CHARS as a public constant
 ✔ ::TRIM_DEFAULT_CHARS is the Unicode `White_Space` set plus NUL
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:77`](../../../../kits/missingbitskit/src/Strings/Str.php#L77)

## Changelog

_No tagged releases yet._

## See Also

- [`StrTrimLocation`](../StrTrimLocation/README.md) — the enum every Str method takes to control trimming
- [`Str::contains()`](contains.md) — searches one or more strings for a needle
- [`Str::isEmpty()`](isEmpty.md) — checks whether a string is really empty
- [`Str::trim()`](trim.md) — strips trim-characters from one or both ends of a string

## Issues

- [Open issues mentioning `Str`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%22)
- [Closed issues mentioning `Str`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
