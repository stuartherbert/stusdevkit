# UnableToReadFileException

Thrown when a file is present at the given path but cannot be read — either because it is not readable (permissions, ACLs, mount state) or because a low-level read operation failed.

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

class UnableToReadFileException extends Rfc9457ProblemDetailsException
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

    // --- UnableToReadFileException ---

    /**
     * Build an UnableToReadFileException carrying the caller-supplied file path and, optionally, a PHP error record describing what went wrong.
     */
    public function __construct(
        string $filePath,
        ?array $phpError = null,
    );
}
```

## Description

`UnableToReadFileException` is a fixed-shape problem-details exception for the "a file is at this path but I couldn't read it" case.

It hard-codes three of the RFC 9457 fields its parent normally accepts:

- **type** — `https://example.com/errors/unable-to-read-file` _(placeholder — see [Here Be Dragons](#here-be-dragons))_
- **status** — `422` (Unprocessable Content)
- **title** — `"Unable to read file"`

The caller supplies `$filePath` and may optionally supply `$phpError` (typically the return value of `error_get_last()`). The constructor stores the file path in the parent's [`extra`](../Rfc9457ProblemDetailsException/getExtra.md) slot under the `file_path` key. When `$phpError` is non-null, the full PHP error record (type, message, file, line) is also stashed under the `php_error` key — so the diagnostic that PHP emitted around the failure travels with the exception. When `$phpError` is omitted or null, no `php_error` key is introduced; the wire payload stays minimal.

The rest of the [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) accessor surface (`getStatus()`, `getTitle()`, `getTypeAsString()`, `jsonSerialize()`, etc.) is inherited unchanged.

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

- [`->getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — returns the extra data array (carries `['file_path' => $filePath]`, and additionally `'php_error' => $phpError` when the caller supplied one)
- [`->getStatus()`](../Rfc9457ProblemDetailsException/getStatus.md) — returns the HTTP status code (always `422` for this class)
- [`->getTitle()`](../Rfc9457ProblemDetailsException/getTitle.md) — returns the problem title (always `"Unable to read file"` for this class)
- [`->getTypeAsString()`](../Rfc9457ProblemDetailsException/getTypeAsString.md) — returns the type URI
- [`->hasDetail()`](../Rfc9457ProblemDetailsException/hasDetail.md) — returns whether detail is set (always `false` for this class)
- [`->hasExtra()`](../Rfc9457ProblemDetailsException/hasExtra.md) — returns whether extra data is set (always `true` for this class)
- [`->hasInstance()`](../Rfc9457ProblemDetailsException/hasInstance.md) — returns whether instance URI is set
- [`->jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md) — serialises to array following RFC 9457 shape
- [`->maybeGetDetail()`](../Rfc9457ProblemDetailsException/maybeGetDetail.md) — returns the detail if set, otherwise `null` (always `null` for this class)
- [`->maybeGetInstanceAsString()`](../Rfc9457ProblemDetailsException/maybeGetInstanceAsString.md) — returns the instance URI as string, or `null` if unset

**From UnableToReadFileException**

- [`->__construct()`](__construct.md) — build an `UnableToReadFileException` carrying the caller-supplied file path and, optionally, a PHP error record describing what went wrong

## Here Be Dragons

**The PHP error record is the caller's responsibility, not the exception's.** The constructor does NOT call `error_get_last()` itself. Callers that want a clean PHP-error capture must call `error_clear_last()` immediately before the operation that might fail, then read `error_get_last()` and pass the result in via `$phpError`. Skipping the `error_clear_last()` step risks attributing a stale, unrelated PHP warning to this throw — `error_get_last()` returns the most recent error from anywhere in the request, not just the line above it.

**The wire-format keys are `file_path` and `php_error`, not the PHP parameter names `filePath` and `phpError`.** The constructor accepts camelCase parameters, but both values land in [`getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) under snake_case keys to match the RFC 9457 convention for extension members. Callers reaching into `getExtra()` from a `catch` block must use the snake_case keys — `getExtra()['filePath']` or `getExtra()['phpError']` will silently return `null`.

**There are no `getFilePath()` / `getPhpError()` helpers.** To recover the pair from a caught instance, read `$e->getExtra()['file_path']` and `$e->getExtra()['php_error']`.

**Absence of `php_error` is meaningful.** When the caller passes `null` (or omits `$phpError` entirely), the constructor does not introduce a `php_error` key with a null value — the key is simply absent from `extra`. Downstream consumers can rely on the key being absent to mean "no PHP-level diagnostic was available at the throw site".

**The `type` URI is a placeholder.** `https://example.com/errors/unable-to-read-file` is not yet a real documentation URL — the docs target hasn't been set up. Treat it as a stable identifier, not a navigable link.

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\UnableToReadFileException
 ✔ lives in the StusDevKit\ExceptionsKit\Exceptions namespace
 ✔ is declared as a class
 ✔ extends Rfc9457ProblemDetailsException
 ✔ declares no additional public methods beyond its parent
```

## Source

[`kits/exceptionskit/src/Exceptions/UnableToReadFileException.php:69`](../../../../kits/exceptionskit/src/Exceptions/UnableToReadFileException.php#L69)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException`](../Rfc9457ProblemDetailsException/README.md) — base class providing the RFC 9457 problem-details accessors
- [`FileNotFoundException`](../FileNotFoundException/README.md) — sibling fixed-shape problem-details exception for file paths that do not exist
- [`InvalidClassException`](../InvalidClassException/README.md) — sibling fixed-shape problem-details exception for unloadable class names
- [`InvalidFunctionException`](../InvalidFunctionException/README.md) — sibling fixed-shape problem-details exception for undefined global function names
- [`InvalidMethodException`](../InvalidMethodException/README.md) — sibling fixed-shape problem-details exception for missing methods

## Issues

- [Open issues mentioning `UnableToReadFileException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UnableToReadFileException%22)
- [Closed issues mentioning `UnableToReadFileException`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UnableToReadFileException%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UnableToReadFileException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
