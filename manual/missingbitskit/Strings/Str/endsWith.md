# Str::endsWith()

> `public static endsWith(string|array $haystack, string $needle, ?callable $transformer = null): bool`

Checks one or more strings to see whether any of them ends with the given needle.

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
     *      the string(s) whose end we want to check
     * @param string $needle
     *      the string we want `$haystack` to end with
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$needle` before the
     *      check (e.g. a trimming transformer). `$needle` is the
     *      only value passed to it; the `$haystack` is never
     *      transformed.
     * @return bool
     * - `false` if `$haystack` is empty
     * - `false` if `$needle` is empty (after transforming)
     * - `true` if any string in `$haystack` ends with `$needle`
     * - `false` otherwise
     */
    public static function endsWith(
        string|array $haystack,
        string $needle,
        ?callable $transformer = null,
    ): bool
}
```

## Description

`endsWith()` checks a haystack against a needle and reports whether the
haystack ends with it. The haystack may be a single string or an array
of strings; an array is checked element by element, and `endsWith()`
returns `true` as soon as any element ends with the needle.

By default the needle is checked exactly as given. Pass an optional
`$transformer` — any `callable(string): string` — to transform the
needle before the check; for example, a trimming transformer
(`fn($v) => Str::trim($v)`, see [`Str::trim()`](trim.md)) strips the
needle's surrounding whitespace so a padded needle can still match. The
transformer is applied to the needle **only**; the haystack is always
checked exactly as given. See the [`Str`](README.md) class's
Transformers tutorial for the full pattern.

An empty haystack has no closing characters, so `endsWith()` returns
`false` for an empty string or empty array. A needle that is empty — or
that transforms down to empty — is not a meaningful "ends with", so
`endsWith()` reports `false` for it via [`Str::isEmpty()`](isEmpty.md).

The check itself delegates to PHP's
[`str_ends_with()`](https://www.php.net/manual/en/function.str-ends-with.php).
Although that is a byte-level function, it is correct on UTF-8 data:
UTF-8 is self-synchronising, so a byte match can only ever land on a
character boundary.

## Parameters

**`$haystack`** (`string|string[]`)

The string, or array of strings, whose end we want to check. An array
is checked element by element; an empty string or empty array has no
closing characters, so `endsWith()` returns `false`.

**`$needle`** (`string`)

The string we want `$haystack` to end with. An empty needle (or one
that transforms down to empty) always yields `false`.

**`$transformer`** (`callable(string): string`, optional, default: `null`)

An optional transform applied to `$needle` before the check (e.g. a
trimming transformer). `$needle` is the only value passed to it; the
`$haystack` is never transformed. With the default `null`, no transform
is applied and the needle is checked verbatim. Pass
[`Str::identity()`](identity.md) if you'd rather spell out "no
transformation" explicitly.

## Return Values

Returns a `bool`:

- `false` if `$haystack` is empty.
- `false` if `$needle` is empty (after transforming).
- `true` if any string in `$haystack` ends with `$needle`.
- `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

- **The transformer applies to the `$needle` only.** The `$haystack`
  is never transformed. If you need the haystack normalised, do it at
  your system boundary — see the [`Str`](README.md) class docblock.
- **Your `$transformer` is skipped when the haystack is empty.**
  `endsWith()` answers the empty-haystack case before it transforms,
  so a transformer with side effects will not fire for an empty
  `$haystack`.
- **Empty `$needle` is always `false` — unlike `str_ends_with()`.**
  PHP's `str_ends_with($h, '')` reports `true` (every string "ends
  with" nothing); `endsWith()` deliberately returns `false`, because
  "ends with nothing" is not a meaningful query. This matches
  [`Str::contains()`](contains.md), and it means a trimming transformer
  that empties the needle yields `false`.
- **Keep transformers pure.** Your transformer may not get called.
  Any transformer that has side effects will cause subtle bugs.
- **Your transformer's result is temporary.** If you want access to
  the result of `$transformer($needle)`, call it yourself first, and
  don't pass `$transformer` into `endsWith()` at all.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::endsWith() as a public method
 ✔ ::endsWith() is declared public static
 ✔ ::endsWith() takes parameters [haystack, needle, transformer]
 ✔ ::endsWith() returns bool
 ✔ ::endsWith() $transformer defaults to null
 ✔ returns true when the string haystack ends with the needle
 ✔ returns false when the string haystack does not end with the needle
 ✔ returns false when the needle appears but not at the end
 ✔ is case-sensitive
 ✔ returns true when only a later element of the array haystack ends with the needle
 ✔ returns false when no element of the array haystack ends with the needle
 ✔ returns false when the haystack is an empty string
 ✔ returns false when the haystack is an empty array
 ✔ returns true when checking the string "0" ends with the needle "0"
 ✔ matches a multibyte needle at the end of the haystack
 ✔ does not match an ASCII needle against the bytes of a trailing multibyte character
 ✔ returns false when the needle is an empty string
 ✔ checks the needle verbatim when no transformer is given
 ✔ checks for a whitespace-only needle when no transformer is given
 ✔ transforms the needle before checking when a transformer is given
 ✔ returns false when a trimming transformer reduces the needle to empty
 ✔ applies the transformer to the needle only, never the haystack
 ✔ does not invoke the transformer when the string haystack is empty
 ✔ does not invoke the transformer when the array haystack is empty
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:333`](../../../../kits/missingbitskit/src/Strings/Str.php#L333)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::startsWith()`](startsWith.md) — the mirror check for the start of the haystack
- [`Str::contains()`](contains.md) — checks anywhere in the haystack, not just the end
- [`Str::isEmpty()`](isEmpty.md) — rejects an empty (or transformed-to-empty) needle before checking
- [`Str::trim()`](trim.md) — the transform you typically pass to strip a needle's padding
- [`Str::identity()`](identity.md) — the no-op transformer, for an explicit "no transformation"
- [`Str`](README.md) — the class-level Transformers tutorial explaining the `$transformer` parameter

## Issues

- [Open issues mentioning `Str::endsWith()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AendsWith()%22)
- [Closed issues mentioning `Str::endsWith()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AendsWith()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AendsWith()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
