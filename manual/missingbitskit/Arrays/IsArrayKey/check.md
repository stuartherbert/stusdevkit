# IsArrayKey::check()

> `public static function check(mixed $input): bool`

Type guard. Can the given `$input` be used as an array key in PHP?

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Arrays\IsArrayKey`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

class IsArrayKey
{
    /**
     * @phpstan-assert-if-true array-key $input
     *
     * @param mixed $input
     *      the type to check
     * @return bool
     * - `true` if `$input`'s type can be used as an array key
     * - `false` otherwise
     */
    public static function check(mixed $input): bool
}
```

## Description

`::check()` is the static entry point for the
[`IsArrayKey`](README.md) type guard. Use it inline whenever the
call site already has a value in hand and does not need to store
the guard as a callable — the static form avoids allocating an
`IsArrayKey` instance.

A value is accepted only when it is strictly an
[`int`](https://www.php.net/manual/en/language.types.integer.php)
or a [`string`](https://www.php.net/manual/en/language.types.string.php).
No type coercion is applied; see the Here Be Dragons section on
[`IsArrayKey`](README.md) for the cases this catches.

The
[`@phpstan-assert-if-true array-key $input`](https://phpstan.org/writing-php-code/narrowing-types)
annotation lets [PHPStan](https://phpstan.org/) narrow `$input`
from `mixed` down to `array-key` on the `true` branch of the
caller's `if` statement — the static call is what lets PHPStan
apply the narrowing without object dereferencing.

When the same guard must travel as a value (stored in a `callable`
slot, passed to a higher-order helper), use
[`->__invoke()`](__invoke.md) instead.

## Parameters

**`$input`** (`mixed`)

The value to check. Any PHP value is accepted; the method's job
is to decide whether the value's runtime type qualifies as an
array key.

## Return Values

Returns `true` when `$input` is strictly an `int` or a `string`,
and `false` otherwise. No type coercion is applied.

## Errors/Exceptions

_None._

## Here Be Dragons

See [Here Be Dragons on `IsArrayKey`](README.md#here-be-dragons) —
the strict-vs-coerce rule applies identically to both entry
points.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\IsArrayKey
 ✔ ::check() is declared public, static
 ✔ ::check() declares $input as its only parameter
 ✔ ::check() returns bool
 ✔ ::check() returns true for a zero integer
 ✔ ::check() returns true for a positive integer
 ✔ ::check() returns true for a negative integer
 ✔ ::check() returns true for PHP_INT_MAX
 ✔ ::check() returns true for PHP_INT_MIN
 ✔ ::check() returns true for an empty string
 ✔ ::check() returns true for a non-empty string
 ✔ ::check() returns true for a numeric string
 ✔ ::check() returns true for a coercible "0"
 ✔ ::check() returns false for null
 ✔ ::check() returns false for a true boolean
 ✔ ::check() returns false for a false boolean
 ✔ ::check() returns false for a zero float
 ✔ ::check() returns false for a positive float
 ✔ ::check() returns false for a negative float
 ✔ ::check() returns false for an empty array
 ✔ ::check() returns false for a populated array
 ✔ ::check() returns false for a stdClass instance
```

## Source

[`kits/missingbitskit/src/Arrays/IsArrayKey.php:96`](../../../../kits/missingbitskit/src/Arrays/IsArrayKey.php#L96)

## Changelog

_No tagged releases yet._

## See Also

- [`IsArrayKey::__invoke()`](__invoke.md) — the invokable entry
  point with identical behaviour; use it when the guard needs
  to travel as a callable.
- [`RequireArrayKey`](../RequireArrayKey/README.md) — throwing-form
  sibling; rejects non-array-key inputs by raising an exception
  rather than returning `false`.

## Issues

- [Open issues mentioning `IsArrayKey::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsArrayKey%3A%3Acheck%28%29%22)
- [Closed issues mentioning `IsArrayKey::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsArrayKey%3A%3Acheck%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsArrayKey%3A%3Acheck%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
