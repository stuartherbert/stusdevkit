# Rfc9457ProblemDetailsException::getStatus()

> `public getStatus(): int`

Returns the HTTP status code describing this problem.

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
     * return the `status` of this problem
     *
     * - HTTP status code that describes the nature of this problem
     */
    public function getStatus(): int {}
}
```

## Description

Returns the HTTP status code that describes the nature of this problem.
This is typically a 4xx or 5xx code, though other HTTP status codes
are allowed.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:183`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L183)

## Changelog

_No tagged releases yet._

## See Also

- [`getTitle()`](./getTitle.md) — retrieve the problem title
- [`getTypeAsString()`](./getTypeAsString.md) — retrieve the type URI

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::getStatus()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::getStatus()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::getStatus()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::getStatus()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
