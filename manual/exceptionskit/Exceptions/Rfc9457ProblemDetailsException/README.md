# Rfc9457ProblemDetailsException

An RFC 9457 compliant exception that holds structured problem details
including type URI, HTTP status code, title, detail, instance URI, and
arbitrary extra data.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:**

- [`Exception`](https://www.php.net/manual/en/class.exception.php) (PHP built-in)

**Implements:**

- [`JsonSerializable`](https://www.php.net/manual/en/class.jsonserializable.php) (PHP built-in)

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

use Exception;
use JsonSerializable;
use Throwable;

class Rfc9457ProblemDetailsException extends Exception implements JsonSerializable
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
    public function hasDetail(): bool;
    public function maybeGetDetail(): ?string;
    public function hasExtra(): bool;
    public function getExtra(): array;
    public function hasInstance(): bool;
    public function maybeGetInstanceAsString(): ?string;
    public function getStatus(): int;
    public function getTitle(): string;
    public function getTypeAsString(): string;
    public function jsonSerialize(): array;

    // --- Rfc9457ProblemDetailsException ---
    public function __construct(
        string $type,
        int $status,
        string $title,
        array $extra = [],
        ?string $detail = null,
        ?string $instance = null,
        ?Throwable $previous = null,
    );
}
```

## Description

`Rfc9457ProblemDetailsException` is a PHP `Exception` that holds data
following the [RFC 9457](https://www.rfc-editor.org/rfc/rfc9457) standard
for structured problem reports.

It carries a type URI (a pointer to documentation for this class of
problem), an HTTP status code, a human-readable title, optional detail
text, an optional instance URI (a link to a resource specific to this
occurrence), and arbitrary extra data for application logs.

The exception forwards `getMessage()` to the parent `Exception`, which
returns `$detail` if set, otherwise falls back to `$title`.

**FORWARDS-COMPATIBILITY NOTES:**

- At the time of writing, PHP 8.5's `Uri` extension is undocumented and
  unrecognised by IDEs. A future release will accept `string|Uri` for
  `$type` and `$instance`, converting strings to `Uri` instances.

## Methods

- [`->getMessage()`](https://www.php.net/manual/en/exception.getMessage) — From `Exception`; returns the exception message
- [`->getCode()`](https://www.php.net/manual/en/exception.getCode) — From `Exception`; returns the exception code
- [`->getFile()`](https://www.php.net/manual/en/exception.getFile) — From `Exception`; returns the file where it was thrown
- [`->getLine()`](https://www.php.net/manual/en/exception.getLine) — From `Exception`; returns the line number
- [`->getTrace()`](https://www.php.net/manual/en/exception.getTrace) — From `Exception`; returns the stack trace
- [`->getTraceAsString()`](https://www.php.net/manual/en/exception.getTraceAsString) — From `Exception`; returns formatted stack trace
- [`->getPrevious()`](https://www.php.net/manual/en/exception.getPrevious) — From `Exception`; returns the previous exception
- [`->__toString()`](https://www.php.net/manual/en/exception.toString) — From `Exception`; returns string representation
- [`->hasDetail()`](./hasDetail.md) — Returns whether detail is set
- [`->maybeGetDetail()`](./maybeGetDetail.md) — Returns the detail if set
- [`->hasExtra()`](./hasExtra.md) — Returns whether extra data is set
- [`->getExtra()`](./getExtra.md) — Returns the extra data array
- [`->hasInstance()`](./hasInstance.md) — Returns whether instance URI is set
- [`->maybeGetInstanceAsString()`](./maybeGetInstanceAsString.md) — Returns instance URI as string
- [`->getStatus()`](./getStatus.md) — Returns the HTTP status code
- [`->getTitle()`](./getTitle.md) — Returns the problem title
- [`->getTypeAsString()`](./getTypeAsString.md) — Returns the type URI
- [`->jsonSerialize()`](./jsonSerialize.md) — Serialises to array following RFC 9457 shape
- [`->__construct()`](./__construct.md) — Accepts type, status, title, extra, detail, instance, and previous exception

## Here Be Dragons

- PHP 8.5's `Uri` extension is not yet documented or IDE-supported;
  `$type` and `$instance` are `string` now but will accept `Uri` in
  a future release.

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException
 ✔ lives in the StusDevKit\ExceptionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends Exception
 ✔ implements JsonSerializable
 ✔ __construct() accepts $type, $status, $title, $extra, $detail, $instance, $previous
 ✔ ->hasDetail() returns false when no detail is set
 ✔ ->maybeGetDetail() returns null when no detail is set
 ✔ ->hasExtra() returns false when extra array is empty
 ✔ ->getExtra() returns the extra data array
 ✔ ->hasInstance() returns false when no instance URI is set
 ✔ ->maybeGetInstanceAsString() returns null when no instance URI is set
 ✔ ->getStatus() returns the HTTP status code
 ✔ ->getTitle() returns the problem title
 ✔ ->getTypeAsString() returns the type URI
 ✔ ->jsonSerialize() returns a structured array following RFC 9457 shape
 ✔ ->getMessage() falls back to title when no detail is set
```

## Source

[`kits/exceptionskit/src/Exceptions/Rfc9457ProblemDetailsException.php:83`](../src/Exceptions/Rfc9457ProblemDetailsException.php#L83)

## Changelog

_No tagged releases yet._

## See Also

- [`IntersectionTypesNotSupportedException`](../../missingbitskit/Reflection/IntersectionTypesNotSupportedException/README.md) — extends this class for intersection type errors
- [`UnsupportedReflectionTypeException`](../../missingbitskit/Reflection/UnsupportedReflectionTypeException/README.md) — extends this class for unrecognised ReflectionType subclasses

## Issues

- [Open issues mentioning `Rfc9457ProblemDetailsException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22Rfc9457ProblemDetailsException%22)
- [Closed issues mentioning `Rfc9457ProblemDetailsException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22Rfc9457ProblemDetailsException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=Rfc9457ProblemDetailsException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
