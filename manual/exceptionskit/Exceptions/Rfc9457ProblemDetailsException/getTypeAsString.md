# Rfc9457ProblemDetailsException::getTypeAsString()

> `public getTypeAsString(): string`

Returns the type URI as a string.

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
     * return the `type` of problem as a string
     *
     * - URI to a page that documents this class of problem
     * - for example, link to a Github wiki page explaining the error
     */
    public function getTypeAsString(): string {}
}
```

## Description

Returns the type URI as a string — a pointer to a page that documents
this class of problem (for example, a link to a GitHub wiki page
explaining the error).

**FORWARDS-COMPATIBILITY NOTE:** A future release will return a `Uri`
instance instead of a string, once PHP 8.5's `Uri` extension is
documented and IDE-supported.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:203`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L203)

## Changelog

_No tagged releases yet._

## See Also

- [`getTitle()`](./getTitle.md) — retrieve the problem title
- [`getStatus()`](./getStatus.md) — retrieve the HTTP status code

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::getTypeAsString()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::getTypeAsString()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::getTypeAsString()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::getTypeAsString()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
