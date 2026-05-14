# RequireArrayKey

Type guarantee. Use this to prove that a value can be used as a
PHP array key.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;

class RequireArrayKey
{
    /**
     * Type guarantee. Throws an exception if the given `$input`
     * isn't compatible with PHP's array-key pseudo type.
     */
    public function __invoke(mixed $input): void;

    /**
     * Type guarantee. Throws an exception if the given `$input`
     * isn't compatible with PHP's array-key pseudo type.
     */
    public static function check(mixed $input): void;
}
```

## Description

`RequireArrayKey` is a type guarantee. Type guarantees are type
guards that throw on failure, so that the caller does not have to
check the return value — control flow continues only when the
input passes the check.

We use type guarantees to ensure that a wider type (e.g. `mixed`)
is compatible with a narrower pseudo-type (e.g. `array-key`).

The guarantee can be used in two ways:

- [`::check()`](check.md) — the static entry point. Use it inline
  when the call site already has a value in hand and does not
  need to allocate.
- [`->__invoke()`](__invoke.md) — the invokable entry point. Use
  it when the guarantee has to be stored in a `callable` slot,
  passed around as a value, or composed with higher-order
  helpers.

Both methods apply the same rule and throw the same exception. A
value passes only when it is strictly an `int` or a `string`; any
other PHP type triggers an
[`InvalidArgumentException`](../../../exceptionskit/Exceptions/InvalidArgumentException/README.md)
with an RFC 9457-style structured `extra` payload (see
[`::check()`'s Return Values](check.md#return-values) for the
shape).

For the non-throwing counterpart that returns a `bool` instead of
throwing, use [`IsArrayKey`](../IsArrayKey/README.md).

## Methods

**From `RequireArrayKey`**

- [`->__invoke()`](__invoke.md) — type guarantee. Throws an exception if the given `$input` isn't compatible with PHP's array-key pseudo type.
- [`::check()`](check.md) — type guarantee. Throws an exception if the given `$input` isn't compatible with PHP's array-key pseudo type.

## Here Be Dragons

**RequireArrayKey uses strict type checking. We don't support
type-coercion here.**

If the given `$input` is a type that can be coerced into being
an array-key, RequireArrayKey will throw an exception.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\RequireArrayKey
 ✔ lives in the StusDevKit\MissingBitsKit\Arrays namespace
 ✔ is declared as a class
 ✔ exposes only __invoke() and check() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/Arrays/RequireArrayKey.php:71`](../../../../kits/missingbitskit/src/Arrays/RequireArrayKey.php#L71)

## Changelog

_No tagged releases yet._

## See Also

- [`IsArrayKey`](../IsArrayKey/README.md) — non-throwing sibling;
  returns `false` instead of raising an exception.
- [`InvalidArgumentException`](../../../exceptionskit/Exceptions/InvalidArgumentException/README.md) —
  the exception class thrown on rejection.

## Issues

- [Open issues mentioning `RequireArrayKey`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22RequireArrayKey%22)
- [Closed issues mentioning `RequireArrayKey`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22RequireArrayKey%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=RequireArrayKey%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
