# NullValueNotAllowedException::__construct()

> `public function __construct(string $detail)`

Build a `NullValueNotAllowedException` carrying the caller-supplied detail string.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class NullValueNotAllowedException
{
    /**
     * @param string $detail
     *     a message explaining where a null value was encountered
     */
    public function __construct(
        string $detail,
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus a `$detail` string supplied by the caller. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/null-value-not-allowed` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Content)
- **title**: `"Null value not allowed"`
- **detail**: `$detail`

The narrower API surface is the point: callers don't have to remember the type URI, the status code, or the title every time they want to raise "a null landed where none is allowed". Drop in a sentence about *where* the null was encountered, and the exception fills in the rest.

```php
throw new NullValueNotAllowedException(
    detail: 'ListOfStrings does not accept null values',
);
```

Unlike its sibling [`InvalidArgumentException`](../InvalidArgumentException/README.md), this constructor takes no `$extra` — the detail string is the entire caller-supplied payload.

## Parameters

**`$detail`** (`string`)

A message explaining where a null value was encountered. Surfaces from [`Rfc9457ProblemDetailsException::maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) and from the built-in `Exception::getMessage()` thereafter (the parent forwards `$detail` into both slots).

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
StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $detail as its only parameter
 ✔ ::__construct() declares $detail as string
 ✔ ::__construct() accepts a detail string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title "Null value not allowed"
 ✔ ->maybeGetDetail() returns the detail string passed into the constructor
 ✔ ->getMessage() returns the detail string passed into the constructor
```

## Source

[`kits/exceptionskit/src/Exceptions/NullValueNotAllowedException.php:60`](../../../../kits/exceptionskit/src/Exceptions/NullValueNotAllowedException.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) — inherited accessor that surfaces `$detail` back to the caller
- [`InvalidArgumentException::__construct()`](../InvalidArgumentException/__construct.md) — sibling detail-shaped constructor that also accepts an optional `$extra` slot

## Issues

- [Open issues mentioning `NullValueNotAllowedException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NullValueNotAllowedException::__construct()%22)
- [Closed issues mentioning `NullValueNotAllowedException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NullValueNotAllowedException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NullValueNotAllowedException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
