# Str::length()

> `public static length(string|null $input, ?callable $transformer = null): int`

Counts how many UTF-8 characters (codepoints) are in the given input string.

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
     * @param string|null $input
     *      the string whose characters we want to count
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$input` before counting
     *      (e.g. a trimming transformer). `$input` is the only value
     *      passed to it.
     * @return int
     * - `0` if `$input` is null
     * - `0` if `$input`'s length is zero before transforming
     * - otherwise the number of UTF-8 codepoints in `$input` (after
     *   transforming, if a `$transformer` was given)
     */
    public static function length(
        string|null $input,
        ?callable $transformer = null,
    ): int
}
```

## Description

`length()` counts how many UTF-8 characters — Unicode codepoints — are
in the given `$input` string.

By default it counts `$input` exactly as given. Pass an optional
`$transformer` — any `callable(string): string` — to transform
`$input` before counting; for example, a trimming transformer
(`fn($v) => Str::trim($v)`, see [`Str::trim()`](trim.md)) counts the
length of the trimmed string rather than the padded one. `$input` is
the only value passed to the transformer. See the [`Str`](README.md)
class's Transformers tutorial for the full pattern.

For convenience, `length()` also accepts a `null` `$input`: a `null`
has no characters, so it counts as `0` — callers do not have to guard
their own null checks before calling.

## Parameters

**`$input`** (`string|null`)

The string whose characters we want to count. A `null` value has no
characters and counts as `0`.

**`$transformer`** (`callable(string): string`, optional, default: `null`)

An optional transform applied to `$input` before counting (e.g. a
trimming transformer). `$input` is the only value passed to it. With
the default `null`, no transform is applied and `$input` is counted
exactly as given. Pass [`Str::identity()`](identity.md) if you'd
rather spell out "no transformation" explicitly.

## Return Values

Returns an `int`:

- `0` if `$input` is null.
- `0` if `$input`'s length is zero before transforming.
- otherwise the number of UTF-8 codepoints in `$input` (after
  transforming, if a `$transformer` was given).

## Errors/Exceptions

_None._

## Here Be Dragons

- **Codepoints, not grapheme clusters.** `length()` counts Unicode
  codepoints. An "e" followed by a combining acute accent (U+0065
  U+0301) looks like one character but counts as 2. Normalise at your
  system boundary — see the [`Str`](README.md) class docblock — if you
  need the count a human would expect.
- **Your `$transformer` is skipped for `null` and zero-length input.**
  `length()` answers the cheap cases (`null`, zero-length) before it
  transforms, so a transformer with side effects will not fire for
  those inputs.
- **Keep transformers pure.** Your transformer may not get called. Any
  transformer that has side effects will cause subtle bugs.
- **Your transformer's result is temporary.** If you want access to
  the result of `$transformer($input)`, call it yourself first, and
  don't pass `$transformer` into `length()` at all.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::length() as a public method
 ✔ ::length() is declared public static
 ✔ ::length() takes parameters [input, transformer]
 ✔ ::length() returns int
 ✔ ::length() $transformer defaults to null
 ✔ returns 0 when input is null
 ✔ returns 0 when input is a zero-length string
 ✔ counts the characters in an ASCII string
 ✔ counts a multibyte character as a single codepoint, not its bytes
 ✔ measures the transformed length when a trimming transformer is given
 ✔ does not invoke the transformer when input is null
 ✔ does not invoke the transformer when input is already zero-length
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:721`](../../../../kits/missingbitskit/src/Strings/Str.php#L721)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::isEmpty()`](isEmpty.md) — the sibling query for "is the length zero?"
- [`Str::trim()`](trim.md) — the transform you typically pass to measure the trimmed length
- [`Str::identity()`](identity.md) — the no-op transformer, for an explicit "no transformation"
- [`Str`](README.md) — the class-level Transformers tutorial explaining the `$transformer` parameter

## Issues

- [Open issues mentioning `Str::length()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3Alength()%22)
- [Closed issues mentioning `Str::length()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3Alength()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3Alength()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
