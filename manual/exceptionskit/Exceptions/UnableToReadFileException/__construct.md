# UnableToReadFileException::__construct()

> `public function __construct(string $filePath, ?array $phpError = null)`

Build an `UnableToReadFileException` carrying the caller-supplied file path and, optionally, a PHP error record describing what went wrong.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\UnableToReadFileException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class UnableToReadFileException
{
    /**
     * @param string $filePath
     *     the file path that could not be read
     * @param array{type: int, message: string, file: string, line: int}|null $phpError
     *     the PHP error record captured around the read failure
     *     (typically the return value of `error_get_last()`), or
     *     null when no PHP error is available
     */
    public function __construct(
        string $filePath,
        ?array $phpError = null,
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus a caller-supplied file path and an optional PHP error record. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/unable-to-read-file` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Content)
- **title**: `"Unable to read file"`
- **extra**: `['file_path' => $filePath]`, with `'php_error' => $phpError` added when `$phpError` is non-null

The narrower API surface is the point: callers don't have to remember the type URI, the status code, the title, or the wire-format keys every time they want to raise "I could not read this file". Hand in the offending file path, optionally attach the PHP error record, and the exception fills in the rest.

```php
error_clear_last();
$contents = @file_get_contents($filePath);
if ($contents === false) {
    throw new UnableToReadFileException(
        filePath: $filePath,
        phpError: error_get_last(),
    );
}
```

The `error_get_last()` capture is the caller's responsibility, not the constructor's. See [Here Be Dragons](#here-be-dragons) for why.

## Parameters

**`$filePath`** (`string`)

The file path that could not be read. Stored verbatim in the parent's `extra` slot under the snake_case key `file_path`, and surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and from [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md).

**`$phpError`** (`array{type: int, message: string, file: string, line: int}|null`, optional, default: `null`)

The PHP error record captured around the read failure (typically the return value of `error_get_last()`), or `null` when no PHP error is available. When non-null, the whole record is stored verbatim under the snake_case key `php_error` in the parent's `extra` slot — its own `type` / `message` / `file` / `line` keys round-trip unchanged. When `null` (or omitted), the constructor does NOT introduce a `php_error` key with a null value; the key is simply absent from `extra`.

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

**The `error_get_last()` capture must happen at the call site, not inside the constructor.** This constructor deliberately does not call `error_get_last()` itself — the caller is responsible. The reason: `error_get_last()` returns the most recent PHP error from anywhere in the current request, not just from the line above. Calling it inside the constructor would attribute stale, unrelated PHP warnings to throws that did not actually trigger a PHP error.

**Call `error_clear_last()` before the operation that might fail.** PHP does not reset its last-error state between unrelated calls. The discipline shown in the example above — `error_clear_last()`, then attempt, then `error_get_last()` — is what guarantees a clean capture.

**PHP parameter names and wire-format keys differ.** The constructor accepts `$filePath` and `$phpError` (camelCase), but the values land in `getExtra()` under `file_path` and `php_error` (snake_case). Callers reaching into `getExtra()` from a `catch` block must use the snake_case keys — `getExtra()['filePath']` or `getExtra()['phpError']` will silently return `null`.

**No `getFilePath()` / `getPhpError()` helpers exist.** To recover the pair, read `$e->getExtra()['file_path']` and `$e->getExtra()['php_error']`.

**A null `$phpError` is not stored.** When the caller passes `null` (or omits the argument), the `php_error` key is absent from `extra` entirely, not present with a null value. Downstream consumers can rely on the key being absent to mean "no PHP-level diagnostic was available at the throw site".

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\UnableToReadFileException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares its parameters in the expected order
 ✔ ::__construct() declares $filePath as string
 ✔ ::__construct() declares $phpError as a nullable array, defaulting to null
 ✔ ::__construct() accepts a file path string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the file path is stored in extra
 ✔ ->getExtra() carries only the file path when the caller omits phpError
 ✔ ->getExtra() carries the caller-supplied PHP error under the "php_error" key when phpError is non-null
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/exceptionskit/src/Exceptions/UnableToReadFileException.php:79`](../../../../kits/exceptionskit/src/Exceptions/UnableToReadFileException.php#L79)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — inherited accessor that surfaces the `file_path` and `php_error` payload back to the caller
- [`FileNotFoundException::__construct()`](../FileNotFoundException/__construct.md) — sibling constructor for the "file does not exist at the given path" case
- [`InvalidClassException::__construct()`](../InvalidClassException/__construct.md) — sibling constructor with the same `extra`-payload shape, for unloadable class names
- [`InvalidFunctionException::__construct()`](../InvalidFunctionException/__construct.md) — sibling constructor with the same `extra`-payload shape, for undefined global function names
- [`InvalidMethodException::__construct()`](../InvalidMethodException/__construct.md) — sibling constructor with the same `extra`-payload shape, for missing methods

## Issues

- [Open issues mentioning `UnableToReadFileException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22UnableToReadFileException::__construct()%22)
- [Closed issues mentioning `UnableToReadFileException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22UnableToReadFileException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=UnableToReadFileException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
