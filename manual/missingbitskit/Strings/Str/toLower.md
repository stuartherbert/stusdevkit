# Str::toLower()

> `public static toLower(string $input): string`

Returns the input with every cased character folded to its lowercase form.

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
     *      the string to fold to lowercase
     * @return string
     *      `$input` with every cased character folded to lowercase
     */
    public static function toLower(string $input): string
}
```

## Description

`toLower()` returns `$input` with every cased character folded to its
lowercase form.

It is a producer, and satisfies the `callable(string): string` shape —
pass `Str::toLower(...)` as the `$transformer` to a `Str` query method
(such as [`Str::isEmpty()`](isEmpty.md) or
[`Str::contains()`](contains.md)) to fold that method's input to
lowercase before the query runs. See the [`Str`](README.md) class's
Transformers tutorial for the full pattern.

## Parameters

**`$input`** (`string`)

The string to fold to lowercase.

## Return Values

Returns `$input` with every cased character folded to lowercase.
Characters with no lowercase form are returned unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

- **Lowercasing is not case-folding.** `toLower()` does a simple
  Unicode lowercase (`mb_strtolower`) — fine for display and for most
  case-insensitive comparisons, but NOT the full Unicode case-folding
  a watertight caseless match needs for every script (German "ß",
  Greek final sigma, and friends will trip you up). Normalise at your
  system boundary — see the [`Str`](README.md) class docblock — if you
  need that.
- Reminder: this handles ASCII and UTF-8 strings, not raw bytes. See
  the [`Str`](README.md) class docblock for details.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::toLower() as a public method
 ✔ ::toLower() is declared public static
 ✔ ::toLower() takes parameters [input]
 ✔ ::toLower() returns string
 ✔ lowercases an ASCII string
 ✔ lowercases a multibyte string
 ✔ leaves an already-lowercase string unchanged
 ✔ returns an empty string unchanged
 ✔ leaves a caseless string unchanged
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:488`](../../../../kits/missingbitskit/src/Strings/Str.php#L488)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::isEmpty()`](isEmpty.md) — a query method that accepts a producer like `Str::toLower()` as its transformer
- [`Str::identity()`](identity.md) — the no-op producer/transformer sibling
- [`Str`](README.md) — the class-level Transformers tutorial explaining producers and transformers

## Issues

- [Open issues mentioning `Str::toLower()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AtoLower()%22)
- [Closed issues mentioning `Str::toLower()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AtoLower()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AtoLower()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
