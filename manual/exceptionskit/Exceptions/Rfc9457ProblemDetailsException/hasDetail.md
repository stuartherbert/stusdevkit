# Rfc9457ProblemDetailsException::hasDetail()

> `public hasDetail(): bool`

Returns whether this problem report includes any further details.

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
     * @phpstan-assert-if-true non-empty-string $this->maybeGetDetail()
     */
    public function hasDetail(): bool {}
}
```

## Description

Returns `true` if a detail string was provided at construction time,
or `false` otherwise.

This is a type-narrowing predicate: when it returns `true`,
[`maybeGetDetail()`](./maybeGetDetail.md) is guaranteed to return a
non-empty string.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:123`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L123)

## Changelog

_No tagged releases yet._

## See Also

- [`maybeGetDetail()`](./maybeGetDetail.md) — retrieve the detail if set
- [`hasExtra()`](./hasExtra.md) — check if extra data is available

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::hasDetail()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::hasDetail()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::hasDetail()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::hasDetail()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
