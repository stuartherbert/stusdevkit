# Str::foldCase()

> `public static foldCase(string $input): string`

Returns the input reduced to its Unicode default case-folded form, for caseless matching.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Strings\Str`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Strings;

class Str
{
    /**
     * @param string $input
     *      the string to case-fold
     * @return string
     *      `$input` reduced to its Unicode default case-folded form
     */
    public static function foldCase(string $input): string
}
```

## Description

`foldCase()` returns `$input` with every character reduced to its
Unicode *default case-folded* form.

It is a producer, and satisfies the `callable(string): string` shape —
pass `Str::foldCase(...)` as the `$transformer` to a `Str` query method
(such as [`Str::isEmpty()`](isEmpty.md) or
[`Str::contains()`](contains.md)) to case-fold that method's input
before the query runs. See the [`Str`](README.md) class's Transformers
tutorial for the full pattern.

Case-folding is the operation you want for *caseless matching* — "are
these two strings the same if we ignore case?". It differs from
[`Str::toLower()`](toLower.md): folding expands the German "ß" to "ss"
and normalises the Greek final sigma, so `foldCase("STRASSE")` and
`foldCase("straße")` both yield `"strasse"`, whereas `toLower()` would
leave them unequal. Use `toLower()` when you want a lowercase string
for **display**; use `foldCase()` when you want a key for
**comparison**.

The fold itself delegates to PHP's
[`mb_convert_case()`](https://www.php.net/manual/en/function.mb-convert-case.php)
with the `MB_CASE_FOLD` mode and a hard-coded `'UTF-8'` encoding, so
behaviour does not drift with the ambient `mb_internal_encoding()`.

## Parameters

**`$input`** (`string`)

The string to case-fold.

## Return Values

Returns `$input` reduced to its Unicode default case-folded form.
Characters with no case (for example CJK ideographs) are returned
unchanged. The result may be a different length from `$input` — see
Here Be Dragons.

## Errors/Exceptions

_None._

## Here Be Dragons

- **The result can be a different length from `$input`.** Folding is
  not one-to-one: "ß" folds to the two characters "ss", so the
  returned string can have more codepoints — and more bytes — than you
  handed in. Never use a `foldCase()` result to index back into the
  original string; the offsets will silently drift out of alignment.
  Fold for comparison, never for slicing.
- **Folding is not canonical normalisation.** `foldCase()` does
  Unicode *default* case-folding (`mb_convert_case` with
  `MB_CASE_FOLD`). It does NOT normalise combining sequences (an "e"
  plus a combining acute accent stays two codepoints) and it does NOT
  apply locale-specific rules (Turkish dotless "ı"/"İ" fold the
  default way, which is the footgun waiting for Turkish text).
  Normalise at your system boundary — see the [`Str`](README.md) class
  docblock — if you need those.
- Reminder: this handles ASCII and UTF-8 strings, not raw bytes. See
  the [`Str`](README.md) class docblock for details.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::foldCase() as a public method
 ✔ ::foldCase() is declared public static
 ✔ ::foldCase() takes parameters [input]
 ✔ ::foldCase() returns string
 ✔ folds an ASCII string to lowercase
 ✔ folds a multibyte string
 ✔ folds the German eszett to a double s
 ✔ folds to a result that can be longer than the input
 ✔ returns an empty string unchanged
 ✔ leaves a caseless string unchanged
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:567`](../../../../kits/missingbitskit/src/Strings/Str.php#L567)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::toLower()`](toLower.md) — the display-oriented sibling (simple lowercase, length-preserving); use it to produce text, not to compare
- [`Str::containsIgnoreCase()`](containsIgnoreCase.md) — case-insensitive substring search, built on `foldCase()`
- [`Str::isEmpty()`](isEmpty.md) — a query method that accepts a producer like `Str::foldCase()` as its transformer
- [`Str::identity()`](identity.md) — the no-op producer/transformer sibling
- [`Str`](README.md) — the class-level Transformers tutorial explaining producers and transformers

## Issues

- [Open issues mentioning `Str::foldCase()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AfoldCase()%22)
- [Closed issues mentioning `Str::foldCase()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AfoldCase()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AfoldCase()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
