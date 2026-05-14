# RequireArrayKey::__invoke()

> `public function __invoke(mixed $input): void`

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
    public function __invoke(mixed $input): void
}
```

## Description

`->__invoke()` is the invokable entry point for the
[`RequireArrayKey`](README.md) type guarantee. It exists so a
`RequireArrayKey` instance can be stored in a `callable` slot,
passed as a value to higher-order helpers, or composed with other
invokable guarantees.

The call is a thin shim over [`::check()`](check.md) — both
methods apply identical strict-type rules and throw the same
exception with the same message and structured detail. Use this
form when the guarantee has to behave as a callable; use
[`::check()`](check.md) when the call site has a value in hand and
does not need to allocate.

The
[`@phpstan-assert array-key $input`](https://phpstan.org/writing-php-code/narrowing-types)
annotation lets [PHPStan](https://phpstan.org/) narrow `$input`
from `mixed` down to `array-key` on every line of the caller's
code that runs after the call returns — because the only way past
the call without an exception is for the assertion to hold.

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

## Here Be Dragons

See [Here Be Dragons on `RequireArrayKey`](README.md#here-be-dragons) —
the strict-vs-coerce rule applies identically to both entry
points.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\RequireArrayKey
 ✔ ->__invoke() is declared public, non-static
 ✔ ->__invoke() declares $input as its only parameter
 ✔ ->__invoke() returns void
 ✔ ->__invoke() returns silently for a zero integer
 ✔ ->__invoke() returns silently for a positive integer
 ✔ ->__invoke() returns silently for a negative integer
 ✔ ->__invoke() returns silently for PHP_INT_MAX
 ✔ ->__invoke() returns silently for PHP_INT_MIN
 ✔ ->__invoke() returns silently for an empty string
 ✔ ->__invoke() returns silently for a non-empty string
 ✔ ->__invoke() returns silently for a numeric string
 ✔ ->__invoke() returns silently for a coercible "0"
 ✔ ->__invoke() throws InvalidArgumentException for null
 ✔ ->__invoke() throws InvalidArgumentException for a true boolean
 ✔ ->__invoke() throws InvalidArgumentException for a false boolean
 ✔ ->__invoke() throws InvalidArgumentException for a zero float
 ✔ ->__invoke() throws InvalidArgumentException for a positive float
 ✔ ->__invoke() throws InvalidArgumentException for a negative float
 ✔ ->__invoke() throws InvalidArgumentException for an empty array
 ✔ ->__invoke() throws InvalidArgumentException for a populated array
 ✔ ->__invoke() throws InvalidArgumentException for a stdClass instance
 ✔ ->__invoke() records actual type "null" in the exception extra for null
 ✔ ->__invoke() records actual type "bool" in the exception extra for a true boolean
 ✔ ->__invoke() records actual type "bool" in the exception extra for a false boolean
 ✔ ->__invoke() records actual type "float" in the exception extra for a zero float
 ✔ ->__invoke() records actual type "float" in the exception extra for a positive float
 ✔ ->__invoke() records actual type "float" in the exception extra for a negative float
 ✔ ->__invoke() records actual type "array" in the exception extra for an empty array
 ✔ ->__invoke() records actual type "array" in the exception extra for a populated array
 ✔ ->__invoke() records actual type "stdClass" in the exception extra for a stdClass instance
```

## Source

[`kits/missingbitskit/src/Arrays/RequireArrayKey.php:83`](../../../../kits/missingbitskit/src/Arrays/RequireArrayKey.php#L83)

## Changelog

_No tagged releases yet._

## See Also

- [`RequireArrayKey::check()`](check.md) — the static entry point
  with identical behaviour; preferred at call sites that do not
  need a callable.
- [`IsArrayKey::__invoke()`](../IsArrayKey/__invoke.md) —
  non-throwing sibling; returns `false` instead of raising an
  exception.

## Issues

- [Open issues mentioning `RequireArrayKey::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22RequireArrayKey%3A%3A__invoke%28%29%22)
- [Closed issues mentioning `RequireArrayKey::__invoke()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22RequireArrayKey%3A%3A__invoke%28%29%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=RequireArrayKey%3A%3A__invoke%28%29%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
