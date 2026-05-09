# Rfc9457ProblemDetailsException::maybeGetDetail()

> `public maybeGetDetail(): ?string`

Returns the detail string if one was provided, or `null` otherwise.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class Rfc9457ProblemDetailsException
{
    /**
     * Return the `detail` of this problem (if any detail was provided)
     *
     * - human-readable explanation specific to this occurrence of the problem
     * - used as the exception message unless empty
     */
    public function maybeGetDetail(): ?string {}
}
```

## Description

Returns the human-readable explanation specific to this occurrence of
the problem, if one was provided at construction time. Returns `null`
if no detail was set.

This method is the counterpart to [`hasDetail()`](./hasDetail.md):
when `hasDetail()` returns `true`, this method is guaranteed to return
a non-empty string.

The detail (when present) is also used as the exception message via
`getMessage()`.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:132`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L132)

## Changelog

_No tagged releases yet._

## See Also

- [`hasDetail()`](./hasDetail.md) — check if detail was provided
- [`getExtra()`](./getExtra.md) — retrieve extra data array

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::maybeGetDetail()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::maybeGetDetail()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::maybeGetDetail()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::maybeGetDetail()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
