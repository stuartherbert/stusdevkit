# Str::identity()

> `public static identity(string $input): string`

Returns its input unchanged — the no-op transformer.

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
     *      the string to return unchanged
     * @return string
     *      `$input`, exactly as given
     */
    public static function identity(string $input): string
}
```

## Description

`identity()` returns `$input` unchanged.

It is the no-op transformer. Pass `Str::identity(...)` to any Str
method's `$transformer` parameter — such as
[`Str::isEmpty()`](isEmpty.md) or [`Str::contains()`](contains.md) —
when you want to spell out "leave the input unchanged" explicitly,
instead of relying on the `null` default. See the [`Str`](README.md)
class's Transformers tutorial for how the `$transformer` parameter
works.

Originally added so that "no transformation" can be an explicit,
greppable choice at the call site — useful for readers and for
automated audits of how Str is being called.

## Parameters

**`$input`** (`string`)

The string to return unchanged.

## Return Values

Returns the `string` passed as `$input`, exactly as given.

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Strings\Str
 ✔ publishes ::identity() as a public method
 ✔ ::identity() is declared public static
 ✔ ::identity() takes parameters [input]
 ✔ ::identity() returns string
 ✔ returns its input unchanged
 ✔ returns an empty string unchanged
 ✔ returns a multibyte string unchanged
 ✔ used as an isEmpty transformer, leaves a whitespace string non-empty
```

## Source

[`kits/missingbitskit/src/Strings/Str.php:298`](../../../../kits/missingbitskit/src/Strings/Str.php#L298)

## Changelog

_No tagged releases yet._

## See Also

- [`Str::isEmpty()`](isEmpty.md) — a query method that accepts `identity()` as its transformer
- [`Str::contains()`](contains.md) — a query method that accepts `identity()` as its transformer
- [`Str`](README.md) — the class-level Transformers tutorial explaining the no-op transformer

## Issues

- [Open issues mentioning `Str::identity()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Str%3A%3Aidentity()%22)
- [Closed issues mentioning `Str::identity()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Str%3A%3Aidentity()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Str%3A%3Aidentity()%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
