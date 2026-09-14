# StringTransformations::applyLtrim()

> `public function applyLtrim(string $characters = Str::TRIM_DEFAULT_CHARS): static`

Left-trims all strings in the collection using `Str::trim()`.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\CollectionsKit\Traits\StringTransformations`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Traits;

use StusDevKit\MissingBitsKit\Strings\Str;

trait StringTransformations
{
    /**
     * @param string $characters
     *      the set of characters to trim from the front of every
     *      string in the collection. Defaults to
     *      `Str::TRIM_DEFAULT_CHARS` (the Unicode White_Space set plus
     *      NUL).
     * @return static
     */
    public function applyLtrim(
        string $characters = Str::TRIM_DEFAULT_CHARS,
    ): static
}
```

## Description

Left-trims every string stored in the collection in place using
[`Str::trim()`](../../../missingbitskit/Strings/Str/trim.md) (from the
front only), so the full Unicode whitespace set (NBSP and friends) is
stripped by default, not just ASCII whitespace.

The collection's underlying array is rewritten via `array_map()`, so
each stored string is replaced by its left-trimmed counterpart. Keys
are preserved. The method returns `$this`, so calls can be chained
fluently.

## Parameters

**`$characters`** (`string`, optional, default: `Str::TRIM_DEFAULT_CHARS`)

The set of characters to trim from the front of every string in the
collection. Passed straight through to
[`Str::trim()`](../../../missingbitskit/Strings/Str/trim.md) as its
trim mask. Defaults to
[`Str::TRIM_DEFAULT_CHARS`](../../../missingbitskit/Strings/Str/README.md)
— the Unicode `White_Space` property plus NUL.

## Return Values

Returns `$this` — the same collection instance, with every stored
string replaced by its left-trimmed value. The return type is
`static`, so subclasses preserve their own type when chaining.

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
 ✔ ::applyLtrim() defaults the character mask to Str::TRIM_DEFAULT_CHARS
 ✔ ::applyLtrim() declares a `static` return type
 ✔ ->applyLtrim() strips default whitespace from the left of every value
 ✔ ->applyLtrim() strips Unicode whitespace such as NBSP from the left by default
 ✔ ->applyLtrim() strips the caller-supplied character mask
 ✔ ->applyLtrim() returns $this for fluent chaining
 ✔ ->applyLtrim() is a no-op on an empty collection
```

## Source

[`kits/collectionskit/src/Traits/StringTransformations.php:101`](../../../../kits/collectionskit/src/Traits/StringTransformations.php#L101)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::trim()`](../../../missingbitskit/Strings/Str/trim.md) — the UTF-8-safe trim this method delegates to
- [`StringTransformations::applyTrim()`](applyTrim.md) — trims both ends of all strings in the collection
- [`StringTransformations::applyRtrim()`](applyRtrim.md) — right-trims all strings in the collection

## Issues

- [Open issues mentioning `StringTransformations::applyLtrim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22StringTransformations::applyLtrim()%22)
- [Closed issues mentioning `StringTransformations::applyLtrim()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22StringTransformations::applyLtrim()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=StringTransformations%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
