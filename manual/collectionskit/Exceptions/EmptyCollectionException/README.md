# EmptyCollectionException

Thrown when an operation requires a non-empty collection but the collection is empty.

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
namespace StusDevKit\CollectionsKit\Exceptions;

use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

class EmptyCollectionException extends Rfc9457ProblemDetailsException
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

    // --- EmptyCollectionException ---

    /**
     * Create an exception for an empty collection.
     */
    public function __construct(
        string $collectionType,
    );
}
```

## Description

`EmptyCollectionException` is a fixed-shape problem-details exception for the "an operation needed a non-empty collection, but the collection was empty" case.

It hard-codes two of the RFC 9457 fields its parent normally accepts, and composes the third from the caller's input:

- **type** — `https://stusdevkit.dev/errors/collections/empty`
- **status** — `500` (Internal Server Error)
- **title** — the collection type name followed by `" is empty"` (for example, `"StackOfStrings is empty"`)

The caller supplies only `$collectionType` — the human-readable name of the collection. No `detail` is provided, so the built-in `Exception::getMessage()` falls back to the composed title. The remainder of the [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md) accessor surface — [`getStatus()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getStatus.md), [`getTitle()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTitle.md), [`getTypeAsString()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTypeAsString.md), [`jsonSerialize()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/jsonSerialize.md), and the rest — is inherited unchanged.

`EmptyCollectionException` is the base class for all empty-collection exceptions in CollectionsKit. Subclasses provide a specific exception type for each collection kind — for example, [`EmptyStackException`](../EmptyStackException/README.md).

## Methods

**From Exception (PHP built-in)**

- [`->getMessage()`](https://www.php.net/manual/en/exception.getMessage) — returns the exception message (the composed `"… is empty"` title for this class)
- [`->getCode()`](https://www.php.net/manual/en/exception.getCode) — returns the exception code
- [`->getFile()`](https://www.php.net/manual/en/exception.getFile) — returns the file where it was thrown
- [`->getLine()`](https://www.php.net/manual/en/exception.getLine) — returns the line number
- [`->getTrace()`](https://www.php.net/manual/en/exception.getTrace) — returns the stack trace
- [`->getTraceAsString()`](https://www.php.net/manual/en/exception.getTraceAsString) — returns the formatted stack trace
- [`->getPrevious()`](https://www.php.net/manual/en/exception.getPrevious) — returns the previous exception
- [`->__toString()`](https://www.php.net/manual/en/exception.toString) — returns string representation

**From Rfc9457ProblemDetailsException**

- [`->getExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getExtra.md) — returns the extra data array (always empty for this class)
- [`->getStatus()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getStatus.md) — returns the HTTP status code (always `500` for this class)
- [`->getTitle()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTitle.md) — returns the problem title (`"<collectionType> is empty"` for this class)
- [`->getTypeAsString()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/getTypeAsString.md) — returns the type URI (always `https://stusdevkit.dev/errors/collections/empty` for this class)
- [`->hasDetail()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasDetail.md) — returns whether detail is set (always `false` for this class)
- [`->hasExtra()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasExtra.md) — returns whether extra data is set (always `false` for this class)
- [`->hasInstance()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/hasInstance.md) — returns whether instance URI is set (always `false` for this class)
- [`->jsonSerialize()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/jsonSerialize.md) — serialises to array following RFC 9457 shape
- [`->maybeGetDetail()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/maybeGetDetail.md) — returns the detail if set, otherwise `null` (always `null` for this class)
- [`->maybeGetInstanceAsString()`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/maybeGetInstanceAsString.md) — returns the instance URI as string, or `null` if unset (always `null` for this class)

**From EmptyCollectionException**

- [`->__construct()`](__construct.md) — create an exception for an empty collection

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Exceptions\EmptyCollectionException
 ✔ lives in the StusDevKit\CollectionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ can be thrown and caught as an EmptyCollectionException
 ✔ can be caught as a Rfc9457ProblemDetailsException
```

## Source

[`kits/collectionskit/src/Exceptions/EmptyCollectionException.php:56`](../../../../kits/collectionskit/src/Exceptions/EmptyCollectionException.php#L56)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md) — base class providing the RFC 9457 problem-details accessors
- [`EmptyStackException`](../EmptyStackException/README.md) — subclass thrown when an operation needs a non-empty stack

## Issues

- [Open issues mentioning `EmptyCollectionException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22EmptyCollectionException%22)
- [Closed issues mentioning `EmptyCollectionException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22EmptyCollectionException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=EmptyCollectionException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
