# Str::isEmpty()

> `public static isEmpty(string|null $input, ?callable $transformer = null): bool`

Checks whether the given input string is empty.

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
     *      the string to check for emptiness
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$input` before the
     *      post-transform emptiness check (e.g. a trimming
     *      transformer). `$input` is the only value passed to it.
     * @return bool
     * - `true` if `$input` is null
     * - `true` if `$input`'s length is zero before transforming
     * - `true` if `$input`'s length is zero after transforming
     * - `false` otherwise
     */
    public static function isEmpty(
        string|null $input,
        ?callable $transformer = null,
    ): bool
}
```

## Description

`isEmpty()` checks whether the given input string is empty.

By default it tests `$input` exactly as given: only `null` or a
zero-length string counts as empty. Whitespace-only input is **not**
empty by default.

Pass a `$transformer` to transform `$input` before the check — for
example, a trimming transformer (`fn($v) => Str::trim($v)`, see
[`Str::trim()`](trim.md)) makes a whitespace-only string count as
empty. If you'd rather spell out "no transformation" explicitly than
rely on the `null` default, pass [`Str::identity()`](identity.md) — it
is behaviourally identical to passing nothing.

For added convenience, `isEmpty()` also accepts a `null` `$input`,
which is always reported as empty — so callers do not have to guard
their own null checks before calling.

## Parameters

**`$input`** (`string|null`)

The string to check for emptiness. A `null` value is always treated as
empty.

**`$transformer`** (`callable(string): string`, optional, default: `null`)

An optional transform applied to `$input` before the (post-transform)
emptiness check. `$input` is the only value passed to it. Pass a
trimming transformer to treat whitespace-only input as empty, or
[`Str::identity()`](identity.md) for an explicit no-op. With the
default `null`, no transform is applied and `$input` is tested exactly
as given.

## Return Values

Returns a `bool`:

- `true` if `$input` is null.
- `true` if `$input`'s length is zero before transforming.
- `true` if `$input`'s length is zero after transforming.
- `false` otherwise.

## Errors/Exceptions

_None._

## Here Be Dragons

- **Your `$transformer` is skipped for `null` and already-empty
  input.** `isEmpty()` answers the cheap cases (`null`, zero-length)
  before it transforms, so a transformer with side effects will not
  fire for those inputs.
- **Keep transformers pure.** Your transformer may not get called.
  Any transformer that has side effects will cause subtle bugs.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::isEmpty() as a public method
 ✔ ::isEmpty() is declared public static
 ✔ ::isEmpty() takes parameters [input, transformer]
 ✔ ::isEmpty() returns bool
 ✔ ::isEmpty() $transformer defaults to null
 ✔ returns true when input is null
 ✔ returns true when input is a zero-length string
 ✔ returns false when input is only whitespace and no transformer is given
 ✔ returns false when input contains visible content
 ✔ returns true when a trimming transformer reduces the input to empty
 ✔ returns true when a trimming transformer reduces a Unicode-whitespace input to empty
 ✔ returns false when the transformer leaves visible content
 ✔ does not invoke the transformer when input is null
 ✔ does not invoke the transformer when input is already zero-length
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:640`](../../../../kits/missingbitskit/src/Strings/Str.php#L640)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::trim()`](trim.md) — the transform you typically pass to treat whitespace as empty
- [`Str::identity()`](identity.md) — the no-op transformer, for an explicit "no transformation"
- [`Str::contains()`](contains.md) — uses `isEmpty()` to reject empty needles before searching

## Issues

- [Open issues mentioning `Str::isEmpty()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AisEmpty()%22)
- [Closed issues mentioning `Str::isEmpty()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AisEmpty()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AisEmpty()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
