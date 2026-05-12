# InvalidArgumentException::__construct()

> `public function __construct(string $detail, array $extra = [])`

Build an `InvalidArgumentException` carrying the caller-supplied detail string and (optionally) supporting data.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class InvalidArgumentException
{
    /**
     * @param string $detail
     *     a message explaining which argument is invalid, and why
     * @param ProblemReportExtra $extra
     *     any supporting data
     */
    public function __construct(
        string $detail,
        array $extra = [],
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus a `$detail` string supplied by the caller (and, optionally, a structured `$extra` array of supporting data). The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/invalid-argument` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Entity)
- **title**: `"Invalid argument"`
- **detail**: `$detail`
- **extra**: `$extra`

The narrower API surface is the point: callers don't have to remember the type URI, the status code, or the title every time they want to raise "the argument is wrong". Drop in a sentence about *why* the argument is wrong, and the exception fills in the rest.

```php
throw new InvalidArgumentException(
    detail: '$name must be a non-empty string',
    extra: [
        'function' => __FUNCTION__,
        'param_name' => '$name',
        'reason' => 'must be a non-empty string',
    ],
);
```

## Parameters

**`$detail`** (`string`)

A message explaining which argument is invalid, and why. Surfaces from [`Rfc9457ProblemDetailsException::maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) and from `getMessage()` thereafter.

**`$extra`** (`ProblemReportExtra`, optional, default: `[]`)

Any supporting data. The `ProblemReportExtra` shape is the recursive map type imported from [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) (`array<string, int|string|array<string,...>>`). Surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and contributes to [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md).

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

_None._

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares its parameters in the expected order
 ✔ ::__construct() declares $detail as string
 ✔ ::__construct() declares $extra as array
 ✔ ::__construct() accepts a detail string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title "Invalid argument"
 ✔ ->maybeGetDetail() returns the detail string passed into the constructor
 ✔ ->getMessage() returns the detail string passed into the constructor
 ✔ ->hasExtra() returns false because no extra data is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/exceptionskit/src/Exceptions/InvalidArgumentException.php:73`](../../../../kits/exceptionskit/src/Exceptions/InvalidArgumentException.php#L73)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) — inherited accessor that surfaces `$detail` back to the caller

## Issues

- [Open issues mentioning `InvalidArgumentException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22InvalidArgumentException::__construct()%22)
- [Closed issues mentioning `InvalidArgumentException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22InvalidArgumentException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=InvalidArgumentException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
