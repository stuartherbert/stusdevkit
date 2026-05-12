# StringTransformations::applyLtrim()

> `public function applyLtrim(string $characters = " \n\r\t\v\0"): static`

Left-trims all strings in the collection using PHP's `ltrim()` function.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Traits\StringTransformations`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Traits;

trait StringTransformations
{
    /**
     * @param string $characters
     *      list of characters to trim from the front of every string
     *      in the collection
     * @return static
     */
    public function applyLtrim(
        string $characters = " \n\r\t\v\0",
    ): static
}
```

## Description

Left-trims every string stored in the collection in place using PHP's [`ltrim()`](https://www.php.net/manual/en/function.ltrim.php) function.

Trailing characters are left untouched. The collection's underlying array is rewritten via `array_map()`, so each stored string is replaced by its left-trimmed counterpart. Keys are preserved. The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$characters`** (`string`, optional, default: `" \n\r\t\v\0"`)

List of characters to trim from the front of every string in the collection. Passed straight through to PHP's `ltrim()`. The default matches PHP's default trim character set (space, tab, newline, carriage return, vertical tab, NUL byte).

## Return Values

Returns `$this` — the same collection instance, with every stored string replaced by its left-trimmed value. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Traits\StringTransformations
 ✔ ::applyLtrim() is declared
 ✔ ::applyLtrim() is public
 ✔ ::applyLtrim() is an instance method, not static
 ✔ ::applyLtrim() declares a single `characters` string parameter
 ✔ ::applyLtrim() defaults the character mask to PHP's default trim set
 ✔ ::applyLtrim() declares a `static` return type
 ✔ ->applyLtrim() strips default whitespace from the left of every value
 ✔ ->applyLtrim() strips the caller-supplied character mask
 ✔ ->applyLtrim() returns $this for fluent chaining
 ✔ ->applyLtrim() is a no-op on an empty collection
```

## Source

[`kits/collectionskit/src/Traits/StringTransformations.php:87`](../../../../kits/collectionskit/src/Traits/StringTransformations.php#L87)

## Changelog

_No tagged releases yet._

## See Also

- [`StringTransformations::applyTrim()`](applyTrim.md) — trims all strings in the collection (both ends)
- [`StringTransformations::applyRtrim()`](applyRtrim.md) — right-trims all strings in the collection

## Issues

- [Open issues mentioning `StringTransformations::applyLtrim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22StringTransformations::applyLtrim()%22)
- [Closed issues mentioning `StringTransformations::applyLtrim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22StringTransformations::applyLtrim()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=StringTransformations%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
