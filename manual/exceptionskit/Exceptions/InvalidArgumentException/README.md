# InvalidArgumentException

Thrown when a method or function receives an argument that is not valid for its intended purpose.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:**

- [`Exception`](https://www.php.net/manual/en/class.exception.php) (PHP built-in)
- [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md)

**Implements:**

- [`JsonSerializable`](https://www.php.net/manual/en/class.jsonserializable.php) (PHP built-in, via [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md))

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

/**
 * @phpstan-import-type ProblemReportExtra from Rfc9457ProblemDetailsException
 */
class InvalidArgumentException extends Rfc9457ProblemDetailsException
{
    // --- Exception (PHP built-in) ---

    public function getMessage(): string;
    public function getCode(): int;
    public function getFile(): string;
    public function getLine(): int;
    public function getTrace(): array;
    public function getTraceAsString(): string;
    public function getPrevious(): ?Throwable;
    public function __toString(): string;

    // --- Rfc9457ProblemDetailsException ---

    /**
     * Returns whether detail is set.
     */
    public function hasDetail(): bool;

    /**
     * Returns the detail if set, otherwise null.
     */
    public function maybeGetDetail(): ?string;

    /**
     * Returns whether extra data is set.
     */
    public function hasExtra(): bool;

    /**
     * Returns the extra data array.
     */
    public function getExtra(): array;

    /**
     * Returns whether instance URI is set.
     */
    public function hasInstance(): bool;

    /**
     * Returns instance URI as string, or null if unset.
     */
    public function maybeGetInstanceAsString(): ?string;

    /**
     * Returns the HTTP status code.
     */
    public function getStatus(): int;

    /**
     * Returns the problem title.
     */
    public function getTitle(): string;

    /**
     * Returns the type URI.
     */
    public function getTypeAsString(): string;

    /**
     * Serialises to array following RFC 9457 shape.
     */
    public function jsonSerialize(): array;

    // --- InvalidArgumentException ---

    /**
     * Build an InvalidArgumentException carrying the caller-supplied detail string and (optionally) supporting data.
     */
    public function __construct(
        string $detail,
        array $extra = [],
    );
}
```

## Description

`InvalidArgumentException` is a fixed-shape problem-details exception for the "the caller passed an argument that wasn't valid" case.

It hard-codes three of the RFC 9457 fields its parent normally accepts:

- **type** — `https://example.com/errors/invalid-argument` _(placeholder — see [Here Be Dragons](#here-be-dragons))_
- **status** — `422` (Unprocessable Entity)
- **title** — `"Invalid argument"`

The caller supplies `$detail` (a message explaining which argument is invalid, and why) and optionally `$extra` (any supporting data, conforming to the `ProblemReportExtra` shape imported from the parent class). The rest of the [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) accessor surface (`getStatus()`, `getTitle()`, `getTypeAsString()`, `jsonSerialize()`, etc.) is inherited unchanged.

`InvalidArgumentException` is intended as a structured replacement for PHP's built-in `\InvalidArgumentException`.

## Methods

**From Exception (PHP built-in)**

- [`->getMessage()`](https://www.php.net/manual/en/exception.getMessage) — returns the exception message (falls back to `$title` when `$detail` is null)
- [`->getCode()`](https://www.php.net/manual/en/exception.getCode) — returns the exception code
- [`->getFile()`](https://www.php.net/manual/en/exception.getFile) — returns the file where it was thrown
- [`->getLine()`](https://www.php.net/manual/en/exception.getLine) — returns the line number
- [`->getTrace()`](https://www.php.net/manual/en/exception.getTrace) — returns the stack trace
- [`->getTraceAsString()`](https://www.php.net/manual/en/exception.getTraceAsString) — returns the formatted stack trace
- [`->getPrevious()`](https://www.php.net/manual/en/exception.getPrevious) — returns the previous exception
- [`->__toString()`](https://www.php.net/manual/en/exception.toString) — returns string representation

**From Rfc9457ProblemDetailsException**

- [`->getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — returns the extra data array
- [`->getStatus()`](../Rfc9457ProblemDetailsException/getStatus.md) — returns the HTTP status code (always `422` for this class)
- [`->getTitle()`](../Rfc9457ProblemDetailsException/getTitle.md) — returns the problem title (always `"Invalid argument"` for this class)
- [`->getTypeAsString()`](../Rfc9457ProblemDetailsException/getTypeAsString.md) — returns the type URI
- [`->hasDetail()`](../Rfc9457ProblemDetailsException/hasDetail.md) — returns whether detail is set
- [`->hasExtra()`](../Rfc9457ProblemDetailsException/hasExtra.md) — returns whether extra data is set
- [`->hasInstance()`](../Rfc9457ProblemDetailsException/hasInstance.md) — returns whether instance URI is set
- [`->jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md) — serialises to array following RFC 9457 shape
- [`->maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) — returns the detail if set, otherwise `null`
- [`->maybeGetInstanceAsString()`](../Rfc9457ProblemDetailsException/maybeGetInstanceAsString.md) — returns the instance URI as string, or `null` if unset

**From InvalidArgumentException**

- [`->__construct()`](__construct.md) — build an `InvalidArgumentException` carrying the caller-supplied detail string and (optionally) supporting data

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException
 ✔ lives in the StusDevKit\ExceptionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ declares no additional public methods beyond its parent
```

## Source

[`kits/exceptionskit/src/Exceptions/InvalidArgumentException.php:65`](../../../../kits/exceptionskit/src/Exceptions/InvalidArgumentException.php#L65)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) — base class providing the RFC 9457 problem-details accessors

## Issues

- [Open issues mentioning `InvalidArgumentException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22InvalidArgumentException%22)
- [Closed issues mentioning `InvalidArgumentException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22InvalidArgumentException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=InvalidArgumentException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
