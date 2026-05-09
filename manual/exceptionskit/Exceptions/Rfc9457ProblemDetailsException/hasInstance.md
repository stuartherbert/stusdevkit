# Rfc9457ProblemDetailsException::hasInstance()

> `public hasInstance(): bool`

Returns whether this problem report includes an instance URI.

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
     * @phpstan-assert-if-true non-empty-string $this->maybeGetInstanceAsString()
     */
    public function hasInstance(): bool {}
}
```

## Description

Returns `true` if an instance URI was provided at construction time,
or `false` otherwise.

This is a type-narrowing predicate: when it returns `true`,
[`maybeGetInstanceAsString()`](./maybeGetInstanceAsString.md) is guaranteed
to return a non-empty string.

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:160`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L160)

## Changelog

_No tagged releases yet._

## See Also

- [`maybeGetInstanceAsString()`](./maybeGetInstanceAsString.md) — retrieve the instance URI
- [`hasExtra()`](./hasExtra.md) — check if extra data is available

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException::hasInstance()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException::hasInstance()%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException::hasInstance()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException::hasInstance()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
