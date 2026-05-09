# Rfc9457ProblemDetailsException::jsonSerialize()

> `public jsonSerialize(): array`

Serialises the problem report to an array following RFC 9457 shape.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException`](README.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

use JsonSerializable;

class Rfc9457ProblemDetailsException implements JsonSerializable
{
    /**
     * You can manually pass the results through `array_filter()` to
     * strip-out empty parts of the problem details.
     *
     * @return array{
     *     type: string,
     *     title: string,
     *     status: int,
     *     instance: string|null,
     *     detail: string|null,
     *     extra: ProblemReportExtra,
     * }
     */
    public function jsonSerialize(): array {}
}
```

## Description

Implements `JsonSerializable` by returning a structured array following
the RFC 9457 problem report shape. The returned array contains:

- `type` — the type URI (string)
- `title` — the problem title (string)
- `status` — the HTTP status code (int)
- `instance` — the instance URI, or null if not set
- `detail` — the detail text, or null if not set
- `extra` — additional data (ProblemReportExtra)

You can manually pass the results through `array_filter()` to strip-out
empty parts of the problem details.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:213`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L213)

## Changelog

_No tagged releases yet._

## See Also

- [`getExtra()`](./getExtra.md) — retrieve extra data array
- [`maybeGetDetail()`](./maybeGetDetail.md) — retrieve detail if set

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::jsonSerialize()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::jsonSerialize()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::jsonSerialize()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::jsonSerialize()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
