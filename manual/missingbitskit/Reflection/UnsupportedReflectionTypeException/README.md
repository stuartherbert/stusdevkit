# UnsupportedReflectionTypeException

Raised when a ReflectionType flattener encounters a `ReflectionType` subclass
it does not yet recognise — signalling a library-maintenance gap, not a caller
contract violation.

**Since:** _Upcoming Release_

## Hierarchy

_Extends [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md), which itself extends `Exception` (PHP built-in)._

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\Reflection;

use ReflectionType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

class UnsupportedReflectionTypeException extends Rfc9457ProblemDetailsException
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

    // --- UnsupportedReflectionTypeException (own) ---
    public function __construct(ReflectionType $refType);
}
```

## Description

`UnsupportedReflectionTypeException` is raised when a ReflectionType flattener
encounters a `ReflectionType` subclass it does not yet recognise.

This exception signals a **library-maintenance gap**: the caller fed in a
perfectly valid `ReflectionType` subclass, but this library does not yet know
how to handle it. Hence the 500-class HTTP status — this is a server-side
problem, not a caller contract violation.

The offending ReflectionType subclass name is stored in `extra` under the
`class_name` key (e.g. `'ReflectionNamedType'`).

Distinct from `IntersectionTypesNotSupportedException`: that one signals
"the input is known and well-formed, but deliberately outside the flattener's
contract" (a caller contract violation with status 422). This one signals
"a new ReflectionType subclass appeared and the library needs updating" (a
library maintenance gap with status 500).

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

**From `UnsupportedReflectionTypeException` (own)**

- [`->__construct()`](__construct.md) — Accepts a `ReflectionType` and
  pre-fills the parent's `type`, `status`, `title`, and `extra` fields.

## Here Be Dragons

- The class name stored in `extra['class_name']` is the concrete subclass
  (e.g. `'ReflectionNamedType'`), NOT the string form of the type itself
  (e.g. `'int'`). Callers expecting `(string)$refType` will see the class
  name instead.

## Contract (from tests)

```
StusDevKit\MissingBitsKit\Reflection\UnsupportedReflectionTypeException
 ✔ lives in the StusDevKit\MissingBitsKit\Reflection namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ declares no additional public methods beyond its parent
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $refType as its only parameter
 ✔ ::__construct() declares $refType as ReflectionType
 ✔ ::__construct() accepts a ReflectionType
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 500
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the class name is stored in extra
 ✔ ->getExtra() carries the ReflectionType subclass name under the "class_name" key
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/missingbitskit/src/Reflection/UnsupportedReflectionTypeException.php:42`](../src/Reflection/UnsupportedReflectionTypeException.php#L42)

## Changelog

_No tagged releases yet._

## See Also

- [`IntersectionTypesNotSupportedException`](../IntersectionTypesNotSupportedException/README.md) — for intersection type errors (caller contract violation, status 422)
- [`GetReflectionTypes`](../GetReflectionTypes/README.md) — the flattener that throws this exception

## Issues

- [Open issues mentioning `UnsupportedReflectionTypeException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UnsupportedReflectionTypeException%22)
- [Closed issues mentioning `UnsupportedReflectionTypeException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UnsupportedReflectionTypeException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UnsupportedReflectionTypeException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
