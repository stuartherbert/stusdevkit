# Str::toUpper()

> `public static toUpper(string $input): string`

Returns the input with every cased character folded to its uppercase form.

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
     *      the string to fold to uppercase
     * @return string
     *      `$input` with every cased character folded to uppercase
     */
    public static function toUpper(string $input): string
}
```

## Description

`toUpper()` returns `$input` with every cased character folded to its
uppercase form.

It is a producer, and satisfies the `callable(string): string` shape —
pass `Str::toUpper(...)` as the `$transformer` to a `Str` query method
(such as [`Str::isEmpty()`](isEmpty.md) or
[`Str::contains()`](contains.md)) to fold that method's input to
uppercase before the query runs. See the [`Str`](README.md) class's
Transformers tutorial for the full pattern.

## Parameters

**`$input`** (`string`)

The string to fold to uppercase.

## Return Values

Returns `$input` with every cased character folded to uppercase.
Characters with no uppercase form are returned unchanged.

## Errors/Exceptions

_None._

## Here Be Dragons

- **Uppercasing is not case-folding.** `toUpper()` does a simple
  Unicode uppercase (`mb_strtoupper`) — fine for display, but NOT the
  full Unicode case-folding a watertight caseless match needs for
  every script (German "ß" expands to "SS", and other surprises
  await). For caseless matching, reach for
  [`Str::foldCase()`](foldCase.md) instead — it does proper Unicode
  case-folding. For canonical equivalence, normalise at your system
  boundary — see the [`Str`](README.md) class docblock.
- Reminder: this handles ASCII and UTF-8 strings, not raw bytes. See
  the [`Str`](README.md) class docblock for details.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::toUpper() as a public method
 ✔ ::toUpper() is declared public static
 ✔ ::toUpper() takes parameters [input]
 ✔ ::toUpper() returns string
 ✔ uppercases an ASCII string
 ✔ uppercases a multibyte string
 ✔ leaves an already-uppercase string unchanged
 ✔ returns an empty string unchanged
 ✔ leaves a caseless string unchanged
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:939`](../../../../kits/missingbitskit/src/Strings/Str.php#L939)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::toLower()`](toLower.md) — the lowercase sibling
- [`Str::foldCase()`](foldCase.md) — the matching-oriented sibling (Unicode case-folding); use it to compare, not to display
- [`Str::isEmpty()`](isEmpty.md) — a query method that accepts a producer like `Str::toUpper()` as its transformer
- [`Str::identity()`](identity.md) — the no-op producer/transformer sibling
- [`Str`](README.md) — the class-level Transformers tutorial explaining producers and transformers

## Issues

- [Open issues mentioning `Str::toUpper()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3AtoUpper()%22)
- [Closed issues mentioning `Str::toUpper()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3AtoUpper()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3AtoUpper()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
