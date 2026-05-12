# StringTransformations::applyTrim()

> `public function applyTrim(string $characters = " \n\r\t\v\0"): static`

Trims all strings in the collection using PHP's `trim()` function.

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
     *      list of characters to trim from the front and end of every
     *      string in the collection
     * @return static
     */
    public function applyTrim(
        string $characters = " \n\r\t\v\0",
    ): static
}
```

## Description

Trims every string stored in the collection in place using PHP's [`trim()`](https://www.php.net/manual/en/function.trim.php) function.

The collection's underlying array is rewritten via `array_map()`, so each stored string is replaced by its trimmed counterpart. Keys are preserved. The method returns `$this`, so calls can be chained fluently.

## Parameters

**`$characters`** (`string`, optional, default: `" \n\r\t\v\0"`)

List of characters to trim from the front and end of every string in the collection. Passed straight through to PHP's `trim()`. The default matches PHP's default trim character set (space, tab, newline, carriage return, vertical tab, NUL byte).

## Return Values

Returns `$this` — the same collection instance, with every stored string replaced by its trimmed value. The return type is `static`, so subclasses preserve their own type when chaining.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Traits\StringTransformations
 ✔ ::applyTrim() is declared
 ✔ ::applyTrim() is public
 ✔ ::applyTrim() is an instance method, not static
 ✔ ::applyTrim() declares a single `characters` string parameter
 ✔ ::applyTrim() defaults the character mask to PHP's default trim set
 ✔ ::applyTrim() declares a `static` return type
 ✔ ->applyTrim() strips default whitespace from both ends of every value
 ✔ ->applyTrim() strips the caller-supplied character mask
 ✔ ->applyTrim() returns $this for fluent chaining
 ✔ ->applyTrim() is a no-op on an empty collection
```

## Source

[`kits/collectionskit/src/Traits/StringTransformations.php:68`](../../../../kits/collectionskit/src/Traits/StringTransformations.php#L68)

## Changelog

_No tagged releases yet._

## See Also

- [`StringTransformations::applyLtrim()`](applyLtrim.md) — left-trims all strings in the collection
- [`StringTransformations::applyRtrim()`](applyRtrim.md) — right-trims all strings in the collection

## Issues

- [Open issues mentioning `StringTransformations::applyTrim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22StringTransformations::applyTrim()%22)
- [Closed issues mentioning `StringTransformations::applyTrim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22StringTransformations::applyTrim()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=StringTransformations%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
