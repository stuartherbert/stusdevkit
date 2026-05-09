# Rfc9457ProblemDetailsException::hasExtra()

> `public hasExtra(): bool`

Returns whether this problem report includes any extra data.

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
     * Does this problem report have any extra data available?
     */
    public function hasExtra(): bool {}
}
```

## Description

Returns `true` if the extra data array is non-empty, or `false`
otherwise.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:142`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L142)

## Changelog

_No tagged releases yet._

## See Also

- [`getExtra()`](./getExtra.md) — retrieve the extra data array
- [`hasDetail()`](./hasDetail.md) — check if detail is available

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::hasExtra()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::hasExtra()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::hasExtra()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::hasExtra()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
