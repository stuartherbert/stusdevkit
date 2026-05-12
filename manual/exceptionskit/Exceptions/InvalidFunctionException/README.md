# InvalidFunctionException

Thrown when a string is supplied as a function name, but that string does not identify a defined global function.

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

class InvalidFunctionException extends Rfc9457ProblemDetailsException
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

    // --- InvalidFunctionException ---

    /**
     * Build an InvalidFunctionException carrying the caller-supplied function name in the `extra` slot under the `function_name` key.
     */
    public function __construct(
        string $functionName,
    );
}
```

## Description

`InvalidFunctionException` is a fixed-shape problem-details exception for the "the caller passed a string that doesn't name a defined global function" case.

It hard-codes three of the RFC 9457 fields its parent normally accepts:

- **type** — `https://example.com/errors/invalid-function` _(placeholder — see [Here Be Dragons](#here-be-dragons))_
- **status** — `422` (Unprocessable Content)
- **title** — `"Function name does not refer to a defined function"`

The caller supplies `$functionName` (the string that was offered as a function name). The constructor stores it in the parent's [`extra`](../Rfc9457ProblemDetailsException/getExtra.md) slot under the `function_name` key — not as a free-form [`detail`](../Rfc9457ProblemDetailsException/maybeGetDetail.md). The rest of the [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) accessor surface (`getStatus()`, `getTitle()`, `getTypeAsString()`, `jsonSerialize()`, etc.) is inherited unchanged.

## Methods

**From Exception (PHP built-in)**

- [`->getMessage()`](https://www.php.net/manual/en/exception.getMessage) — returns the exception message (falls back to `$title` when no `$detail` is set)
- [`->getCode()`](https://www.php.net/manual/en/exception.getCode) — returns the exception code
- [`->getFile()`](https://www.php.net/manual/en/exception.getFile) — returns the file where it was thrown
- [`->getLine()`](https://www.php.net/manual/en/exception.getLine) — returns the line number
- [`->getTrace()`](https://www.php.net/manual/en/exception.getTrace) — returns the stack trace
- [`->getTraceAsString()`](https://www.php.net/manual/en/exception.getTraceAsString) — returns the formatted stack trace
- [`->getPrevious()`](https://www.php.net/manual/en/exception.getPrevious) — returns the previous exception
- [`->__toString()`](https://www.php.net/manual/en/exception.toString) — returns string representation

**From Rfc9457ProblemDetailsException**

- [`->getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — returns the extra data array (carries `['function_name' => $functionName]` for this class)
- [`->getStatus()`](../Rfc9457ProblemDetailsException/getStatus.md) — returns the HTTP status code (always `422` for this class)
- [`->getTitle()`](../Rfc9457ProblemDetailsException/getTitle.md) — returns the problem title (always `"Function name does not refer to a defined function"` for this class)
- [`->getTypeAsString()`](../Rfc9457ProblemDetailsException/getTypeAsString.md) — returns the type URI
- [`->hasDetail()`](../Rfc9457ProblemDetailsException/hasDetail.md) — returns whether detail is set (always `false` for this class)
- [`->hasExtra()`](../Rfc9457ProblemDetailsException/hasExtra.md) — returns whether extra data is set (always `true` for this class)
- [`->hasInstance()`](../Rfc9457ProblemDetailsException/hasInstance.md) — returns whether instance URI is set
- [`->jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md) — serialises to array following RFC 9457 shape
- [`->maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) — returns the detail if set, otherwise `null` (always `null` for this class)
- [`->maybeGetInstanceAsString()`](../Rfc9457ProblemDetailsException/maybeGetInstanceAsString.md) — returns the instance URI as string, or `null` if unset

**From InvalidFunctionException**

- [`->__construct()`](__construct.md) — build an `InvalidFunctionException` carrying the caller-supplied function name in the `extra` slot under the `function_name` key

## Here Be Dragons

**The wire-format key is `function_name`, not the PHP parameter name `functionName`.** The constructor accepts `$functionName` (camelCase), but the value lands in [`getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) under `function_name` (snake_case), to match the RFC 9457 convention for extension members. Callers reaching into `getExtra()` from a `catch` block must use the snake_case key — `getExtra()['functionName']` will silently return `null`.

**There is no `getFunctionName()` helper.** To recover the offending function name from a caught instance, read `$e->getExtra()['function_name']`.

**The `type` URI is a placeholder.** `https://example.com/errors/invalid-function` is not yet a real documentation URL — the docs target hasn't been set up. Treat it as a stable identifier, not a navigable link.

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\InvalidFunctionException
 ✔ lives in the StusDevKit\ExceptionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ declares no additional public methods beyond its parent
```

## Source

[`kits/exceptionskit/src/Exceptions/InvalidFunctionException.php:55`](../../../../kits/exceptionskit/src/Exceptions/InvalidFunctionException.php#L55)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) — base class providing the RFC 9457 problem-details accessors
- [`InvalidClassException`](../InvalidClassException/README.md) — sibling fixed-shape problem-details exception for invalid-class-name cases
- [`InvalidArgumentException`](../InvalidArgumentException/README.md) — sibling fixed-shape problem-details exception for invalid-argument cases

## Issues

- [Open issues mentioning `InvalidFunctionException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22InvalidFunctionException%22)
- [Closed issues mentioning `InvalidFunctionException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22InvalidFunctionException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=InvalidFunctionException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
