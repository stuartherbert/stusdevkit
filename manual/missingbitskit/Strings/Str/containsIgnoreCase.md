# Str::containsIgnoreCase()

> `public static containsIgnoreCase(string|array $haystack, string $needle, ?callable $transformer = null): bool`

Searches one or more strings to see whether any of them contains the given needle, ignoring letter case.

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
     * @param string|string[] $haystack
     *      the string(s) that we want to search
     * @param string $needle
     *      the string we want to look for inside `$haystack`,
     *      ignoring letter case
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$needle` before the fold
     *      and search (e.g. a trimming transformer). `$needle` is the
     *      only value passed to it; the `$haystack` is case-folded but
     *      otherwise never transformed.
     * @return bool
     * - `false` if `$haystack` is empty
     * - `false` if `$needle` is empty (after transforming)
     * - `true` if any string in `$haystack` contains `$needle` once
     *   both are case-folded
     * - `false` otherwise
     */
    public static function containsIgnoreCase(
        string|array $haystack,
        string $needle,
        ?callable $transformer = null,
    ): bool
}
```

## Description

`containsIgnoreCase()` is the case-insensitive companion to
[`Str::contains()`](contains.md). It searches a haystack for a needle
and reports whether the needle appears, **ignoring differences of
letter case**. The haystack may be a single string or an array of
strings; an array is searched element by element, and the method
returns `true` as soon as any element contains the needle.

To match case-insensitively it case-folds **both** the needle and each
haystack string via [`Str::foldCase()`](foldCase.md) before searching —
so "STRASSE" matches a haystack written with "ß", and "HELLO" matches
"hello".

By default the needle is searched for (case-folded) exactly as given.
Pass an optional `$transformer` — any `callable(string): string` — to
transform the needle before the fold and search; for example, a
trimming transformer (`fn($v) => Str::trim($v)`, see
[`Str::trim()`](trim.md)) strips the needle's surrounding whitespace so
a padded needle can still match. The transformer is applied to the
needle **only**; the haystack is folded for case but otherwise never
transformed. See the [`Str`](README.md) class's Transformers tutorial
for the full pattern.

An empty haystack contains nothing, so the method returns `false` for
an empty string or empty array. A needle that is empty — or that
transforms down to empty — can never be "contained", so it reports
`false` via [`Str::isEmpty()`](isEmpty.md).

The search itself delegates to PHP's
[`str_contains()`](https://www.php.net/manual/en/function.str-contains.php),
run over the folded strings. Although that is a byte-level function, it
is correct on UTF-8 data: UTF-8 is self-synchronising, so a byte match
can only ever land on a character boundary.

## Parameters

**`$haystack`** (`string|string[]`)

The string, or array of strings, that we want to search. An array is
searched element by element; an empty string or empty array contains
nothing, so the method returns `false`.

**`$needle`** (`string`)

The string we want to look for inside `$haystack`, ignoring letter
case. An empty needle (or one that transforms down to empty) always
yields `false`.

**`$transformer`** (`callable(string): string`, optional, default: `null`)

An optional transform applied to `$needle` before the case-fold and
search (e.g. a trimming transformer). `$needle` is the only value
passed to it; the `$haystack` is case-folded but otherwise never
transformed. With the default `null`, no transform is applied. Pass
[`Str::identity()`](identity.md) if you'd rather spell out "no
transformation" explicitly.

## Return Values

Returns a `bool`:

- `false` if `$haystack` is empty.
- `false` if `$needle` is empty (after transforming).
- `true` if any string in `$haystack` contains `$needle` once both are
  case-folded.
- `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

- **This DOES case-fold the haystack — unlike `contains()`.**
  Case-insensitive matching is impossible without folding both sides,
  so this is a deliberate exception to `contains()`'s
  never-touch-the-haystack rule. The `$transformer` (if any) still
  applies to the `$needle` only; the haystack is folded for case but is
  otherwise never transformed.
- **Folding is simple default case-folding, not canonical
  normalisation.** It inherits every caveat of
  [`Str::foldCase()`](foldCase.md): combining sequences are not
  normalised, and locale-specific rules (Turkish dotless "ı"/"İ") fold
  the default way. Normalise at your system boundary — see the
  [`Str`](README.md) class docblock — if you need that.
- **Your `$transformer` is skipped when the haystack is empty.** The
  method answers the empty-haystack case before it transforms, so a
  transformer with side effects will not fire for an empty `$haystack`.
- **Empty `$needle` is always `false`.** An empty needle can never be
  meaningfully "contained"; like `contains()`, this reports `false` for
  it, so a trimming transformer that empties the needle yields `false`.
- **Keep transformers pure.** Your transformer may not get called. Any
  transformer that has side effects will cause subtle bugs.
- **Your transformer's result is temporary.** If you want access to the
  result of `$transformer($needle)`, call it yourself first, and don't
  pass `$transformer` into `containsIgnoreCase()` at all.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::containsIgnoreCase() as a public method
 ✔ ::containsIgnoreCase() is declared public static
 ✔ ::containsIgnoreCase() takes parameters [haystack, needle, transformer]
 ✔ ::containsIgnoreCase() returns bool
 ✔ ::containsIgnoreCase() $transformer defaults to null
 ✔ returns true when the string haystack contains the needle in a different case
 ✔ returns true when the string haystack contains the needle in the same case
 ✔ returns false when the string haystack does not contain the needle in any case
 ✔ folds the German eszett on both sides before searching
 ✔ returns true when only a later element of the array haystack matches case-insensitively
 ✔ returns false when no element of the array haystack matches in any case
 ✔ returns false when the haystack is an empty string
 ✔ returns false when the haystack is an empty array
 ✔ returns true when searching the string "0" for the needle "0"
 ✔ returns false when the needle is an empty string
 ✔ transforms the needle before folding and searching when a transformer is given
 ✔ returns false when a trimming transformer reduces the needle to empty
 ✔ applies the transformer to the needle only, though it still case-folds the haystack
 ✔ does not invoke the transformer when the string haystack is empty
 ✔ does not invoke the transformer when the array haystack is empty
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:344`](../../../../kits/missingbitskit/src/Strings/Str.php#L344)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::contains()`](contains.md) — the case-sensitive companion (does not fold either side)
- [`Str::foldCase()`](foldCase.md) — the Unicode case-folding both sides are reduced through
- [`Str::isEmpty()`](isEmpty.md) — rejects an empty (or transformed-to-empty) needle before searching
- [`Str::trim()`](trim.md) — the transform you typically pass to strip a needle's padding
- [`Str::identity()`](identity.md) — the no-op transformer, for an explicit "no transformation"
- [`Str`](README.md) — the class-level Transformers tutorial explaining the `$transformer` parameter

## Issues

- [Open issues mentioning `Str::containsIgnoreCase()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AcontainsIgnoreCase()%22)
- [Closed issues mentioning `Str::containsIgnoreCase()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AcontainsIgnoreCase()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AcontainsIgnoreCase()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
