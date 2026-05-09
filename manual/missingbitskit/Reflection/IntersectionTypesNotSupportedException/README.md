# IntersectionTypesNotSupportedException

Raised when an operation that produces a flat list of type names encounters
an intersection type that cannot be faithfully represented in that flat form.

**Since:** _Upcoming Release_

## Hierarchy

_Extends [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md), which itself extends `Exception` (PHP built-in)._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionIntersectionType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

class IntersectionTypesNotSupportedException extends Rfc9457ProblemDetailsException
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

    // --- IntersectionTypesNotSupportedException ---
    public function __construct(ReflectionIntersectionType $refType);
}
```

## Description

`IntersectionTypesNotSupportedException` is raised when an operation that
produces a flat list of type names encounters an intersection type that cannot
be faithfully represented in that flat form.

An intersection `A&B` means "a value that satisfies both A and B simultaneously".
Collapsing that to `['A', 'B']` discards the "and" semantics — the flat list
becomes indistinguishable from the list produced for a union `A|B` (which means
"a value that satisfies either A or B"). Callers reasoning from the flat list
would therefore draw wrong conclusions. Rather than silently produce misleading
output, the flattener refuses the input.

Distinct from `UnsupportedReflectionTypeException`: that one signals "this is a
ReflectionType subclass the flattener does not recognise" (a library maintenance
gap). This one signals "the input is known and well-formed, but deliberately
outside the flattener's contract" (a caller contract violation).

## Methods

**From `Exception`**

- [`->getMessage()`](https://www.php.net/manual/en/exception.getMessage) — returns the exception message
- [`->getCode()`](https://www.php.net/manual/en/exception.getCode) — returns the exception code
- [`->getFile()`](https://www.php.net/manual/en/exception.getFile) — returns the file where it was thrown
- [`->getLine()`](https://www.php.net/manual/en/exception.getLine) — returns the line number
- [`->getTrace()`](https://www.php.net/manual/en/exception.getTrace) — returns the stack trace
- [`->getTraceAsString()`](https://www.php.net/manual/en/exception.getTraceAsString) — returns formatted stack trace
- [`->getPrevious()`](https://www.php.net/manual/en/exception.getPrevious) — returns the previous exception
- [`->__toString()`](https://www.php.net/manual/en/exception.toString) — returns string representation

**From `Rfc9457ProblemDetailsException`**

- [`->hasDetail()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasDetail.md) — returns whether detail is set
- [`->maybeGetDetail()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/maybeGetDetail.md) — returns the detail if set
- [`->hasExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasExtra.md) — returns whether extra data is set
- [`->getExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getExtra.md) — returns the extra data array
- [`->hasInstance()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasInstance.md) — returns whether instance URI is set
- [`->maybeGetInstanceAsString()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/maybeGetInstanceAsString.md) — returns instance URI as string
- [`->getStatus()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getStatus.md) — returns the HTTP status code
- [`->getTitle()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTitle.md) — returns the problem title
- [`->getTypeAsString()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTypeAsString.md) — returns the type URI
- [`->jsonSerialize()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/jsonSerialize.md) — serialises to array

**From `IntersectionTypesNotSupportedException`**

- [`->__construct()`](__construct.md) — Accepts a `ReflectionIntersectionType` and
  pre-fills the parent's `type`, `status`, `title`, and `extra` fields.

## Here Be Dragons

This exception is specific to intersection types — it does not apply to union
types, which can be safely flattened into a list.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\IntersectionTypesNotSupportedException
 ✔ lives in the StusDevKit\MissingBitsKit\Reflection namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ declares no additional public methods beyond its parent
```

## Source

[`kits/missingbitskit/src/Reflection/IntersectionTypesNotSupportedException.php:65`](../src/Reflection/IntersectionTypesNotSupportedException.php#L65)

## Changelog

_No tagged releases yet._

## See Also

- [`UnsupportedReflectionTypeException`](../UnsupportedReflectionTypeException/README.md) — for unrecognised ReflectionType subclasses
- [`GetReflectionTypes`](../GetReflectionTypes/README.md) — the flattener that throws this exception

## Issues

- [Open issues mentioning `IntersectionTypesNotSupportedException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22IntersectionTypesNotSupportedException%22)
- [Closed issues mentioning `IntersectionTypesNotSupportedException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22IntersectionTypesNotSupportedException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=IntersectionTypesNotSupportedException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
