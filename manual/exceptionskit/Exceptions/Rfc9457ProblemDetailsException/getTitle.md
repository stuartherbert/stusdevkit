# Rfc9457ProblemDetailsException::getTitle()

> `public getTitle(): string`

Returns the short, human-readable title of this problem.

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
     * return the `title` of this problem
     *
     * - short, human-readable summary of the problem type
     * - used as the exception message if `$detail` is empty
     */
    public function getTitle(): string {}
}
```

## Description

Returns the short, human-readable summary of the problem type. This is
used as the exception message (via `getMessage()`) when no detail was
provided.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:192`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L192)

## Changelog

_No tagged releases yet._

## See Also

- [`getStatus()`](./getStatus.md) — retrieve the HTTP status code
- [`getTypeAsString()`](./getTypeAsString.md) — retrieve the type URI

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::getTitle()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::getTitle()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::getTitle()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::getTitle()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
