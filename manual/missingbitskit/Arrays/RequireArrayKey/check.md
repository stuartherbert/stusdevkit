# RequireArrayKey::check()

> `public static function check(mixed $input): void`

Type guarantee. Throws an exception if the given `$input` isn't
compatible with PHP's array-key pseudo type.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\MissingBitsKit\Arrays\RequireArrayKey`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;

class RequireArrayKey
{
    /**
     * @phpstan-assert array-key $input
     *
     * @param mixed $input
     *     the value to type-check
     * @throws InvalidArgumentException
     */
    public static function check(mixed $input): void
}
```

## Description

`::check()` is the static entry point for the
[`RequireArrayKey`](README.md) type guarantee. Use it inline
whenever the call site already has a value in hand and does not
need to store the guarantee as a callable — the static form
avoids allocating a `RequireArrayKey` instance.

A value passes the check only when it is strictly an
[`int`](https://www.php.net/manual/en/language.types.integer.php)
or a [`string`](https://www.php.net/manual/en/language.types.string.php).
Any other PHP type triggers an
[`InvalidArgumentException`](../../../exceptionskit/Exceptions/InvalidArgumentException/README.md);
no type coercion is applied. See the Here Be Dragons section on
[`RequireArrayKey`](README.md) for the cases this catches.

The
[`@phpstan-assert array-key $input`](https://phpstan.org/writing-php-code/narrowing-types)
annotation lets [PHPStan](https://phpstan.org/) narrow `$input`
from `mixed` down to `array-key` on every line of the caller's
code that runs after the call returns — because the only way past
the call without an exception is for the assertion to hold.

When the same guarantee must travel as a value (stored in a
`callable` slot, passed to a higher-order helper), use
[`->__invoke()`](__invoke.md) instead.

## Parameters

**`$input`** (`mixed`)

The value to type-check. Any PHP value is accepted; the method's
job is to either return silently (when the value's runtime type
qualifies as an array key) or throw.

## Return Values

Returns nothing — declared `void`. A silent return is the success
signal; the only other outcome is a thrown exception.

## Errors/Exceptions

- **[`InvalidArgumentException`](../../../exceptionskit/Exceptions/InvalidArgumentException/README.md)** —
  when `$input` is not strictly a PHP `int` or `string`. The
  thrown exception carries the message `input is not a supported
  PHP array-key`, and an RFC 9457-style `extra` payload of the
  form:

  ```php
  [
      'expected_type' => 'array-key',
      'actual_type'   => '<get_debug_type($input)>',
  ]
  ```

  The `actual_type` value is whatever
  [`get_debug_type()`](https://www.php.net/manual/en/function.get-debug-type.php)
  returns for the rejected input — e.g. `'null'`, `'bool'`,
  `'float'`, `'array'`, or the fully-qualified class name for
  objects.

## Here Be Dragons

See [Here Be Dragons on `RequireArrayKey`](README.md#here-be-dragons) —
the strict-vs-coerce rule applies identically to both entry
points.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\RequireArrayKey
 ✔ ::check() is declared public, static
 ✔ ::check() declares $input as its only parameter
 ✔ ::check() returns void
 ✔ ::check() returns silently for a zero integer
 ✔ ::check() returns silently for a positive integer
 ✔ ::check() returns silently for a negative integer
 ✔ ::check() returns silently for PHP_INT_MAX
 ✔ ::check() returns silently for PHP_INT_MIN
 ✔ ::check() returns silently for an empty string
 ✔ ::check() returns silently for a non-empty string
 ✔ ::check() returns silently for a numeric string
 ✔ ::check() returns silently for a coercible "0"
 ✔ ::check() throws InvalidArgumentException for null
 ✔ ::check() throws InvalidArgumentException for a true boolean
 ✔ ::check() throws InvalidArgumentException for a false boolean
 ✔ ::check() throws InvalidArgumentException for a zero float
 ✔ ::check() throws InvalidArgumentException for a positive float
 ✔ ::check() throws InvalidArgumentException for a negative float
 ✔ ::check() throws InvalidArgumentException for an empty array
 ✔ ::check() throws InvalidArgumentException for a populated array
 ✔ ::check() throws InvalidArgumentException for a stdClass instance
 ✔ ::check() records actual type "null" in the exception extra for null
 ✔ ::check() records actual type "bool" in the exception extra for a true boolean
 ✔ ::check() records actual type "bool" in the exception extra for a false boolean
 ✔ ::check() records actual type "float" in the exception extra for a zero float
 ✔ ::check() records actual type "float" in the exception extra for a positive float
 ✔ ::check() records actual type "float" in the exception extra for a negative float
 ✔ ::check() records actual type "array" in the exception extra for an empty array
 ✔ ::check() records actual type "array" in the exception extra for a populated array
 ✔ ::check() records actual type "stdClass" in the exception extra for a stdClass instance
```

## Source

[`kits/missingbitskit/src/Arrays/RequireArrayKey.php:98`](../../../../kits/missingbitskit/src/Arrays/RequireArrayKey.php#L98)

## Changelog

_No tagged releases yet._

## See Also

- [`RequireArrayKey::__invoke()`](__invoke.md) — the invokable
  entry point with identical behaviour; use it when the guarantee
  needs to travel as a callable.
- [`IsArrayKey::check()`](../IsArrayKey/check.md) — non-throwing
  sibling; returns `false` instead of raising an exception.

## Issues

- [Open issues mentioning `RequireArrayKey::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22RequireArrayKey%3A%3Acheck%28%29%22)
- [Closed issues mentioning `RequireArrayKey::check()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22RequireArrayKey%3A%3Acheck%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=RequireArrayKey%3A%3Acheck%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
