# IsArrayKey

Type guard. Use this to determine if a value can be used as a PHP
array key.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:** _(none)_

**Implements:** _(none)_

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Arrays;

class IsArrayKey
{
    /**
     * Type guard. Can the given `$input` be used as an array key
     * in PHP?
     */
    public function __invoke(mixed $input): bool;

    /**
     * Type guard. Can the given `$input` be used as an array key
     * in PHP?
     */
    public static function check(mixed $input): bool;
}
```

## Description

`IsArrayKey` is a type guard. Type guards are a concept borrowed
from TypeScript — they help [PHPStan](https://phpstan.org/) understand
that a wider PHP type (e.g. `mixed`) is compatible with a narrow
pseudo-type (e.g. `array-key`).

The guard can be used in two ways:

- [`::check()`](check.md) — the static entry point. Use it inline
  when the call site already has a value in hand and does not
  need to allocate.
- [`->__invoke()`](__invoke.md) — the invokable entry point. Use
  it when the guard has to be stored in a `callable` slot, passed
  around as a value, or composed with higher-order helpers.

Both methods apply the same rule: a value is accepted only when
it is strictly an `int` or a `string`. See the Here Be Dragons
section below for why no other PHP type is accepted, even when
PHP itself would silently coerce it.

## Methods

**From `IsArrayKey`**

- [`->__invoke()`](__invoke.md) — type guard. Can the given `$input` be used as an array key in PHP?
- [`::check()`](check.md) — type guard. Can the given `$input` be used as an array key in PHP?

## Here Be Dragons

**IsArrayKey uses strict type checking. We don't support
type-coercion here.**

If the given `$input` is a type that can be coerced into being
an array-key, IsArrayKey will return `false`.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Arrays\IsArrayKey
 ✔ lives in the StusDevKit\MissingBitsKit\Arrays namespace
 ✔ is declared as a class
 ✔ exposes only __invoke() and check() as its public methods
 ✔ ::__construct() returns a new instance
```

## Source

[`kits/missingbitskit/src/Arrays/IsArrayKey.php:65`](../../../../kits/missingbitskit/src/Arrays/IsArrayKey.php#L65)

## Changelog

_No tagged releases yet._

## See Also

- [`RequireArrayKey`](../RequireArrayKey/README.md) — throwing-form
  sibling; rejects non-array-key inputs by raising an exception
  rather than returning `false`.

## Issues

- [Open issues mentioning `IsArrayKey`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IsArrayKey%22)
- [Closed issues mentioning `IsArrayKey`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IsArrayKey%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IsArrayKey%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
