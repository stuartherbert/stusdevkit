# EmptyStackException

Thrown when `pop()` or `peek()` is called on an empty stack.

**Since:** _Upcoming Release_

## Hierarchy

**Extends:**

- [`Exception`](https://www.php.net/manual/en/class.exception.php) (PHP built-in)
- [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md)
- [`EmptyCollectionException`](../EmptyCollectionException/README.md)

**Implements:**

- [`JsonSerializable`](https://www.php.net/manual/en/class.jsonserializable.php) (PHP built-in, via [`Rfc9457ProblemDetailsException`](../../../exceptionskit/Exceptions/Rfc9457ProblemDetailsException/README.md))

**Uses:** _(none)_

## Synopsis

```php
declare(strict_types=1);
namespace StusDevKit\CollectionsKit\Exceptions;

class EmptyStackException extends EmptyCollectionException
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

`EmptyStackException` is the empty-collection exception for stacks. It is thrown when [`CollectionAsStack::pop()`](../../Stacks/CollectionAsStack/pop.md) or [`CollectionAsStack::peek()`](../../Stacks/CollectionAsStack/peek.md) is called on a stack that has no items.

The class adds no behaviour of its own — its body is empty. It is a pure specialisation of [`EmptyCollectionException`](../EmptyCollectionException/README.md), one of the per-collection-kind subclasses that base class is designed to have. The specialisation exists so callers can catch stack emptiness as its own type: a `catch (EmptyStackException)` block handles only empty-stack failures, while a `catch (EmptyCollectionException)` block still catches it alongside every other empty-collection failure.

Everything observable is inherited from [`EmptyCollectionException`](../EmptyCollectionException/README.md) unchanged — the fixed `https://stusdevkit.dev/errors/collections/empty` type URI, the `500` status, the `"<collectionType> is empty"` title, and the [`__construct()`](../EmptyCollectionException/__construct.md) signature that takes a single `$collectionType` name.

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

- [`->__construct()`](../EmptyCollectionException/__construct.md) — create an exception for an empty collection

## Here Be Dragons

_None._

## Contract (from tests)

```
StusDevKit\CollectionsKit\Exceptions\EmptyStackException
 ✔ lives in the StusDevKit\CollectionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends EmptyCollectionException
 ✔ declares no methods of its own
 ✔ ::__construct() inherits from the parent and composes the title from the type
```

## Source

[`kits/collectionskit/src/Exceptions/EmptyStackException.php:48`](../../../../kits/collectionskit/src/Exceptions/EmptyStackException.php#L48)

## Changelog

_No tagged releases yet._

## See Also

- [`EmptyCollectionException`](../EmptyCollectionException/README.md) — base class; the general empty-collection exception this specialises, and the source of the inherited constructor and accessors
- [`CollectionAsStack::pop()`](../../Stacks/CollectionAsStack/pop.md) — remove and return the top value from the stack; throws this exception when the stack is empty
- [`CollectionAsStack::peek()`](../../Stacks/CollectionAsStack/peek.md) — return the top value without removing it; throws this exception when the stack is empty

## Issues

- [Open issues mentioning `EmptyStackException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22EmptyStackException%22)
- [Closed issues mentioning `EmptyStackException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22EmptyStackException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=EmptyStackException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
