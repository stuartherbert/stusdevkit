# NullValueNotAllowedException

Thrown when a null value is encountered where null values are not permitted.

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

class NullValueNotAllowedException extends Rfc9457ProblemDetailsException
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

    // --- NullValueNotAllowedException ---

    /**
     * Build a NullValueNotAllowedException carrying the caller-supplied detail string.
     */
    public function __construct(
        string $detail,
    );
}
```

## Description

`NullValueNotAllowedException` is a fixed-shape problem-details exception for the "a null value was encountered where none is permitted" case.

It hard-codes three of the RFC 9457 fields its parent normally accepts:

- **type** — `https://example.com/errors/null-value-not-allowed` _(placeholder — see [Here Be Dragons](#here-be-dragons))_
- **status** — `422` (Unprocessable Content)
- **title** — `"Null value not allowed"`

The caller supplies `$detail` (a message explaining where the null was encountered). The constructor forwards the detail to the parent so it surfaces from both [`Rfc9457ProblemDetailsException::maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) and the built-in `Exception::getMessage()`. The rest of the [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) accessor surface (`getStatus()`, `getTitle()`, `getTypeAsString()`, `jsonSerialize()`, etc.) is inherited unchanged.

Unlike its sibling [`InvalidArgumentException`](../InvalidArgumentException/README.md), this constructor takes no `$extra` — the detail string is the entire caller-supplied payload, so `getExtra()` always reports empty for instances of this class.

## Methods

**From Exception (PHP built-in)**

- [`->getMessage()`](https://www.php.net/manual/en/exception.getMessage) — returns the exception message (set to `$detail` for this class)
- [`->getCode()`](https://www.php.net/manual/en/exception.getCode) — returns the exception code
- [`->getFile()`](https://www.php.net/manual/en/exception.getFile) — returns the file where it was thrown
- [`->getLine()`](https://www.php.net/manual/en/exception.getLine) — returns the line number
- [`->getTrace()`](https://www.php.net/manual/en/exception.getTrace) — returns the stack trace
- [`->getTraceAsString()`](https://www.php.net/manual/en/exception.getTraceAsString) — returns the formatted stack trace
- [`->getPrevious()`](https://www.php.net/manual/en/exception.getPrevious) — returns the previous exception
- [`->__toString()`](https://www.php.net/manual/en/exception.toString) — returns string representation

**From Rfc9457ProblemDetailsException**

- [`->getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — returns the extra data array (always empty for this class)
- [`->getStatus()`](../Rfc9457ProblemDetailsException/getStatus.md) — returns the HTTP status code (always `422` for this class)
- [`->getTitle()`](../Rfc9457ProblemDetailsException/getTitle.md) — returns the problem title (always `"Null value not allowed"` for this class)
- [`->getTypeAsString()`](../Rfc9457ProblemDetailsException/getTypeAsString.md) — returns the type URI
- [`->hasDetail()`](../Rfc9457ProblemDetailsException/hasDetail.md) — returns whether detail is set (always `true` for this class — `$detail` is required)
- [`->hasExtra()`](../Rfc9457ProblemDetailsException/hasExtra.md) — returns whether extra data is set (always `false` for this class)
- [`->hasInstance()`](../Rfc9457ProblemDetailsException/hasInstance.md) — returns whether instance URI is set
- [`->jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md) — serialises to array following RFC 9457 shape
- [`->maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) — returns the detail if set, otherwise `null` (always the constructor's `$detail` for this class)
- [`->maybeGetInstanceAsString()`](../Rfc9457ProblemDetailsException/maybeGetInstanceAsString.md) — returns the instance URI as string, or `null` if unset

**From NullValueNotAllowedException**

- [`->__construct()`](__construct.md) — build a `NullValueNotAllowedException` carrying the caller-supplied detail string

## Here Be Dragons

**The `type` URI is a placeholder.** `https://example.com/errors/null-value-not-allowed` is not yet a real documentation URL — the docs target hasn't been set up. Treat it as a stable identifier, not a navigable link.

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\NullValueNotAllowedException
 ✔ lives in the StusDevKit\ExceptionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ declares no additional public methods beyond its parent
```

## Source

[`kits/exceptionskit/src/Exceptions/NullValueNotAllowedException.php:54`](../../../../kits/exceptionskit/src/Exceptions/NullValueNotAllowedException.php#L54)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) — base class providing the RFC 9457 problem-details accessors
- [`InvalidArgumentException`](../InvalidArgumentException/README.md) — sibling detail-shaped problem-details exception for invalid-argument cases (adds an optional `$extra` slot)

## Issues

- [Open issues mentioning `NullValueNotAllowedException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22NullValueNotAllowedException%22)
- [Closed issues mentioning `NullValueNotAllowedException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22NullValueNotAllowedException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=NullValueNotAllowedException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
