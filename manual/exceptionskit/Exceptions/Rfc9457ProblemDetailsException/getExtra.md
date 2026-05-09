# Rfc9457ProblemDetailsException::getExtra()

> `public getExtra(): array`

Returns the extra data array containing additional problem information.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

/**
 * @phpstan-type ProblemReportExtraLeaf int|string|array<string,int|string>
 * @phpstan-type ProblemReportExtraNode int|string|array<string, ProblemReportExtraLeaf>
 * @phpstan-type ProblemReportExtra array<string, int|string|array<string,ProblemReportExtraNode>>
 */
class Rfc9457ProblemDetailsException
{
    /**
     * @return ProblemReportExtra
     */
    public function getExtra(): array {}
}
```

## Description

Returns the extra data array — an array of additional information
specific to this occurrence of the problem. Think of these as
additional fields to add to your application logs.

The return type is `ProblemReportExtra`, a recursive structure:
each value can be an `int`, a `string`, or a nested array of the
same shape.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:148`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L148)

## Changelog

_No tagged releases yet._

## See Also

- [`hasExtra()`](./hasExtra.md) — check if extra data is available
- [`maybeGetDetail()`](./maybeGetDetail.md) — retrieve detail if set

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::getExtra()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::getExtra()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::getExtra()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::getExtra()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
