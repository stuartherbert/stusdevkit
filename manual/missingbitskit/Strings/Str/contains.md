# Str::contains()

> `public static contains(string|array $haystack, string $needle, ?callable $transformer = null): bool`

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
    /**
     * @param string|string[] $haystack
     *      the string(s) that we want to search
     * @param string $needle
     *      the string we want to look for inside `$haystack`
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$needle` before the
     *      search (e.g. a trimming transformer). `$needle` is the
     *      only value passed to it; the `$haystack` is never
     *      transformed.
     * @return bool
     * - `false` if `$haystack` is empty
     * - `false` if `$needle` is empty (after transforming)
     * - `true` if any string in `$haystack` contains `$needle`
     * - `false` otherwise
     */
    public static function contains(
        string|array $haystack,
        string $needle,
        ?callable $transformer = null,
    ): bool
}
```

## Description

`contains()` searches a haystack for a needle and reports whether the
needle appears. The haystack may be a single string or an array of
strings; an array is searched element by element, and `contains()`
returns `true` as soon as any element contains the needle.

By default the needle is searched for exactly as given. Pass an
optional `$transformer` — any `callable(string): string` — to
transform the needle before searching; for example, a trimming
transformer (`fn($v) => Str::trim($v)`, see [`Str::trim()`](trim.md))
strips the needle's surrounding whitespace so a padded needle can
still match. The transformer is applied to the needle **only**; the
haystack is always searched exactly as given. See the [`Str`](README.md)
class's Transformers tutorial for the full pattern.

An empty haystack contains nothing, so `contains()` returns `false`
for an empty string or empty array. A needle that is empty — or that
transforms down to empty — can never be "contained", so `contains()`
reports `false` for it via [`Str::isEmpty()`](isEmpty.md).

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
(or one that transforms down to empty) always yields `false`.

**`$transformer`** (`callable(string): string`, optional, default: `null`)

An optional transform applied to `$needle` before the search (e.g. a
trimming transformer). `$needle` is the only value passed to it; the
`$haystack` is never transformed. With the default `null`, no
transform is applied and the needle is searched for verbatim. Pass
[`Str::identity()`](identity.md) if you'd rather spell out "no
transformation" explicitly.

## Return Values

Returns a `bool`:

- `false` if `$haystack` is empty.
- `false` if `$needle` is empty (after transforming).
- `true` if any string in `$haystack` contains `$needle`.
- `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

- **The transformer applies to the `$needle` only.** The `$haystack`
  is never transformed. If you need the haystack normalised, do it at
  your system boundary — see the [`Str`](README.md) class docblock.
- **Your `$transformer` is skipped when the haystack is empty.**
  `contains()` answers the empty-haystack case before it transforms,
  so a transformer with side effects will not fire for an empty
  `$haystack`.
- **Keep transformers pure.** Your transformer may not get called.
  Any transformer that has side effects will cause subtle bugs.
- **Your transformer's result is temporary.** If you want access to
  the result of `$transformer($needle)`, call it yourself first, and
  don't pass `$transformer` into `contains()` at all.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::contains() as a public method
 ✔ ::contains() is declared public static
 ✔ ::contains() takes parameters [haystack, needle, transformer]
 ✔ ::contains() returns bool
 ✔ ::contains() $transformer defaults to null
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
 ✔ searches the needle verbatim when no transformer is given
 ✔ searches for a whitespace-only needle when no transformer is given
 ✔ transforms the needle before searching when a transformer is given
 ✔ returns false when a trimming transformer reduces the needle to empty
 ✔ applies the transformer to the needle only, never the haystack
 ✔ does not invoke the transformer when the string haystack is empty
 ✔ does not invoke the transformer when the array haystack is empty
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:225`](../../../../kits/missingbitskit/src/Strings/Str.php#L225)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::containsIgnoreCase()`](containsIgnoreCase.md) — the case-insensitive companion (folds both sides before searching)
- [`Str::startsWith()`](startsWith.md) — checks only the start of the haystack, not anywhere within it
- [`Str::endsWith()`](endsWith.md) — checks only the end of the haystack, not anywhere within it
- [`Str::isEmpty()`](isEmpty.md) — rejects an empty (or transformed-to-empty) needle before searching
- [`Str::trim()`](trim.md) — the transform you typically pass to strip a needle's padding
- [`Str::identity()`](identity.md) — the no-op transformer, for an explicit "no transformation"
- [`Str`](README.md) — the class-level Transformers tutorial explaining the `$transformer` parameter

## Issues

- [Open issues mentioning `Str::contains()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3Acontains()%22)
- [Closed issues mentioning `Str::contains()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3Acontains()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3Acontains()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
