# IsArrayKey::__invoke()

> `public function __invoke(mixed $input): bool`

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
    public function __invoke(mixed $input): bool
}
```

## Description

`->__invoke()` is the invokable entry point for the
[`IsArrayKey`](README.md) type guard. It exists so an `IsArrayKey`
instance can be stored in a `callable` slot, passed as a value to
higher-order helpers, or composed with other invokable guards.

The call is a thin shim over [`::check()`](check.md) — both
methods apply identical strict-type rules. Use this form when the
guard has to behave as a callable; use [`::check()`](check.md)
when the call site has a value in hand and does not need to
allocate.

The
[`@phpstan-assert-if-true array-key $input`](https://phpstan.org/writing-php-code/narrowing-types)
annotation lets [PHPStan](https://phpstan.org/) narrow `$input`
from `mixed` down to `array-key` on the `true` branch of the
caller's `if` statement.

## Parameters

**`$input`** (`mixed`)

The value to check. Any PHP value is accepted; the method's job
is to decide whether the value's runtime type qualifies as an
array key.

## Return Values

Returns `true` when `$input` is strictly an [`int`](https://www.php.net/manual/en/language.types.integer.php)
or a [`string`](https://www.php.net/manual/en/language.types.string.php),
and `false` otherwise. No type coercion is applied — see the Here
Be Dragons section on [`IsArrayKey`](README.md) for the cases this
catches.

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
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() declares $input as its only parameter
 ✔ ->__invoke() returns bool
 ✔ ->__invoke() returns true for a zero integer
 ✔ ->__invoke() returns true for a positive integer
 ✔ ->__invoke() returns true for a negative integer
 ✔ ->__invoke() returns true for PHP_INT_MAX
 ✔ ->__invoke() returns true for PHP_INT_MIN
 ✔ ->__invoke() returns true for an empty string
 ✔ ->__invoke() returns true for a non-empty string
 ✔ ->__invoke() returns true for a numeric string
 ✔ ->__invoke() returns true for a coercible "0"
 ✔ ->__invoke() returns false for null
 ✔ ->__invoke() returns false for a true boolean
 ✔ ->__invoke() returns false for a false boolean
 ✔ ->__invoke() returns false for a zero float
 ✔ ->__invoke() returns false for a positive float
 ✔ ->__invoke() returns false for a negative float
 ✔ ->__invoke() returns false for an empty array
 ✔ ->__invoke() returns false for a populated array
 ✔ ->__invoke() returns false for a stdClass instance
```

## Source

[`kits/missingbitskit/src/Arrays/IsArrayKey.php:79`](../../../../kits/missingbitskit/src/Arrays/IsArrayKey.php#L79)

## Changelog

_No tagged releases yet._

## See Also

- [`IsArrayKey::check()`](check.md) — the static entry point with
  identical behaviour; preferred at call sites that do not need
  a callable.
- [`RequireArrayKey`](../RequireArrayKey/README.md) — throwing-form
  sibling; rejects non-array-key inputs by raising an exception
  rather than returning `false`.

## Issues

- [Open issues mentioning `IsArrayKey::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsArrayKey%3A%3A__invoke%28%29%22)
- [Closed issues mentioning `IsArrayKey::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsArrayKey%3A%3A__invoke%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsArrayKey%3A%3A__invoke%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
