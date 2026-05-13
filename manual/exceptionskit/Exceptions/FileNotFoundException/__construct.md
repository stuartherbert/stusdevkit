# FileNotFoundException::__construct()

> `public function __construct(string $filePath)`

Build a `FileNotFoundException` carrying the caller-supplied file path in the `extra` slot under the `file_path` key.

**Since:** _Upcoming Release_

## Defined On

[`StusDevKit\ExceptionsKit\Exceptions\FileNotFoundException`](README.md), overriding [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md)

## Signature

```php
declare(strict_types=1);
namespace StusDevKit\ExceptionsKit\Exceptions;

class FileNotFoundException
{
    /**
     * @param string $filePath
     *     the file path that was expected to exist but does not
     */
    public function __construct(
        string $filePath,
    )
}
```

## Description

Constructs the exception with three RFC 9457 fields already fixed by the class, plus the caller-supplied file path stashed in the parent's [`extra`](../Rfc9457ProblemDetailsException/getExtra.md) slot. The constructor forwards to [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) with:

- **type**: `https://example.com/errors/file-not-found` _(placeholder — the docs target hasn't been set up yet)_
- **status**: `422` (Unprocessable Content)
- **title**: `"File not found"`
- **extra**: `['file_path' => $filePath]`

The narrower API surface is the point: callers don't have to remember the type URI, the status code, the title, or the wire-format key every time they want to raise "a file was expected at this path but isn't there". Hand in the offending file path, and the exception fills in the rest.

```php
throw new FileNotFoundException(
    filePath: '/tmp/does-not-exist.txt',
);
```

Unlike its sibling [`InvalidArgumentException`](../InvalidArgumentException/README.md), this constructor takes no `$detail` and no `$extra` — the file path is the entire payload, and it is carried in `extra` (not `detail`) so the value is machine-readable rather than embedded in prose.

## Parameters

**`$filePath`** (`string`)

The file path that was expected to exist but does not. Stored verbatim in the parent's `extra` slot under the snake_case key `file_path`, and surfaces from [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) and from [`jsonSerialize()`](../Rfc9457ProblemDetailsException/jsonSerialize.md).

## Return Values

_Not applicable — constructors do not return values._

## Errors/Exceptions

_None._

## Here Be Dragons

**The PHP parameter name and the wire-format key are different.** The constructor accepts `$filePath` (camelCase), but the value lands in `getExtra()` under `file_path` (snake_case). Callers reaching into `getExtra()` from a `catch` block must use the snake_case key — `getExtra()['filePath']` will silently return `null`.

**No `getFilePath()` helper exists.** To recover the offending file path, read `$e->getExtra()['file_path']`.

## Examples

_None yet._

## Contract (from tests)

```
StusDevKit\ExceptionsKit\Exceptions\FileNotFoundException
 ✔ ::__construct() is declared
 ✔ ::__construct() is public
 ✔ ::__construct() declares $filePath as its only parameter
 ✔ ::__construct() declares $filePath as string
 ✔ ::__construct() accepts a file path string
 ✔ ->getTypeAsString() returns the fixed type URI
 ✔ ->getStatus() returns 422
 ✔ ->getTitle() returns the fixed title
 ✔ ->hasExtra() returns true because the file path is stored in extra
 ✔ ->getExtra() carries the file path under the "file_path" key
 ✔ ->maybeGetDetail() returns null because no detail is set
 ✔ ->getMessage() falls back to the title when no detail is set
 ✔ ->hasInstance() returns false because no instance URI is set
```

## Source

[`kits/exceptionskit/src/Exceptions/FileNotFoundException.php:60`](../../../../kits/exceptionskit/src/Exceptions/FileNotFoundException.php#L60)

## Changelog

_No tagged releases yet._

## See Also

- [`Rfc9457ProblemDetailsException::__construct()`](../Rfc9457ProblemDetailsException/__construct.md) — the parent constructor this override forwards to
- [`Rfc9457ProblemDetailsException::getExtra()`](../Rfc9457ProblemDetailsException/getExtra.md) — inherited accessor that surfaces the `file_path` payload back to the caller
- [`InvalidClassException::__construct()`](../InvalidClassException/__construct.md) — sibling constructor with the same `extra`-payload shape, for unloadable class names
- [`InvalidFunctionException::__construct()`](../InvalidFunctionException/__construct.md) — sibling constructor with the same `extra`-payload shape, for undefined global function names
- [`InvalidMethodException::__construct()`](../InvalidMethodException/__construct.md) — sibling constructor with the same `extra`-payload shape, for missing methods

## Issues

- [Open issues mentioning `FileNotFoundException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aopen+%22FileNotFoundException::__construct()%22)
- [Closed issues mentioning `FileNotFoundException::__construct()`](https://github.com/stuartherbert/stusdevkit/issues?q=is%3Aissue+is%3Aclosed+%22FileNotFoundException::__construct()%22)
- [Report a new issue](https://github.com/stuartherbert/stusdevkit/issues/new?title=FileNotFoundException%3A+%3Cdescribe+the+issue%3E)

---

_Generated from source code and PHPUnit TestDox output by the
`generate-manual-page` skill._
