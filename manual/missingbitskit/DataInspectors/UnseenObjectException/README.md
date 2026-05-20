# UnseenObjectException

Thrown when [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md) is asked for the visit index of an object that was never recorded via [`NormalisationContext::markSeen()`](../NormalisationContext/markSeen.md) — signalling a broken calling protocol, not a bad input.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:**

- [`Exception`](https://www.php.net/manual/en/class.exception.php) (PHP built-in)
- [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md)

**Implements:**

- [`JsonSerializable`](https://www.php.net/manual/en/class.jsonserializable.php) (PHP built-in, via [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md))

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\MissingBitsKit\DataInspectors;

use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

class UnseenObjectException extends Rfc9457ProblemDetailsException
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

    // --- UnseenObjectException (own) ---
    public function __construct(object $obj);
}
```

## Description

`UnseenObjectException` is thrown when [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md) is called for the visit index of an object that was never recorded via [`NormalisationContext::markSeen()`](../NormalisationContext/markSeen.md).

It exists so that [`NormalisationContext`](../NormalisationContext/README.md) surfaces its **own** typed exception on misuse, instead of leaking the [`UnexpectedValueException`](https://www.php.net/manual/en/class.unexpectedvalueexception.php) thrown by the [`SplObjectStorage`](https://www.php.net/manual/en/class.splobjectstorage.php) it is built on. The wrapper owns its boundary: no caller has to know which storage backs the context, and a future change of storage cannot change the exception type callers catch.

Reaching this exception means a calling-protocol bug — [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md) must be paired with [`NormalisationContext::hasSeen()`](../NormalisationContext/hasSeen.md). Because that is a programmer error rather than a bad input, the exception carries HTTP status `500`. The offending object's class name is stored in `extra` under the `class_name` key (e.g. `'stdClass'`).

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

**From `UnseenObjectException` (own)**

- [`->__construct()`](__construct.md) — Accepts the offending object and
  pre-fills the parent's `type`, `status`, `title`, and `extra` fields.

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\MissingBitsKit\DataInspectors\UnseenObjectException
 ✔ lives in the StusDevKit\MissingBitsKit\DataInspectors namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ is throwable
 ✔ declares only __construct as its own method
 ✔ ::__construct() takes exactly one parameter
 ✔ ::__construct()'s parameter is named $obj
 ✔ ::__construct()'s parameter is typed as object
 ✔ ::__construct()'s parameter is required, not optional
 ✔ can be instantiated with the offending object
 ✔ can be thrown and caught as an UnseenObjectException
 ✔ can be caught as a Rfc9457ProblemDetailsException
 ✔ ->getStatus() returns HTTP 500
 ✔ ->getTitle() returns the unseen-object summary
 ✔ ->getMessage() uses the title as the exception message
 ✔ ->getTypeAsString() returns the StusDevKit problem-type URI
 ✔ ->hasDetail() returns false
 ✔ ->hasExtra() returns true
 ✔ ->getExtra() reports the offending object class stdClass
 ✔ ->getExtra() reports the offending object class ArrayObject
 ✔ ->jsonSerialize() returns the RFC 9457 problem-details payload
```

## Source

[`kits/missingbitskit/src/DataInspectors/UnseenObjectException.php:57`](../../../../kits/missingbitskit/src/DataInspectors/UnseenObjectException.php#L57)

## Changelog

_No tagged releases yet._

## See Also

- [`NormalisationContext`](../NormalisationContext/README.md) — the visited-set wrapper that throws this exception
- [`NormalisationContext::visitIndexOf()`](../NormalisationContext/visitIndexOf.md) — the method that throws it when its `hasSeen()` precondition is skipped
- [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md) — the RFC 9457 problem-details base this exception configures

## Issues

- [Open issues mentioning `UnseenObjectException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UnseenObjectException%22)
- [Closed issues mentioning `UnseenObjectException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UnseenObjectException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UnseenObjectException%3A%20%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the `generate-manual-page` skill._
